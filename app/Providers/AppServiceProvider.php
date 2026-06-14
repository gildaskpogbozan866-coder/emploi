<?php

namespace App\Providers;

use App\Events\PaymentConfirmed;
use App\Listeners\HandlePaymentConfirmed;
use App\View\Composers\NotificationComposer;
use App\View\Composers\SeoComposer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Event::listen(PaymentConfirmed::class, HandlePaymentConfirmed::class);
        $this->configureRateLimiters();
        $this->configurePasswordReset();
        $this->configureEmailVerification();

        View::composer(
            ['layouts.candidat', 'layouts.recruteur', 'layouts.admin', 'layouts.annonceur'],
            NotificationComposer::class
        );

        View::composer('layouts.app', SeoComposer::class);

        // reCAPTCHA — disponible dans toutes les vues publiques
        try {
            $siteKey = \App\Models\ParametreApp::get('recaptcha_site_key', '');
            View::share('recaptchaSiteKey', $siteKey);
            View::share('recaptchaActif',   !app()->isLocal() && $siteKey !== '');
        } catch (\Throwable) {
            View::share('recaptchaSiteKey', '');
            View::share('recaptchaActif',   false);
        }
    }

    private function configureRateLimiters(): void
    {
        RateLimiter::for('connexion', function (Request $request) {
            return Limit::perMinutes(10, 10)
                ->by($request->input('email', '') . '|' . $request->ip());
        });
    }

    private function configurePasswordReset(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return route('auth.reinitialiser', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });
    }

    private function configureEmailVerification(): void
    {
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id'   => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });
    }
}
