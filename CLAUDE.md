# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Emploi Bouge Bénin** — a Laravel 12 job platform for Benin with four distinct user roles: `candidat` (job seeker), `recruteur` (employer), `annonceur` (advertiser — buys ad placements), and `admin`. A former `talent` (freelancer) role existed but was merged into `candidat`; do not reintroduce it — see "Role & Permission System" below.

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

`AuthController::connecter()` (login) must use `redirect()->intended($this->dashboardUrl(...))`, never a bare `redirect($this->dashboardUrl(...))` with `session()->forget('url.intended')`. The signed email-verification link (`verification.verify`) sits behind `auth` middleware — if a user clicks it while logged out, Laravel stores it as the session's intended URL before bouncing to login. Dropping that intended URL on login sends the user straight to their (unverified) dashboard instead, which the `verified` middleware then bounces back to `verification.notice` — an unrecoverable loop where the email link never actually gets fulfilled. This exact bug existed and was fixed (2026-07-02).

**Custom branded mail theme**: `resources/views/vendor/mail/` (published via `php artisan vendor:publish --tag=laravel-mail`) is customized to the site's brand colors (`#042C53` dark blue, `#F5C842` yellow — matches `--bleu-fonce`/`--jaune` in `public/css/admin/admin.css`). This reskins **every** notification built with Laravel's `MailMessage` fluent API automatically (verification email, abonnement activation, payment confirmations, etc.) — don't hand-roll per-notification branding, it already applies globally. The logo in `html/header.blade.php` is embedded as a base64 `data:` URI (reads `public_path('images/Logo.png')` at render time), not an `asset()` URL — mail hosts (Mailtrap, and potentially some corporate mail relays) can't necessarily reach `APP_URL` to fetch a linked image, especially in local dev. Trade-off: base64 images don't render in older Outlook desktop (Word rendering engine); switch to a true CID `embed()`-based attachment if that audience matters.

### Role & Permission System

Two parallel layers are used together:

1. **`spatie.role` middleware** — enforces which role group a user belongs to (gates entire route prefix).
2. **`permission` middleware** — enforces granular permissions within that role group.

All role and permission strings are defined as constants in:
- `app/Enums/Role.php` — `ADMIN`, `RECRUTEUR`, `CANDIDAT`, `ANNONCEUR`
- `app/Enums/Permission.php` — all permission constants, grouped by role, with `Permission::all()`, `Permission::adminPermissions()`, etc.

Always use these enum constants (e.g., `Permission::PUBLISH_OFFRE`, `Role::CANDIDAT`) rather than raw strings, both in PHP and in Blade (`\App\Enums\Role::CANDIDAT` — fully-qualify, no `use` needed in Blade). After adding a new permission, add it to the relevant `*Permissions()` method and re-run the seeder.

The `User` model carries both a plain `role` column (for legacy/simple checks, e.g. filtering/listing queries) and a Spatie role assignment. Prefer Spatie's `hasRole()` / `can()` for any actual authorization decision — never mix the two for the same gate (raw-column check + Spatie check disagreeing is how access-control bugs happen). There is no more `CheckRole` middleware (removed as dead code); `spatie.role` + `permission` middleware are the only two role-gating mechanisms.

**`users.role` DB column is a MySQL `ENUM`** — currently `candidat, recruteur, admin, annonceur`. `annonceur` was added to the real MySQL enum back in `2026_06_13_142018_add_annonceur_to_users_role_enum` (already applied — production has always been fine here). That migration's `up()`/`down()` were wrapped in `if (DB::getDriverName() !== 'sqlite')`, which skipped SQLite entirely — meaning the annonceur role could never actually be tested (creating one in the SQLite test suite threw a CHECK-constraint error). `2026_07_02_180000_fix_role_enum_add_annonceur_remove_talent` fixed that by branching on the driver instead of skipping non-MySQL ones: raw `ALTER ... MODIFY COLUMN` for MySQL, Schema Builder `->change()` for everything else (also drops the now-unused `talent` value). If you ever add a new role, follow that pattern rather than an SQLite skip-guard, or you'll silently lose test coverage for it.

