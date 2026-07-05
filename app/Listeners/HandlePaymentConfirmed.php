<?php

namespace App\Listeners;

use App\Enums\Role;
use App\Events\PaymentConfirmed;
use App\Models\Commande;
use App\Models\Notification;
use App\Models\Publicite;
use App\Models\User;
use App\Notifications\AbonnementActiveNotification;
use App\Notifications\NouvelleCommandeServiceNotification;
use App\Notifications\NouvellePubliciteAdminNotification;
use App\Notifications\PaiementConfirmeNotification;
use App\Notifications\PubliciteSoumiseNotification;
use App\Services\AbonnementSchedulingService;
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
            $paiement->refresh()->load(['abonnement.plan', 'payable']);
            $paiement->user?->notify(new PaiementConfirmeNotification($paiement));
        } catch (\Throwable $e) {
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

        // Notification in-app au client
        if ($client) {
            try {
                Notification::create([
                    'user_id' => $client->id,
                    'type'    => 'commande',
                    'titre'   => 'Commande confirmée',
                    'contenu' => "Votre commande « {$service} » a bien été reçue et est en cours de traitement.",
                    'lien'    => url('/'),
                ]);
            } catch (\Throwable $e) {
                Log::warning('Notification in-app commande client non créée', ['error' => $e->getMessage()]);
            }
        }

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
        if ($paiement->credits_cv <= 0 || !$paiement->user_id) return;

        User::where('id', $paiement->user_id)
            ->increment('cv_credits', $paiement->credits_cv);

        try {
            Notification::create([
                'user_id' => $paiement->user_id,
                'type'    => 'commande',
                'titre'   => 'Crédits CVthèque ajoutés',
                'contenu' => "{$paiement->credits_cv} crédit(s) CVthèque ont été ajoutés à votre compte.",
                'lien'    => route('recruteur.cvtheque'),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Notification in-app cv_credits non créée', ['error' => $e->getMessage()]);
        }
    }

    private function activateAbonnement($paiement): void
    {
        if (!$paiement->subscription_id) return;

        $abonnement = $paiement->abonnement()->with('plan.features')->first();
        if (!$abonnement) return;

        $user = $paiement->user;

        // Un abonnement en cours (ou déjà programmé) et encore valide n'est
        // jamais annulé par une nouvelle souscription — celle-ci prend le
        // relais à l'expiration de l'ancien plutôt que de l'écraser.
        $startsAt = $user
            ? app(AbonnementSchedulingService::class)->dateDebut($user, $abonnement->id)
            : now();
        $endsAt   = $abonnement->plan?->duration_days
            ? $startsAt->copy()->addDays($abonnement->plan->duration_days)
            : null;

        $abonnement->update([
            'status'    => 'active',
            'starts_at' => $startsAt,
            'ends_at'   => $endsAt,
        ]);

        $planNom = $abonnement->plan?->name ?? 'votre plan';

        // Notification in-app
        if ($user) {
            try {
                Notification::create([
                    'user_id' => $user->id,
                    'type'    => 'commande',
                    'titre'   => 'Abonnement activé',
                    'contenu' => "Votre abonnement {$planNom} est maintenant actif."
                        . ($endsAt ? " Valable jusqu'au " . $endsAt->format('d/m/Y') . '.' : ''),
                    'lien' => $user->hasRole(Role::RECRUTEUR)
                        ? route('recruteur.abonnement')
                        : route('candidat.abonnement'),
                ]);
            } catch (\Throwable $e) {
                Log::warning('Notification in-app abonnement non créée', ['error' => $e->getMessage()]);
            }

            // Email riche avec détails du plan
            try {
                $user->notify(new AbonnementActiveNotification($abonnement));
            } catch (\Throwable $e) {
                Log::warning('Email AbonnementActive non envoyé', ['error' => $e->getMessage()]);
            }
        }

        // Notification admin
        $admins = User::role(Role::ADMIN)->get();
        foreach ($admins as $admin) {
            try {
                Notification::create([
                    'user_id' => $admin->id,
                    'type'    => 'commande',
                    'titre'   => 'Nouvel abonnement activé',
                    'contenu' => ($user?->nom_complet ?? 'Un utilisateur')
                        . " a souscrit à l'abonnement {$planNom}.",
                    'lien' => route('admin.abonnements'),
                ]);
            } catch (\Throwable $e) {
                Log::warning('Notification admin abonnement non créée', ['error' => $e->getMessage()]);
            }
        }
    }
}
