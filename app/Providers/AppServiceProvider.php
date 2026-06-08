<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->configureRateLimiters();
    }

    private function configureRateLimiters(): void
    {
        // Inscription + connexion : 5 envois OTP / 10 min / IP+email
        // Clé composite IP+email : un attaquant ne peut pas spammer un email précis
        // même en changeant d'adresse, et ne peut pas spammer depuis la même IP.
        RateLimiter::for('otp-envoi', function (Request $request) {
            return Limit::perMinutes(10, 5)
                ->by($request->input('email', '') . '|' . $request->ip());
        });

        // Vérification du code : 10 essais / 10 min / IP
        // Plus permissif (fautes de frappe légitimes), bloqué par IP.
        RateLimiter::for('otp-verification', function (Request $request) {
            return Limit::perMinutes(10, 10)
                ->by($request->ip());
        });

        // Renvoi OTP : 3 demandes / 10 min / IP — très strict.
        RateLimiter::for('otp-renvoyer', function (Request $request) {
            return Limit::perMinutes(10, 3)
                ->by($request->ip());
        });
    }
}
