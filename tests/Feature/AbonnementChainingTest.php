<?php

namespace Tests\Feature;

use App\Events\PaymentConfirmed;
use App\Models\Abonnement;
use App\Models\Alerte;
use App\Models\Candidature;
use App\Models\CV;
use App\Models\Offre;
use App\Models\Paiement;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\RecruteurVerification;
use App\Models\User;
use App\Services\CvQuotaService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Décision explicite de l'utilisateur : souscrire à un nouvel abonnement
 * pendant qu'un autre est encore valide ne doit jamais l'annuler — le nouveau
 * prend le relais automatiquement à l'expiration de l'ancien (comme deux
 * forfaits qui se suivent, jamais l'un n'écrase l'autre prématurément).
 */
class AbonnementChainingTest extends TestCase
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

    private function creerRecruteur(): User
    {
        $user = User::factory()->recruteur()->create();
        $user->assignRole('recruteur');
        RecruteurVerification::create(['user_id' => $user->id, 'statut' => 'approuve']);
        return $user;
    }

    private function creerPlanGratuit(string $cible): Plan
    {
        return Plan::create([
            'name' => 'Gratuit', 'slug' => 'gratuit-'.uniqid(), 'target_type' => $cible,
            'price' => 0, 'duration_days' => 15, 'is_free' => true, 'is_active' => true,
        ]);
    }

    private function creerPlanPayant(string $cible): Plan
    {
        return Plan::create([
            'name' => 'Premium', 'slug' => 'premium-'.uniqid(), 'target_type' => $cible,
            'price' => 5000, 'currency' => 'XOF', 'duration_days' => 30, 'is_free' => false, 'is_active' => true,
        ]);
    }

    private function creerPlanAvecFeature(string $cible, string $cle, string $valeur): Plan
    {
        $plan = Plan::create([
            'name' => 'Plan-'.$cle.'-'.$valeur, 'slug' => 'plan-'.uniqid(), 'target_type' => $cible,
            'price' => 5000, 'currency' => 'XOF', 'duration_days' => 30, 'is_free' => false, 'is_active' => true,
        ]);
        PlanFeature::create(['plan_id' => $plan->id, 'feature_key' => $cle, 'feature_value' => $valeur]);
        return $plan;
    }

    /**
     * Décision explicite du client : les deux abonnements restent "actifs",
     * mais dès que l'actuel épuise l'un de ses avantages (ici le quota CV),
     * le suivant déjà souscrit prend le relais immédiatement — sans attendre
     * sa date de départ programmée.
     */
    public function test_quota_cv_epuise_declenche_le_relais_anticipe(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '1');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        CV::create(['candidat_id' => $candidat->id]);

        $nouveauPlan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        $nouveau = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $nouveauPlan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $quota = app(CvQuotaService::class)->quotaFor($candidat->fresh());

        // Le nouveau plan (5 CV) prend le relais tout de suite : quota non
        // atteint (1 CV sur 5), au lieu d'attendre 9 jours.
        $this->assertFalse($quota['reached']);
        $this->assertSame(5, $quota['limit']);
        $this->assertTrue($ancien->fresh()->ends_at->lessThanOrEqualTo(now()));
        $this->assertTrue($nouveau->fresh()->starts_at->lessThanOrEqualTo(now()));
    }

    /**
     * Chantier B (décision produit confirmée) : renouveler le MÊME plan
     * pendant que le quota STOCK (CV) est déjà au plafond ne débloque jamais
     * plus de places (le plafond reste celui du plan, identique des deux
     * côtés) — mais le paiement ne doit plus non plus rester sans aucun
     * effet : la date de fin GLOBALE de l'abonnement actuel est prolongée de
     * la durée du nouveau plan, sans aucun reset de compteur. Remplace
     * l'ancien comportement (aucune bascule du tout), qui laissait le
     * renouvellement "dormir" jusqu'à l'échéance sans rien apporter à
     * l'utilisateur dans l'immédiat.
     */
    public function test_renouvellement_identique_avec_stock_epuise_prolonge_la_date_sans_reset(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        for ($i = 0; $i < 5; $i++) {
            CV::create(['candidat_id' => $candidat->id]);
        }
        $finAvantFusion = $ancien->ends_at->copy();

        // Renouvellement du même plan, programmé pour prendre le relais dans 9 jours.
        $renouvellement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $quota = app(CvQuotaService::class)->quotaFor($candidat->fresh());

        // Plafond inchangé, toujours plein — aucun CV supplémentaire débloqué.
        $this->assertTrue($quota['reached']);
        $this->assertSame(5, $quota['limit']);
        $this->assertSame(5, CV::where('candidat_id', $candidat->id)->count());

        // Mais la date de fin de l'abonnement actuel a bien été prolongée de
        // la durée du plan renouvelé (30 jours) : aucun jour payé perdu.
        $ancien->refresh();
        $this->assertTrue($ancien->ends_at->equalTo($finAvantFusion->copy()->addDays(30)));

        // Le renouvellement est neutralisé : sa fenêtre est réduite à un
        // instant, il ne doit plus jamais réapparaître comme "prochain".
        $renouvellement->refresh();
        $this->assertTrue($renouvellement->starts_at->equalTo($renouvellement->ends_at));
    }

    /**
     * Chantier B, cas symétrique : renouvellement identique SANS aucun quota
     * épuisé — rien ne doit changer du tout, ni la date de fin de l'actuel,
     * ni le renouvellement programmé.
     */
    public function test_renouvellement_identique_sans_quota_epuise_ne_change_rien(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        CV::create(['candidat_id' => $candidat->id]);
        CV::create(['candidat_id' => $candidat->id]);
        $finOriginale = $ancien->ends_at->copy();

        $renouvellement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);
        $debutOriginal = $renouvellement->starts_at->copy();

        $quota = app(CvQuotaService::class)->quotaFor($candidat->fresh());

        $this->assertFalse($quota['reached']);
        $ancien->refresh();
        $this->assertTrue($ancien->ends_at->equalTo($finOriginale));
        $renouvellement->refresh();
        $this->assertTrue($renouvellement->starts_at->equalTo($debutOriginal));
    }

    /**
     * Chantier B, quota STOCK alert_limit (le sujet central de
     * l'investigation) : même comportement que pour cv_limit — le plafond ne
     * bouge pas, mais la date de fin globale est prolongée.
     */
    public function test_renouvellement_identique_avec_alert_limit_epuise_prolonge_la_date_sans_reset(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'alert_limit', '2');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        Alerte::create(['user_id' => $candidat->id, 'nom' => 'A1', 'frequence' => 'immediat', 'active' => true, 'metier' => 'Comptable']);
        Alerte::create(['user_id' => $candidat->id, 'nom' => 'A2', 'frequence' => 'immediat', 'active' => true, 'metier' => 'Vendeur']);
        $finAvantFusion = $ancien->ends_at->copy();

        $renouvellement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), ['metier' => 'Développeur', 'frequence' => 'immediat'])
            ->assertSessionHas('error');

        $this->assertSame(2, Alerte::where('user_id', $candidat->id)->count());

        $ancien->refresh();
        $this->assertTrue($ancien->ends_at->equalTo($finAvantFusion->copy()->addDays(30)));

        $renouvellement->refresh();
        $this->assertTrue($renouvellement->starts_at->equalTo($renouvellement->ends_at));
    }

    /**
     * Chantier B, quota FLUX job_post_limit : le relais anticipé sur
     * renouvellement identique ne touche QUE l'horloge de ce quota précis
     * (QuotaCycle) — la date de fin GLOBALE de l'abonnement, elle, ne bouge
     * pas (contrairement au cas STOCK ci-dessus).
     */
    public function test_renouvellement_identique_avec_flux_job_post_limit_epuise_reset_uniquement_ce_quota(): void
    {
        $recruteur = $this->creerRecruteur();
        $plan = $this->creerPlanAvecFeature('recruteur', 'job_post_limit', '1');
        $ancien = Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        // created_at nettement avant l'instant de la fusion (pas juste
        // "now()") : la précision DB est à la seconde, et le reset du cycle
        // se produit dans la même requête — sans cette marge, l'offre
        // existante tomberait dans la même seconde que le reset et resterait
        // comptée par erreur (>= inclusif), ce qui ne peut jamais arriver en
        // production où la fusion a toujours lieu bien après le dépôt.
        Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active', 'created_at' => now()->subMinute()]);
        $finAvantFusion = $ancien->ends_at->copy();

        $renouvellement = Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $typeContrat = \App\Models\TypeContrat::firstOrCreate(['code' => 'CDI'], ['libelle' => 'CDI']);

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), [
                'titre' => 'Comptable senior', 'entreprise' => 'ACME', 'localisation' => 'Cotonou',
                'type' => $typeContrat->id,
                'description' => str_repeat('Une description suffisamment longue pour passer la validation. ', 3),
            ])
            ->assertRedirect(route('recruteur.offres'));

        // La 2e offre a bien pu être publiée : le compteur FLUX est reparti à
        // zéro sans attendre l'échéance normale.
        $this->assertSame(2, Offre::where('recruteur_id', $recruteur->id)->count());

        // L'abonnement lui-même (date de fin globale) n'a PAS bougé — seul le
        // cycle FLUX de job_post_limit a été réinitialisé.
        $ancien->refresh();
        $this->assertTrue($ancien->ends_at->equalTo($finAvantFusion));

        $renouvellement->refresh();
        $this->assertTrue($renouvellement->starts_at->equalTo($renouvellement->ends_at));

        // L'horloge du quota a sa propre nouvelle échéance : ancienne
        // échéance du cycle + durée du nouveau plan (30 jours).
        $cycle = \App\Models\QuotaCycle::where('user_id', $recruteur->id)->where('quota_key', 'job_post_limit')->first();
        $this->assertNotNull($cycle);
        $this->assertTrue($cycle->cycle_ends_at->equalTo($finAvantFusion->copy()->addDays(30)));
    }

    /**
     * Chantier B, quota FLUX job_apply_limit : ce quota n'est aujourd'hui
     * affiché nulle part comme réellement bloquant (aucun enforcement câblé
     * dans l'application — écart connu, hors périmètre ici, cf. consigne
     * explicite de ne pas y toucher). Ce test vérifie donc directement le
     * mécanisme générique de promouvoirSiEpuise() pour ce quota FLUX,
     * indépendamment de son absence d'enforcement réel dans les contrôleurs.
     */
    public function test_fusion_flux_job_apply_limit_reset_horloge_dediee(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'job_apply_limit', '3');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        // 3 candidatures sur 3 offres DISTINCTES : candidatures a une
        // contrainte unique (offre_id, candidat_id), un candidat ne peut
        // postuler qu'une fois par offre.
        $recruteur = $this->creerRecruteur();
        for ($i = 0; $i < 3; $i++) {
            $offre = Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active']);
            Candidature::create([
                'offre_id' => $offre->id, 'candidat_id' => $candidat->id, 'statut' => 'envoyee',
                'created_at' => now()->subMinute(),
            ]);
        }
        $finAvantFusion = $ancien->ends_at->copy();

        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $planning = app(\App\Services\AbonnementSchedulingService::class);
        $resultat = $planning->promouvoirSiEpuise($candidat->fresh(), $ancien->fresh(), 'job_apply_limit');

        $this->assertTrue($resultat);

        $ancien->refresh();
        $this->assertTrue($ancien->ends_at->equalTo($finAvantFusion));

        $cycle = \App\Models\QuotaCycle::where('user_id', $candidat->id)->where('quota_key', 'job_apply_limit')->first();
        $this->assertNotNull($cycle);
        $this->assertTrue($cycle->cycle_ends_at->equalTo($finAvantFusion->copy()->addDays(30)));
        $this->assertTrue($cycle->cycle_starts_at->diffInSeconds(now()) < 5);
    }

    public function test_quota_alertes_epuise_declenche_le_relais_anticipe(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanAvecFeature('candidat', 'alert_limit', '1');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        Alerte::create(['user_id' => $candidat->id, 'nom' => 'Existante', 'frequence' => 'immediat', 'active' => true, 'metier' => 'Comptable']);

        $nouveauPlan = $this->creerPlanAvecFeature('candidat', 'alert_limit', '5');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $nouveauPlan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        // Le plan actuel (1 alerte, déjà utilisée) laisse place au nouveau
        // (5 alertes) sans attendre — la création doit réussir.
        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), [
                'metier' => 'Développeur', 'frequence' => 'immediat',
            ])
            ->assertSessionHas('success');

        $this->assertSame(2, Alerte::where('user_id', $candidat->id)->count());
    }

    public function test_quota_offres_epuise_declenche_le_relais_anticipe(): void
    {
        $recruteur = $this->creerRecruteur();
        $ancienPlan = $this->creerPlanAvecFeature('recruteur', 'job_post_limit', '1');
        $ancien = Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active', 'created_at' => now()]);

        $nouveauPlan = $this->creerPlanAvecFeature('recruteur', 'job_post_limit', '5');
        Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $nouveauPlan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $typeContrat = \App\Models\TypeContrat::firstOrCreate(['code' => 'CDI'], ['libelle' => 'CDI']);

        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), [
                'titre' => 'Comptable senior', 'entreprise' => 'ACME', 'localisation' => 'Cotonou',
                'type' => $typeContrat->id,
                'description' => str_repeat('Une description suffisamment longue pour passer la validation. ', 3),
            ])
            ->assertRedirect(route('recruteur.offres'));

        $this->assertSame(2, Offre::where('recruteur_id', $recruteur->id)->count());
        $this->assertTrue($ancien->fresh()->ends_at->lessThanOrEqualTo(now()));
    }

    /**
     * Angle mort trouvé en vérifiant : les pages de STATISTIQUES (pas
     * seulement les actions de création) doivent aussi déclencher la
     * promotion — sinon elles affichent encore l'ancien plan épuisé tant que
     * l'utilisateur n'a pas lui-même tenté l'action bloquée.
     */
    public function test_page_abonnement_recruteur_promeut_meme_sans_tenter_de_publier(): void
    {
        $recruteur = $this->creerRecruteur();
        $ancienPlan = $this->creerPlanAvecFeature('recruteur', 'job_post_limit', '1');
        Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active', 'created_at' => now()]);

        $nouveauPlan = $this->creerPlanAvecFeature('recruteur', 'job_post_limit', '5');
        Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $nouveauPlan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        // Simple consultation de la page de stats, sans jamais essayer de publier.
        $this->actingAs($recruteur)
            ->get(route('recruteur.abonnement'))
            ->assertOk()
            ->assertSee($nouveauPlan->name, false);

        $this->assertSame($nouveauPlan->id, $recruteur->fresh()->abonnementActif()->first()->plan_id);
    }

    public function test_page_abonnement_candidat_promeut_meme_sans_tenter_dajouter_un_cv(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '1');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        CV::create(['candidat_id' => $candidat->id]);

        $nouveauPlan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $nouveauPlan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $this->actingAs($candidat)
            ->get(route('candidat.abonnement'))
            ->assertOk()
            ->assertSee($nouveauPlan->name, false);

        $this->assertSame($nouveauPlan->id, $candidat->fresh()->abonnementActif()->first()->plan_id);
    }

    public function test_page_alertes_candidat_promeut_meme_sans_tenter_de_creer(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanAvecFeature('candidat', 'alert_limit', '1');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        Alerte::create(['user_id' => $candidat->id, 'nom' => 'Existante', 'frequence' => 'immediat', 'active' => true, 'metier' => 'Comptable']);

        $nouveauPlan = $this->creerPlanAvecFeature('candidat', 'alert_limit', '5');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $nouveauPlan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $this->actingAs($candidat)
            ->get(route('candidat.alertes'))
            ->assertOk();

        $this->assertSame($nouveauPlan->id, $candidat->fresh()->abonnementActif()->first()->plan_id);
    }

    /**
     * Bug distinct trouvé en vérifiant : job_post_limit=0 signifie "publication
     * désactivée sur ce plan" pour verifierQuota(), mais la page de stats
     * l'affichait comme "illimité" — corrigé pour que les deux concordent.
     */
    /**
     * Décision explicite de l'utilisateur : les pages "Mon abonnement" (pas
     * seulement les tableaux de bord/sidebar) doivent aussi expliquer
     * clairement qu'un plan est déjà souscrit mais pas encore en vigueur.
     */
    public function test_page_abonnement_candidat_affiche_lencart_explicatif(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanPayant('candidat');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(2),
        ]);
        $nouveauPlan = $this->creerPlanGratuit('candidat');
        $this->actingAs($candidat)->post(route('candidat.abonnement.store'), ['plan_id' => $nouveauPlan->id]);

        $this->actingAs($candidat)
            ->get(route('candidat.abonnement'))
            ->assertOk()
            ->assertSee('en attente', false)
            ->assertSee('prendra automatiquement le relais', false);
    }

    public function test_page_abonnement_recruteur_affiche_lencart_explicatif(): void
    {
        $recruteur = $this->creerRecruteur();
        $ancienPlan = $this->creerPlanPayant('recruteur');
        Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(2),
        ]);
        $nouveauPlan = $this->creerPlanGratuit('recruteur');
        $this->actingAs($recruteur)->post(route('recruteur.abonnement.store'), ['plan_id' => $nouveauPlan->id]);

        $this->actingAs($recruteur)
            ->get(route('recruteur.abonnement'))
            ->assertOk()
            ->assertSee('en attente', false)
            ->assertSee('prendra automatiquement le relais', false);
    }

    public function test_page_abonnement_recruteur_distingue_illimite_et_desactive(): void
    {
        $recruteur = $this->creerRecruteur();
        $plan = $this->creerPlanAvecFeature('recruteur', 'job_post_limit', '0');
        Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);

        $this->actingAs($recruteur)
            ->get(route('recruteur.abonnement'))
            ->assertOk()
            ->assertSee('Désactivées', false)
            ->assertDontSee('Illimitées', false);
    }

    /**
     * Angle mort trouvé en creusant : la liste admin des abonnements affichait
     * encore le statut brut en base (toujours "Actif") au lieu de l'état réel
     * — un abonnement programmé (pas encore démarré) s'y affichait comme
     * n'importe quel abonnement en cours, sans distinction.
     */
    public function test_page_admin_abonnements_distingue_programme_de_actif(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanPayant('candidat');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(2),
        ]);
        $nouveauPlan = $this->creerPlanGratuit('candidat');
        $this->actingAs($candidat)->post(route('candidat.abonnement.store'), ['plan_id' => $nouveauPlan->id]);

        $this->actingAs($admin)
            ->get(route('admin.abonnements'))
            ->assertOk()
            ->assertSee('Programmé', false);
    }

    public function test_souscrire_gratuit_pendant_un_abonnement_valide_programme_le_relais(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanPayant('candidat');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(2),
        ]);

        $nouveauPlan = $this->creerPlanGratuit('candidat');
        $this->actingAs($candidat)
            ->post(route('candidat.abonnement.store'), ['plan_id' => $nouveauPlan->id])
            ->assertRedirect(route('candidat.abonnement'));

        // L'ancien reste actif et intact.
        $this->assertSame('active', $ancien->fresh()->status);

        $nouveau = Abonnement::where('plan_id', $nouveauPlan->id)->first();
        $this->assertNotNull($nouveau);
        $this->assertSame('active', $nouveau->status);
        $this->assertTrue($nouveau->starts_at->equalTo($ancien->ends_at));
        $this->assertSame('programme', $nouveau->etat());

        // abonnementActif() doit toujours retourner l'ANCIEN, pas le nouveau.
        $actif = $candidat->abonnementActif()->first();
        $this->assertSame($ancien->id, $actif->id);
    }

    /**
     * Décision explicite de l'utilisateur : le fait qu'un plan déjà souscrit
     * n'entre pas encore en vigueur doit être clairement expliqué (tableau de
     * bord + sidebar), pas juste silencieux.
     */
    public function test_dashboard_et_sidebar_annoncent_le_plan_programme(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanPayant('candidat');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(2),
        ]);

        $nouveauPlan = $this->creerPlanGratuit('candidat');
        $this->actingAs($candidat)->post(route('candidat.abonnement.store'), ['plan_id' => $nouveauPlan->id]);

        $this->actingAs($candidat)
            ->get(route('candidat.dashboard'))
            ->assertOk()
            ->assertSee('en attente', false)
            ->assertSee($nouveauPlan->name, false);
    }

    public function test_souscrire_gratuit_sans_abonnement_existant_active_immediatement(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanGratuit('candidat');

        $this->actingAs($candidat)
            ->post(route('candidat.abonnement.store'), ['plan_id' => $plan->id])
            ->assertRedirect(route('candidat.abonnement'));

        $abonnement = Abonnement::where('plan_id', $plan->id)->first();
        $this->assertSame('active', $abonnement->etat());
        $this->assertTrue($abonnement->starts_at->diffInSeconds(now()) < 5);
    }

    public function test_souscrire_remplace_immediatement_un_abonnement_sans_date_de_fin(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanPayant('candidat');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => null,
        ]);

        $nouveauPlan = $this->creerPlanGratuit('candidat');
        $this->actingAs($candidat)->post(route('candidat.abonnement.store'), ['plan_id' => $nouveauPlan->id]);

        $this->assertSame('cancelled', $ancien->fresh()->status);
        $nouveau = Abonnement::where('plan_id', $nouveauPlan->id)->first();
        $this->assertSame('active', $nouveau->etat());
    }

    /**
     * Aucune action de l'app ne permet aujourd'hui d'écourter un abonnement en
     * cours — mais si l'ancien devient invalide plus tôt que prévu (ends_at
     * raccourci, ou statut changé manuellement en base), le suivant déjà
     * programmé doit prendre le relais immédiatement, sans attendre sa date de
     * démarrage d'origine ni aucune tâche planifiée.
     */
    public function test_relais_anticipe_si_lancien_devient_invalide_plus_tot_que_prevu(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanPayant('candidat');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(2),
        ]);

        $nouveauPlan = $this->creerPlanGratuit('candidat');
        $this->actingAs($candidat)->post(route('candidat.abonnement.store'), ['plan_id' => $nouveauPlan->id]);
        $nouveau = Abonnement::where('plan_id', $nouveauPlan->id)->first();

        // Toujours l'ancien tant qu'il est valide.
        $this->assertSame($ancien->id, $candidat->abonnementActif()->first()->id);

        // L'ancien est raccourci / annulé avant terme (ex. futur cas admin, ou
        // simplement expiré plus tôt que sa date de fin prévue).
        $ancien->update(['ends_at' => now()->subMinute()]);

        // Le nouveau, bien que programmé pour démarrer dans 2 jours, prend le
        // relais tout de suite — pas de trou sans abonnement actif.
        $actif = $candidat->fresh()->abonnementActif()->first();
        $this->assertSame($nouveau->id, $actif->id);
    }

    /**
     * Vérification demandée : si DEUX quotas STOCK (CV et alertes) sont
     * épuisés simultanément sur le même renouvellement identique, la date de
     * fin ne doit être prolongée qu'UNE seule fois — pas une fois par quota
     * épuisé. La neutralisation de $prochain (starts_at === ends_at) après
     * la première fusion l'empêche naturellement de resservir pour un
     * second quota.
     */
    public function test_deux_quotas_stock_epuises_simultanement_ne_prolonge_la_date_quune_seule_fois(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        PlanFeature::create(['plan_id' => $plan->id, 'feature_key' => 'alert_limit', 'feature_value' => '2']);

        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        for ($i = 0; $i < 5; $i++) {
            CV::create(['candidat_id' => $candidat->id]);
        }
        Alerte::create(['user_id' => $candidat->id, 'nom' => 'A1', 'frequence' => 'immediat', 'active' => true, 'metier' => 'Comptable']);
        Alerte::create(['user_id' => $candidat->id, 'nom' => 'A2', 'frequence' => 'immediat', 'active' => true, 'metier' => 'Vendeur']);
        $finAvantFusion = $ancien->ends_at->copy();

        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        // Le premier quota vérifié (CV) déclenche la fusion.
        $quotaCv = app(CvQuotaService::class)->quotaFor($candidat->fresh());
        $this->assertTrue($quotaCv['reached']);

        $ancien->refresh();
        $this->assertTrue($ancien->ends_at->equalTo($finAvantFusion->copy()->addDays(30)));

        // Le second quota (alertes), vérifié ENSUITE : toujours plein
        // (plafond inchangé, normal pour du STOCK), mais ne doit PAS
        // prolonger une deuxième fois la date déjà fusionnée.
        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), ['metier' => 'Développeur', 'frequence' => 'immediat'])
            ->assertSessionHas('error');

        $ancien->refresh();
        $this->assertTrue($ancien->ends_at->equalTo($finAvantFusion->copy()->addDays(30)));
    }

    /**
     * Correction apportée suite au point de vérification précédent : si un
     * quota STOCK ET un quota FLUX sont épuisés simultanément sur le MÊME
     * renouvellement, les DEUX effets doivent maintenant se déclencher
     * ensemble, en un seul appel — fusionnerRenouvellementIdentique() évalue
     * tous les quotas STOCK et FLUX avant de neutraliser $prochain, au lieu
     * de ne traiter que le quota qui a déclenché l'appel (ancien
     * comportement, qui laissait le second quota sans effet — cf. l'ancienne
     * version de ce test, remplacée).
     */
    public function test_stock_et_flux_epuises_simultanement_declenchent_les_deux_effets_ensemble(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        PlanFeature::create(['plan_id' => $plan->id, 'feature_key' => 'job_apply_limit', 'feature_value' => '3']);

        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        for ($i = 0; $i < 5; $i++) {
            CV::create(['candidat_id' => $candidat->id]);
        }
        // 3 candidatures sur 3 offres DISTINCTES (contrainte unique
        // offre_id+candidat_id), bien avant la fusion (voir la note sur la
        // précision DB à la seconde dans le test FLUX job_post_limit plus haut).
        $recruteur = $this->creerRecruteur();
        for ($i = 0; $i < 3; $i++) {
            $offre = Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active']);
            Candidature::create([
                'offre_id' => $offre->id, 'candidat_id' => $candidat->id, 'statut' => 'envoyee',
                'created_at' => now()->subMinute(),
            ]);
        }
        $finAvantFusion = $ancien->ends_at->copy();

        $renouvellement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $planning = app(\App\Services\AbonnementSchedulingService::class);

        // Un seul appel (peu importe quel quota l'a déclenché) : les DEUX
        // effets doivent avoir eu lieu.
        $this->assertTrue($planning->promouvoirSiEpuise($candidat->fresh(), $ancien->fresh(), 'cv_limit'));

        // Effet STOCK (cv_limit) : date de fin globale prolongée.
        $ancien->refresh();
        $this->assertTrue($ancien->ends_at->equalTo($finAvantFusion->copy()->addDays(30)));

        // Effet FLUX (job_apply_limit) : sa propre horloge a bien été reset,
        // dans le MÊME appel, sans qu'on ait eu besoin de le déclencher séparément.
        $cycle = \App\Models\QuotaCycle::where('user_id', $candidat->id)->where('quota_key', 'job_apply_limit')->first();
        $this->assertNotNull($cycle);
        $this->assertTrue($cycle->cycle_starts_at->diffInSeconds(now()) < 5);
        $this->assertTrue($cycle->cycle_ends_at->equalTo($finAvantFusion->copy()->addDays(30)));

        // $prochain neutralisé une seule fois, à la fin des deux effets.
        $renouvellement->refresh();
        $this->assertTrue($renouvellement->starts_at->equalTo($renouvellement->ends_at));
    }

    /**
     * Tests sentinelles (protection contre la duplication) : pour chacun des
     * 4 quotas, on compare le résultat de la méthode privée de lecture de
     * AbonnementSchedulingService (via Reflection, puisqu'elle est privée) à
     * la VRAIE logique d'origine, sur les mêmes données. Si un jour la
     * définition de "épuisé" change dans l'un des 4 endroits d'origine sans
     * que la copie de AbonnementSchedulingService soit mise à jour en
     * parallèle, l'un de ces tests doit échouer immédiatement.
     */
    private function appelerQuotaEpuise(string $quotaKey, User $user, Abonnement $abonnement): bool
    {
        $service    = app(\App\Services\AbonnementSchedulingService::class);
        $reflection = new \ReflectionMethod($service, 'quotaEpuise');
        $reflection->setAccessible(true);
        return $reflection->invoke($service, $quotaKey, $user, $abonnement);
    }

    private function appelerFusion(User $user, Abonnement $actuel, Abonnement $prochain): bool
    {
        $service    = app(\App\Services\AbonnementSchedulingService::class);
        $reflection = new \ReflectionMethod($service, 'fusionnerRenouvellementIdentique');
        $reflection->setAccessible(true);
        return $reflection->invoke($service, $user, $actuel, $prochain);
    }

    /**
     * Audit pré-mise en ligne, point 2c (BLOQUANT) : deux requêtes
     * concurrentes qui chargent chacune $actuel/$prochain avant que l'une
     * des deux ne fusionne ne doivent PAS double-appliquer l'effet (ex.
     * prolonger la date deux fois). Simule la concurrence en appelant
     * fusionnerRenouvellementIdentique() directement (via Reflection) deux
     * fois de suite, avec deux jeux d'instances figées AVANT toute fusion —
     * exactement ce qui se produirait si deux pages étaient ouvertes en
     * parallèle et lisaient l'état avant que l'une des deux ne commite.
     */
    public function test_fusion_protegee_contre_un_double_appel_concurrent_sur_le_meme_prochain(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        for ($i = 0; $i < 5; $i++) {
            CV::create(['candidat_id' => $candidat->id]);
        }
        $finAvantFusion = $ancien->ends_at->copy();

        $renouvellement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        // Deux jeux d'instances "figées", chargées AVANT toute fusion — les
        // deux appels partent des mêmes données de départ, comme deux
        // requêtes concurrentes le feraient.
        $actuelFige1   = $ancien->fresh();
        $prochainFige1 = $renouvellement->fresh();
        $actuelFige2   = $ancien->fresh();
        $prochainFige2 = $renouvellement->fresh();

        $resultat1 = $this->appelerFusion($candidat->fresh(), $actuelFige1, $prochainFige1);
        $resultat2 = $this->appelerFusion($candidat->fresh(), $actuelFige2, $prochainFige2);

        $this->assertTrue($resultat1);
        $this->assertFalse($resultat2, 'le 2e appel concurrent, sur un $prochain déjà neutralisé par le 1er, ne doit rien faire');

        $ancien->refresh();
        $this->assertTrue(
            $ancien->ends_at->equalTo($finAvantFusion->copy()->addDays(30)),
            'la date ne doit être prolongée quune seule fois, pas deux'
        );
    }

    /**
     * Audit pré-mise en ligne, point 1h (BLOQUANT) : le filet de sécurité
     * pour actuel->ends_at === null n'était jusqu'ici jamais réellement
     * exécuté par un test (seulement démontré inatteignable par le
     * raisonnement, cf. dateDebut()). Ce test force ce cas et vérifie qu'il
     * ne plante pas et ne fusionne rien.
     */
    public function test_fusion_ignoree_sans_crash_si_actuel_ends_at_est_null(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        $ancien = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => null, // forcé, cas normalement inatteignable
        ]);
        for ($i = 0; $i < 5; $i++) {
            CV::create(['candidat_id' => $candidat->id]);
        }

        $renouvellement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $planning = app(\App\Services\AbonnementSchedulingService::class);
        $resultat = $planning->promouvoirSiEpuise($candidat->fresh(), $ancien->fresh(), 'cv_limit');

        $this->assertFalse($resultat);
        $ancien->refresh();
        $this->assertNull($ancien->ends_at);
        $renouvellement->refresh();
        $this->assertTrue($renouvellement->starts_at->isFuture());
    }

    public function test_sentinelle_cv_limit_coherent_avec_cvquotaservice(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '3');
        $abonnement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        // Volontairement PAS au plafond : la sentinelle doit rester vraie
        // dans les deux sens (épuisé ET pas épuisé), pas seulement au bord.
        CV::create(['candidat_id' => $candidat->id]);

        $epuiseCopie = $this->appelerQuotaEpuise('cv_limit', $candidat->fresh(), $abonnement->fresh());
        $epuiseOrigine = app(CvQuotaService::class)->quotaFor($candidat->fresh())['reached'];
        $this->assertSame($epuiseOrigine, $epuiseCopie);
        $this->assertFalse($epuiseCopie);

        CV::create(['candidat_id' => $candidat->id]);
        CV::create(['candidat_id' => $candidat->id]);

        $epuiseCopie = $this->appelerQuotaEpuise('cv_limit', $candidat->fresh(), $abonnement->fresh());
        $epuiseOrigine = app(CvQuotaService::class)->quotaFor($candidat->fresh())['reached'];
        $this->assertSame($epuiseOrigine, $epuiseCopie);
        $this->assertTrue($epuiseCopie);
    }

    public function test_sentinelle_alert_limit_coherent_avec_le_vrai_comportement_http(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'alert_limit', '2');
        $abonnement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        Alerte::create(['user_id' => $candidat->id, 'nom' => 'A1', 'frequence' => 'immediat', 'active' => true, 'metier' => 'Comptable']);

        // Pas encore au plafond (1/2) : la vraie création doit réussir, la
        // copie doit dire "pas épuisé".
        $this->assertFalse($this->appelerQuotaEpuise('alert_limit', $candidat->fresh(), $abonnement->fresh()));
        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), ['metier' => 'Vendeur', 'frequence' => 'immediat'])
            ->assertSessionHas('success');

        // Maintenant au plafond (2/2) : la vraie création doit être
        // rejetée, la copie doit dire "épuisé".
        $this->assertTrue($this->appelerQuotaEpuise('alert_limit', $candidat->fresh(), $abonnement->fresh()));
        $this->actingAs($candidat)
            ->post(route('candidat.alertes.store'), ['metier' => 'Développeur', 'frequence' => 'immediat'])
            ->assertSessionHas('error');
    }

    public function test_sentinelle_job_post_limit_coherent_avec_le_vrai_comportement_http(): void
    {
        $recruteur = $this->creerRecruteur();
        $plan = $this->creerPlanAvecFeature('recruteur', 'job_post_limit', '1');
        $abonnement = Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);

        // Pas encore au plafond (0/1) : la copie dit "pas épuisé", la vraie
        // publication doit réussir.
        $this->assertFalse($this->appelerQuotaEpuise('job_post_limit', $recruteur->fresh(), $abonnement->fresh()));
        $typeContrat = \App\Models\TypeContrat::firstOrCreate(['code' => 'CDI'], ['libelle' => 'CDI']);
        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), [
                'titre' => 'Comptable senior', 'entreprise' => 'ACME', 'localisation' => 'Cotonou',
                'type' => $typeContrat->id,
                'description' => str_repeat('Une description suffisamment longue pour passer la validation. ', 3),
            ])
            ->assertRedirect(route('recruteur.offres'));

        // Maintenant au plafond (1/1) : la copie dit "épuisé", la vraie
        // publication doit être bloquée (redirigée vers les plans).
        $this->assertTrue($this->appelerQuotaEpuise('job_post_limit', $recruteur->fresh(), $abonnement->fresh()));
        $this->actingAs($recruteur)
            ->post(route('recruteur.offres.store'), [
                'titre' => 'Vendeur', 'entreprise' => 'ACME', 'localisation' => 'Cotonou',
                'type' => $typeContrat->id,
                'description' => str_repeat('Une autre description suffisamment longue pour la validation. ', 3),
            ])
            ->assertRedirect(route('recruteur.abonnement.plans'));
    }

    /**
     * ATTENTION — garantie plus faible que les 3 autres tests sentinelles
     * ci-dessus : job_apply_limit n'étant enforced NULLE PART dans
     * l'application aujourd'hui (écart connu, ticket séparé), ce test
     * compare deux formules d'affichage (la copie de
     * AbonnementSchedulingService et la formule utilisée par
     * Candidat\AbonnementController::buildQuotas() pour l'affichage des
     * stats), PAS une formule à une règle métier réellement appliquée. Il
     * protège contre une dérive entre ces deux formules d'affichage, mais ni
     * l'une ni l'autre n'est "la vérité" puisqu'aucune n'est enforced. À
     * mettre à jour pour comparer à la vraie règle métier le jour où ce
     * quota sera réellement appliqué.
     */
    public function test_sentinelle_job_apply_limit_coherent_avec_la_formule_daffichage(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'job_apply_limit', '2');
        $abonnement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        $recruteur = $this->creerRecruteur();
        $offre1 = Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active']);
        Candidature::create(['offre_id' => $offre1->id, 'candidat_id' => $candidat->id, 'statut' => 'envoyee']);

        $epuiseCopie = $this->appelerQuotaEpuise('job_apply_limit', $candidat->fresh(), $abonnement->fresh());
        // Reproduit exactement la formule de buildQuotas() (candidatures
        // comptées depuis abonnement->starts_at, limite via getFeature).
        $applyLimit = (int) $plan->getFeature('job_apply_limit', 10);
        $used = $candidat->fresh()->candidatures()->where('created_at', '>=', $abonnement->starts_at)->count();
        $epuiseAffichage = $used >= $applyLimit;
        $this->assertSame($epuiseAffichage, $epuiseCopie);
        $this->assertFalse($epuiseCopie);

        // Contrainte unique (offre_id, candidat_id) : offre distincte pour
        // cette 2e candidature.
        $offre2 = Offre::factory()->create(['recruteur_id' => $recruteur->id, 'statut' => 'active']);
        Candidature::create(['offre_id' => $offre2->id, 'candidat_id' => $candidat->id, 'statut' => 'envoyee']);

        $epuiseCopie = $this->appelerQuotaEpuise('job_apply_limit', $candidat->fresh(), $abonnement->fresh());
        $used = $candidat->fresh()->candidatures()->where('created_at', '>=', $abonnement->starts_at)->count();
        $epuiseAffichage = $used >= $applyLimit;
        $this->assertSame($epuiseAffichage, $epuiseCopie);
        $this->assertTrue($epuiseCopie);
    }

    /**
     * Vérification demandée : une fois $prochain neutralisé (starts_at ===
     * ends_at) suite à une fusion, le bandeau "abonnement programmé" doit
     * disparaître proprement du dashboard — pas de ligne avec une date de
     * début égale à la date de fin.
     */
    public function test_bandeau_programme_disparait_apres_neutralisation_du_prochain(): void
    {
        $candidat = $this->creerCandidat();
        $plan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        for ($i = 0; $i < 5; $i++) {
            CV::create(['candidat_id' => $candidat->id]);
        }
        $renouvellement = Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $plan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        // Déclenche directement la fusion (équivalent à une visite de la
        // page "Mon abonnement", qui appelle ce même service en premier).
        app(CvQuotaService::class)->quotaFor($candidat->fresh());

        $renouvellement->refresh();
        $this->assertTrue($renouvellement->starts_at->equalTo($renouvellement->ends_at));

        $this->actingAs($candidat)
            ->get(route('candidat.dashboard'))
            ->assertOk()
            ->assertDontSee('en attente', false)
            ->assertDontSee($renouvellement->starts_at->format('d/m/Y'), false);
    }

    /**
     * Non-régression (comportement déjà existant, pas une nouveauté du
     * Chantier B) : lors d'un UPGRADE (plan différent, pas un renouvellement
     * à l'identique) où le quota CV était plein sur l'ancien plan, le nouveau
     * plafond appliqué doit être celui du plan supérieur SEUL — jamais
     * l'addition des deux plafonds.
     */
    public function test_upgrade_cv_limit_applique_le_plafond_du_nouveau_plan_seul_jamais_laddition(): void
    {
        $candidat = $this->creerCandidat();
        $ancienPlan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '5');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(10),
        ]);
        for ($i = 0; $i < 5; $i++) {
            CV::create(['candidat_id' => $candidat->id]);
        }

        $nouveauPlan = $this->creerPlanAvecFeature('candidat', 'cv_limit', '10');
        Abonnement::create([
            'user_id' => $candidat->id, 'plan_id' => $nouveauPlan->id, 'status' => 'active',
            'starts_at' => now()->addDays(9), 'ends_at' => now()->addDays(39),
        ]);

        $quota = app(CvQuotaService::class)->quotaFor($candidat->fresh());

        // Plafond = 10 (le plan supérieur seul), jamais 5+10=15.
        $this->assertSame(10, $quota['limit']);
        $this->assertFalse($quota['reached']);
        $this->assertSame(5, $quota['used']);
        $this->assertSame(5, $quota['remaining']);
    }

    public function test_paiement_confirme_programme_le_relais_sans_toucher_labonnement_valide(): void
    {
        Notification::fake();
        $recruteur = $this->creerRecruteur();
        $ancienPlan = $this->creerPlanPayant('recruteur');
        $ancien = Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $ancienPlan->id, 'status' => 'active',
            'starts_at' => now()->subDay(), 'ends_at' => now()->addDays(5),
        ]);

        $nouveauPlan = $this->creerPlanPayant('recruteur');
        $nouveau = Abonnement::create([
            'user_id' => $recruteur->id, 'plan_id' => $nouveauPlan->id, 'status' => 'cancelled',
            'starts_at' => now(), 'ends_at' => now()->addDays(30),
        ]);
        $paiement = Paiement::create([
            'user_id' => $recruteur->id, 'subscription_id' => $nouveau->id,
            'montant' => $nouveauPlan->price, 'devise' => 'XOF',
            'type' => 'abonnement_recruteur', 'statut' => 'en_attente',
        ]);

        $paiement->update(['statut' => 'confirme']);
        event(new PaymentConfirmed($paiement));

        $this->assertSame('active', $ancien->fresh()->status);
        $nouveau->refresh();
        $this->assertSame('active', $nouveau->status);
        $this->assertTrue($nouveau->starts_at->equalTo($ancien->ends_at));
        $this->assertSame('programme', $nouveau->etat());
    }
}
