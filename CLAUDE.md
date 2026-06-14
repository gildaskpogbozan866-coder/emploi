# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Emploi Bouge Bénin** — a Laravel 12 job platform for Benin with four distinct user roles: `candidat` (job seeker), `recruteur` (employer), `talent` (freelancer/consultant), and `admin`.

## Common Commands

```bash
# Full first-time setup
composer run setup

# Start development (server + queue + logs + Vite, all concurrently)
composer run dev

# Run all tests
composer run test
# or
php artisan test

# Run a single test
php artisan test --filter=TestName

# Code style (Laravel Pint)
./vendor/bin/pint

# Migrations
php artisan migrate

# Seed roles and permissions (required after fresh migration)
php artisan db:seed --class=RolesAndPermissionsSeeder

# Clear permission cache after any role/permission change
php artisan permission:cache-reset
```

## Architecture

### Authentication — Email + password

Authentication uses classic email/password via `AuthController`. The flow is: submit email + password → session opened. Email verification is handled by Laravel's built-in `MustVerifyEmail` flow. Password reset uses the standard `Password::sendResetLink()` / `Password::reset()` pipeline.

Routes: `/auth/connexion`, `/auth/inscription`, `/auth/mot-de-passe-oublie`, `/auth/reinitialiser/{token}`, `/auth/changer-mot-de-passe`.

### Role & Permission System

Two parallel layers are used together:

1. **`spatie.role` middleware** — enforces which role group a user belongs to (gates entire route prefix).
2. **`permission` middleware** — enforces granular permissions within that role group.

All role and permission strings are defined as constants in:
- `app/Enums/Role.php` — `admin`, `recruteur`, `candidat`, `talent`
- `app/Enums\Permission.php` — all permission constants, grouped by role, with `Permission::all()`, `Permission::adminPermissions()`, etc.

Always use these enum constants (e.g., `Permission::PUBLISH_OFFRE`) rather than raw strings. After adding a new permission, add it to the relevant `*Permissions()` method and re-run the seeder.

The `User` model carries both a plain `role` column (for legacy/simple checks) and a Spatie role assignment. Prefer Spatie's `hasRole()` / `can()` methods; the `CheckRole` middleware is a simpler fallback not used in main routes.

### Blade & Frontend

- **Layouts** are role-scoped: `layouts/app.blade.php` (public), `layouts/auth.blade.php`, `layouts/candidat.blade.php`, `layouts/recruteur.blade.php`, `layouts/admin.blade.php`, `layouts/dashboard.blade.php`.
- **CSS is static** — files live in `public/css/` and are served directly via `asset('css/style.css')`. There is no CSS build step (no Tailwind, no PostCSS). Edit CSS files in `public/css/` directly.
- **JS** uses Vite (`resources/js/app.js`, `resources/js/bootstrap.js`). Run `npm run dev` for hot reload.
- **`<x-can>`** is a Blade component for permission-gated rendering: `<x-can permission="manage-blog">…</x-can>`. It accepts either `permission` or `role` prop.

### Route Structure

Routes in `routes/web.php` are organized into five prefix groups, each using named routes:
- `/` — public pages (offres, CVs, blog, services, talents)
- `/auth` — OTP login/registration flow
- `/candidat` — guarded by `spatie.role:candidat`
- `/recruteur` — guarded by `spatie.role:recruteur`
- `/talent` — guarded by `spatie.role:talent`
- `/admin` — guarded by `spatie.role:admin`

Within each role group, features are further gated by `permission` middleware sub-groups.

### Key Models & Relationships

- `User` has many: `Offre` (as recruteur), `Candidature`, `CV`, `Commande`, `Paiement`, `Abonnement`, `Alerte`, `Notification`, `Article`
- `User` has one: `TalentProfil`
- `User` belongsToMany: `Offre` (via `offres_sauvegardees`), `CV` (via `cv_favoris`)
- Policies in `app/Policies/` enforce ownership checks (e.g., `CVPolicy` allows edit/delete only to the CV's owner or an admin).

### Database Notes

- `Schema::defaultStringLength(191)` is set globally in `AppServiceProvider` for MySQL utf8mb4 compatibility.
- The `otp_codes` table holds temporary codes with `expires_at`; rows are deleted after use.
- After any fresh migration, run `RolesAndPermissionsSeeder` to populate roles and permissions — the app will not work without it.
