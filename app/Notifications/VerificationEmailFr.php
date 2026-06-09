<?php

namespace App\Notifications;

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

    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Vérifiez votre adresse e-mail — Emploi Bouge Bénin')
            ->greeting('Bonjour !')
            ->line('Merci de vous être inscrit(e) sur **Emploi Bouge Bénin**.')
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
