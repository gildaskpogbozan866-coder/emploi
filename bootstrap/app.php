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
        $middleware->alias([
            // Notre middleware de rôle custom (garde-fou simple)
            'role'       => \App\Http\Middleware\CheckRole::class,

            // Middlewares Spatie granulaires
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'spatie.role'=> \Spatie\Permission\Middleware\RoleMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Pour les routes web OTP, rediriger avec un message d'erreur ciblé
        // plutôt qu'afficher une page 429 générique incompréhensible.
        $exceptions->render(function (
            \Illuminate\Http\Exceptions\ThrottleRequestsException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($request->expectsJson()) {
                return null; // laisser le comportement API par défaut
            }

            $field = str_contains($request->path(), 'verification') ? 'code' : 'email';

            return back()
                ->withErrors([$field => 'Trop de tentatives. Patientez quelques minutes avant de réessayer.'])
                ->withInput();
        });
    })->create();
