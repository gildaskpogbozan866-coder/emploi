<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->configureRateLimiters();
        $this->configurePasswordReset();
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
        // Pointer le lien de réinitialisation vers notre route personnalisée
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return route('auth.reinitialiser', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });
    }
}
