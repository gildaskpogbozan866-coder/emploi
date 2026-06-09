<?php

namespace Tests\Feature\Candidat;

use App\Models\Formation;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FormationControllerTest extends TestCase
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

    private function payloadValide(array $extra = []): array
    {
        return array_merge([
            'diplome'       => 'Licence',
            'etablissement' => 'UAC',
            'domaine'       => 'Informatique',
            'date_debut'    => '2018-09-01',
            'date_fin'      => '2021-06-30',
            'en_cours'      => false,
            'activites'     => ['Major de promotion', 'Projet tutoré'],
        ], $extra);
    }

    // ── Ajout ─────────────────────────────────────────────

    public function test_ajout_formation_valide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide())
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'formation' => ['id', 'diplome', 'etablissement']]);

        $this->assertDatabaseHas('formations', [
            'candidat_id'  => $candidat->id,
            'diplome'      => 'Licence',
            'etablissement'=> 'UAC',
        ]);
    }

    public function test_activites_stockees_en_tableau(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide([
                'activites' => ['Activité A', 'Activité B'],
            ]));

        $formation = Formation::where('candidat_id', $candidat->id)->first();
        $this->assertIsArray($formation->activites);
        $this->assertContains('Activité A', $formation->activites);
    }

    public function test_activites_vides_ignorees(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide([
                'activites' => ['Activité A', '', '  '],
            ]));

        $formation = Formation::where('candidat_id', $candidat->id)->first();
        $this->assertCount(1, $formation->activites);
    }

    public function test_date_fin_nulle_si_en_cours(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide([
                'en_cours' => true,
                'date_fin' => '2025-01-01',
            ]));

        $formation = Formation::where('candidat_id', $candidat->id)->first();
        $this->assertTrue($formation->en_cours);
        $this->assertNull($formation->date_fin);
    }

    public function test_ajout_lie_au_candidat_connecte(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide());

        $this->assertDatabaseHas('formations', ['candidat_id' => $candidat->id]);
    }

    // ── Validation ────────────────────────────────────────

    public function test_validation_diplome_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide(['diplome' => '']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('diplome');
    }

    public function test_validation_etablissement_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide(['etablissement' => '']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('etablissement');
    }

    public function test_validation_date_debut_requise(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide(['date_debut' => '']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_debut');
    }

    public function test_validation_date_debut_dans_le_futur(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide([
                'date_debut' => now()->addYear()->format('Y-m-d'),
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_debut');
    }

    public function test_validation_date_fin_avant_date_debut(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide([
                'date_debut' => '2022-01-01',
                'date_fin'   => '2021-01-01',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_fin');
    }

    public function test_validation_activites_max_20(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.formations.store'), $this->payloadValide([
                'activites' => array_fill(0, 21, 'Activité'),
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('activites');
    }

    // ── Modification ──────────────────────────────────────

    public function test_modification_formation_proprietaire(): void
    {
        $candidat  = $this->creerCandidat();
        $formation = Formation::factory()->create([
            'candidat_id' => $candidat->id,
            'diplome'     => 'Ancien diplôme',
        ]);

        $this->actingAs($candidat)
            ->putJson(route('candidat.profil.formations.update', $formation), $this->payloadValide([
                'diplome' => 'Master',
            ]))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('formations', [
            'id'      => $formation->id,
            'diplome' => 'Master',
        ]);
    }

    public function test_modification_refusee_pour_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $formation    = Formation::factory()->create(['candidat_id' => $proprietaire->id]);

        $this->actingAs($autre)
            ->putJson(route('candidat.profil.formations.update', $formation), $this->payloadValide())
            ->assertForbidden();
    }

    // ── Suppression ───────────────────────────────────────

    public function test_suppression_formation_proprietaire(): void
    {
        $candidat  = $this->creerCandidat();
        $formation = Formation::factory()->create(['candidat_id' => $candidat->id]);

        $this->actingAs($candidat)
            ->deleteJson(route('candidat.profil.formations.destroy', $formation))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('formations', ['id' => $formation->id]);
    }

    public function test_suppression_refusee_pour_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $formation    = Formation::factory()->create(['candidat_id' => $proprietaire->id]);

        $this->actingAs($autre)
            ->deleteJson(route('candidat.profil.formations.destroy', $formation))
            ->assertForbidden();

        $this->assertDatabaseHas('formations', ['id' => $formation->id]);
    }

    // ── Accès ─────────────────────────────────────────────

    public function test_ajout_bloque_si_non_connecte(): void
    {
        $this->postJson(route('candidat.profil.formations.store'), $this->payloadValide())
            ->assertUnauthorized();
    }
}
