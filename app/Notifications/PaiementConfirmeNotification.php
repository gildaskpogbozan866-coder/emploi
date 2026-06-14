<?php

namespace App\Notifications;

use App\Models\Paiement;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaiementConfirmeNotification extends Notification
{
    public function __construct(private Paiement $paiement) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $paiement = $this->paiement;
        $montant  = number_format($paiement->montant, 0, ',', ' ') . ' ' . $paiement->devise;

        [$sujet, $ligne] = match($paiement->type) {
            'cv_credits' => [
                'Vos crédits CVthèque sont disponibles',
                "{$paiement->credits_cv} crédit(s) CVthèque ont été ajoutés à votre compte.",
            ],
            'abonnement_candidat' => [
                'Votre abonnement est activé',
                "Votre abonnement candidat ({$paiement->abonnement?->plan?->name}) est maintenant actif.",
            ],
            'abonnement_recruteur' => [
                'Votre abonnement recruteur est activé',
                "Votre abonnement recruteur ({$paiement->abonnement?->plan?->name}) est maintenant actif.",
            ],
            default => [
                'Paiement confirmé',
                'Votre paiement a été confirmé avec succès.',
            ],
        };

        return (new MailMessage)
            ->subject($sujet . ' — Emploi Bouge Bénin')
            ->greeting('Bonjour ' . ($notifiable->prenom ?? '') . ',')
            ->line($ligne)
            ->line("Montant réglé : **{$montant}**")
            ->action('Accéder à mon espace', url('/'))
            ->line('Merci de votre confiance.');
    }
}
