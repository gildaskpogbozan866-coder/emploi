<?php

namespace Tests\Feature\Candidat;

use App\Models\Langue;
use App\Models\LangueCandidat;
use App\Models\NiveauLangue;
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

    private function creerLangue(string $nom = 'Français'): Langue
    {
        return Langue::create(['nom' => $nom]);
    }

    private function creerNiveau(string $code = 'B2', int $ordre = 4): NiveauLangue
    {
        return NiveauLangue::create(['code' => $code, 'libelle' => 'Niveau ' . $code, 'ordre' => $ordre]);
    }

    // ── Ajout ─────────────────────────────────────────────

    public function test_ajout_langue_valide(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Français');
        $niveau   = $this->creerNiveau('C2', 6);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $langue->id,
                'niveau_id' => $niveau->id,
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'langue' => ['id', 'langue', 'niveau']]);

        $this->assertDatabaseHas('langues_candidat', [
            'candidat_id' => $candidat->id,
            'langue_id'   => $langue->id,
            'niveau_id'   => $niveau->id,
        ]);
    }

    public function test_ajout_niveau_natif(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Fon');
        $niveau   = $this->creerNiveau('natif', 7);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $langue->id,
                'niveau_id' => $niveau->id,
            ])
            ->assertCreated();
    }

    public function test_ajout_lie_au_candidat_connecte(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Anglais');
        $niveau   = $this->creerNiveau('B2', 4);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $langue->id,
                'niveau_id' => $niveau->id,
            ]);

        $this->assertDatabaseHas('langues_candidat', ['candidat_id' => $candidat->id]);
    }

    public function test_json_retourne_nom_et_code_niveau(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Espagnol');
        $niveau   = $this->creerNiveau('B1', 3);

        $response = $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $langue->id,
                'niveau_id' => $niveau->id,
            ])
            ->assertCreated();

        $this->assertEquals('Espagnol', $response->json('langue.langue'));
        $this->assertEquals('B1', $response->json('langue.niveau'));
    }

    // ── Limite et doublons ────────────────────────────────

    public function test_limite_10_langues_atteinte(): void
    {
        $candidat = $this->creerCandidat();
        $niveau   = $this->creerNiveau('A1', 1);

        for ($i = 0; $i < 10; $i++) {
            $l = Langue::create(['nom' => 'Langue' . $i]);
            LangueCandidat::create([
                'candidat_id' => $candidat->id,
                'langue_id'   => $l->id,
                'niveau_id'   => $niveau->id,
            ]);
        }

        $extra = Langue::create(['nom' => 'LangueExtra']);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $extra->id,
                'niveau_id' => $niveau->id,
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Maximum 10 langues atteint.');
    }

    public function test_doublon_exact_refuse(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Anglais');
        $niveau   = $this->creerNiveau('C1', 5);

        LangueCandidat::create([
            'candidat_id' => $candidat->id,
            'langue_id'   => $langue->id,
            'niveau_id'   => $niveau->id,
        ]);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $langue->id,
                'niveau_id' => $niveau->id,
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Cette langue existe déjà.');
    }

    public function test_meme_langue_niveau_different_aussi_refuse(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Portugais');
        $niveauB1 = $this->creerNiveau('B1', 3);
        $niveauC1 = NiveauLangue::create(['code' => 'C1', 'libelle' => 'Avancé', 'ordre' => 5]);

        LangueCandidat::create([
            'candidat_id' => $candidat->id,
            'langue_id'   => $langue->id,
            'niveau_id'   => $niveauB1->id,
        ]);

        // Même langue, niveau différent → doublon (un candidat ne peut avoir une langue qu'une fois)
        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $langue->id,
                'niveau_id' => $niveauC1->id,
            ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Cette langue existe déjà.');
    }

    public function test_meme_langue_pour_deux_candidats_differents(): void
    {
        $candidat1 = $this->creerCandidat();
        $candidat2 = $this->creerCandidat();
        $langue    = $this->creerLangue('Espagnol');
        $niveau    = $this->creerNiveau('B2', 4);

        LangueCandidat::create([
            'candidat_id' => $candidat1->id,
            'langue_id'   => $langue->id,
            'niveau_id'   => $niveau->id,
        ]);

        $this->actingAs($candidat2)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $langue->id,
                'niveau_id' => $niveau->id,
            ])
            ->assertCreated();
    }

    // ── Validation ────────────────────────────────────────

    public function test_validation_langue_id_requis(): void
    {
        $candidat = $this->creerCandidat();
        $niveau   = $this->creerNiveau('B2', 4);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), ['niveau_id' => $niveau->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('langue_id');
    }

    public function test_validation_niveau_id_requis(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Anglais');

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), ['langue_id' => $langue->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('niveau_id');
    }

    public function test_validation_langue_id_inexistant(): void
    {
        $candidat = $this->creerCandidat();
        $niveau   = $this->creerNiveau('B2', 4);

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => 9999,
                'niveau_id' => $niveau->id,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('langue_id');
    }

    public function test_validation_niveau_id_inexistant(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Anglais');

        $this->actingAs($candidat)
            ->postJson(route('candidat.profil.langues.store'), [
                'langue_id' => $langue->id,
                'niveau_id' => 9999,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('niveau_id');
    }

    // ── Suppression ───────────────────────────────────────

    public function test_suppression_langue_proprietaire(): void
    {
        $candidat = $this->creerCandidat();
        $langue   = $this->creerLangue('Allemand');
        $niveau   = $this->creerNiveau('A2', 2);

        $lc = LangueCandidat::create([
            'candidat_id' => $candidat->id,
            'langue_id'   => $langue->id,
            'niveau_id'   => $niveau->id,
        ]);

        $this->actingAs($candidat)
            ->deleteJson(route('candidat.profil.langues.destroy', $lc))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('langues_candidat', ['id' => $lc->id]);
    }

    public function test_suppression_refusee_pour_autre_candidat(): void
    {
        $proprietaire = $this->creerCandidat();
        $autre        = $this->creerCandidat();
        $langue       = $this->creerLangue('Italien');
        $niveau       = $this->creerNiveau('B2', 4);

        $lc = LangueCandidat::create([
            'candidat_id' => $proprietaire->id,
            'langue_id'   => $langue->id,
            'niveau_id'   => $niveau->id,
        ]);

        $this->actingAs($autre)
            ->deleteJson(route('candidat.profil.langues.destroy', $lc))
            ->assertForbidden();

        $this->assertDatabaseHas('langues_candidat', ['id' => $lc->id]);
    }

    // ── Accès ─────────────────────────────────────────────

    public function test_ajout_bloque_si_non_connecte(): void
    {
        $langue = $this->creerLangue('Français');
        $niveau = $this->creerNiveau('C2', 6);

        $this->postJson(route('candidat.profil.langues.store'), [
            'langue_id' => $langue->id,
            'niveau_id' => $niveau->id,
        ])->assertUnauthorized();
    }
}
