<?php

namespace Tests\Feature\Candidat;

use App\Models\Abonnement;
use App\Models\Document;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\TypeDocument;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AbonnementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    private function creerCandidatAvecPlanGratuit(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');

        $plan = Plan::create([
            'name' => 'Gratuit', 'slug' => 'gratuit', 'target_type' => 'candidat',
            'price' => 0, 'duration_days' => null, 'is_free' => true, 'is_active' => true,
        ]);
        PlanFeature::create(['plan_id' => $plan->id, 'feature_key' => 'cv_limit', 'feature_value' => '1']);
        Abonnement::create([
            'user_id' => $user->id, 'plan_id' => $plan->id,
            'starts_at' => now()->subDay(), 'ends_at' => null, 'status' => 'active',
        ]);

        return $user;
    }

    /**
     * Bug réel corrigé : le quota de CV ("Documents déposés") comptait aussi les
     * Document (diplôme, attestation...) en plus des vrais CV, faisant croire à
     * un candidat sans aucun CV mais avec un diplôme déposé qu'il avait atteint
     * sa limite de 1 CV.
     */
    public function test_quota_cv_ne_compte_pas_les_documents(): void
    {
        $candidat = $this->creerCandidatAvecPlanGratuit();
        $type     = TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        Document::create([
            'user_id' => $candidat->id, 'type_document_id' => $type->id,
            'nom' => 'Licence', 'fichier' => 'candidats/documents/test.pdf',
        ]);

        $response = $this->actingAs($candidat)->get(route('candidat.abonnement'));

        $response->assertOk();
        $quotas = $response->viewData('quotas');
        $this->assertSame(0, $quotas['cvs']['used']);
        $this->assertSame(1, $quotas['documents']['used']);
    }

    public function test_dashboard_ne_bloque_pas_le_depot_cv_si_seuls_des_documents_existent(): void
    {
        $candidat = $this->creerCandidatAvecPlanGratuit();
        $type     = TypeDocument::create(['nom' => 'Diplôme', 'actif' => true, 'ordre' => 1]);
        Document::create([
            'user_id' => $candidat->id, 'type_document_id' => $type->id,
            'nom' => 'Licence', 'fichier' => 'candidats/documents/test.pdf',
        ]);

        $this->actingAs($candidat)
            ->get(route('candidat.dashboard'))
            ->assertOk()
            ->assertDontSee('Limite atteinte');
    }
}
