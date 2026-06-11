<?php

namespace Tests\Feature\Admin;

use App\Models\Competence;
use App\Models\Langue;
use App\Models\LangueCandidat;
use App\Models\Metier;
use App\Models\NiveauEtude;
use App\Models\NiveauExperience;
use App\Models\NiveauLangue;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ReferentielControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    // ── Helpers ───────────────────────────────────────────

    private function creerAdmin(): User
    {
        $user = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $user->assignRole('admin');
        return $user;
    }

    private function creerCandidat(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');
        return $user;
    }

    // ══════════════════════════════════════════════════════
    //  CONTRÔLE D'ACCÈS
    // ══════════════════════════════════════════════════════

    public function test_admin_peut_acceder_dashboard(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_candidat_ne_peut_pas_acceder_admin(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->get(route('admin.competences.index'))
            ->assertForbidden();
    }

    public function test_invite_redirige_vers_connexion(): void
    {
        $this->get(route('admin.competences.index'))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_candidat_ne_peut_pas_detruire_referentiel(): void
    {
        $candidat   = $this->creerCandidat();
        $competence = Competence::create(['nom' => 'Laravel', 'slug' => 'laravel']);

        $this->actingAs($candidat)
            ->deleteJson(route('admin.competences.destroy', $competence))
            ->assertForbidden();
    }

    // ══════════════════════════════════════════════════════
    //  COMPÉTENCES (nom, slug)
    // ══════════════════════════════════════════════════════

    public function test_competences_index_accessible(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.competences.index'))
            ->assertOk()
            ->assertViewIs('admin.referentiels.index');
    }

    public function test_competence_creation_valide(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.competences.store'), ['nom' => 'Vue.js'])
            ->assertRedirect(route('admin.competences.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('competences', ['nom' => 'Vue.js']);
    }

    public function test_competence_slug_genere_automatiquement(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.competences.store'), ['nom' => 'Node JS']);

        $this->assertDatabaseHas('competences', ['slug' => 'node-js']);
    }

    public function test_competence_store_validation_nom_requis(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.competences.store'), [])
            ->assertSessionHasErrors('nom');
    }

    public function test_competence_store_validation_nom_unique(): void
    {
        $admin = $this->creerAdmin();
        Competence::create(['nom' => 'Laravel', 'slug' => 'laravel']);

        $this->actingAs($admin)
            ->post(route('admin.competences.store'), ['nom' => 'Laravel'])
            ->assertSessionHasErrors('nom');
    }

    public function test_competence_update_valide(): void
    {
        $admin      = $this->creerAdmin();
        $competence = Competence::create(['nom' => 'Vue', 'slug' => 'vue']);

        $this->actingAs($admin)
            ->put(route('admin.competences.update', $competence), ['nom' => 'Vue.js'])
            ->assertRedirect(route('admin.competences.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('competences', ['id' => $competence->id, 'nom' => 'Vue.js']);
    }

    public function test_competence_update_accepte_meme_nom(): void
    {
        $admin      = $this->creerAdmin();
        $competence = Competence::create(['nom' => 'Laravel', 'slug' => 'laravel']);

        $this->actingAs($admin)
            ->put(route('admin.competences.update', $competence), ['nom' => 'Laravel'])
            ->assertRedirect(route('admin.competences.index'));

        $this->assertDatabaseHas('competences', ['id' => $competence->id, 'nom' => 'Laravel']);
    }

    public function test_competence_destroy_retourne_json_succes(): void
    {
        $admin      = $this->creerAdmin();
        $competence = Competence::create(['nom' => 'À supprimer', 'slug' => 'a-supprimer']);

        $this->actingAs($admin)
            ->deleteJson(route('admin.competences.destroy', $competence))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('competences', ['id' => $competence->id]);
    }

    // ══════════════════════════════════════════════════════
    //  LANGUES (nom)
    // ══════════════════════════════════════════════════════

    public function test_langues_index_accessible(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.langues.index'))
            ->assertOk();
    }

    public function test_langue_creation_valide(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.langues.store'), ['nom' => 'Swahili'])
            ->assertRedirect(route('admin.langues.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('langues', ['nom' => 'Swahili']);
    }

    public function test_langue_store_validation_nom_requis(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.langues.store'), [])
            ->assertSessionHasErrors('nom');
    }

    public function test_langue_store_validation_nom_unique(): void
    {
        $admin = $this->creerAdmin();
        Langue::create(['nom' => 'Français']);

        $this->actingAs($admin)
            ->post(route('admin.langues.store'), ['nom' => 'Français'])
            ->assertSessionHasErrors('nom');
    }

    public function test_langue_update_valide(): void
    {
        $admin  = $this->creerAdmin();
        $langue = Langue::create(['nom' => 'Anglais']);

        $this->actingAs($admin)
            ->put(route('admin.langues.update', $langue), ['nom' => 'English'])
            ->assertRedirect(route('admin.langues.index'));

        $this->assertDatabaseHas('langues', ['id' => $langue->id, 'nom' => 'English']);
    }

    public function test_langue_destroy_retourne_json_succes(): void
    {
        $admin  = $this->creerAdmin();
        $langue = Langue::create(['nom' => 'À supprimer']);

        $this->actingAs($admin)
            ->deleteJson(route('admin.langues.destroy', $langue))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('langues', ['id' => $langue->id]);
    }

    // ══════════════════════════════════════════════════════
    //  TYPES DE CONTRAT (code + libelle)
    // ══════════════════════════════════════════════════════

    public function test_types_contrat_index_accessible(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.types-contrat.index'))
            ->assertOk();
    }

    public function test_type_contrat_creation_valide(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.types-contrat.store'), [
                'code'    => 'cdi',
                'libelle' => 'CDI',
            ])
            ->assertRedirect(route('admin.types-contrat.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('type_contrats', ['code' => 'cdi', 'libelle' => 'CDI']);
    }

    public function test_type_contrat_store_validation_code_requis(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.types-contrat.store'), ['libelle' => 'CDI'])
            ->assertSessionHasErrors('code');
    }

    public function test_type_contrat_store_validation_libelle_requis(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.types-contrat.store'), ['code' => 'cdi'])
            ->assertSessionHasErrors('libelle');
    }

    public function test_type_contrat_store_validation_code_unique(): void
    {
        $admin = $this->creerAdmin();
        TypeContrat::create(['code' => 'cdi', 'libelle' => 'CDI']);

        $this->actingAs($admin)
            ->post(route('admin.types-contrat.store'), ['code' => 'cdi', 'libelle' => 'CDI duplicata'])
            ->assertSessionHasErrors('code');
    }

    public function test_type_contrat_update_valide(): void
    {
        $admin      = $this->creerAdmin();
        $tc = TypeContrat::create(['code' => 'cdd', 'libelle' => 'Contrat à durée déterminée']);

        $this->actingAs($admin)
            ->put(route('admin.types-contrat.update', $tc), [
                'code'    => 'cdd',
                'libelle' => 'CDD',
            ])
            ->assertRedirect(route('admin.types-contrat.index'));

        $this->assertDatabaseHas('type_contrats', ['id' => $tc->id, 'libelle' => 'CDD']);
    }

    public function test_type_contrat_destroy_retourne_json_succes(): void
    {
        $admin = $this->creerAdmin();
        $tc    = TypeContrat::create(['code' => 'stage', 'libelle' => 'Stage']);

        $this->actingAs($admin)
            ->deleteJson(route('admin.types-contrat.destroy', $tc))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('type_contrats', ['id' => $tc->id]);
    }

    // ══════════════════════════════════════════════════════
    //  NIVEAUX DE LANGUE (code + libelle + ordre)
    // ══════════════════════════════════════════════════════

    public function test_niveaux_langue_index_accessible(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.niveaux-langue.index'))
            ->assertOk();
    }

    public function test_niveau_langue_creation_valide(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.niveaux-langue.store'), [
                'code'    => 'B2',
                'libelle' => 'Intermédiaire supérieur',
                'ordre'   => 4,
            ])
            ->assertRedirect(route('admin.niveaux-langue.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('niveaux_langue', ['code' => 'B2', 'ordre' => 4]);
    }

    public function test_niveau_langue_store_validation_code_requis(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.niveaux-langue.store'), [
                'libelle' => 'Test',
                'ordre'   => 1,
            ])
            ->assertSessionHasErrors('code');
    }

    public function test_niveau_langue_store_validation_ordre_requis(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.niveaux-langue.store'), [
                'code'    => 'A1',
                'libelle' => 'Débutant',
            ])
            ->assertSessionHasErrors('ordre');
    }

    public function test_niveau_langue_store_validation_code_unique(): void
    {
        $admin = $this->creerAdmin();
        NiveauLangue::create(['code' => 'B2', 'libelle' => 'Intermédiaire sup.', 'ordre' => 4]);

        $this->actingAs($admin)
            ->post(route('admin.niveaux-langue.store'), [
                'code'    => 'B2',
                'libelle' => 'Autre libellé',
                'ordre'   => 9,
            ])
            ->assertSessionHasErrors('code');
    }

    public function test_niveau_langue_update_valide(): void
    {
        $admin  = $this->creerAdmin();
        $niveau = NiveauLangue::create(['code' => 'A1', 'libelle' => 'Débutant', 'ordre' => 1]);

        $this->actingAs($admin)
            ->put(route('admin.niveaux-langue.update', $niveau), [
                'code'    => 'A1',
                'libelle' => 'Débutant confirmé',
                'ordre'   => 1,
            ])
            ->assertRedirect(route('admin.niveaux-langue.index'));

        $this->assertDatabaseHas('niveaux_langue', [
            'id'      => $niveau->id,
            'libelle' => 'Débutant confirmé',
        ]);
    }

    public function test_niveau_langue_destroy_retourne_json_succes(): void
    {
        $admin  = $this->creerAdmin();
        $niveau = NiveauLangue::create(['code' => 'ZZ', 'libelle' => 'À supprimer', 'ordre' => 99]);

        $this->actingAs($admin)
            ->deleteJson(route('admin.niveaux-langue.destroy', $niveau))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('niveaux_langue', ['id' => $niveau->id]);
    }

    public function test_niveau_langue_destroy_echoue_si_utilise_par_candidat(): void
    {
        $admin    = $this->creerAdmin();
        $candidat = $this->creerCandidat();
        $langue   = Langue::create(['nom' => 'Français']);
        $niveau   = NiveauLangue::create(['code' => 'C2', 'libelle' => 'Maîtrise', 'ordre' => 6]);

        LangueCandidat::create([
            'candidat_id' => $candidat->id,
            'langue_id'   => $langue->id,
            'niveau_id'   => $niveau->id,
        ]);

        $this->actingAs($admin)
            ->deleteJson(route('admin.niveaux-langue.destroy', $niveau))
            ->assertStatus(422)
            ->assertJsonPath('success', false);

        $this->assertDatabaseHas('niveaux_langue', ['id' => $niveau->id]);
    }

    // ══════════════════════════════════════════════════════
    //  NIVEAUX D'ÉTUDE (code + libelle + ordre)
    // ══════════════════════════════════════════════════════

    public function test_niveaux_etude_index_accessible(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.niveaux-etude.index'))
            ->assertOk();
    }

    public function test_niveau_etude_creation_valide(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.niveaux-etude.store'), [
                'code'    => 'LICENCE',
                'libelle' => 'Licence (Bac+3)',
                'ordre'   => 5,
            ])
            ->assertRedirect(route('admin.niveaux-etude.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('niveaux_etudes', ['code' => 'LICENCE']);
    }

    public function test_niveau_etude_destroy_retourne_json_succes(): void
    {
        $admin  = $this->creerAdmin();
        $niveau = NiveauEtude::create(['code' => 'TEST', 'libelle' => 'Test', 'ordre' => 99]);

        $this->actingAs($admin)
            ->deleteJson(route('admin.niveaux-etude.destroy', $niveau))
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    // ══════════════════════════════════════════════════════
    //  NIVEAUX D'EXPÉRIENCE (code + libelle + ordre)
    // ══════════════════════════════════════════════════════

    public function test_niveaux_experience_index_accessible(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.niveaux-experience.index'))
            ->assertOk();
    }

    public function test_niveau_experience_creation_valide(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.niveaux-experience.store'), [
                'code'    => 'SENIOR',
                'libelle' => 'Senior (5+ ans)',
                'ordre'   => 4,
            ])
            ->assertRedirect(route('admin.niveaux-experience.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('niveaux_experience', ['code' => 'SENIOR']);
    }

    public function test_niveau_experience_destroy_retourne_json_succes(): void
    {
        $admin  = $this->creerAdmin();
        $niveau = NiveauExperience::create(['code' => 'TMP', 'libelle' => 'Temporaire', 'ordre' => 99]);

        $this->actingAs($admin)
            ->deleteJson(route('admin.niveaux-experience.destroy', $niveau))
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    // ══════════════════════════════════════════════════════
    //  MÉTIERS (nom + description optionnelle)
    // ══════════════════════════════════════════════════════

    public function test_metiers_index_accessible(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.metiers.index'))
            ->assertOk();
    }

    public function test_metier_creation_valide(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.metiers.store'), [
                'nom'         => 'Développeur backend',
                'description' => 'Conception et développement de la logique serveur.',
            ])
            ->assertRedirect(route('admin.metiers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('metiers', ['nom' => 'Développeur backend']);
    }

    public function test_metier_store_validation_nom_requis(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.metiers.store'), [])
            ->assertSessionHasErrors('nom');
    }

    public function test_metier_update_valide(): void
    {
        $admin  = $this->creerAdmin();
        $metier = Metier::create(['nom' => 'Dev web', 'slug' => 'dev-web']);

        $this->actingAs($admin)
            ->put(route('admin.metiers.update', $metier), ['nom' => 'Développeur web'])
            ->assertRedirect(route('admin.metiers.index'));

        $this->assertDatabaseHas('metiers', ['id' => $metier->id, 'nom' => 'Développeur web']);
    }

    public function test_metier_destroy_retourne_json_succes(): void
    {
        $admin  = $this->creerAdmin();
        $metier = Metier::create(['nom' => 'À supprimer', 'slug' => 'a-supprimer-2']);

        $this->actingAs($admin)
            ->deleteJson(route('admin.metiers.destroy', $metier))
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    // ══════════════════════════════════════════════════════
    //  SECTEURS D'ACTIVITÉ (code + libelle)
    // ══════════════════════════════════════════════════════

    public function test_secteurs_activite_index_accessible(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->get(route('admin.secteurs-activite.index'))
            ->assertOk();
    }

    public function test_secteur_activite_creation_valide(): void
    {
        $admin = $this->creerAdmin();

        $this->actingAs($admin)
            ->post(route('admin.secteurs-activite.store'), [
                'code'    => 'IT',
                'libelle' => 'Informatique & Technologies',
            ])
            ->assertRedirect(route('admin.secteurs-activite.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('secteurs_activite', ['code' => 'IT']);
    }

    public function test_secteur_activite_destroy_retourne_json_succes(): void
    {
        $admin   = $this->creerAdmin();
        $secteur = SecteurActivite::create(['code' => 'TMP', 'libelle' => 'Temporaire']);

        $this->actingAs($admin)
            ->deleteJson(route('admin.secteurs-activite.destroy', $secteur))
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
