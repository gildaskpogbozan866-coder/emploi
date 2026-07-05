<?php

namespace App\Services;

use App\Models\Abonnement;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbonnementSchedulingService
{
    /**
     * Quotas STOCK (plafond lié au palier, jamais de reset périodique) —
     * classification produit confirmée par le client. Tout le reste des
     * quotas gérés par promouvoirSiEpuise() est FLUX (horloge propre via
     * QuotaCycleService).
     */
    private const QUOTAS_STOCK = ['cv_limit', 'alert_limit'];

    /**
     * Quotas FLUX gérés par le sweep de fusionnerRenouvellementIdentique().
     */
    private const QUOTAS_FLUX = ['job_apply_limit', 'job_post_limit'];

    public function __construct(private readonly QuotaCycleService $cycles) {}

    /**
     * Détermine à quelle date un nouvel abonnement doit démarrer pour cet
     * utilisateur : immédiatement s'il n'a rien de valide en cours ou déjà
     * programmé, sinon juste après l'expiration du dernier en date — jamais
     * d'annulation d'un abonnement encore valide (l'ancien va au bout de sa
     * durée, le nouveau prend le relais automatiquement).
     *
     * Cas rare : un abonnement sans date de fin (ends_at null) en cours ne
     * laisse aucune date à programmer derrière lui — il est alors remplacé
     * immédiatement, faute d'alternative sensée.
     */
    public function dateDebut(User $user, ?int $excludeAbonnementId = null): Carbon
    {
        $valides = $user->abonnements()
            ->where('status', 'active')
            ->when($excludeAbonnementId, fn ($q) => $q->where('id', '!=', $excludeAbonnementId))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()))
            ->get();

        $illimite = $valides->first(fn ($a) => $a->ends_at === null);
        if ($illimite) {
            $user->abonnements()->where('status', 'active')->update(['status' => 'cancelled']);
            return now();
        }

        $dernier = $valides->sortByDesc('ends_at')->first();

        return $dernier ? $dernier->ends_at->copy() : now();
    }

    /**
     * Décision explicite du client : les deux abonnements restent "actifs",
     * mais dès que l'actuel épuise l'un de ses avantages (quota CV, alertes,
     * offres...), le suivant déjà souscrit prend le relais immédiatement —
     * sans attendre sa date de départ programmée ni l'expiration réelle de
     * l'actuel. Bascule concrète et définitive (pas juste un emprunt ponctuel
     * de quota) : l'actuel s'arrête maintenant, le suivant démarre maintenant.
     *
     * Bug réel corrigé : si le suivant n'apporte PAS plus de marge sur ce
     * quota précis (ex. plan différent mais pas meilleur), le promouvoir
     * quand même ne fait que raccourcir sa durée pour rien. $prochainOffrePlus
     * (fourni par l'appelant, qui seul connaît la sémantique de son quota —
     * ex. 0 = illimité pour les CV, 0 = désactivé pour les offres) doit
     * confirmer que le prochain plan aide réellement avant de basculer.
     *
     * Cas particulier (Chantier B, décision produit confirmée) : si le
     * suivant est le MÊME palier que l'actuel (renouvellement à l'identique),
     * $prochainOffrePlus est ignoré — ce comparateur ne s'applique qu'entre
     * deux plans différents. Un renouvellement identique ne débloque jamais
     * plus de marge par définition, mais ne doit plus non plus laisser le
     * paiement sans aucun effet : voir fusionnerRenouvellementIdentique().
     */
    public function promouvoirSiEpuise(User $user, Abonnement $actuel, string $quotaKey, ?callable $prochainOffrePlus = null): bool
    {
        $prochain = $user->abonnements()
            ->where('status', 'active')
            ->where('id', '!=', $actuel->id)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->first();

        if (!$prochain) {
            return false;
        }

        if ($prochain->plan_id === $actuel->plan_id) {
            return $this->fusionnerRenouvellementIdentique($user, $actuel, $prochain);
        }

        if ($prochainOffrePlus !== null && !$prochainOffrePlus($prochain->plan)) {
            return false;
        }

        $maintenant = now();
        $actuel->update(['ends_at' => $maintenant]);
        $prochain->update(['starts_at' => $maintenant]);

        return true;
    }

    /**
     * Renouvellement du MÊME palier : ne change jamais le plafond ni le plan
     * de $actuel. Contrairement à une première version de cette méthode, elle
     * n'applique plus l'effet d'un seul quota (celui qui a déclenché l'appel)
     * — elle évalue TOUS les quotas STOCK et FLUX de $actuel avant de rien
     * neutraliser, pour appliquer tous les effets applicables en une seule
     * fois. Nécessaire car $prochain est neutralisé à la fin de cette
     * méthode : si on ne traitait que le quota déclencheur, un second quota
     * épuisé simultanément (vérifié par un appel séparé, ex. alert_limit
     * après cv_limit) trouverait $prochain déjà neutralisé et resterait sans
     * effet — perte silencieuse d'une fusion pourtant due.
     *
     * Effet selon le type de quota épuisé (classification produit confirmée) :
     * - STOCK (cv_limit, alert_limit) : prolonge la date de fin GLOBALE de
     *   l'abonnement actuel de la durée du nouveau plan — aucun jour payé
     *   perdu, aucun reset de compteur (le plafond ne bouge pas de toute
     *   façon, palier identique). Un seul effet STOCK partagé suffit, peu
     *   importe combien de quotas STOCK sont épuisés en même temps.
     * - FLUX (job_apply_limit, job_post_limit) : remet à zéro l'horloge de
     *   CHAQUE quota FLUX épuisé (QuotaCycleService), avec sa propre
     *   nouvelle échéance. Les quotas FLUX non épuisés ne sont pas touchés.
     *
     * $prochain (le renouvellement) n'est neutralisé qu'une seule fois, à la
     * fin, et seulement si au moins un effet a été appliqué — sa fenêtre est
     * réduite à un instant (starts_at === ends_at) pour que son temps déjà
     * "absorbé" ne soit plus jamais redétecté comme un "prochain" disponible.
     * Son status reste 'active' : ce n'est pas une annulation, l'abonnement a
     * bien existé et a bien été payé, son temps a simplement été fusionné
     * dans l'actuel.
     *
     * Protection contre la concurrence (audit pré-mise en ligne) : deux
     * requêtes qui déclenchent chacune une vérification de quota au même
     * instant (ex. deux onglets ouverts en parallèle) pourraient toutes deux
     * lire le même $prochain avant qu'aucune n'ait fusionné, et double-
     * appliquer l'effet (ex. prolonger la date deux fois). Toute la méthode
     * s'exécute donc dans une transaction avec verrouillage (lockForUpdate)
     * sur $actuel et $prochain rechargés depuis la base : la seconde requête
     * à obtenir le verrou relit alors un $prochain déjà neutralisé par la
     * première et abandonne, au lieu de fusionner une seconde fois.
     */
    private function fusionnerRenouvellementIdentique(User $user, Abonnement $actuel, Abonnement $prochain): bool
    {
        return DB::transaction(function () use ($user, $actuel, $prochain) {
            $actuel   = Abonnement::whereKey($actuel->id)->lockForUpdate()->first();
            $prochain = Abonnement::whereKey($prochain->id)->lockForUpdate()->first();

            if (!$actuel || !$prochain || !$prochain->starts_at->isFuture()) {
                // $prochain a déjà été neutralisé par un appel concurrent
                // pendant l'attente du verrou (ou a disparu) : plus rien à
                // fusionner.
                return false;
            }

            if ($actuel->ends_at === null) {
                // Ne devrait jamais arriver : dateDebut() remplace
                // immédiatement un abonnement illimité dès qu'un nouveau
                // paiement est confirmé (aucune mise en file d'attente
                // possible dans ce cas). Filet de sécurité : on ne fusionne
                // rien plutôt que de deviner une date.
                Log::warning('promouvoirSiEpuise: fusion ignorée, actuel->ends_at est null', [
                    'abonnement_id' => $actuel->id,
                ]);
                return false;
            }

            $finOriginale     = $actuel->ends_at->copy();
            $dureeNouveauPlan = $prochain->plan?->duration_days;
            $maintenant       = now();
            $effetApplique    = false;

            // FLUX évalué et appliqué EN PREMIER, avant toute mutation de
            // $actuel : cycleFor() amorce l'horloge d'un quota FLUX pas
            // encore suivi à partir de $actuel->ends_at (bug trouvé et
            // corrigé — si le bloc STOCK ci-dessous s'exécutait avant,
            // $actuel->ends_at serait déjà prolongé en mémoire au moment où
            // le bootstrap FLUX le lirait, provoquant un double comptage en
            // cascade sur la nouvelle échéance du cycle).
            foreach (self::QUOTAS_FLUX as $quotaKey) {
                if ($this->quotaEpuise($quotaKey, $user, $actuel)) {
                    $effetApplique = true;
                    $cycleActuel = $this->cycles->cycleFor($user, $quotaKey, $actuel);
                    $nouvelleEcheanceCycle = ($dureeNouveauPlan && $cycleActuel->cycle_ends_at)
                        ? $cycleActuel->cycle_ends_at->copy()->addDays($dureeNouveauPlan)
                        : null;
                    $this->cycles->resetCycle($user, $quotaKey, $maintenant, $nouvelleEcheanceCycle);
                }
            }

            foreach (self::QUOTAS_STOCK as $quotaKey) {
                if ($this->quotaEpuise($quotaKey, $user, $actuel)) {
                    $effetApplique = true;
                    $nouvelleFin = $dureeNouveauPlan
                        ? $finOriginale->copy()->addDays($dureeNouveauPlan)
                        : null;
                    $actuel->update(['ends_at' => $nouvelleFin]);
                    break;
                }
            }

            if (!$effetApplique) {
                return false;
            }

            $prochain->update(['starts_at' => $maintenant, 'ends_at' => $maintenant]);

            return true;
        });
    }

    /**
     * Vérification de lecture seule, SANS effet de bord : ce quota précis
     * est-il épuisé maintenant pour $actuel ? Duplique volontairement (pas
     * d'appel croisé, pour éviter tout risque de dépendance circulaire avec
     * CvQuotaService/JobPostQuotaService, qui injectent déjà ce service dans
     * leur propre constructeur) la sémantique "épuisé" déjà définie dans :
     * - cv_limit  : CvQuotaService::quotaFor()
     * - alert_limit : Candidat\AlerteController::abonnementEtLimite()
     * - job_apply_limit : Candidat\AbonnementController::buildQuotas() (affichage
     *   uniquement — ce quota n'est enforced nulle part à ce jour)
     * - job_post_limit : JobPostQuotaService::quotaFor()
     * Toute modification de la définition de "épuisé" dans l'un de ces 4
     * endroits doit être répercutée ici — les tests sentinelles de
     * AbonnementChainingTest.php (test_sentinelle_*) comparent cette méthode
     * à la logique d'origine et échouent si elles divergent.
     */
    private function quotaEpuise(string $quotaKey, User $user, Abonnement $abonnement): bool
    {
        return match ($quotaKey) {
            'cv_limit'        => $this->cvLimitEpuise($user, $abonnement),
            'alert_limit'     => $this->alertLimitEpuise($user, $abonnement),
            'job_apply_limit' => $this->jobApplyLimitEpuise($user, $abonnement),
            'job_post_limit'  => $this->jobPostLimitEpuise($user, $abonnement),
            default           => false,
        };
    }

    private function cvLimitEpuise(User $user, Abonnement $abonnement): bool
    {
        $limit = (int) ($abonnement->plan?->getFeature('cv_limit', 1) ?? 1);
        return $limit !== 0 && $user->cvs()->count() >= $limit;
    }

    private function alertLimitEpuise(User $user, Abonnement $abonnement): bool
    {
        $limit = (int) ($abonnement->plan?->getFeature('alert_limit', 0) ?? 0);
        return $limit > 0 && $user->alertes()->count() >= $limit;
    }

    private function jobApplyLimitEpuise(User $user, Abonnement $abonnement): bool
    {
        $limit = (int) $abonnement->plan?->getFeature('job_apply_limit', 10);
        if ($limit >= 100) {
            return false; // convention existante : "illimité en pratique" (AbonnementController::buildQuotas)
        }

        $cycle = $this->cycles->cycleFor($user, 'job_apply_limit', $abonnement);
        return $user->candidatures()->where('created_at', '>=', $cycle->cycle_starts_at)->count() >= $limit;
    }

    private function jobPostLimitEpuise(User $user, Abonnement $abonnement): bool
    {
        $limitValue = $abonnement->plan?->getFeature('job_post_limit');
        if ($limitValue === null) {
            return false; // illimité
        }

        $limit = (int) $limitValue;
        if ($limit === 0) {
            return true; // désactivé sur ce plan = toujours "épuisé"
        }

        $cycle = $this->cycles->cycleFor($user, 'job_post_limit', $abonnement);
        return $user->offres()->where('created_at', '>=', $cycle->cycle_starts_at)->count() >= $limit;
    }
}
