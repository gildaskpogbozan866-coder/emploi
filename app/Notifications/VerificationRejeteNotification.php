<?php

namespace App\Notifications;

use App\Models\RecruteurVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationRejeteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private RecruteurVerification $verification,
        private string $motif,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $prenom     = $notifiable->prenom ?? 'Recruteur';
        $entreprise = $notifiable->entreprise ?? 'votre entreprise';

        return (new MailMessage)
            ->subject("❌ Votre dossier recruteur n'a pas été approuvé — Emploi Bouge Bénin")
            ->greeting("Bonjour {$prenom},")
            ->line("Après examen de votre dossier de vérification pour **{$entreprise}**, nous ne pouvons malheureusement pas l'approuver en l'état.")
            ->line("**Motif :** {$this->motif}")
            ->line("Vous pouvez corriger les points mentionnés et soumettre un nouveau dossier depuis votre espace recruteur.")
            ->action('Soumettre un nouveau dossier', route('recruteur.verification'))
            ->line("Si vous pensez qu'il s'agit d'une erreur ou si vous avez besoin d'aide, contactez-nous.")
            ->salutation("L'équipe Emploi Bouge Bénin");
    }
}