### Blade & Frontend

- **Layouts** are role-scoped: `layouts/app.blade.php` (public), `layouts/auth.blade.php`, `layouts/candidat.blade.php`, `layouts/recruteur.blade.php`, `layouts/admin.blade.php`, `layouts/annonceur.blade.php`.
- **CSS is static** — files live in `public/css/` and are served directly via `asset('css/style.css')`. There is no CSS build step (no Tailwind, no PostCSS). Edit CSS files in `public/css/` directly.
- **JS** uses Vite (`resources/js/app.js`, `resources/js/bootstrap.js`). Run `npm run dev` for hot reload.
- **`<x-can>`** is a Blade component for permission-gated rendering: `<x-can permission="manage-blog">…</x-can>`. It accepts either `permission` or `role` prop.
- **Double-escaping trap in `layouts/app.blade.php`**: `@section('title', 'some string')` — the **inline** two-argument form of `@section` — is pre-escaped by Blade internally (`Illuminate\View\Factory::startSection()` runs `e()` on it before storing). The layout reads it back via `$__env->yieldContent('title')` and must output it with `{!! !!}`, not `{{ }}` — wrapping an already-escaped string in `{{ }}` double-encodes it (`'` → `&#039;` → `&amp;#039;`, visibly broken in the browser tab). This applies to every SEO field computed the same way in that layout (`title`, `description`, `robots`, `canonical`, `og_title`, `og_description`, `og_url`, `og_image`) — the `$seo[...]` fallback branches (no `@section` set) are raw/unescaped and must go through `e()` manually instead, since they never passed through `startSection()`. Other layouts (`auth`, `candidat`, `recruteur`, `admin`, `annonceur`) sidestep this entirely by using `@yield('title', 'default')` directly — don't "fix" those to match `app.blade.php`'s pattern, they're already correct.

### Route Structure

Routes in `routes/web.php` are organized into prefix groups, each using named routes:
- `/` — public pages (offres, CVs, blog, services)
- `/auth` — email + password login/registration flow (see Authentication above)
- `/candidat` — guarded by `spatie.role:candidat`
- `/recruteur` — guarded by `spatie.role:recruteur`
- `/annonceur` — guarded by `spatie.role:annonceur`
- `/admin` — guarded by `spatie.role:admin`

Within each role group, features are further gated by `permission` middleware sub-groups.

### Key Models & Relationships

