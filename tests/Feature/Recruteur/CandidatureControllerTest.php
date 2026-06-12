<?php

namespace Tests\Feature\Recruteur;

use App\Models\Candidature;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\Plan;
use App\Models\RecruteurVerification;
use App\Models\User;
use App\Notifications\CandidatureStatutNotification;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Tests\TestCase;

class CandidatureControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    // ── Helpers ───────────────────────────────────────────

    private function creerRecruteur(): User
    {
        $user = User::factory()->recruteur()->create();
        $user->assignRole('recruteur');
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'approuve']);
        $plan = Plan::firstOrCreate(
            ['slug' => 'rec-test'],
            ['name' => 'Plan Rec Test', 'target_type' => 'recruteur', 'price' => 0, 'is_free' => true, 'is_active' => true]
        );
        \App\Models\Abonnement::create([
            'user_id'   => $user->id,
            'plan_id'   => $plan->id,
            'starts_at' => now()->subDay(),
            'ends_at'   => now()->addYear(),
            'status'    => 'active',
        ]);
        return $user;
    }

    private function creerCandidat(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');
        return $user;
    }

    private function creerCandidature(User $recruteur, User $candidat, array $attrs = []): Candidature
    {
        $offre = Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active']);
        return Candidature::create(array_merge([
            'offre_id'    => $offre->id,
            'candidat_id' => $candidat->id,
            'statut'      => 'envoyee',
        ], $attrs));
    }

    // ── Index ─────────────────────────────────────────────

    public function test_index_accessible_au_recruteur(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->get(route('recruteur.candidatures'))
            ->assertOk()
            ->assertViewIs('recruteur.candidatures');
    }

    public function test_index_redirige_si_non_connecte(): void
    {
        $this->get(route('recruteur.candidatures'))
            ->assertRedirect(route('auth.connexion'));
    }

    // ── Show ──────────────────────────────────────────────

    public function test_show_accessible_au_proprietaire_de_loffre(): void
    {
        $recruteur    = $this->creerRecruteur();
        $candidat     = $this->creerCandidat();
        $candidature  = $this->creerCandidature($recruteur, $candidat);

        $this->actingAs($recruteur)
            ->get(route('recruteur.candidatures.show', $candidature))
            ->assertOk()
            ->assertViewIs('recruteur.candidature-detail');
    }

    public function test_show_marque_statut_vue_automatiquement(): void
    {
        $recruteur   = $this->creerRecruteur();
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($recruteur, $candidat, ['statut' => 'envoyee']);

        $this->actingAs($recruteur)
            ->get(route('recruteur.candidatures.show', $candidature));

        $this->assertEquals('vue', $candidature->fresh()->statut);
    }

    public function test_show_interdit_a_un_autre_recruteur(): void
    {
        $recruteur1  = $this->creerRecruteur();
        $recruteur2  = $this->creerRecruteur();
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($recruteur1, $candidat);

        $this->actingAs($recruteur2)
            ->get(route('recruteur.candidatures.show', $candidature))
            ->assertForbidden();
    }

    // ── UpdateStatut ──────────────────────────────────────

    public function test_updatestatut_met_a_jour_le_statut(): void
    {
        NotificationFacade::fake();

        $recruteur   = $this->creerRecruteur();
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($recruteur, $candidat);

        $this->actingAs($recruteur)
            ->patch(route('recruteur.candidatures.statut', $candidature), [
                'statut'         => 'retenue',
                'note_recruteur' => 'Très bon profil',
            ]);

        $this->assertEquals('retenue', $candidature->fresh()->statut);
        $this->assertEquals('Très bon profil', $candidature->fresh()->note_recruteur);
    }

    public function test_updatestatut_cree_notification_inapp_pour_le_candidat(): void
    {
        NotificationFacade::fake();

        $recruteur   = $this->creerRecruteur();
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($recruteur, $candidat);

        $this->actingAs($recruteur)
            ->patch(route('recruteur.candidatures.statut', $candidature), [
                'statut' => 'retenue',
            ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $candidat->id,
            'type'    => 'candidature',
        ]);
    }

    public function test_updatestatut_envoie_email_au_candidat(): void
    {
        NotificationFacade::fake();

        $recruteur   = $this->creerRecruteur();
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($recruteur, $candidat);

        $this->actingAs($recruteur)
            ->patch(route('recruteur.candidatures.statut', $candidature), [
                'statut'         => 'entretien',
                'note_recruteur' => 'Bienvenue en entretien',
            ]);

        NotificationFacade::assertSentTo(
            $candidat,
            CandidatureStatutNotification::class,
            function ($notification) {
                return $notification->statut === 'entretien'
                    && $notification->noteRecruteur === 'Bienvenue en entretien';
            }
        );
    }

    public function test_updatestatut_redirige_vers_index_candidatures(): void
    {
        NotificationFacade::fake();

        $recruteur   = $this->creerRecruteur();
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($recruteur, $candidat);

        $this->actingAs($recruteur)
            ->patch(route('recruteur.candidatures.statut', $candidature), [
                'statut' => 'refusee',
            ])
            ->assertRedirect(route('recruteur.candidatures'))
            ->assertSessionHas('success');
    }

    public function test_updatestatut_interdit_a_un_autre_recruteur(): void
    {
        NotificationFacade::fake();

        $recruteur1  = $this->creerRecruteur();
        $recruteur2  = $this->creerRecruteur();
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($recruteur1, $candidat);

        $this->actingAs($recruteur2)
            ->patch(route('recruteur.candidatures.statut', $candidature), [
                'statut' => 'retenue',
            ])
            ->assertForbidden();
    }

    public function test_updatestatut_valide_les_statuts_autorises(): void
    {
        NotificationFacade::fake();

        $recruteur   = $this->creerRecruteur();
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($recruteur, $candidat);

        $this->actingAs($recruteur)
            ->patch(route('recruteur.candidatures.statut', $candidature), [
                'statut' => 'invalide',
            ])
            ->assertSessionHasErrors('statut');
    }
}
