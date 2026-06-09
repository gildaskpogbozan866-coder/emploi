<?php

namespace Tests\Feature\Candidat;

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

    // ── Ajout ─────────────────────────────────────────────

    public function test_ajout_competence_valide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'nom'    => 'Laravel',
                'niveau' => 'avance',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'competence' => ['id', 'nom', 'niveau']]);

        $this->assertDatabaseHas('competences_candidat', [
            'candidat_id' => $candidat->id,
            'nom'         => 'Laravel',
            'niveau'      => 'avance',
        ]);
    }

    public function test_ajout_lie_au_candidat_connecte(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'nom'    => 'Python',
                'niveau' => 'intermediaire',
            ]);

        $comp = CompetenceCandidat::first();
        $this->assertEquals($candidat->id, $comp->candidat_id);
    }

    // ── Limite et doublons ────────────────────────────────

    public function test_limite_30_competences_atteinte(): void
    {
        $candidat = $this->creerCandidat();

        CompetenceCandidat::factory()->count(30)->create(['candidat_id' => $candidat->id]);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'nom'    => 'Nouvelle compétence',
                'niveau' => 'debutant',
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Maximum 30 compétences atteint.');
    }

    public function test_doublon_exact_refuse(): void
    {
        $candidat = $this->creerCandidat();
        CompetenceCandidat::factory()->create([
            'candidat_id' => $candidat->id,
            'nom'         => 'Laravel',
        ]);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'nom'    => 'Laravel',
                'niveau' => 'expert',
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Cette compétence existe déjà.');
    }

    public function test_doublon_insensible_a_la_casse(): void
    {
        $candidat = $this->creerCandidat();
        CompetenceCandidat::factory()->create([
            'candidat_id' => $candidat->id,
            'nom'         => 'Laravel',
        ]);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'nom'    => 'laravel',
                'niveau' => 'intermediaire',
            ])
            ->assertStatus(422);
    }

    public function test_meme_competence_pour_deux_candidats_differents(): void
    {
        $candidat1 = $this->creerCandidat();
        $candidat2 = $this->creerCandidat();

        CompetenceCandidat::factory()->create([
            'candidat_id' => $candidat1->id,
            'nom'         => 'MySQL',
        ]);

        $this->actingAs($candidat2)
            ->postJson(route('candidat.profil.competences.store'), [
                'nom'    => 'MySQL',
                'niveau' => 'avance',
            ])
            ->assertCreated();
    }

    // ── Validation ────────────────────────────────────────

    public function test_validation_nom_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), ['niveau' => 'expert'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('nom');
    }

    public function test_validation_niveau_invalide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.competences.store'), [
                'nom'    => 'React',
                'niveau' => 'dieu',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('niveau');
    }

    public function test_validation_niveaux_valides_acceptes(): void
    {
        $candidat = $this->creerCandidat();
        $niveaux  = ['debutant', 'intermediaire', 'avance', 'expert'];

        foreach ($niveaux as $i => $niveau) {
            $this->actingAs($candidat)
                ->postJson(route('candidat.profil.competences.store'), [
                    'nom'    => 'Compétence ' . $i,
                    'niveau' => $niveau,
                ])
                ->assertCreated();
        }
    }

    // ── Suppression ───────────────────────────────────────

    public function test_suppression_competence_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $comp = CompetenceCandidat::factory()->create(['candidat_id' => $candidat->id]);

        $this->actingAs($candidat)
            ->deleteJson(route('candidat.profil.competences.destroy', $comp))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('competences_candidat', ['id' => $comp->id]);
    }

    public function test_suppression_refusee_pour_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $comp = CompetenceCandidat::factory()->create(['candidat_id' => $proprietaire->id]);

        $this->actingAs($autre)
            ->deleteJson(route('candidat.profil.competences.destroy', $comp))
            ->assertForbidden();

        $this->assertDatabaseHas('competences_candidat', ['id' => $comp->id]);
    }

    // ── Accès ─────────────────────────────────────────────

    public function test_ajout_bloque_si_non_connecte(): void
    {
        $this->postJson(route('candidat.profil.competences.store'), [
            'nom' => 'PHP', 'niveau' => 'avance',
        ])->assertUnauthorized();
    }
}
