<?php

namespace Tests\Feature;

use App\Events\PaymentConfirmed;
use App\Models\Abonnement;
use App\Models\Paiement;
use App\Models\Plan;
use App\Models\RecruteurVerification;
use App\Models\User;
use App\Notifications\AbonnementActiveNotification;
use App\Notifications\PaiementConfirmeNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Même règle métier appliquée à l'abonnement recruteur payant : pas
 * d'activation ni d'email tant que le paiement n'est pas confirmé, et
 * chaque email ne doit partir qu'une seule fois (régression du bug de
 * double-exécution des listeners corrigé dans AppServiceProvider).
 */
class AbonnementEmailFlowTest extends TestCase
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
        $user = User::factory()->recruteur()->create();
        $user->assignRole('recruteur');
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'approuve']);
        return $user;
    }

    private function creerPlanPayant(): Plan
    {
        return Plan::create([
            'name'          => 'Premium Recruteur',
            'slug'          => 'premium-recruteur-'.uniqid(),
            'target_type'   => 'recruteur',
            'price'         => 25000,
            'currency'      => 'XOF',
            'duration_days' => 30,
            'is_free'       => false,
            'is_active'     => true,
        ]);
    }

    public function test_souscrire_a_un_plan_payant_ne_declenche_aucun_email_et_nactive_rien(): void
    {
        Notification::fake();
        $recruteur = $this->creerRecruteur();
        $plan      = $this->creerPlanPayant();

        $this->actingAs($recruteur)
            ->post(route('recruteur.abonnement.store'), ['plan_id' => $plan->id])
            ->assertRedirect();

        $abonnement = Abonnement::first();
        $this->assertNotNull($abonnement);
        $this->assertSame('cancelled', $abonnement->status);

        $paiement = Paiement::first();
        $this->assertSame('en_attente', $paiement->statut);

        Notification::assertNothingSent();
    }

    public function test_paiement_abonnement_refuse_naction_rien(): void
    {
        Notification::fake();
        $recruteur = $this->creerRecruteur();
        $plan      = $this->creerPlanPayant();

        $abonnement = Abonnement::create([
            'user_id'   => $recruteur->id,
            'plan_id'   => $plan->id,
            'status'    => 'cancelled',
            'starts_at' => now(),
            'ends_at'   => now()->addDays(30),
        ]);

        $paiement = Paiement::create([
            'user_id'         => $recruteur->id,
            'subscription_id' => $abonnement->id,
            'montant'         => $plan->price,
            'devise'          => 'XOF',
            'type'            => 'abonnement_recruteur',
            'statut'          => 'en_attente',
        ]);

        $paiement->update(['statut' => 'echec']);

        $this->assertSame('cancelled', $abonnement->fresh()->status);
        Notification::assertNothingSent();
    }

    public function test_paiement_abonnement_confirme_active_et_envoie_email_une_seule_fois(): void
    {
        Notification::fake();
        $recruteur = $this->creerRecruteur();
        $plan      = $this->creerPlanPayant();

        $abonnement = Abonnement::create([
            'user_id'   => $recruteur->id,
            'plan_id'   => $plan->id,
            'status'    => 'cancelled',
            'starts_at' => now(),
            'ends_at'   => now()->addDays(30),
        ]);

        $paiement = Paiement::create([
            'user_id'         => $recruteur->id,
            'subscription_id' => $abonnement->id,
            'montant'         => $plan->price,
            'devise'          => 'XOF',
            'type'            => 'abonnement_recruteur',
            'statut'          => 'en_attente',
        ]);

        $paiement->update(['statut' => 'confirme']);
        event(new PaymentConfirmed($paiement));

        $this->assertSame('active', $abonnement->fresh()->status);

        // Garde-fou explicite contre la régression du double-listener :
        // chaque notification ne doit partir qu'UNE seule fois.
        Notification::assertSentToTimes($recruteur, AbonnementActiveNotification::class, 1);
        Notification::assertSentToTimes($recruteur, PaiementConfirmeNotification::class, 1);
    }
}
