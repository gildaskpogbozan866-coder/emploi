<?php

namespace App\Notifications;

use App\Models\Abonnement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbonnementActiveNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Abonnement $abonnement) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $abonnement = $this->abonnement;
        $plan       = $abonnement->plan;
        $prenom     = $notifiable->prenom ?? 'Utilisateur';
        $role       = $notifiable->role ?? 'candidat';

        $typeLabel  = $role === 'recruteur' ? 'recruteur' : 'candidat';
        $dashRoute  = $role === 'recruteur'
            ? route('recruteur.abonnement')
            : route('candidat.abonnement');

        $finLabel = $abonnement->ends_at
            ? $abonnement->ends_at->format('d/m/Y')
            : 'Sans limite de durée';

        $mail = (new MailMessage)
            ->subject("🎉 Votre abonnement {$typeLabel} est activé — Emploi Bouge Bénin")
            ->greeting("Bonjour {$prenom},")
            ->line("Votre abonnement **{$plan->name}** vient d'être activé avec succès.")
            ->line("**Date d'activation :** " . $abonnement->starts_at->format('d/m/Y'))
            ->line("**Valable jusqu'au :** {$finLabel}");

        // Lister les fonctionnalités du plan si disponibles
        $features = $plan->features ?? collect();
        if ($features->isNotEmpty()) {
            $mail->line('---')
                 ->line('**Ce que votre plan inclut :**');
            foreach ($features as $feature) {
                $mail->line("• {$feature->feature_key} : {$feature->feature_value}");
            }
        }

        return $mail
            ->line('---')
            ->action('Gérer mon abonnement', $dashRoute)
            ->line("Pour toute question, n'hésitez pas à nous contacter.")
            ->salutation("L'équipe Emploi Bouge Bénin");
    }
}
