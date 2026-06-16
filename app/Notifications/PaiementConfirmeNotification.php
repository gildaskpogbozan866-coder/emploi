<?php

namespace App\Notifications;

use App\Models\Paiement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaiementConfirmeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Paiement $paiement) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $paiement = $this->paiement;
        $montant  = number_format($paiement->montant, 0, ',', ' ') . ' ' . ($paiement->devise ?? 'XOF');
        $prenom   = $notifiable->prenom ?? 'Utilisateur';

        [$sujet, $lignes, $lien, $labelLien] = $this->getContenu($paiement, $montant, $notifiable);

        $mail = (new MailMessage)
            ->subject($sujet . ' — Emploi Bouge Bénin')
            ->greeting("Bonjour {$prenom},");

        foreach (array_filter($lignes) as $ligne) {
            $mail->line($ligne);
        }

        return $mail
            ->line("**Montant réglé : {$montant}**")
            ->line("**Référence :** " . ($paiement->reference ?? $paiement->id))
            ->action($labelLien, $lien)
            ->line('Merci de votre confiance en Emploi Bouge Bénin.')
            ->salutation("L'équipe Emploi Bouge Bénin");
    }

    private function getContenu(Paiement $paiement, string $montant, object $notifiable): array
    {
        return match($paiement->type) {
            'cv_credits' => [
                '✅ Vos crédits CVthèque sont disponibles',
                [
                    "Votre paiement a été confirmé et **{$paiement->credits_cv} crédit(s) CVthèque** ont été ajoutés à votre compte.",
                    "Vous pouvez dès maintenant accéder à la CVthèque et consulter les profils des candidats.",
                ],
                route('recruteur.cvtheque'),
                'Accéder à la CVthèque',
            ],
            'abonnement_candidat' => [
                '✅ Votre abonnement candidat est activé',
                [
                    "Votre abonnement **{$paiement->abonnement?->plan?->name}** est maintenant actif.",
                    "Vous bénéficiez désormais de toutes les fonctionnalités incluses dans votre plan : candidatures illimitées, profil optimisé et bien plus encore.",
                    $paiement->abonnement?->ends_at
                        ? "Votre abonnement est valable jusqu'au **" . $paiement->abonnement->ends_at->format('d/m/Y') . "**."
                        : null,
                ],
                route('candidat.abonnement'),
                'Voir mon abonnement',
            ],
            'abonnement_recruteur' => [
                '✅ Votre abonnement recruteur est activé',
                [
                    "Votre abonnement **{$paiement->abonnement?->plan?->name}** est maintenant actif.",
                    "Vous pouvez désormais publier des offres d'emploi, accéder à la CVthèque et gérer vos candidatures selon les limites de votre plan.",
                    $paiement->abonnement?->ends_at
                        ? "Votre abonnement est valable jusqu'au **" . $paiement->abonnement->ends_at->format('d/m/Y') . "**."
                        : null,
                ],
                route('recruteur.abonnement'),
                'Voir mon abonnement',
            ],
            'service' => [
                '✅ Votre commande de service est confirmée',
                [
                    "Votre paiement a été reçu et votre commande est enregistrée.",
                    "**Service :** " . ($paiement->payable?->service?->nom ?? 'Service commandé'),
                    "**Référence commande :** " . ($paiement->payable?->reference ?? '—'),
                    "Notre équipe va prendre en charge votre demande dans les meilleurs délais. Vous serez notifié(e) à chaque étape.",
                ],
                url('/'),
                'Accéder à mon espace',
            ],
            'publicite' => [
                '✅ Votre publicité est soumise pour validation',
                [
                    "Votre paiement a été confirmé et votre annonce publicitaire est maintenant en attente de validation par notre équipe.",
                    "**Annonce :** " . ($paiement->payable?->titre ?? '—'),
                    "Notre équipe examine les annonces sous 24h ouvrées. Vous recevrez un e-mail dès l'approbation ou en cas de modification requise.",
                ],
                route('annonceur.publicites'),
                'Suivre mes annonces',
            ],
            default => [
                '✅ Paiement confirmé',
                ["Votre paiement a été confirmé avec succès sur Emploi Bouge Bénin."],
                url('/'),
                'Accéder à mon espace',
            ],
        };
    }
}
