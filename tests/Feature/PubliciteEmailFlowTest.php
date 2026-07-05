<?php

namespace Tests\Feature;

use App\Events\PaymentConfirmed;
use App\Models\JobPublicationPlan;
use App\Models\Paiement;
use App\Models\Publicite;
use App\Models\User;
use App\Notifications\NouvellePubliciteAdminNotification;
use App\Notifications\PaiementConfirmeNotification;
use App\Notifications\PubliciteSoumiseNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Même règle métier pour les publicités annonceur payantes : pas de
 * soumission ni d'email tant que le paiement n'est pas confirmé. Les
 * publicités gratuites sont un cas légitimement différent (pas de
 * transaction du tout), donc soumises et notifiées immédiatement par design.
 */
class PubliciteEmailFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    private function creerAnnonceur(): User
    {
        $user = User::factory()->create(['role' => 'annonceur']);
        $user->assignRole('annonceur');
        return $user;
    }

    private function creerPlanPayant(): JobPublicationPlan
    {
        return JobPublicationPlan::create([
            'name'          => 'Diffusion 30 jours',
            'duration_days' => 30,
            'price'         => 20000,
            'is_free'       => false,
            'is_active'     => true,
        ]);
    }

    private function creerPlanGratuit(): JobPublicationPlan
    {
        return JobPublicationPlan::create([
            'name'          => 'Diffusion gratuite',
            'duration_days' => 7,
            'price'         => 0,
            'is_free'       => true,
            'is_active'     => true,
        ]);
    }

    /**
     * Construit un vrai petit PNG valide (1x1 px) en dur, pour ne pas
     * dépendre de l'extension GD (absente de cet environnement local) que
     * UploadedFile::fake()->image() utilise pour générer une image à la volée.
     */
    private function fakeImage(string $nom = 'pub.png'): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $path = tempnam(sys_get_temp_dir(), 'pub') . '.png';
        file_put_contents($path, $png);

        return new UploadedFile($path, $nom, 'image/png', null, true);
    }

    public function test_soumettre_une_publicite_payante_ne_declenche_aucun_email(): void
    {
        Notification::fake();
        Storage::fake('public');
        $annonceur = $this->creerAnnonceur();
        $plan      = $this->creerPlanPayant();

        $this->actingAs($annonceur)
            ->post(route('annonceur.publicites.store'), [
                'titre'   => 'Ma pub',
                'image'   => $this->fakeImage(),
                'plan_id' => $plan->id,
            ])
            ->assertRedirect();

        $publicite = Publicite::first();
        $this->assertNotNull($publicite);
        $this->assertSame('brouillon', $publicite->statut);

        $paiement = Paiement::first();
        $this->assertSame('en_attente', $paiement->statut);

        Notification::assertNothingSent();
    }

    public function test_paiement_publicite_confirme_soumet_et_notifie_une_seule_fois(): void
    {
        Notification::fake();
        $admin     = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');
        $annonceur = $this->creerAnnonceur();
        $plan      = $this->creerPlanPayant();

        $publicite = Publicite::create([
            'user_id'    => $annonceur->id,
            'plan_id'    => $plan->id,
            'titre'      => 'Ma pub',
            'image'      => 'publicites/fake.jpg',
            'statut'     => 'brouillon',
        ]);

        $paiement = Paiement::create([
            'user_id'      => $annonceur->id,
            'montant'      => $plan->price,
            'devise'       => 'XOF',
            'type'         => 'publicite',
            'payable_id'   => $publicite->id,
            'payable_type' => Publicite::class,
            'statut'       => 'en_attente',
        ]);

        $paiement->update(['statut' => 'confirme']);
        event(new PaymentConfirmed($paiement));

        $this->assertSame('en_attente', $publicite->fresh()->statut);
        Notification::assertSentToTimes($admin, NouvellePubliciteAdminNotification::class, 1);
        Notification::assertSentToTimes($annonceur, PubliciteSoumiseNotification::class, 1);
        Notification::assertSentToTimes($annonceur, PaiementConfirmeNotification::class, 1);
    }

    public function test_publicite_gratuite_est_soumise_et_notifiee_immediatement_sans_paiement(): void
    {
        Notification::fake();
        Storage::fake('public');
        $admin     = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');
        $annonceur = $this->creerAnnonceur();
        $plan      = $this->creerPlanGratuit();

        $this->actingAs($annonceur)
            ->post(route('annonceur.publicites.store'), [
                'titre'   => 'Ma pub gratuite',
                'image'   => $this->fakeImage(),
                'plan_id' => $plan->id,
            ])
            ->assertRedirect();

        $publicite = Publicite::first();
        $this->assertSame('en_attente', $publicite->statut);

        // Cas légitimement différent : pas de transaction, donc pas de Paiement créé.
        $this->assertSame(0, Paiement::count());

        Notification::assertSentToTimes($admin, NouvellePubliciteAdminNotification::class, 1);
        Notification::assertSentToTimes($annonceur, PubliciteSoumiseNotification::class, 1);
    }
}
