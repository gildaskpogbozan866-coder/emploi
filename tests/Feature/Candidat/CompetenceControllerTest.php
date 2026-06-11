<?php

namespace Tests\Feature\Candidat;

use App\Models\Competence;
use App\Models\CompetenceCandidat;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CompetenceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    private function creerCandidat(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');
        return $user;
    }

    private function creerCompetence(string $nom = 'Laravel'): Competence
    {
        return Competence::create([
            'nom'  => $nom,
            'slug' => \Illuminate\Support\Str::slug($nom),
        ]);
    }

    // ── Ajout ─────────────────────────────────────────────

    public function test_ajout_competence_valide(): void
    {
        $candidat   = $this->creerCandidat();
        $competence = $this->creerCompetence('Laravel');

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id' => $competence->id,
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'competence' => ['id', 'nom', 'annees_experience']]);

        $this->assertDatabaseHas('competence_candidat', [
            'candidat_id'  => $candidat->id,
            'competence_id' => $competence->id,
        ]);
    }

    public function test_ajout_avec_annees_experience(): void
    {
        $candidat   = $this->creerCandidat();
        $competence = $this->creerCompetence('PHP');

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id'    => $competence->id,
                'annees_experience' => 3,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('competence_candidat', [
            'candidat_id'      => $candidat->id,
            'competence_id'    => $competence->id,
            'annees_experience' => 3,
        ]);
    }

    public function test_ajout_lie_au_candidat_connecte(): void
    {
        $candidat   = $this->creerCandidat();
        $competence = $this->creerCompetence('Python');

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id' => $competence->id,
            ]);

        $pivot = CompetenceCandidat::where('competence_id', $competence->id)->first();
        $this->assertEquals($candidat->id, $pivot->candidat_id);
    }

    // ── Limite et doublons ────────────────────────────────

    public function test_limite_30_competences_atteinte(): void
    {
        $candidat = $this->creerCandidat();

        for ($i = 0; $i < 30; $i++) {
            $comp = Competence::create(['nom' => 'Comp' . $i, 'slug' => 'comp-' . $i]);
            CompetenceCandidat::create([
                'candidat_id'  => $candidat->id,
                'competence_id' => $comp->id,
            ]);
        }

        $nouvelle = $this->creerCompetence('NouvelleComp');

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id' => $nouvelle->id,
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Maximum 30 compétences atteint.');
    }

    public function test_doublon_exact_refuse(): void
    {
        $candidat   = $this->creerCandidat();
        $competence = $this->creerCompetence('Laravel');

        CompetenceCandidat::create([
            'candidat_id'  => $candidat->id,
            'competence_id' => $competence->id,
        ]);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id' => $competence->id,
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Cette compétence est déjà dans votre profil.');
    }

    public function test_meme_competence_pour_deux_candidats_differents(): void
    {
        $candidat1  = $this->creerCandidat();
        $candidat2  = $this->creerCandidat();
        $competence = $this->creerCompetence('MySQL');

        CompetenceCandidat::create([
            'candidat_id'  => $candidat1->id,
            'competence_id' => $competence->id,
        ]);

        $this->actingAs($candidat2)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id' => $competence->id,
            ])
            ->assertCreated();
    }

    // ── Validation ────────────────────────────────────────

    public function test_validation_competence_id_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('competence_id');
    }

    public function test_validation_competence_id_inexistant(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id' => 9999,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('competence_id');
    }

    public function test_validation_annees_experience_negatif(): void
    {
        $candidat   = $this->creerCandidat();
        $competence = $this->creerCompetence('React');

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id'    => $competence->id,
                'annees_experience' => -1,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('annees_experience');
    }

    public function test_validation_annees_experience_max_50(): void
    {
        $candidat   = $this->creerCandidat();
        $competence = $this->creerCompetence('Vue');

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'competence_id'    => $competence->id,
                'annees_experience' => 51,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('annees_experience');
    }

    // ── Suppression ───────────────────────────────────────

    public function test_suppression_competence_proprietaire(): void
    {
        $candidat   = $this->creerCandidat();
        $competence = $this->creerCompetence('Docker');

        $pivot = CompetenceCandidat::create([
            'candidat_id'  => $candidat->id,
            'competence_id' => $competence->id,
        ]);

        $this->actingAs($candidat)
            ->deleteJson(route('candidat.profil.competences.destroy', $pivot))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('competence_candidat', ['id' => $pivot->id]);
    }

    public function test_suppression_refusee_pour_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $competence   = $this->creerCompetence('Redis');

        $pivot = CompetenceCandidat::create([
            'candidat_id'  => $proprietaire->id,
            'competence_id' => $competence->id,
        ]);

        $this->actingAs($autre)
            ->deleteJson(route('candidat.profil.competences.destroy', $pivot))
            ->assertForbidden();

        $this->assertDatabaseHas('competence_candidat', ['id' => $pivot->id]);
    }

    // ── Accès ─────────────────────────────────────────────

    public function test_ajout_bloque_si_non_connecte(): void
    {
        $competence = $this->creerCompetence('PHP');

        $this->postJson(route('candidat.profil.competences.store'), [
            'competence_id' => $competence->id,
        ])->assertUnauthorized();
    }
}
