<?php

namespace App\Listeners;

use App\Enums\Role;
use App\Events\PaymentConfirmed;
use App\Models\Commande;
use App\Models\User;
use App\Notifications\NouvelleCommandeServiceNotification;
use App\Notifications\PaiementConfirmeNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandlePaymentConfirmed
{
    public function handle(PaymentConfirmed $event): void
    {
        $paiement = $event->paiement;

        match($paiement->type) {
            'cv_credits'                                  => $this->activateCvCredits($paiement),
            'abonnement_recruteur', 'abonnement_candidat' => $this->activateAbonnement($paiement),
            'service'                                     => $this->confirmCommande($paiement),
            default                                       => null,
        };

        try {
            $paiement->user?->notify(new PaiementConfirmeNotification($paiement));
        } catch (\Throwable $e) {
            // L'email échoue (ex: rate limit Mailtrap en dev) mais le paiement est déjà confirmé
            Log::warning('Notification paiement non envoyée', [
                'paiement_id' => $paiement->id,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    private function confirmCommande($paiement): void
    {
        $commande = $paiement->payable;
        if (!$commande instanceof Commande) return;

        $commande->update([
            'paiement_statut' => 'paye',
            'statut'          => 'en_cours',
        ]);

        $admins = User::role(Role::ADMIN)->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new NouvelleCommandeServiceNotification($commande, $paiement));
            } catch (\Throwable $e) {
                Log::warning('Notification admin commande service non envoyée', ['error' => $e->getMessage()]);
            }
        }
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
