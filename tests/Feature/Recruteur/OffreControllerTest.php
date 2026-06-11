<?php

namespace Tests\Feature\Recruteur;

use App\Models\Competence;
use App\Models\Offre;
use App\Models\RecruteurVerification;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OffreControllerTest extends TestCase
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
        // Vérification approuvée pour passer le middleware recruteur.approuve
        RecruteurVerification::create([
            'user_id' => $user->id,
            'statut'  => 'approuve',
        ]);
        return $user;
    }

    private function creerCandidat(): User
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');
        return $user;
    }

    private function creerOffre(User $recruteur, array $attrs = []): Offre
    {
        return Offre::factory()->create(array_merge([
            'recruteur_id' => $recruteur->id,
            'entreprise'   => $recruteur->entreprise ?? 'Acme',
        ], $attrs));
    }

    private function donneesValides(array $override = []): array
    {
        return array_merge([
            'titre'        => 'Développeur Laravel',
            'entreprise'   => 'Acme Corp',
            'localisation' => 'Cotonou',
            'type'         => 'CDI',
            'description'  => str_repeat('Lorem ipsum ', 10),
        ], $override);
    }

    // ── Index (liste) ─────────────────────────────────────

    public function test_liste_offres_accessible_au_recruteur(): void
    {
        $recruteur = $this->creerRecruteur();
        $this->creerOffre($recruteur);

        $this->actingAs($recruteur)
            ->get(route('recruteur.offres'))
            ->assertOk()
            ->assertViewIs('recruteur.offres');
    }

    public function test_liste_offres_redirige_si_non_connecte(): void
    {
        $this->get(route('recruteur.offres'))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_liste_offres_filtree_par_statut(): void
    {
        $recruteur = $this->creerRecruteur();
        $this->creerOffre($recruteur, ['statut' => 'active',  'titre' => 'Poste Comptable']);
        $this->creerOffre($recruteur, ['statut' => 'expiree', 'titre' => 'Poste Juriste']);

        $response = $this->actingAs($recruteur)
            ->get(route('recruteur.offres', ['statut' => 'active']));

        $response->assertOk()
            ->assertSee('Poste Comptable')
            ->assertDontSee('Poste Juriste');
    }

    public function test_liste_offres_filtree_par_recherche(): void
    {
        $recruteur = $this->creerRecruteur();
        $this->creerOffre($recruteur, ['titre' => 'Comptable Senior']);
        $this->creerOffre($recruteur, ['titre' => 'Développeur PHP']);

        $this->actingAs($recruteur)
            ->get(route('recruteur.offres', ['q' => 'Comptable']))
            ->assertOk()
            ->assertSee('Comptable Senior')
            ->assertDontSee('Développeur PHP');
    }

    // ── Création (store) ──────────────────────────────────

    public function test_store_cree_offre_active(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), $this->donneesValides())
            ->assertRedirect(route('recruteur.offres'));

        $this->assertDatabaseHas('offres', [
            'recruteur_id' => $recruteur->id,
            'titre'        => 'Développeur Laravel',
            'statut'       => 'active',
        ]);
    }

    public function test_store_avec_fichier_le_stocke(): void
    {
        Storage::fake('public');
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), $this->donneesValides([
                'fichier' => UploadedFile::fake()->create('annonce.pdf', 500, 'application/pdf'),
            ]))
            ->assertRedirect(route('recruteur.offres'));

        $offre = Offre::where('recruteur_id', $recruteur->id)->first();
        $this->assertNotNull($offre->fichier);
        Storage::disk('public')->assertExists($offre->fichier);
    }

    public function test_store_sync_competences(): void
    {
        $recruteur = $this->creerRecruteur();
        $comp      = Competence::create(['nom' => 'PHP', 'slug' => 'php']);

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), $this->donneesValides([
                'competences' => ['PHP'],
            ]));

        $offre = Offre::where('recruteur_id', $recruteur->id)->first();
        $this->assertTrue($offre->competences->contains('slug', 'php'));
    }

    public function test_store_cree_competence_inconnue_a_la_volee(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), $this->donneesValides([
                'competences' => ['NouvelleCompétence2025'],
            ]));

        $this->assertDatabaseHas('competences', ['slug' => 'nouvellecompetence2025']);
    }

    public function test_store_validation_titre_requis(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), $this->donneesValides(['titre' => '']))
            ->assertSessionHasErrors('titre');
    }

    public function test_store_validation_description_min_50_caracteres(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), $this->donneesValides(['description' => 'Trop court']))
            ->assertSessionHasErrors('description');
    }

    public function test_store_validation_type_contrat_invalide(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), $this->donneesValides(['type' => 'INVALIDE']))
            ->assertSessionHasErrors('type');
    }

    public function test_store_validation_fichier_type_invalide(): void
    {
        Storage::fake('public');
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), $this->donneesValides([
                'fichier' => UploadedFile::fake()->create('script.exe', 100),
            ]))
            ->assertSessionHasErrors('fichier');
    }

    // ── Modification (update) ─────────────────────────────

    public function test_update_modifie_offre(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($recruteur)
            ->put(route('recruteur.offres.update', $offre), $this->donneesValides([
                'titre' => 'Titre modifié',
            ]))
            ->assertRedirect(route('recruteur.offres'));

        $this->assertDatabaseHas('offres', ['id' => $offre->id, 'titre' => 'Titre modifié']);
    }

    public function test_update_remplace_fichier_et_supprime_lancien(): void
    {
        Storage::fake('public');
        $recruteur = $this->creerRecruteur();
        Storage::disk('public')->put('offres/fichiers/ancien.pdf', 'x');
        $offre = $this->creerOffre($recruteur, ['fichier' => 'offres/fichiers/ancien.pdf']);

        $this->actingAs($recruteur)
            ->put(route('recruteur.offres.update', $offre), $this->donneesValides([
                'fichier' => UploadedFile::fake()->create('nouveau.pdf', 400, 'application/pdf'),
            ]))
            ->assertRedirect();

        Storage::disk('public')->assertMissing('offres/fichiers/ancien.pdf');
        $this->assertNotEquals('offres/fichiers/ancien.pdf', $offre->fresh()->fichier);
    }

    public function test_update_supprime_fichier_avec_checkbox(): void
    {
        Storage::fake('public');
        $recruteur = $this->creerRecruteur();
        Storage::disk('public')->put('offres/fichiers/doc.pdf', 'x');
        $offre = $this->creerOffre($recruteur, ['fichier' => 'offres/fichiers/doc.pdf']);

        $this->actingAs($recruteur)
            ->put(route('recruteur.offres.update', $offre), $this->donneesValides([
                '_supprimer_fichier' => '1',
            ]))
            ->assertRedirect();

        Storage::disk('public')->assertMissing('offres/fichiers/doc.pdf');
        $this->assertNull($offre->fresh()->fichier);
    }

    public function test_update_interdit_a_un_autre_recruteur(): void
    {
        $proprietaire = $this->creerRecruteur();
        $autre        = $this->creerRecruteur();
        $offre        = $this->creerOffre($proprietaire);

        $this->actingAs($autre)
            ->put(route('recruteur.offres.update', $offre), $this->donneesValides())
            ->assertForbidden();
    }

    // ── Suppression ───────────────────────────────────────

    public function test_destroy_supprime_offre(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($recruteur)
            ->delete(route('recruteur.offres.destroy', $offre))
            ->assertRedirect(route('recruteur.offres'));

        $this->assertDatabaseMissing('offres', ['id' => $offre->id]);
    }

    public function test_destroy_supprime_le_fichier_joint(): void
    {
        Storage::fake('public');
        $recruteur = $this->creerRecruteur();
        Storage::disk('public')->put('offres/fichiers/fichier.pdf', 'x');
        $offre = $this->creerOffre($recruteur, ['fichier' => 'offres/fichiers/fichier.pdf']);

        $this->actingAs($recruteur)
            ->delete(route('recruteur.offres.destroy', $offre));

        Storage::disk('public')->assertMissing('offres/fichiers/fichier.pdf');
    }

    public function test_destroy_interdit_a_un_autre_recruteur(): void
    {
        $proprietaire = $this->creerRecruteur();
        $autre        = $this->creerRecruteur();
        $offre        = $this->creerOffre($proprietaire);

        $this->actingAs($autre)
            ->delete(route('recruteur.offres.destroy', $offre))
            ->assertForbidden();

        $this->assertDatabaseHas('offres', ['id' => $offre->id]);
    }

    // ── Clôture ───────────────────────────────────────────

    public function test_cloturer_passe_statut_a_clos(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur, ['statut' => 'active']);

        $this->actingAs($recruteur)
            ->patch(route('recruteur.offres.cloturer', $offre))
            ->assertRedirect();

        $this->assertDatabaseHas('offres', ['id' => $offre->id, 'statut' => 'clos']);
    }

    public function test_cloturer_interdit_a_un_autre_recruteur(): void
    {
        $proprietaire = $this->creerRecruteur();
        $autre        = $this->creerRecruteur();
        $offre        = $this->creerOffre($proprietaire, ['statut' => 'active']);

        $this->actingAs($autre)
            ->patch(route('recruteur.offres.cloturer', $offre))
            ->assertForbidden();

        $this->assertDatabaseHas('offres', ['id' => $offre->id, 'statut' => 'active']);
    }

    // ── Duplication ───────────────────────────────────────

    public function test_dupliquer_cree_une_copie(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur, ['titre' => 'Offre Originale']);

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.dupliquer', $offre))
            ->assertRedirect();

        $this->assertDatabaseCount('offres', 2);
        $this->assertDatabaseHas('offres', ['titre' => 'Offre Originale (copie)', 'statut' => 'active']);
    }

    public function test_dupliquer_copie_les_competences(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);
        $comp      = Competence::create(['nom' => 'Laravel', 'slug' => 'laravel']);
        $offre->competences()->attach($comp->id);

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.dupliquer', $offre));

        $copie = Offre::where('titre', 'like', '%(copie)')->first();
        $this->assertNotNull($copie);
        $this->assertTrue($copie->competences->contains('slug', 'laravel'));
    }

    public function test_dupliquer_remet_vues_a_zero(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur, ['vues' => 150]);

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.dupliquer', $offre));

        $copie = Offre::where('titre', 'like', '%(copie)')->first();
        $this->assertEquals(0, $copie->vues);
    }

    public function test_dupliquer_interdit_a_un_autre_recruteur(): void
    {
        $proprietaire = $this->creerRecruteur();
        $autre        = $this->creerRecruteur();
        $offre        = $this->creerOffre($proprietaire);

        $this->actingAs($autre)
            ->post(route('recruteur.offres.dupliquer', $offre))
            ->assertForbidden();

        $this->assertDatabaseCount('offres', 1);
    }

    // ── Statistiques par offre ────────────────────────────

    public function test_stats_accessible_au_proprietaire(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($recruteur)
            ->get(route('recruteur.offres.stats', $offre))
            ->assertOk()
            ->assertViewIs('recruteur.offre-stats');
    }

    public function test_stats_interdit_a_un_autre_recruteur(): void
    {
        $proprietaire = $this->creerRecruteur();
        $autre        = $this->creerRecruteur();
        $offre        = $this->creerOffre($proprietaire);

        $this->actingAs($autre)
            ->get(route('recruteur.offres.stats', $offre))
            ->assertForbidden();
    }
}
