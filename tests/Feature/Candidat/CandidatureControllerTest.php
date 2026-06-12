<?php

namespace Tests\Feature\Candidat;

use App\Models\Candidature;
use App\Models\CV;
use App\Models\Offre;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
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
        return $user;
    }

    private function creerCandidature(User $candidat, array $attrs = []): Candidature
    {
        $recruteur = $this->creerRecruteur();
        $offre     = Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active']);
        return Candidature::create(array_merge([
            'offre_id'    => $offre->id,
            'candidat_id' => $candidat->id,
            'statut'      => 'envoyee',
        ], $attrs));
    }

    // ── Index ─────────────────────────────────────────────

    public function test_index_accessible_au_candidat(): void
    {
        $candidat = $this->creerCandidat();
        $this->creerCandidature($candidat);

        $this->actingAs($candidat)
            ->get(route('candidat.candidatures'))
            ->assertOk()
            ->assertViewIs('candidat.candidatures');
    }

    public function test_index_redirige_si_non_connecte(): void
    {
        $this->get(route('candidat.candidatures'))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_index_filtre_par_statut(): void
    {
        $candidat = $this->creerCandidat();
        $this->creerCandidature($candidat, ['statut' => 'retenue']);
        $this->creerCandidature($candidat, ['statut' => 'refusee']);

        $response = $this->actingAs($candidat)
            ->get(route('candidat.candidatures', ['statut' => 'retenue']));

        $response->assertOk();
        $this->assertCount(1, $response->viewData('candidatures'));
    }

    // ── Détail ────────────────────────────────────────────

    public function test_detail_accessible_au_proprietaire(): void
    {
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($candidat);

        $this->actingAs($candidat)
            ->get(route('candidat.candidatures.detail', $candidature))
            ->assertOk()
            ->assertViewIs('candidat.candidature-detail');
    }

    public function test_detail_interdit_a_un_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $candidature  = $this->creerCandidature($proprietaire);

        $this->actingAs($autre)
            ->get(route('candidat.candidatures.detail', $candidature))
            ->assertForbidden();
    }

    public function test_detail_redirige_si_non_connecte(): void
    {
        $candidat    = $this->creerCandidat();
        $candidature = $this->creerCandidature($candidat);

        $this->get(route('candidat.candidatures.detail', $candidature))
            ->assertRedirect(route('auth.connexion'));
    }

    // ── Offres sauvegardées ───────────────────────────────

    public function test_offres_sauvegardees_accessible_au_candidat(): void
    {
        $candidat  = $this->creerCandidat();
        $recruteur = $this->creerRecruteur();
        $offre     = Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active']);
        $candidat->offresSauvegardees()->attach($offre->id);

        $this->actingAs($candidat)
            ->get(route('candidat.offres-sauvegardees'))
            ->assertOk()
            ->assertViewIs('candidat.offres-sauvegardees');
    }

    public function test_offres_sauvegardees_vide_si_aucune(): void
    {
        $candidat = $this->creerCandidat();

        $response = $this->actingAs($candidat)
            ->get(route('candidat.offres-sauvegardees'));

        $response->assertOk();
        $this->assertCount(0, $response->viewData('offres'));
    }
}
