<?php

namespace App\Notifications;

use App\Models\Offre;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidatureRecueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Offre $offre) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Candidature envoyée — {$this->offre->titre}")
            ->greeting('Bonjour ' . $notifiable->prenom . ' !')
            ->line("Votre candidature pour le poste **{$this->offre->titre}** chez **{$this->offre->entreprise}** a bien été transmise au recruteur.")
            ->line('Vous serez notifié(e) par e-mail dès que le recruteur examinera votre dossier.')
            ->action('Suivre mes candidatures', route('candidat.candidatures'))
            ->line('Bonne chance dans votre recherche d\'emploi !')
            ->salutation('L\'équipe Emploi Bouge Bénin');
    }
}
