<?php

namespace Tests\Feature\Public;

use App\Models\Competence;
use App\Models\Offre;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OffrePubliqueTest extends TestCase
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
        ], $attrs));
    }

    // ── Liste publique ────────────────────────────────────

    public function test_liste_offres_accessible_sans_connexion(): void
    {
        $this->get(route('offre.list'))->assertOk()->assertViewIs('public.offre.list');
    }

    public function test_liste_affiche_uniquement_les_offres_actives(): void
    {
        $recruteur = $this->creerRecruteur();
        $this->creerOffre($recruteur, ['titre' => 'Offre Active',   'statut' => 'active']);
        $this->creerOffre($recruteur, ['titre' => 'Offre Expirée',  'statut' => 'expiree']);
        $this->creerOffre($recruteur, ['titre' => 'Offre Clôturée', 'statut' => 'clos']);

        $this->get(route('offre.list'))
            ->assertSee('Offre Active')
            ->assertDontSee('Offre Expirée')
            ->assertDontSee('Offre Clôturée');
    }

    public function test_liste_filtre_par_type(): void
    {
        $recruteur = $this->creerRecruteur();
        $this->creerOffre($recruteur, ['titre' => 'Offre CDI',   'type' => 'CDI']);
        $this->creerOffre($recruteur, ['titre' => 'Offre Stage', 'type' => 'Stage']);

        $this->get(route('offre.list', ['type' => 'CDI']))
            ->assertSee('Offre CDI')
            ->assertDontSee('Offre Stage');
    }

    public function test_liste_filtre_par_competence(): void
    {
        $recruteur = $this->creerRecruteur();
        $comp      = Competence::create(['nom' => 'React', 'slug' => 'react']);
        $offreAvec = $this->creerOffre($recruteur, ['titre' => 'Poste React']);
        $offreSans = $this->creerOffre($recruteur, ['titre' => 'Poste Autre']);
        $offreAvec->competences()->attach($comp->id);

        $this->get(route('offre.list', ['competence' => 'react']))
            ->assertSee('Poste React')
            ->assertDontSee('Poste Autre');
    }

    // ── Détail offre ──────────────────────────────────────

    public function test_detail_accessible_sans_connexion(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->get(route('offre.detail', $offre))->assertOk()->assertViewIs('public.offre.detail');
    }

    public function test_detail_incremente_vue_une_seule_fois_par_session(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur, ['vues' => 0]);

        $this->get(route('offre.detail', $offre));
        $this->assertEquals(1, $offre->fresh()->vues);

        // Deuxième visite — même session — ne compte pas
        $this->get(route('offre.detail', $offre));
        $this->assertEquals(1, $offre->fresh()->vues);
    }

    public function test_detail_ne_compte_pas_la_vue_du_recruteur_proprietaire(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur, ['vues' => 0]);

        $this->actingAs($recruteur)->get(route('offre.detail', $offre));
        $this->assertEquals(0, $offre->fresh()->vues);
    }

    public function test_detail_incremente_vue_pour_un_autre_utilisateur(): void
    {
        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur, ['vues' => 5]);

        $this->actingAs($candidat)->get(route('offre.detail', $offre));
        $this->assertEquals(6, $offre->fresh()->vues);
    }

    public function test_detail_passe_est_sauvegarde_false_si_non_connecte(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->get(route('offre.detail', $offre))
            ->assertViewHas('estSauvegarde', false);
    }

    public function test_detail_passe_est_sauvegarde_true_si_sauvegarde(): void
    {
        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $candidat->offresSauvegardees()->attach($offre->id);

        $this->actingAs($candidat)
            ->get(route('offre.detail', $offre))
            ->assertViewHas('estSauvegarde', true);
    }

    public function test_detail_affiche_offres_similaires(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur, ['secteur' => 'Informatique']);
        $similaire = $this->creerOffre($recruteur, ['secteur' => 'Informatique', 'titre' => 'Offre Similaire Info']);

        $this->get(route('offre.detail', $offre))
            ->assertViewHas('similaires')
            ->assertSee('Offre Similaire Info');
    }

    public function test_detail_exclut_loffre_courante_des_similaires(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur, ['secteur' => 'Informatique']);

        $response = $this->get(route('offre.detail', $offre));
        $similaires = $response->viewData('similaires');

        $this->assertFalse($similaires->contains('id', $offre->id));
    }

    // ── Postuler ──────────────────────────────────────────

    public function test_postuler_redirige_vers_connexion_si_non_connecte(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->get(route('offre.postuler', $offre))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_postuler_accessible_au_candidat_connecte(): void
    {
        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($candidat)
            ->get(route('offre.postuler', $offre))
            ->assertOk()
            ->assertViewIs('public.offre.postuler');
    }

    // ── Sauvegarde / dé-sauvegarde ────────────────────────

    public function test_toggle_sauvegarde_ajoute_si_absent(): void
    {
        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);

        $this->actingAs($candidat)
            ->post(route('candidat.offres-sauvegardees.toggle', $offre))
            ->assertRedirect();

        $this->assertDatabaseHas('offres_sauvegardees', [
            'user_id'  => $candidat->id,
            'offre_id' => $offre->id,
        ]);
    }

    public function test_toggle_sauvegarde_retire_si_present(): void
    {
        $recruteur = $this->creerRecruteur();
        $candidat  = $this->creerCandidat();
        $offre     = $this->creerOffre($recruteur);
        $candidat->offresSauvegardees()->attach($offre->id);

        $this->actingAs($candidat)
            ->post(route('candidat.offres-sauvegardees.toggle', $offre))
            ->assertRedirect();

        $this->assertDatabaseMissing('offres_sauvegardees', [
            'user_id'  => $candidat->id,
            'offre_id' => $offre->id,
        ]);
    }

    public function test_toggle_sauvegarde_redirige_si_non_connecte(): void
    {
        $recruteur = $this->creerRecruteur();
        $offre     = $this->creerOffre($recruteur);

        $this->post(route('candidat.offres-sauvegardees.toggle', $offre))
            ->assertRedirect(route('auth.connexion'));
    }

    // ── Publication publique (formulaire /publier) ────────

    public function test_publier_redirige_si_non_connecte(): void
    {
        $this->get(route('offre.publier'))
            ->assertRedirect(route('auth.connexion'));
    }

    public function test_publier_bloque_un_candidat(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->get(route('offre.publier'))
            ->assertRedirect(route('candidat.dashboard'))
            ->assertSessionHas('error');
    }

    public function test_publier_accessible_au_recruteur(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->get(route('offre.publier'))
            ->assertOk()
            ->assertViewIs('public.offre.publier');
    }

    public function test_storer_offre_cree_en_attente(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('offre.publier.store'), [
                'titre'        => 'Développeur PHP',
                'entreprise'   => 'Acme',
                'localisation' => 'Cotonou',
                'type'         => 'CDI',
                'description'  => str_repeat('Lorem ipsum ', 10),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('offres', [
            'recruteur_id' => $recruteur->id,
            'titre'        => 'Développeur PHP',
            'statut'       => 'en_attente',
        ]);
    }

    public function test_storer_offre_bloque_un_candidat(): void
    {
        $candidat = $this->creerCandidat();

        $this->actingAs($candidat)
            ->post(route('offre.publier.store'), [
                'titre'        => 'Offre frauduleuse',
                'entreprise'   => 'Fausse',
                'localisation' => 'Cotonou',
                'type'         => 'CDI',
                'description'  => str_repeat('x', 60),
            ])
            ->assertRedirect(route('candidat.dashboard'))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('offres', ['recruteur_id' => $candidat->id]);
    }

    public function test_storer_offre_validation_titre_requis(): void
    {
        $recruteur = $this->creerRecruteur();

        $this->actingAs($recruteur)
            ->post(route('offre.publier.store'), [
                'titre'        => '',
                'entreprise'   => 'Acme',
                'localisation' => 'Cotonou',
                'type'         => 'CDI',
                'description'  => str_repeat('Lorem ', 20),
            ])
            ->assertSessionHasErrors('titre');
    }
}
