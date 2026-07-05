<?php

namespace Tests\Feature;

use App\Events\PaymentConfirmed;
use App\Models\CreditCvPack;
use App\Models\Paiement;
use App\Models\RecruteurVerification;
use App\Models\User;
use App\Notifications\PaiementConfirmeNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Même règle métier que pour les commandes de service, appliquée à l'achat
 * de crédits CVthèque : pas d'email tant que le paiement n'est pas confirmé,
 * et les crédits ne doivent être crédités qu'à la confirmation.
 */
class CvCreditsEmailFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    private function creerRecruteur(): User
    {
        $user = User::factory()->recruteur()->create(['cv_credits' => 0]);
        $user->assignRole('recruteur');
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'approuve']);
        return $user;
    }

    private function creerPack(): CreditCvPack
    {
        return CreditCvPack::create([
            'label'   => 'Pack 10 crédits',
            'credits' => 10,
            'prix'    => 5000,
            'actif'   => true,
            'ordre'   => 1,
        ]);
    }

    public function test_acheter_des_credits_ne_declenche_aucun_email_et_ne_credite_rien(): void
    {
        Notification::fake();
        $recruteur = $this->creerRecruteur();
        $pack      = $this->creerPack();

        $this->actingAs($recruteur)
            ->post(route('recruteur.cv-credits.store'), ['pack_id' => $pack->id])
            ->assertRedirect();

        $paiement = Paiement::first();
        $this->assertNotNull($paiement);
        $this->assertSame('en_attente', $paiement->statut);
        $this->assertSame(0, $recruteur->fresh()->cv_credits);

        Notification::assertNothingSent();
    }

    public function test_paiement_credits_refuse_ne_credite_rien_et_aucun_email(): void
    {
        Notification::fake();
        $recruteur = $this->creerRecruteur();
        $pack      = $this->creerPack();

        $paiement = Paiement::create([
            'user_id'    => $recruteur->id,
            'montant'    => $pack->prix,
            'devise'     => 'XOF',
            'type'       => 'cv_credits',
            'credits_cv' => $pack->credits,
            'statut'     => 'en_attente',
        ]);

        $paiement->update(['statut' => 'echec']);

        $this->assertSame(0, $recruteur->fresh()->cv_credits);
        Notification::assertNothingSent();
    }

    public function test_paiement_credits_confirme_credite_et_envoie_email(): void
    {
        Notification::fake();
        $recruteur = $this->creerRecruteur();
        $pack      = $this->creerPack();

        $paiement = Paiement::create([
            'user_id'    => $recruteur->id,
            'montant'    => $pack->prix,
            'devise'     => 'XOF',
            'type'       => 'cv_credits',
            'credits_cv' => $pack->credits,
            'statut'     => 'en_attente',
        ]);

        $paiement->update(['statut' => 'confirme']);
        event(new PaymentConfirmed($paiement));

        $this->assertSame(10, $recruteur->fresh()->cv_credits);
        Notification::assertSentTo($recruteur, PaiementConfirmeNotification::class);
    }
}
