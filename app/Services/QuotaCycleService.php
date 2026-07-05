<?php

namespace App\Services;

use App\Models\Abonnement;
use App\Models\QuotaCycle;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Horloge indépendante par quota FLUX (job_apply_limit, job_post_limit) —
 * jamais utilisée pour les quotas STOCK (cv_limit, alert_limit), qui n'ont
 * pas de date associée et restent de simples plafonds.
 */
class QuotaCycleService
{
    public function cycleFor(User $user, string $quotaKey, Abonnement $abonnement): QuotaCycle
    {
        $cycle = QuotaCycle::firstOrNew(['user_id' => $user->id, 'quota_key' => $quotaKey]);

        // Horloge absente, ou abonnement plus récent que la dernière période
        // connue (chaînage normal à l'échéance) : on recale sur l'abonnement
        // actif du moment — équivalent de l'ancien calcul direct sur
        // $abonnement->starts_at, avant l'introduction de cette table. Si la
        // période en cours vient d'un relais anticipé (resetCycle), son
        // cycle_starts_at est postérieur à celui de l'abonnement : on ne
        // l'écrase pas.
        if (!$cycle->exists || $abonnement->starts_at->gt($cycle->cycle_starts_at)) {
            $cycle->fill([
                'cycle_starts_at' => $abonnement->starts_at,
                'cycle_ends_at'   => $abonnement->ends_at,
            ]);
            $cycle->save();
        }

        return $cycle;
    }

    /**
     * Relais anticipé sur CE quota précis (Chantier B) : remet son horloge à
     * zéro immédiatement, sans toucher aux autres quotas ni à l'Abonnement.
     */
    public function resetCycle(User $user, string $quotaKey, Carbon $debut, ?Carbon $fin): QuotaCycle
    {
        return QuotaCycle::updateOrCreate(
            ['user_id' => $user->id, 'quota_key' => $quotaKey],
            ['cycle_starts_at' => $debut, 'cycle_ends_at' => $fin]
        );
    }
}