- `User` has many: `Offre` (as recruteur), `Candidature`, `CV`, `Commande`, `Paiement`, `Abonnement`, `Alerte`, `Notification`, `Article`, `Publicite` (as annonceur)
- `User` has one: `CandidatProfil` (extended candidat profile — titre_professionnel, bio, ville, disponibilite, salaire_min/max, remote, linkedin, portfolio, annees_experience)
- `User` belongsToMany: `Offre` (via `offres_sauvegardees`), `CV` (via `cv_favoris`)
- Policies in `app/Policies/` enforce ownership checks (e.g., `CVPolicy` allows edit/delete only to the CV's owner or an admin).

### CV lifecycle & CVthèque visibility (2026-07-03)

- **`CV` uses `SoftDeletes`.** A candidat "deleting" their CV (`CVController::destroy()`, calls plain `$cv->delete()`) must never hard-delete — it only sets `deleted_at`. Reason: `cv_downloads` (a recruteur's paid-credit download history) and `candidatures.cv_id` both reference `cvs.id`, and a recruteur must keep access to a CV they already legitimately viewed/paid for or that was already submitted with a job application, even after the candidat removes it from public view. `Candidature::cv()` and `CvDownload::cv()` both use `->withTrashed()` for exactly this reason — don't remove that, it silently breaks recruiter-facing history for any CV a candidat has since deleted.
- **`CV::scopeVisible()`** requires `visible = true AND publie_le IS NOT NULL` — this is the one gate that must be used (not raw `where('visible', true)`) anywhere a CV is surfaced to the public or to recruiters (CVthèque listings, detail pages, downloads). `SoftDeletes`'s global scope already excludes trashed rows from this automatically.
- **`Document`** (diplomas, attestations, certificates — a separate upload type from `CV`, both created through the same "Déposer un CV" form at `cv.public.depot`, branching on `type_document_id`) has **no visibility/consent mechanism at all** — no `visible` column, nothing. It must never be merged into a public-facing CVthèque search/listing (it once was, in both `Candidat\CVController::theque()` and `Recruteur\CvthequeController::index()` — removed 2026-07-03). It remains fully usable via the candidat's own document management pages (`candidat.cvs`, `Candidat\DocumentController`) and the admin document list — only the public/recruiter *search* merge was the problem.
- **`public.candidat.detail`** (`/candidat/{id}/profil`, `CVController::candidatDetails()`) requires both a `CandidatProfil` row **and** a real visible+published CV — filling in a profile alone is not enough to be publicly listed. This route is not linked from anywhere in the current UI (confirmed via full-codebase search) — every "view a candidat" surface (CVthèque, homepage, candidature detail) links to the CV detail page (`cv.public.detail`) instead, not this one. It's still live and reachable by direct/guessed URL, so it still needs its own guard; don't assume "unlinked" means "safe to leave unguarded."
- **`document.public.detail`** (`/documents/{document}`, `CVController::documentDetail()`) has **no auth check and no visibility gate at all** — this is a known, still-open gap (not yet fixed as of 2026-07-03). Any document ID is viewable by anyone who has/guesses it.
- The `actif` (deactivated account) filter must be applied consistently across all three "browse candidats" surfaces (`HomeController::index()`, `Candidat\CVController::theque()`, `Recruteur\CvthequeController::index()`) — it was previously only on the public CVthèque, so a deactivated candidat could still surface to recruiters and on the homepage.

### Payments & Notifications — event-driven, confirmation-gated

All paid flows (`Commande` service orders, CV credit purchases, subscriptions, ad placements) follow the same pattern — **never send an email or activate anything just because a `Paiement` row was created**:

1. Controller creates the domain record (`Commande`/`Publicite`/etc.) with a pending status, then a `Paiement` with `statut: 'en_attente'`, then redirects to `payment.choose`. No notification fires here.
2. The gateway (FedaPay/KKiaPay webhook or verified browser callback — `app/Http/Controllers/Payment/{WebhookController,CallbackController}.php`) or an admin manual override (`Admin\PaiementController`) is the **only** thing allowed to flip `Paiement::statut` to `confirme` and dispatch `App\Events\PaymentConfirmed`.
3. `App\Listeners\HandlePaymentConfirmed` (the **sole** listener for that event) does everything: activates the record (`confirmCommande`/`activateCvCredits`/`activateAbonnement`/`submitPublicite` based on `Paiement::type`) and sends the notifications.
4. A declined/failed payment or an admin cancelling a `Commande` never touches this path — no event, no email. This is intentional and covered by tests (`tests/Feature/{ServiceCommandeEmailFlowTest,CvCreditsEmailFlowTest,AbonnementEmailFlowTest,PubliciteEmailFlowTest}.php`).

**Do not add `Event::listen(...)` for `PaymentConfirmed` or `CandidatureDeposee` in a service provider.** Both listeners (`HandlePaymentConfirmed`, `NotifierRecruteurCandidature`) live in `app/Listeners/` with a `handle()` method type-hinted to their event, so Laravel's automatic event discovery already registers them. A manual `Event::listen()` on top of that silently double-registers the listener, causing every payment confirmation and every candidature email to fire **twice** (this exact bug existed and was fixed — double credits on CV-credit purchases, duplicate emails everywhere). Run `php artisan event:list` after touching `AppServiceProvider` to confirm each event has exactly one listener.

### Database Notes

- `Schema::defaultStringLength(191)` is set globally in `AppServiceProvider` for MySQL utf8mb4 compatibility.
- The `otp_codes` table holds temporary codes with `expires_at`; rows are deleted after use (not part of the current login flow, which is email+password — kept for a possible future OTP feature).
- After any fresh migration, run `RolesAndPermissionsSeeder` to populate roles and permissions — the app will not work without it.
- **Schema drift risk**: several columns have been dropped by later migrations after earlier code was written against them (e.g. `cvs.titre_poste`/`pays` → replaced by `cvs.metier`; `candidat_profils.specialite` was added then dropped again). Grep for a column name across `app/` and `resources/views/` before trusting it still exists — `Schema::getColumnListing('table')` in tinker is the fastest way to check the real current schema.
- Local dev note (updated 2026-07-03): `.env` now has `APP_ENV=local` (was `production` — client/user has since fixed this) and `APP_URL=http://localhost:8000` (was `http://localhost/` with **no port** — this silently broke every signed/absolute URL Laravel generates, including email verification and password-reset links, which pointed at port 80 where XAMPP's Apache serves its own default dashboard, not this app; the actual app runs on the `php artisan serve` built-in server on port 8000). If `APP_URL` still looks wrong, check both `php artisan serve` and `php artisan queue:work` — **restart both** after any `.env` edit. `config:clear` only clears the cached config *file*; a long-running `serve`/`queue:work` process already booted the old config into memory at process start and won't pick up `.env` changes until it's killed and restarted.
- Local `MAIL_HOST` is Mailtrap (`smtp.mailtrap.io:2525`, sandbox testing inbox) — the **free plan rate-limits to a small number of emails/second** ("550 5.7.0 Too many emails per second"). If you retry several `failed_jobs` at once (`queue:retry all`), expect some to fail again on the retry itself; space out `queue:retry <uuid>` calls by several seconds rather than blasting them all at once.
- `Recruteur\OffreController::syncCompetences()` must look up existing `Competence` rows by `nom` (`firstOrCreate(['nom' => $nom], ['slug' => Str::slug($nom)])`), never by a freshly-recomputed `slug`. Both `nom` and `slug` are independently unique-constrained; several seeded competences have a `slug` that doesn't match what `Str::slug($nom)` produces today (e.g. `"Boucherie / Découpe de viande"` seeded as `boucherie-decoupe-viande`, but `Str::slug()` now yields `boucherie-decoupe-de-viande`). Looking up by the recomputed slug misses the existing row and crashes on the `competences_nom_unique` constraint at insert. This exact crash happened in production use (2026-07-02) and was fixed by switching the lookup key to `nom`.

### Testing Notes

- Full suite is green (`php artisan test`): ~329 tests passing, 1 intentionally skipped. Tests run against SQLite `:memory:`, not the local MySQL — always write migrations that work on both (see the ENUM note above).
- **GD extension is not installed in this local PHP CLI** (`php -m | grep gd` → empty), so `UploadedFile::fake()->image(...)` throws `LogicException: GD extension is not installed.` in tests. Don't touch `php.ini` to fix this — instead build a real minimal image file manually and wrap it in `new UploadedFile($path, $name, $mime, null, true)` (see `tests/Feature/PubliciteEmailFlowTest::fakeImage()` for the pattern — a hardcoded base64 1×1 PNG).
- Google OAuth login is currently **disabled** at the client's request: the routes in `routes/web.php` (`/auth/google*`) are commented out and the "Continuer avec Google" buttons are hidden (`@if(false)`) in `connexion.blade.php`/`inscription.blade.php`. `GoogleController.php` itself is untouched/unwired — re-enabling is a matter of uncommenting the routes and restoring the `@if(config('services.google.client_id'))` guards, but check with the client first since this was an explicit request, not a bug fix.
- `AttestationController` (candidat attestations) is fully implemented but has no routes wired to it — left as-is per client instruction, not a bug.
