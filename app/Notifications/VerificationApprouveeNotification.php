<?php

namespace App\Notifications;

use App\Models\RecruteurVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationApprouveeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private RecruteurVerification $verification) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $prenom     = $notifiable->prenom ?? 'Recruteur';
        $entreprise = $notifiable->entreprise ?? 'votre entreprise';

        return (new MailMessage)
            ->subject('✅ Votre dossier recruteur a été approuvé — Emploi Bouge Bénin')
            ->greeting("Bonjour {$prenom},")
            ->line("Bonne nouvelle ! Votre dossier de vérification pour **{$entreprise}** a été examiné et **approuvé** par notre équipe.")
            ->line("Vous avez désormais accès à toutes les fonctionnalités recruteur de la plateforme :")
            ->line("• Publier des offres d'emploi")
            ->line("• Accéder à la CVthèque et contacter les candidats")
            ->line("• Gérer vos candidatures")
            ->action('Accéder à mon espace recruteur', route('recruteur.dashboard'))
            ->line("Si vous avez des questions, notre équipe est disponible pour vous accompagner.")
            ->salutation("L'équipe Emploi Bouge Bénin");
    }
}
