<?php

namespace App\Services;

use App\Models\User;

class CvQuotaService
{
    public function __construct(private readonly AbonnementSchedulingService $planning) {}

    public function quotaFor(User $user): array
    {
        $abonnement = $user->abonnementActif()->with('plan.features')->first();

        // Nombre de CV actuellement possédés (SoftDeletes exclut déjà ceux
        // supprimés) — un compteur "en cours", pas "créés depuis X" : c'est
        // une limite de stockage, pas une limite d'activité sur une période.
        $total = $user->cvs()->count();
        $limit = $abonnement ? (int) ($abonnement->plan?->getFeature('cv_limit', 1) ?? 1) : 1;

        $unlimited = $limit === 0;
        $reached   = !$unlimited && $total >= $limit;

        // Décision explicite du client : dès qu'un abonnement épuise l'un de
        // ses avantages, le suivant déjà souscrit prend le relais tout de
        // suite plutôt que d'attendre sa date de départ programmée — mais
        // seulement si ce suivant offre vraiment plus de marge CV (0 = illimité
        // ici). Renouveler le même plan (même limite) ne raccourcit plus la
        // durée déjà payée pour rien : le quota reste simplement atteint.
        if ($reached && $abonnement && $this->planning->promouvoirSiEpuise($user, $abonnement, 'cv_limit', function ($planProchain) use ($limit) {
            $limiteProchaine = (int) ($planProchain?->getFeature('cv_limit', 1) ?? 1);
            return $limiteProchaine === 0 || $limiteProchaine > $limit;
        })) {
            return $this->quotaFor($user->fresh());
        }

        return [
            'used'      => $total,
            'limit'     => $limit,
            'unlimited' => $unlimited,
            'reached'   => $reached,
            'remaining' => $unlimited ? null : max(0, $limit - $total),
        ];
    }
}
