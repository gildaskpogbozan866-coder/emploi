<?php

namespace App\Providers;

use App\Events\CandidatureDeposee;
use App\Events\PaymentConfirmed;
use App\Listeners\HandlePaymentConfirmed;
use App\Listeners\NotifierRecruteurCandidature;
use App\Models\CreditCvPack;
use App\Models\Disponibilite;
use App\Models\Langue;
use App\Models\Metier;
use App\Models\NiveauEtude;
use App\Models\NiveauExperience;
use App\Models\Pays;
use App\Models\Region;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use App\View\Composers\NotificationComposer;
use App\View\Composers\SeoComposer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
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
        Event::listen(CandidatureDeposee::class, NotifierRecruteurCandidature::class);
        $this->configureRateLimiters();
        $this->configurePasswordReset();
        $this->configureEmailVerification();

        View::composer(
            ['layouts.candidat', 'layouts.recruteur', 'layouts.admin', 'layouts.annonceur'],
            NotificationComposer::class
        );

        View::composer('layouts.app', SeoComposer::class);

        // ── Invalidation automatique du cache dès qu'un référentiel est modifié ──
        $cacheInvalidationMap = [
            Pays::class           => ['pays_list'],
            Disponibilite::class  => ['disponibilites_list'],
            SecteurActivite::class => ['secteursList'],
            TypeContrat::class    => ['typeContratsList'],
            NiveauExperience::class => ['niveauxExpList'],
            NiveauEtude::class    => ['niveauxEtudeList'],
            Langue::class         => ['languesList'],
            Metier::class         => ['metiersList'],
            Region::class         => ['regionsList'],
            CreditCvPack::class   => [],
        ];

        foreach ($cacheInvalidationMap as $modelClass => $keys) {
            $modelClass::saved(function () use ($keys) {
                foreach ($keys as $key) Cache::forget($key);
            });
            $modelClass::deleted(function () use ($keys) {
                foreach ($keys as $key) Cache::forget($key);
            });
        }

        // Liste des pays — partagée dans toutes les vues
        try {
            $paysList = Cache::remember('pays_list', 3600, fn () => Pays::actifs()->pluck('nom'));
            View::share('paysList', $paysList);
        } catch (\Throwable) {
            View::share('paysList', collect(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Niger','Guinée','Congo','Madagascar','Autre']));
        }

        // Liste des disponibilités — partagée dans toutes les vues
        try {
            $disponibilitesList = Cache::remember('disponibilites_list', 3600, fn () => Disponibilite::actifs()->get());
            View::share('disponibilitesList', $disponibilitesList);
        } catch (\Throwable) {
            View::share('disponibilitesList', collect([
                (object)['code' => 'en_recherche', 'libelle' => 'En recherche active',    'couleur' => '#4ade80'],
                (object)['code' => 'ouvert',       'libelle' => 'Ouvert aux opportunités', 'couleur' => '#facc15'],
                (object)['code' => 'indisponible', 'libelle' => 'Non disponible',          'couleur' => '#f87171'],
            ]));
        }

        // Référentiels RH — partagés dans toutes les vues (cache 1h)
        $referentiels = [
            'secteursList'     => fn () => SecteurActivite::orderBy('libelle')->get(),
            'typeContratsList' => fn () => TypeContrat::orderBy('libelle')->get(),
            'niveauxExpList'   => fn () => NiveauExperience::orderBy('ordre')->get(),
            'niveauxEtudeList' => fn () => NiveauEtude::orderBy('ordre')->get(),
            'languesList'      => fn () => Langue::orderBy('nom')->get(),
            'metiersList'      => fn () => Metier::orderBy('nom')->get(),
            'regionsList'      => fn () => Region::actifs()->get(),
        ];

        foreach ($referentiels as $varName => $loader) {
            try {
                View::share($varName, Cache::remember($varName, 3600, $loader));
            } catch (\Throwable) {
                View::share($varName, collect());
            }
        }

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
