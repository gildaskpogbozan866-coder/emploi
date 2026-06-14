<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('auth.connexion'));

        // Endpoints webhook (push serveur-à-serveur) — pas de session, pas de CSRF
        $middleware->validateCsrfTokens(except: [
            'payment/webhook/*',
        ]);

        $middleware->alias([
            'role'               => \App\Http\Middleware\CheckRole::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'spatie.role'        => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'recruteur.approuve' => \App\Http\Middleware\RecruteurApprouve::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // 419 — CSRF expiré : redirect back avec message au lieu de page blanche
        $exceptions->render(function (
            \Illuminate\Session\TokenMismatchException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expirée. Veuillez réessayer.'], 419);
            }

            return redirect()->back()
                ->withInput($request->except('password', 'mot_de_passe_actuel', 'password_confirmation'))
                ->withErrors(['session' => 'Votre session a expiré. Veuillez soumettre le formulaire à nouveau.']);
        });

        // 429 — Trop de tentatives
        $exceptions->render(function (
            \Illuminate\Http\Exceptions\ThrottleRequestsException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($request->expectsJson()) {
                return null;
            }

            return back()
                ->withErrors(['credentials' => 'Trop de tentatives. Veuillez patienter quelques minutes avant de réessayer.'])
                ->withInput($request->except('password', 'mot_de_passe_actuel', 'password_confirmation'));
        });

    })->create();
