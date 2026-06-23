<?php

namespace App\Notifications;

use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleCommandeServiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Commande $commande,
        private Paiement $paiement,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $montant  = number_format($this->paiement->montant, 0, ',', ' ') . ' XOF';
        $service  = $this->commande->service?->nom ?? 'Service #' . $this->commande->service_id;
        $adminUrl = route('admin.commandes.detail', $this->commande);

        $user      = $this->commande->user;
        $clientNom = $user
            ? trim(($user->prenom ?? '') . ' ' . ($user->nom ?? ''))
            : 'Invité';
        $clientEmail = $user?->email ?? $this->commande->email_contact ?? 'Non renseigné';

        $details = $this->commande->details_demande
            ? $this->commande->details_demande
            : '(aucun détail renseigné)';

        $mail = (new MailMessage)
            ->subject("🆕 Nouvelle commande payée — {$service}")
            ->greeting('Bonjour,')
            ->line("Une nouvelle commande de service vient d'être réglée et attend votre traitement.")
            ->line('---')
            ->line("**📋 Service :** {$service}")
            ->line("**👤 Client :** {$clientNom}")
            ->line("**📧 Email :** {$clientEmail}")
            ->line("**🔖 Référence :** {$this->commande->reference}")
            ->line("**💰 Montant payé :** {$montant}")
            ->line("**⏱ Délai de livraison :** " . ($this->commande->service?->delai ?? 'Non précisé'))
            ->line('---')
            ->line("**💬 Informations du client :**")
            ->line($details);

        if ($this->commande->fichier_joint) {
            $fileUrl = url('storage/' . $this->commande->fichier_joint);
            $mail->line('---')
                 ->line("**📎 Document joint :** Le client a joint un fichier.")
                 ->action('Télécharger le document', $fileUrl);
        } else {
            $mail->action('Voir la commande dans l\'admin', $adminUrl);
        }

        $mail->line('---')
             ->line('**Important :** Traitez cette commande dans les délais indiqués.')
             ->action('Accéder à la commande', $adminUrl)
             ->salutation('Emploi Bouge Bénin — Notifications automatiques');

        return $mail;
    }
}
