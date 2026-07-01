<?php

namespace App\Notifications;

use App\Models\Paiement;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaiementConfirme extends Notification
{
    public function __construct(public Paiement $paiement) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $montant = number_format($this->paiement->montant, 0, ',', ' ');

        return (new MailMessage)
            ->subject('Paiement confirmé — Emploi Bouge Bénin')
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Votre paiement de **{$montant} FCFA** a été confirmé avec succès.")
            ->line("Votre abonnement est maintenant actif. Vous pouvez profiter de toutes les fonctionnalités Premium.")
            ->action('Accéder à mon espace', url('/'))
            ->line('Merci de votre confiance.')
            ->salutation('L\'équipe Emploi Bouge Bénin');
    }
}
