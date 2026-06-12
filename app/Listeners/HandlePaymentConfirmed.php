<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HandlePaymentConfirmed
{
    public function handle(PaymentConfirmed $event): void
    {
        $paiement = $event->paiement;

        match($paiement->type) {
            'cv_credits'           => $this->activateCvCredits($paiement),
            'abonnement_recruteur' => $this->activateAbonnement($paiement),
            default                => null,
        };
    }

    private function activateCvCredits($paiement): void
    {
        if ($paiement->credits_cv > 0 && $paiement->user_id) {
            User::where('id', $paiement->user_id)
                ->increment('cv_credits', $paiement->credits_cv);
        }
    }

    private function activateAbonnement($paiement): void
    {
        if (!$paiement->subscription_id) return;

        $abonnement = $paiement->abonnement()->with('plan')->first();
        if (!$abonnement) return;

        $startsAt = now();
        $endsAt   = $abonnement->plan?->duration_days
            ? $startsAt->copy()->addDays($abonnement->plan->duration_days)
            : null;

        $abonnement->update([
            'status'    => 'active',
            'starts_at' => $startsAt,
            'ends_at'   => $endsAt,
        ]);
    }
}
