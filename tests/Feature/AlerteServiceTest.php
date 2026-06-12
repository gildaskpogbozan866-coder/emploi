<?php

namespace Tests\Feature;

use App\Models\Alerte;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\RecruteurVerification;
use App\Models\User;
use App\Notifications\AlerteOffreNotification;
use App\Services\AlerteService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Tests\TestCase;

class AlerteServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    // ── Helpers ───────────────────────────────────────────

    private function creerCandidat(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');
        return $user;
    }

    private function creerRecruteur(): User
    {
        $user = User::factory()->recruteur()->create();
        $user->assignRole('recruteur');
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'approuve']);
        return $user;
    }

    private function creerOffre(User $recruteur, array $attrs = []): Offre
    {
        return Offre::factory()->create(array_merge([
            'recruteur_id' => $recruteur->id,
            'statut'       => 'active',
        ], $attrs));
    }

    private function creerAlerte(User $candidat, array $attrs = []): Alerte
    {
        return Alerte::create(array_merge([
            'user_id'    => $candidat->id,
            'nom'        => 'Mon alerte test',
            'frequence'  => 'immediat',
            'active'     => true,
        ], $attrs));
    }

    // ── matcheOffre ───────────────────────────────────────

    public function test_matcheoffre_retourne_true_si_mot_cle_dans_titre(): void
    {
        $candidat = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte   = $this->creerAlerte($candidat, ['mots_cles' => 'Laravel']);
        $offre    = $this->creerOffre($recruteur, ['titre' => 'Développeur Laravel Senior']);

        $service = app(AlerteService::class);
        $this->assertTrue($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_retourne_false_si_aucun_mot_cle_present(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['mots_cles' => 'Python Django']);
        $offre     = $this->creerOffre($recruteur, ['titre' => 'Développeur Laravel', 'description' => 'PHP uniquement']);

        $service = app(AlerteService::class);
        $this->assertFalse($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_filtre_par_localisation(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['localisation' => 'Cotonou']);
        $offre     = $this->creerOffre($recruteur, ['localisation' => 'Parakou, Bénin']);

        $service = app(AlerteService::class);
        $this->assertFalse($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_localisation_correspondante(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['localisation' => 'Cotonou']);
        $offre     = $this->creerOffre($recruteur, ['localisation' => 'Cotonou, Bénin']);

        $service = app(AlerteService::class);
        $this->assertTrue($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_filtre_par_type_contrat(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat, ['type_contrat' => 'CDI']);
        $offre     = $this->creerOffre($recruteur, ['type' => 'Stage']);

        $service = app(AlerteService::class);
        $this->assertFalse($service->matcheOffre($alerte, $offre));
    }

    public function test_matcheoffre_sans_criteres_matche_tout(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $alerte    = $this->creerAlerte($candidat);
        $offre     = $this->creerOffre($recruteur);

        $service = app(AlerteService::class);
        $this->assertTrue($service->matcheOffre($alerte, $offre));
    }

    // ── notifierImmediat ──────────────────────────────────

    public function test_notifier_immediat_cree_notification_inapp(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['mots_cles' => 'Laravel']);
        $offre = $this->creerOffre($recruteur, [
            'titre'       => 'Développeur Laravel',
            'description' => 'Travail en Laravel avancé',
        ]);

        app(AlerteService::class)->notifierImmediat($offre);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $candidat->id,
            'type'    => 'alerte',
        ]);
    }

    public function test_notifier_immediat_envoie_email_au_candidat(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['mots_cles' => 'Laravel']);
        $offre = $this->creerOffre($recruteur, [
            'titre'       => 'Développeur Laravel',
            'description' => 'Maîtrise Laravel requise',
        ]);

        app(AlerteService::class)->notifierImmediat($offre);

        NotificationFacade::assertSentTo(
            $candidat,
            AlerteOffreNotification::class,
            fn ($n) => $n->offre->id === $offre->id
        );
    }

    public function test_notifier_immediat_ne_notifie_pas_si_alerte_inactive(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['mots_cles' => 'Laravel', 'active' => false]);
        $offre = $this->creerOffre($recruteur, ['titre' => 'Développeur Laravel']);

        app(AlerteService::class)->notifierImmediat($offre);

        $this->assertDatabaseMissing('notifications', ['user_id' => $candidat->id, 'type' => 'alerte']);
        NotificationFacade::assertNothingSent();
    }

    public function test_notifier_immediat_evite_les_doublons(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat);
        $offre = $this->creerOffre($recruteur, ['titre' => 'Développeur']);

        $service = app(AlerteService::class);
        $service->notifierImmediat($offre);
        $service->notifierImmediat($offre); // Deuxième appel

        $this->assertEquals(1, Notification::where('user_id', $candidat->id)->where('type', 'alerte')->count());
    }

    public function test_notifier_immediat_ne_notifie_pas_frequence_quotidienne(): void
    {
        NotificationFacade::fake();

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['frequence' => 'quotidien']);
        $offre = $this->creerOffre($recruteur);

        app(AlerteService::class)->notifierImmediat($offre);

        $this->assertDatabaseMissing('notifications', ['user_id' => $candidat->id, 'type' => 'alerte']);
    }

    // ── Admin activation offre ────────────────────────────

    public function test_admin_activation_offre_declenche_alertes(): void
    {
        NotificationFacade::fake();

        $adminUser = User::factory()->create(['role' => 'admin']);
        $adminUser->assignRole('admin');

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat, ['mots_cles' => 'Comptable']);
        $offre = $this->creerOffre($recruteur, [
            'titre'       => 'Comptable senior',
            'description' => 'Poste de comptable',
            'statut'      => 'en_attente',
        ]);

        $this->actingAs($adminUser)
            ->patch(route('admin.offres.statut', $offre), ['statut' => 'active'])
            ->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $candidat->id,
            'type'    => 'alerte',
        ]);
    }

    public function test_admin_activation_ne_declenche_pas_alertes_si_deja_active(): void
    {
        NotificationFacade::fake();

        $adminUser = User::factory()->create(['role' => 'admin']);
        $adminUser->assignRole('admin');

        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $this->creerAlerte($candidat);
        $offre = $this->creerOffre($recruteur, ['statut' => 'active']);

        // Passer de active à active ne doit pas re-notifier
        $this->actingAs($adminUser)
            ->patch(route('admin.offres.statut', $offre), ['statut' => 'active']);

        $this->assertDatabaseMissing('notifications', ['user_id' => $candidat->id, 'type' => 'alerte']);
    }
}
