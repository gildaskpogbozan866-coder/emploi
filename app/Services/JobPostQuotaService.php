<?php

namespace App\Services;

use App\Models\User;

class JobPostQuotaService
{
    public function __construct(
        private readonly AbonnementSchedulingService $planning,
        private readonly QuotaCycleService $cycles,
    ) {}

    /**
     * Sémantique du plan (préservée telle qu'elle existait avant) : la
     * fonctionnalité job_post_limit non définie du tout = illimité ; définie
     * à 0 = publication désactivée sur ce plan (pas "illimité" !) ; > 0 = la
     * vraie limite.
     */
    public function quotaFor(User $user): array
    {
        $abonnement = $user->abonnementActif()->with('plan.features')->first();

        if (!$abonnement) {
            return [
                'used' => 0, 'limit' => 0, 'unlimited' => false, 'disabled' => false,
                'reached' => true, 'remaining' => 0,
                'message' => 'Vous devez souscrire à un abonnement pour publier des offres.',
            ];
        }

        $limitValue = $abonnement->plan?->getFeature('job_post_limit');

        if ($limitValue === null) {
            return ['used' => 0, 'limit' => 0, 'unlimited' => true, 'disabled' => false, 'reached' => false, 'remaining' => null, 'message' => null];
        }

        if ((int) $limitValue === 0) {
            return [
                'used' => 0, 'limit' => 0, 'unlimited' => false, 'disabled' => true,
                'reached' => true, 'remaining' => 0,
                'message' => "Votre plan « {$abonnement->plan->name} » ne permet pas de publier des offres. Souscrivez un plan supérieur.",
            ];
        }

        $limit = (int) $limitValue;
        $cycle = $this->cycles->cycleFor($user, 'job_post_limit', $abonnement);
        $used  = $user->offres()->where('created_at', '>=', $cycle->cycle_starts_at)->count();
        $reached = $used >= $limit;

        // Décision explicite du client : dès qu'un abonnement épuise l'un de
        // ses avantages, le suivant déjà souscrit prend le relais tout de
        // suite plutôt que d'attendre sa date de départ programmée — mais
        // seulement s'il offre vraiment plus de marge (null = illimité ;
        // 0 = désactivé, toujours pire ; sinon une vraie limite supérieure).
        // Renouveler le même plan (même limite) ne raccourcit plus sa durée
        // déjà payée pour rien.
        if ($reached && $this->planning->promouvoirSiEpuise($user, $abonnement, 'job_post_limit', function ($planProchain) use ($limit) {
            $valeurProchaine = $planProchain?->getFeature('job_post_limit');
            return $valeurProchaine === null || (int) $valeurProchaine > $limit;
        })) {
            return $this->quotaFor($user->fresh());
        }

        return [
            'used' => $used, 'limit' => $limit, 'unlimited' => false, 'disabled' => false,
            'reached' => $reached, 'remaining' => max(0, $limit - $used),
            'message' => $reached
                ? "Vous avez utilisé {$used}/{$limit} offres de votre plan « {$abonnement->plan->name} ». Renouvelez votre abonnement pour continuer à publier."
                : null,
        ];
    }
}
