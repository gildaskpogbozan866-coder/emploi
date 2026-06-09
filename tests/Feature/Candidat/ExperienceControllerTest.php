<?php

namespace Tests\Feature\Candidat;

use App\Models\Experience;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ExperienceControllerTest extends TestCase
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

    private function payloadValide(array $extra = []): array
    {
        return array_merge([
            'poste'      => 'Développeur Web',
            'entreprise' => 'Tech Bénin',
            'lieu'       => 'Cotonou',
            'secteur'    => 'Informatique',
            'date_debut' => '2021-01-01',
            'date_fin'   => '2023-06-30',
            'en_cours'   => false,
            'missions'   => ['Développement API', 'Revue de code'],
        ], $extra);
    }

    // ── Ajout ─────────────────────────────────────────────

    public function test_ajout_experience_valide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide())
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'experience' => ['id', 'poste', 'entreprise']]);

        $this->assertDatabaseHas('experiences', [
            'candidat_id' => $candidat->id,
            'poste'       => 'Développeur Web',
            'entreprise'  => 'Tech Bénin',
        ]);
    }

    public function test_missions_stockees_en_tableau(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide([
                'missions' => ['Mission A', 'Mission B', 'Mission C'],
            ]));

        $exp = Experience::where('candidat_id', $candidat->id)->first();
        $this->assertIsArray($exp->missions);
        $this->assertCount(3, $exp->missions);
        $this->assertContains('Mission A', $exp->missions);
    }

    public function test_missions_vides_ignorees(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide([
                'missions' => ['Mission A', '', '   '],
            ]));

        $exp = Experience::where('candidat_id', $candidat->id)->first();
        $this->assertCount(1, $exp->missions);
    }

    public function test_date_fin_nulle_si_en_cours(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide([
                'en_cours' => true,
                'date_fin' => '2024-01-01',
            ]));

        $exp = Experience::where('candidat_id', $candidat->id)->first();
        $this->assertTrue($exp->en_cours);
        $this->assertNull($exp->date_fin);
    }

    public function test_ajout_lie_au_candidat_connecte(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide());

        $exp = Experience::first();
        $this->assertEquals($candidat->id, $exp->candidat_id);
    }

    // ── Validation ────────────────────────────────────────

    public function test_validation_poste_requis(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide(['poste' => '']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('poste');
    }

    public function test_validation_entreprise_requise(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide(['entreprise' => '']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('entreprise');
    }

    public function test_validation_date_debut_requise(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide(['date_debut' => '']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_debut');
    }

    public function test_validation_date_debut_dans_le_futur(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide([
                'date_debut' => now()->addYear()->format('Y-m-d'),
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_debut');
    }

    public function test_validation_date_fin_avant_date_debut(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide([
                'date_debut' => '2023-01-01',
                'date_fin'   => '2022-01-01',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('date_fin');
    }

    public function test_validation_missions_max_20(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.experiences.store'), $this->payloadValide([
                'missions' => array_fill(0, 21, 'Mission'),
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('missions');
    }

    // ── Modification ──────────────────────────────────────

    public function test_modification_experience_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $exp = Experience::factory()->create([
            'candidat_id' => $candidat->id,
            'poste'       => 'Ancien poste',
        ]);

        $this->actingAs($candidat)
            ->putJson(route('candidat.profil.experiences.update', $exp), $this->payloadValide([
                'poste' => 'Nouveau poste',
            ]))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('experiences', [
            'id'    => $exp->id,
            'poste' => 'Nouveau poste',
        ]);
    }

    public function test_modification_refusee_pour_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $exp = Experience::factory()->create(['candidat_id' => $proprietaire->id]);

        $this->actingAs($autre)
            ->putJson(route('candidat.profil.experiences.update', $exp), $this->payloadValide())
            ->assertForbidden();
    }

    // ── Suppression ───────────────────────────────────────

    public function test_suppression_experience_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $exp = Experience::factory()->create(['candidat_id' => $candidat->id]);

        $this->actingAs($candidat)
            ->deleteJson(route('candidat.profil.experiences.destroy', $exp))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('experiences', ['id' => $exp->id]);
    }

    public function test_suppression_refusee_pour_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $exp = Experience::factory()->create(['candidat_id' => $proprietaire->id]);

        $this->actingAs($autre)
            ->deleteJson(route('candidat.profil.experiences.destroy', $exp))
            ->assertForbidden();

        $this->assertDatabaseHas('experiences', ['id' => $exp->id]);
    }

    // ── Accès ─────────────────────────────────────────────

    public function test_ajout_bloque_si_non_connecte(): void
    {
        $this->postJson(route('candidat.profil.experiences.store'), $this->payloadValide())
            ->assertUnauthorized();
    }
}
