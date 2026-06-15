<?php

namespace App\Listeners;

use App\Enums\Role;
use App\Events\PaymentConfirmed;
use App\Models\Commande;
use App\Models\Notification;
use App\Models\Publicite;
use App\Models\User;
use App\Notifications\NouvelleCommandeServiceNotification;
use App\Notifications\NouvellePubliciteAdminNotification;
use App\Notifications\PaiementConfirmeNotification;
use App\Notifications\PubliciteSoumiseNotification;
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
            'publicite'                                   => $this->submitPublicite($paiement),
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

        $client  = $commande->user;
        $service = $commande->service?->nom ?? 'Service';

        $admins = User::role(Role::ADMIN)->get();
        foreach ($admins as $admin) {
            try {
                Notification::create([
                    'user_id' => $admin->id,
                    'type'    => 'commande',
                    'titre'   => 'Nouvelle commande à traiter',
                    'contenu' => ($client?->nom_complet ?? 'Un client') . " a commandé « {$service} » et vient de payer.",
                    'lien'    => route('admin.commandes.detail', $commande),
                ]);
                $admin->notify(new NouvelleCommandeServiceNotification($commande, $paiement));
            } catch (\Throwable $e) {
                Log::warning('Notification admin commande service non envoyée', ['error' => $e->getMessage()]);
            }
        }
    }

    private function submitPublicite($paiement): void
    {
        $publicite = $paiement->payable;
        if (!$publicite instanceof Publicite) return;

        $publicite->update(['statut' => 'en_attente']);

        $annonceur = $publicite->user;

        // Notification in-app + email à tous les admins
        $admins = User::role(Role::ADMIN)->get();
        foreach ($admins as $admin) {
            try {
                Notification::create([
                    'user_id' => $admin->id,
                    'type'    => 'publicite',
                    'titre'   => 'Nouvelle publicité à valider',
                    'contenu' => ($annonceur?->nom_complet ?? 'Un annonceur') . " a soumis une publicité : « {$publicite->titre} ».",
                    'lien'    => route('admin.publicites.show', $publicite),
                ]);
                $admin->notify(new NouvellePubliciteAdminNotification($publicite));
            } catch (\Throwable $e) {
                Log::warning('Notification admin publicite non envoyée', ['error' => $e->getMessage()]);
            }
        }

        // Email de confirmation à l'annonceur
        if ($annonceur) {
            try {
                $annonceur->notify(new PubliciteSoumiseNotification($publicite));
            } catch (\Throwable $e) {
                Log::warning('Notification annonceur publicite non envoyée', ['error' => $e->getMessage()]);
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
