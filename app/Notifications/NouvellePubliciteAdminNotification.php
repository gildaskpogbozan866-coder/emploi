<?php

namespace App\Notifications;

use App\Models\Publicite;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvellePubliciteAdminNotification extends Notification
{
    public function __construct(public Publicite $publicite) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $annonceur = $this->publicite->user;
        $adminUrl  = route('admin.publicites.show', $this->publicite);

        return (new MailMessage)
            ->subject('🔔 Nouvelle publicité à valider — ' . $this->publicite->titre)
            ->greeting('Bonjour ' . ($notifiable->prenom ?? 'Admin') . ',')
            ->line('Un annonceur vient de soumettre une publicité après paiement. Elle attend votre validation.')
            ->line('**Annonce :** ' . $this->publicite->titre)
            ->line('**Annonceur :** ' . ($annonceur?->nom_complet ?? 'Inconnu'))
            ->line('**Email :** ' . ($annonceur?->email ?? '—'))
            ->action('Examiner la publicité', $adminUrl)
            ->salutation('Emploi Bouge Bénin — Système de notifications');
    }
}
