<?php

namespace Tests\Feature\Candidat;

use App\Models\LangueCandidat;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LangueControllerTest extends TestCase
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

    public function test_ajout_langue_valide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue' => 'Français',
                'niveau' => 'C2',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'langue' => ['id', 'langue', 'niveau']]);

        $this->assertDatabaseHas('langues_candidat', [
            'candidat_id' => $candidat->id,
            'langue'      => 'Français',
            'niveau'      => 'C2',
        ]);
    }

    public function test_ajout_niveau_natif(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue' => 'Fon',
                'niveau' => 'natif',
            ])
            ->assertCreated();
    }

    public function test_ajout_lie_au_candidat_connecte(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue' => 'Anglais',
                'niveau' => 'B2',
            ]);

        $this->assertDatabaseHas('langues_candidat', ['candidat_id' => $candidat->id]);
    }

    // ── Limite et doublons ────────────────────────────────

    public function test_limite_10_langues_atteinte(): void
    {
        $candidat = $this->creerCandidat();
        $langues  = ['L1','L2','L3','L4','L5','L6','L7','L8','L9','L10'];

        foreach ($langues as $l) {
            LangueCandidat::factory()->create([
                'candidat_id' => $candidat->id,
                'langue'      => $l,
            ]);
        }

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue' => 'L11',
                'niveau' => 'A1',
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Maximum 10 langues atteint.');
    }

    public function test_doublon_exact_refuse(): void
    {
        $candidat = $this->creerCandidat();
        LangueCandidat::factory()->create([
            'candidat_id' => $candidat->id,
            'langue'      => 'Anglais',
        ]);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue' => 'Anglais',
                'niveau' => 'C1',
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Cette langue existe déjà.');
    }

    public function test_doublon_insensible_a_la_casse(): void
    {
        $candidat = $this->creerCandidat();
        LangueCandidat::factory()->create([
            'candidat_id' => $candidat->id,
            'langue'      => 'Anglais',
        ]);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue' => 'anglais',
                'niveau' => 'B1',
            ])
            ->assertStatus(422);
    }

    public function test_meme_langue_pour_deux_candidats_differents(): void
    {
        $candidat1 = $this->creerCandidat();
        $candidat2 = $this->creerCandidat();

        LangueCandidat::factory()->create([
            'candidat_id' => $candidat1->id,
            'langue'      => 'Espagnol',
        ]);

        $this->actingAs($candidat2)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue' => 'Espagnol',
                'niveau' => 'B2',
            ])
            ->assertCreated();
    }

    // ── Validation ────────────────────────────────────────

    public function test_validation_langue_requise(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), ['niveau' => 'B2'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('langue');
    }

    public function test_validation_niveau_cefr_invalide(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue' => 'Anglais',
                'niveau' => 'D1',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('niveau');
    }

    public function test_validation_tous_niveaux_cefr_valides(): void
    {
        $candidat = $this->creerCandidat();
        $niveaux  = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'natif'];

        foreach ($niveaux as $i => $niveau) {
            $this->actingAs($candidat)
                ->postJson(route('candidat.profil.langues.store'), [
                    'langue' => 'Langue ' . $i,
                    'niveau' => $niveau,
                ])
                ->assertCreated();
        }
    }

    // ── Suppression ───────────────────────────────────────

    public function test_suppression_langue_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = LangueCandidat::factory()->create(['candidat_id' => $candidat->id]);

        $this->actingAs($candidat)
            ->deleteJson(route('candidat.profil.langues.destroy', $langue))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('langues_candidat', ['id' => $langue->id]);
    }

    public function test_suppression_refusee_pour_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $langue       = LangueCandidat::factory()->create(['candidat_id' => $proprietaire->id]);

        $this->actingAs($autre)
            ->deleteJson(route('candidat.profil.langues.destroy', $langue))
            ->assertForbidden();

        $this->assertDatabaseHas('langues_candidat', ['id' => $langue->id]);
    }

    // ── Accès ─────────────────────────────────────────────

    public function test_ajout_bloque_si_non_connecte(): void
    {
        $this->postJson(route('candidat.profil.langues.store'), [
            'langue' => 'Français', 'niveau' => 'C2',
        ])->assertUnauthorized();
    }
}
