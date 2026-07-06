<?php

namespace Tests\Feature\Recruteur;

use App\Models\CV;
use App\Models\CvConsultation;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CvthequeAchatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Cache::flush();
    }

    private function creerRecruteur(int $credits = 0): User
    {
        $user = User::factory()->create(['role' => 'recruteur', 'cv_credits' => $credits]);
        $user->assignRole('recruteur');
        return $user;
    }

    private function creerCandidatAvecCv(array $attrs = []): CV
    {
        $candidat = User::factory()->candidat()->create(['actif' => true]);
        $candidat->assignRole('candidat');

        return CV::create(array_merge([
            'candidat_id' => $candidat->id,
            'metier'      => 'Développeur',
            'ville'       => 'Cotonou',
            'plan'        => 'gratuit',
            'visible'     => true,
            'publie_le'   => now(),
        ], $attrs));
    }

    public function test_recruteur_avec_credits_debloque_automatiquement_la_fiche_publique(): void
    {
        $recruteur = $this->creerRecruteur(credits: 3);
        $cv        = $this->creerCandidatAvecCv();

        $this->actingAs($recruteur)
            ->get(route('cv.public.detail', $cv))
            ->assertOk()
            ->assertSee('coordonnées débloquées')
            ->assertSee($cv->candidat->email);

        $this->assertDatabaseHas('cv_consultations', [
            'recruteur_id' => $recruteur->id,
            'cv_id'        => $cv->id,
        ]);
    }

    public function test_recruteur_sans_credits_voit_la_fiche_masquee(): void
    {
        $recruteur = $this->creerRecruteur(credits: 0);
        $cv        = $this->creerCandidatAvecCv();

        $this->actingAs($recruteur)
            ->get(route('cv.public.detail', $cv))
            ->assertOk()
            ->assertDontSee('coordonnées débloquées')
            ->assertDontSee($cv->candidat->email);

        $this->assertDatabaseMissing('cv_consultations', [
            'recruteur_id' => $recruteur->id,
            'cv_id'        => $cv->id,
        ]);
    }

    public function test_profil_deja_consulte_reste_debloque_meme_sans_credits_restants(): void
    {
        $recruteur = $this->creerRecruteur(credits: 1);
        $cv        = $this->creerCandidatAvecCv();

        // Première visite : débloque et (dans ce flux) ne consomme aucun crédit.
        $this->actingAs($recruteur)->get(route('cv.public.detail', $cv))->assertSee('coordonnées débloquées');

        $recruteur->update(['cv_credits' => 0]);

        $this->actingAs($recruteur->fresh())
            ->get(route('cv.public.detail', $cv))
            ->assertSee('coordonnées débloquées');
    }

    public function test_badge_deja_achete_visible_sur_la_liste_publique_apres_achat(): void
    {
        $recruteur = $this->creerRecruteur(credits: 2);
        $cv        = $this->creerCandidatAvecCv();

        $this->actingAs($recruteur)->get(route('cv.public.theque'))->assertDontSee('Déjà acheté');

        $this->actingAs($recruteur)->get(route('cv.public.detail', $cv));

        $this->actingAs($recruteur->fresh())
            ->get(route('cv.public.theque'))
            ->assertSee('Déjà acheté');
    }

    public function test_guest_ne_debloque_jamais_et_ne_cree_aucune_consultation(): void
    {
        $cv = $this->creerCandidatAvecCv();

        $this->get(route('cv.public.detail', $cv))
            ->assertOk()
            ->assertDontSee($cv->candidat->email);

        $this->assertDatabaseCount('cv_consultations', 0);
    }

    public function test_espace_acheter_des_cv_ne_montre_que_les_cv_deja_achetes(): void
    {
        $recruteur = $this->creerRecruteur(credits: 5);
        $cvAchete   = $this->creerCandidatAvecCv();
        $cvNonAchete = $this->creerCandidatAvecCv();

        CvConsultation::create(['recruteur_id' => $recruteur->id, 'cv_id' => $cvAchete->id]);

        $response = $this->actingAs($recruteur)->get(route('recruteur.cvtheque'));

        $response->assertOk()
            ->assertSee($cvAchete->candidat->nom_complet)
            ->assertDontSee($cvNonAchete->candidat->nom_complet);
    }
}
