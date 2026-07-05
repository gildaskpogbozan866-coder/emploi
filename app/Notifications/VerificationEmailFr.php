<?php

namespace App\Notifications;

use App\Enums\Role;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerificationEmailFr extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    private ?string $notifiableRole   = null;
    private ?string $notifiablePrenom = null;

    public function toMail($notifiable)
    {
        $this->notifiableRole   = $notifiable->role ?? null;
        $this->notifiablePrenom = $notifiable->prenom ?? null;

        return parent::toMail($notifiable);
    }

    protected function buildMailMessage($url): MailMessage
    {
        $roleLabel = match ($this->notifiableRole ?? null) {
            Role::RECRUTEUR => 'recruteur',
            Role::ANNONCEUR => 'annonceur',
            Role::ADMIN     => 'administrateur',
            default         => 'candidat',
        };

        $prenom = $this->notifiablePrenom ?: null;

        return (new MailMessage)
            ->subject('Vérifiez votre adresse e-mail — Emploi Bouge Bénin')
            ->greeting($prenom ? "Bonjour {$prenom} !" : 'Bonjour !')
            ->line("Vous venez de créer un compte **{$roleLabel}** sur **Emploi Bouge Bénin**. Bienvenue !")
            ->line('Cliquez sur le bouton ci-dessous pour activer votre compte. Ce lien expire dans 60 minutes.')
            ->action('Activer mon compte', $url)
            ->line('Si vous n\'avez pas créé de compte, vous pouvez ignorer cet e-mail.')
            ->salutation('L\'équipe Emploi Bouge Bénin');
    }

    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }
}
