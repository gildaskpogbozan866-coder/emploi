<?php

namespace App\Notifications;

use App\Models\RecruteurVerification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleVerificationRecruteur extends Notification
{
    public function __construct(public RecruteurVerification $verification) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $recruteur  = $this->verification->user;
        $examinerUrl = route('admin.verifications.show', $this->verification);

        return (new MailMessage)
            ->subject('Nouveau dossier recruteur à examiner — Emploi Bouge Bénin')
            ->greeting('Bonjour,')
            ->line("Un nouveau dossier de vérification vient d'être soumis et attend votre validation.")
            ->line("**Entreprise :** " . ($recruteur->entreprise ?? '—'))
            ->line("**Contact :** {$recruteur->prenom} {$recruteur->nom} ({$recruteur->email})")
            ->action('Examiner le dossier', $examinerUrl)
            ->line('Connectez-vous à votre espace administrateur pour approuver ou rejeter ce dossier.')
            ->salutation('Emploi Bouge Bénin — Système de notifications');
    }
}
