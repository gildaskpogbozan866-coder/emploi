# Emploi Bouge Bénin — Guide du développeur

Plateforme d'emploi et de services professionnels pour le Bénin, construite avec **Laravel 12**.  
Elle connecte candidats, recruteurs et annonceurs via un système de rôles et d'abonnements.

---

## Table des matières

1. [Vue d'ensemble](#1-vue-densemble)
2. [Prérequis et installation](#2-prérequis-et-installation)
3. [Architecture du projet](#3-architecture-du-projet)
4. [Base de données — schéma complet](#4-base-de-données--schéma-complet)
5. [Système de rôles et permissions](#5-système-de-rôles-et-permissions)
6. [Routes et structure URL](#6-routes-et-structure-url)
7. [Authentification](#7-authentification)
8. [Plans d'abonnement et paiements](#8-plans-dabonnement-et-paiements)
9. [Vues et frontend](#9-vues-et-frontend)
10. [Commandes utiles](#10-commandes-utiles)
11. [Tests](#11-tests)
12. [Variables d'environnement clés](#12-variables-denvironnement-clés)
13. [Déploiement](#13-déploiement)

---

## 1. Vue d'ensemble

**Emploi Bouge Bénin** est une marketplace RH multi-profils. Elle comprend :

- Un **espace candidat** : dépôt de CV, candidature aux offres, alertes emploi, messagerie, abonnements premium.
- Un **espace recruteur** : publication d'offres, consultation de la CVthèque, gestion des candidatures, vérification d'identité entreprise.
- Un **espace annonceur** : soumission et gestion de publicités display.
- Un **back-office admin** : modération de tous les contenus, configuration de la plateforme, statistiques, blog, services, FAQs, pages légales, paramètres SEO.

### Stack technique

| Couche | Technologie |
|---|---|
| Backend | PHP 8.2 + Laravel 12 |
| Base de données | MySQL 5.7+ (tests : SQLite in-memory) |
| Permissions | spatie/laravel-permission ^6 |
| Paiement | FedaPay (SDK PHP) + KKiaPay (webhook) |
| Frontend | HTML/CSS static + Vite pour JS, Chart.js 4, Summernote |
| CSS | Fichiers statiques dans `public/css/` — pas de build CSS |
| File storage | `storage/app/public` (lien symlink via `php artisan storage:link`) |

---

## 2. Prérequis et installation

### Prérequis

- PHP >= 8.2 avec extensions : `pdo_mysql`, `pdo_sqlite`, `mbstring`, `fileinfo`, `gd`, `zip`, `openssl`
- Composer >= 2
- Node.js >= 18 + npm
- MySQL 5.7+ ou MariaDB 10.4+
- XAMPP (Windows) ou environnement Linux équivalent

### Installation complète

```bash
# 1. Cloner le dépôt
git clone <url-du-repo> emploi
cd emploi

# 2. Copier et configurer l'environnement
cp .env.example .env
# Editer .env : DB_DATABASE, DB_USERNAME, DB_PASSWORD, MAIL_*, APP_URL

# 3. Installer les dépendances et initialiser (migrations + seeders + storage)
composer run setup

# 4. Créer le lien symbolique pour les fichiers publics (si pas fait automatiquement)
php artisan storage:link

# 5. Démarrer le serveur de développement (Laravel + queue + Vite)
composer run dev
```

### Configuration `.env` minimale

```ini
APP_NAME="Emploi Bouge Bénin"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=emploi
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@emploibouge.bj"
MAIL_FROM_NAME="${APP_NAME}"

# Paiements (optionnel en local)
FEDAPAY_SECRET_KEY=
FEDAPAY_PUBLIC_KEY=
FEDAPAY_ENV=sandbox

KKIAPAY_PRIVATE_KEY=
KKIAPAY_API_KEY=
KKIAPAY_SECRET=
```

---

## 3. Architecture du projet

```
emploi/
├── app/
│   ├── Enums/
│   │   ├── Role.php            # Constantes des 4 rôles
│   │   └── Permission.php      # Constantes des 21 permissions + helpers
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Gestion back-office
│   │   │   ├── Auth/           # Authentification (inscription, connexion, MDP)
│   │   │   ├── Candidat/       # Espace candidat
│   │   │   ├── Payment/        # Passerelles de paiement et webhooks
│   │   │   ├── Public/         # Pages publiques (offres, CVs, blog, contact)
│   │   │   └── Recruteur/      # Espace recruteur
│   │   ├── Middleware/
│   │   │   ├── RecruteurApprouve.php   # Bloque accès dashboard si dossier en attente
│   │   │   └── CheckRole.php           # Middleware de rôle simple (non utilisé sur routes principales)
│   │   └── Requests/           # Form requests (validation)
│   ├── Models/                 # ~48 modèles Eloquent
│   ├── Notifications/          # Email de vérification, réinitialisation MDP
│   ├── Policies/               # CVPolicy, OffrePolicy, etc.
│   ├── Providers/
│   │   └── AppServiceProvider.php   # Schema::defaultStringLength(191), View::share globaux
│   └── Services/
│       ├── FedaPayService.php       # Création et vérification transactions FedaPay
│       └── KKiaPayService.php       # Vérification webhooks KKiaPay
├── database/
│   ├── migrations/             # ~90 migrations chronologiques
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── RolesAndPermissionsSeeder.php   # A exécuter après chaque migration fraîche
│   │   ├── PlansSeeder.php                 # Plans candidat, recruteur, annonceur
│   │   ├── AdminSeeder.php                 # Crée le compte admin par défaut
│   │   └── JobPublicationPlansSeeder.php   # Plans de publication d'offres
│   └── factories/
├── public/
│   └── css/                    # CSS statiques (style.css, admin/, candidat/, cv/)
├── resources/
│   ├── js/                     # Vite / Bootstrap JS
│   └── views/
│       ├── layouts/            # app.blade.php, auth.blade.php, admin.blade.php, candidat.blade.php, recruteur.blade.php
│       ├── admin/
│       ├── auth/
│       ├── candidat/
│       ├── errors/             # 401, 403, 404, 419, 429, 500, 503, 405
│       ├── public/
│       └── recruteur/
├── routes/
│   └── web.php                 # Toutes les routes groupées par rôle
└── tests/
    ├── Feature/                # Tests d'intégration
    └── Unit/
```

---

## 4. Base de données — schéma complet

> La règle `Schema::defaultStringLength(191)` est définie dans `AppServiceProvider` pour la compatibilité MySQL utf8mb4.

### Tables principales

#### `users`

| Colonne | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `prenom`, `nom` | varchar | |
| `email` | varchar unique | |
| `password` | varchar | Hash bcrypt |
| `tel` | varchar nullable | |
| `pays` | varchar | |
| `role` | enum | `candidat`, `recruteur`, `admin`, `annonceur` |
| `entreprise` | varchar nullable | Pour recruteurs/annonceurs |
| `metier` | varchar nullable | |
| `premium` | boolean | Accès premium direct (legacy) |
| `avatar` | varchar nullable | Chemin fichier storage |
| `actif` | boolean | `false` = compte suspendu |
| `email_verified_at` | timestamp nullable | |

#### `offres`

| Colonne | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `recruteur_id` | FK -> users | |
| `titre` | varchar | |
| `entreprise` | varchar | |
| `localisation` | varchar | |
| `type` | varchar | CDI, CDD, Stage, Freelance, etc. |
| `secteur` | text nullable | |
| `salaire` | varchar nullable | |
| `description` | text | |
| `exigences` | text nullable | |
| `date_limite` | date nullable | |
| `fichier` | varchar nullable | Chemin fichier joint (PDF) |
| `statut` | enum | `en_attente`, `active`, `expiree`, `suspendue`, `brouillon`, `clos` |
| `premium` | boolean | Mise en avant |
| `vues` | int | |
| `publication_plan_id` | FK -> job_publication_plans nullable | |
| `published_at`, `expires_at` | timestamp nullable | |

#### `cvs`

| Colonne | Type | Description |
|---|---|---|
| `candidat_id` | FK -> users | |
| `titre_poste` | varchar | |
| `pays`, `ville` | varchar | |
| `competences`, `experience`, `formation`, `langues` | text nullable | |
| `fichier_path` | varchar nullable | PDF du CV |
| `photo` | varchar nullable | |
| `plan` | varchar | `gratuit` ou `premium` |
| `visible` | boolean | Visible dans la CVthèque publique |
| `vues` | int | |
| `disponibilite` | varchar nullable | |
| `resume` | text nullable | Bio courte |
| `secteur` | varchar nullable | |

#### `candidatures`

| Colonne | Type | Description |
|---|---|---|
| `offre_id` | FK -> offres | |
| `candidat_id` | FK -> users | |
| `message_motivation` | text nullable | |
| `cv_path` | varchar nullable | |
| `cv_id` | FK -> cvs nullable | |
| `statut` | varchar | `en_attente`, `vu`, `retenu`, `refuse` |
| `note_recruteur` | text nullable | |

#### `plans`

| Colonne | Type | Description |
|---|---|---|
| `name` | varchar | Nom du plan |
| `slug` | varchar unique | |
| `description` | text nullable | |
| `target_type` | enum | `candidat`, `recruteur`, `both`, `annonceur` |
| `price` | decimal | En FCFA |
| `currency` | varchar | `XOF` |
| `duration_days` | int | Durée en jours |
| `is_free` | boolean | Plan gratuit |
| `is_active` | boolean | |

#### `plan_features`

Fonctionnalités associées à un plan (clé/valeur) :

| `feature_key` | `feature_value` | Exemples d'usage |
|---|---|---|
| `cv_limit` | `1` | Nombre de CVs autorisés |
| `apply_limit` | `10` | Candidatures/mois |
| `job_post_limit` | `5` | Offres publiables |
| `annonce_limit` | `3` | Annonces publicitaires (annonceur) |
| `display_days` | `30` | Durée d'affichage d'annonce (annonceur) |
| `priority_display` | `true` | Affichage prioritaire (annonceur) |

#### `abonnements`

| Colonne | Type | Description |
|---|---|---|
| `user_id` | FK -> users | |
| `plan_id` | FK -> plans | |
| `starts_at` | timestamp | |
| `ends_at` | timestamp | |
| `status` | varchar | `active`, `expired`, `cancelled` |
| `auto_renew` | boolean | |

#### `paiements`

| Colonne | Type | Description |
|---|---|---|
| `user_id` | FK -> users | |
| `subscription_id` | FK -> abonnements nullable | |
| `reference` | varchar | Référence interne |
| `montant` | decimal | |
| `devise` | varchar | `XOF` |
| `type` | varchar | `abonnement`, `service`, `cv_credits` |
| `credits_cv` | int | Si achat de crédits CVthèque |
| `payable_id` / `payable_type` | morphs | Polymorphique |
| `methode` | varchar | `fedapay`, `kkiapay` |
| `statut` | varchar | `en_attente`, `paye`, `echoue`, `rembourse` |
| `gateway_transaction_id` | varchar nullable | ID côté passerelle |
| `gateway_status` | varchar nullable | Statut brut passerelle |
| `paid_at` | timestamp nullable | |

#### `recruteur_verifications`

| Colonne | Type | Description |
|---|---|---|
| `user_id` | FK -> users | |
| `statut` | varchar | `en_attente`, `approuve`, `rejete` |
| `note_admin` | text nullable | Motif de rejet |
| `reviewed_by` | FK -> users nullable | Admin qui a statué |
| `reviewed_at` | timestamp nullable | |

#### `parametres_app`

Table clé/valeur pour la configuration admin :

| `cle` | Usage |
|---|---|
| `recruteur_validation_docs` | `'1'` = dossier obligatoire avant dashboard recruteur |
| `recaptcha_site_key` | Clé publique Google reCAPTCHA v2 |
| `recaptcha_secret_key` | Clé secrète Google reCAPTCHA v2 |
| `maintenance_mode` | Mode maintenance |
| `contact_email` | Email de contact |
| `fedapay_public_key`, `fedapay_secret_key` | Clés FedaPay |
| `kkiapay_public_key`, `kkiapay_private_key`, `kkiapay_secret` | Clés KKiaPay |

Accès dans le code :

```php
ParametreApp::get('recruteur_validation_docs', '0'); // lecture avec défaut
ParametreApp::set('recruteur_validation_docs', '1'); // écriture
```

#### Autres tables importantes

| Table | Description |
|---|---|
| `articles` | Blog — articles avec `auteur_id`, `statut` (`brouillon`/`publie`) |
| `services` | Services proposés sur la plateforme |
| `commandes` | Commandes de services |
| `alertes` | Alertes emploi email par mots-clés |
| `conversations` / `messages` | Messagerie interne entre utilisateurs |
| `publicites` | Annonces display (espace annonceur) |
| `signalements` | Signalements de contenu par les utilisateurs |
| `seo_pages` | Meta-données SEO configurables par l'admin |
| `page_legales` | Pages CGU/Mentions légales éditables |
| `faqs` | Questions/Réponses configurables |
| `contact_messages` | Messages du formulaire de contact |
| `type_documents` / `documents` | Documents uploadés par les candidats |
| `recruteur_document_types` / `recruteur_documents` | Documents du dossier recruteur |
| `job_publication_plans` | Plans de publication d'offres (avec limite) |
| `competences` / `metiers` | Référentiels RH |
| `type_contrats` / `secteurs_activite` / `langues` | Référentiels emploi |

---

## 5. Système de rôles et permissions

### Les 4 rôles

Définis dans `app/Enums/Role.php` :

| Constante | Valeur | Description |
|---|---|---|
| `Role::ADMIN` | `'admin'` | Accès total au back-office |
| `Role::RECRUTEUR` | `'recruteur'` | Publie des offres, consulte les CVs |
| `Role::CANDIDAT` | `'candidat'` | Dépose des CVs, postule aux offres |
| `Role::ANNONCEUR` | `'annonceur'` | Soumet des publicités display |

### Les 21 permissions

Définies dans `app/Enums/Permission.php` et regroupées par méthode helper :

**Admin (13)** : `manage-users`, `manage-offres`, `manage-cvs`, `manage-blog`, `manage-services`, `manage-commandes`, `manage-paiements`, `manage-abonnements`, `manage-messagerie`, `manage-signalements`, `manage-parametres`, `manage-referentiels`, `view-statistiques`

**Recruteur (5)** : `publish-offre`, `view-candidatures`, `view-cvtheque`, `contact-candidats`, `manage-abonnement-recruteur`

**Annonceur (2)** : `submit-publicite`, `manage-publicites`

**Candidat (5)** : `deposit-cv`, `apply-offre`, `save-offre`, `create-alerte`, `manage-abonnement-candidat`

### Double couche de protection

Les routes utilisent **deux middlewares en cascade** :

```php
// 1. Spatie role — bloque l'accès au groupe entier si mauvais rôle
Route::middleware(['auth', 'verified', 'spatie.role:candidat'])->prefix('candidat')->group(function () {

    // 2. Permission — contrôle granulaire à l'intérieur du groupe
    Route::middleware('permission:deposit-cv')->group(function () {
        Route::get('/mes-cvs', [CVController::class, 'index'])->name('candidat.cvs');
    });
});
```

### Utilisation dans les contrôleurs et vues

```php
// Via Policy (ownership checks)
$this->authorize('update', $cv);

// Via permission directe
abort_if(!auth()->user()->can('publish-offre'), 403);
```

```blade
{{-- Composant Blade x-can --}}
<x-can permission="manage-blog">
    <a href="{{ route('admin.blog.create') }}">Nouvel article</a>
</x-can>

<x-can role="admin">
    <span>Visible seulement par admin</span>
</x-can>
```

### Après modification des rôles/permissions

```bash
# Re-seeder
php artisan db:seed --class=RolesAndPermissionsSeeder

# Vider le cache Spatie
php artisan permission:cache-reset
```

---

## 6. Routes et structure URL

Toutes les routes sont dans `routes/web.php`. Convention de nommage : `[role].[ressource].[action]`.

### Pages publiques (sans authentification)

| URL | Route nommée | Description |
|---|---|---|
| `/` | `home` | Page d'accueil |
| `/offres` | `offre.list` | Liste des offres |
| `/offres/{offre}` | `offre.detail` | Détail d'une offre |
| `/offres/publier` | `offre.publier` | Formulaire de publication rapide |
| `/cvs` | `cv.public.list` | CVthèque publique |
| `/cvs/deposer` | `cv.public.depot` | Dépôt de CV |
| `/blog` | `blog.list` | Liste des articles |
| `/blog/{slug}` | `blog.detail` | Détail article |
| `/services` | `service.list` | Services de la plateforme |
| `/contact` | `contact` | Formulaire de contact |
| `/faq` | `faq` | FAQ |
| `/legale/{slug}` | `legale.show` | Pages légales (CGU, mentions, etc.) |

### Espace candidat (`/candidat/*`)

Middleware : `['auth', 'verified', 'spatie.role:candidat']`

| URL | Route nommée | Permission |
|---|---|---|
| `/candidat/tableau-de-bord` | `candidat.dashboard` | — |
| `/candidat/mes-cvs` | `candidat.cvs` | `deposit-cv` |
| `/candidat/mes-candidatures` | `candidat.candidatures` | `apply-offre` |
| `/candidat/offres-sauvegardees` | `candidat.offres-sauvegardees` | `save-offre` |
| `/candidat/alertes` | `candidat.alertes` | `create-alerte` |
| `/candidat/abonnement` | `candidat.abonnement` | `manage-abonnement-candidat` |
| `/candidat/messages` | `candidat.messages` | — |
| `/candidat/profil` | `candidat.profil` | — |

### Espace recruteur (`/recruteur/*`)

Middleware : `['auth', 'verified', 'spatie.role:recruteur']`

| URL | Route nommée | Permission | Middleware additionnel |
|---|---|---|---|
| `/recruteur/verification` | `recruteur.verification` | — | (accessible sans approbation) |
| `/recruteur/tableau-de-bord` | `recruteur.dashboard` | — | `recruteur.approuve` |
| `/recruteur/offres` | `recruteur.offres` | `publish-offre` | `recruteur.approuve` |
| `/recruteur/candidatures` | `recruteur.candidatures` | `view-candidatures` | `recruteur.approuve` |
| `/recruteur/cvtheque` | `recruteur.cvtheque` | `view-cvtheque` | `recruteur.approuve` |
| `/recruteur/abonnement` | `recruteur.abonnement` | `manage-abonnement-recruteur` | — |

> **Middleware `recruteur.approuve`** : n'est actif que si `ParametreApp::get('recruteur_validation_docs')` vaut `'1'`. Sinon, les recruteurs accèdent directement au dashboard après vérification email.

### Espace admin (`/admin/*`)

Middleware : `['auth', 'spatie.role:admin']`

| Section | Préfixe | Permission requise |
|---|---|---|
| Dashboard | `/admin/tableau-de-bord` | — |
| Utilisateurs | `/admin/utilisateurs` | `manage-users` |
| Vérifications recruteur | `/admin/verifications` | `manage-users` |
| Offres | `/admin/offres` | `manage-offres` |
| CVs / Documents | `/admin/cvs`, `/admin/documents` | `manage-cvs` |
| Blog | `/admin/blog` | `manage-blog` |
| Services / Commandes | `/admin/services`, `/admin/commandes` | `manage-services`, `manage-commandes` |
| Paiements | `/admin/paiements` | `manage-paiements` |
| Abonnements / Plans | `/admin/abonnements`, `/admin/plans` | `manage-abonnements` |
| Publicités | `/admin/publicites` | `manage-publicites` |
| Signalements | `/admin/signalements` | `manage-signalements` |
| Statistiques | `/admin/statistiques` | `view-statistiques` |
| Paramètres / SEO / Légal | `/admin/parametres`, `/admin/seo`, `/admin/legale` | `manage-parametres` |
| Référentiels RH | `/admin/referentiels` | `manage-referentiels` |

### Paiements (`/payment/*`)

| Route | Description |
|---|---|
| `POST /payment/webhook/fedapay` | Webhook FedaPay (sans auth) |
| `POST /payment/webhook/kkiapay` | Webhook KKiaPay (sans auth) |
| `GET /payment/gateway` | Choix de la passerelle |
| `POST /payment/initier` | Démarrer un paiement |
| `GET /payment/callback` | Retour après paiement |

---

## 7. Authentification

### Flow inscription

1. L'utilisateur soumet `/auth/inscription` (email + password + rôle parmi `candidat`, `recruteur`, `annonceur`)
2. Le compte est créé avec `email_verified_at = null`
3. Une notification `VerificationEmailFr` est envoyée par email
4. L'utilisateur est redirigé vers la page d'attente de vérification

### Comportement après vérification email

| Rôle | Paramètre `recruteur_validation_docs` | Redirection |
|---|---|---|
| `candidat` | N/A | `candidat.dashboard` |
| `recruteur` | `'0'` (désactivé) | `recruteur.dashboard` |
| `recruteur` | `'1'` (activé) | `recruteur.verification` |
| `annonceur` | N/A | `annonceur.dashboard` |
| `admin` | N/A | `admin.dashboard` |

### Flow vérification recruteur (quand activée)

```
Recruteur soumet son dossier (documents entreprise)
    → statut: en_attente
    → Admin examine dans /admin/verifications
    → Admin approuve → statut: approuve → accès dashboard débloqué
    → Admin rejette → statut: rejete → recruteur voit motif, peut repostuler
```

Le middleware `RecruteurApprouve` (`app/Http/Middleware/RecruteurApprouve.php`) gère les redirections selon le statut.

### Réinitialisation de mot de passe

`/auth/mot-de-passe-oublie` → email avec lien signé (60 min) → `/auth/reinitialiser/{token}` → `/auth/connexion`

### Changement de mot de passe (connecté)

`/auth/changer-mot-de-passe` — requiert l'ancien mot de passe, disponible pour tous les rôles.

---

## 8. Plans d'abonnement et paiements

### Plans prédéfinis (seeder)

**Candidats** :
- Gratuit (0 FCFA — 1 CV, 10 candidatures/mois)
- Premium Mensuel (2 000 FCFA/mois — CVs illimités, candidatures illimitées)
- Premium Annuel (20 000 FCFA/an)

**Recruteurs** :
- Gratuit (0 FCFA — 2 offres actives)
- Starter, Pro, Enterprise (limites croissantes)

**Annonceurs** :
- Gratuit (0 FCFA — 1 annonce, 7 jours)
- Starter (5 000 FCFA — 3 annonces, 30 jours)
- Pro (15 000 FCFA — 10 annonces, 30 jours, affichage prioritaire)

### Passerelles de paiement

| Passerelle | Classe service | Webhook |
|---|---|---|
| FedaPay | `App\Services\FedaPayService` | `POST /payment/webhook/fedapay` |
| KKiaPay | `App\Services\KKiaPayService` | `POST /payment/webhook/kkiapay` |

Les clés API sont configurables depuis **Admin > Paramètres > Passerelles de paiement**.

### Flow de paiement

```
Utilisateur choisit un plan
    → GET /payment/gateway (choix passerelle)
    → POST /payment/initier (crée Paiement + Abonnement "en_attente")
    → Redirection vers interface FedaPay / KKiaPay
    → Callback / Webhook reçu
    → Listener HandlePaymentConfirmed activé
    → Abonnement passe en "active", permissions accordées
```

---

## 9. Vues et frontend

### Layouts

| Fichier | Utilisé par |
|---|---|
| `layouts/app.blade.php` | Pages publiques (bannière cookie, reCAPTCHA conditionnel) |
| `layouts/auth.blade.php` | Pages de connexion/inscription |
| `layouts/candidat.blade.php` | Espace candidat |
| `layouts/recruteur.blade.php` | Espace recruteur |
| `layouts/admin.blade.php` | Back-office admin |
| `layouts/dashboard.blade.php` | Dashboard générique partagé |

### CSS

Les fichiers CSS sont **statiques** dans `public/css/`. Pas de compilation, pas de Tailwind, pas de PostCSS.

```
public/css/
├── style.css              # Styles publics principaux
├── admin/admin.css        # Back-office
├── candidat/candidat.css
├── cv/cvtheque.css
├── dashboard-layout.css
├── contact.css
└── faq.css
```

### JavaScript

- `resources/js/app.js` + `bootstrap.js` — compilés par **Vite** (`npm run dev` / `npm run build`)
- **Chart.js 4** via CDN — graphiques du dashboard admin (8 charts)
- **Summernote 0.9.0 lite** via CDN — éditeur WYSIWYG pour le blog (fr-FR)
- Pas de framework JS (pas de Vue, pas de React)

### Composant Blade `<x-can>`

```blade
<x-can permission="manage-blog">
    <!-- Visible uniquement si l'utilisateur a la permission manage-blog -->
</x-can>

<x-can role="admin">
    <!-- Visible uniquement pour les admins -->
</x-can>
```

### Pages d'erreur personnalisées

Toutes dans `resources/views/errors/` :

| Code | Situation |
|---|---|
| `401.blade.php` | Non authentifié |
| `403.blade.php` | Accès interdit |
| `404.blade.php` | Page introuvable |
| `419.blade.php` | Session expirée (propose reconnexion si connecté) |
| `429.blade.php` | Trop de tentatives (rate limiting) |
| `500.blade.php` | Erreur serveur |
| `503.blade.php` | Site en maintenance |
| `405.blade.php` | Méthode HTTP non autorisée |

### Variables globales partagées (View::share)

Disponibles dans **toutes** les vues (définies dans `AppServiceProvider::boot()`) :

| Variable | Type | Description |
|---|---|---|
| `$recaptchaSiteKey` | string | Clé publique reCAPTCHA (vide si non configuré) |
| `$recaptchaActif` | bool | `true` uniquement en production avec clé configurée |

### Google reCAPTCHA v2

Actif uniquement en production. La condition : `!app()->isLocal() && $siteKey !== ''`.  
Configuré dans Admin > Paramètres > Google reCAPTCHA v2.  
Appliqué sur : formulaire de contact et page d'inscription.

### Widget publicités

Le widget publicitaire (présent sur les pages publiques) peut être replié/déplié via un clic sur la barre de titre. L'état est persisté en `sessionStorage` (réinitialisé à la fermeture du navigateur).

### Bannière de consentement cookies

Présente sur les pages publiques (`layouts/app.blade.php`). L'état (accepté/refusé) est stocké en `localStorage`. Pas de tracking côté serveur.

---

## 10. Commandes utiles

```bash
# Démarrer l'environnement de développement complet
composer run dev

# Setup initial complet (migrations + seeders + storage)
composer run setup

# Migrations seules
php artisan migrate

# Seeder des rôles et permissions (requis après migrate:fresh)
php artisan db:seed --class=RolesAndPermissionsSeeder

# Seeder des plans d'abonnement
php artisan db:seed --class=PlansSeeder

# Créer le compte admin par défaut
php artisan db:seed --class=AdminSeeder

# Vider le cache des permissions Spatie
php artisan permission:cache-reset

# Vider tous les caches
php artisan optimize:clear

# Créer le lien de stockage public
php artisan storage:link

# Formatage du code (Laravel Pint)
./vendor/bin/pint

# Tests
php artisan test
php artisan test --filter=AuthTest
php artisan test tests/Feature/Candidat/
```

---

## 11. Tests

Les tests utilisent **SQLite in-memory** (configuré dans `phpunit.xml`). Chaque test utilise `RefreshDatabase` pour repartir d'une base vierge.

### Structure

```
tests/
├── Feature/
│   ├── Auth/AuthTest.php                         # Inscription, connexion, vérification email, MDP
│   ├── Candidat/
│   │   ├── CVControllerTest.php                  # CRUD CVs, limites par plan
│   │   ├── CandidatureControllerTest.php         # Candidature aux offres
│   │   ├── ProfilControllerTest.php              # Profil candidat
│   │   ├── ExperienceControllerTest.php
│   │   ├── FormationControllerTest.php
│   │   ├── CompetenceControllerTest.php
│   │   ├── LangueControllerTest.php
│   │   ├── DocumentControllerTest.php
│   │   └── NotificationControllerTest.php
│   ├── Recruteur/
│   │   ├── OffreControllerTest.php               # CRUD offres, filtres, clôture, duplication
│   │   └── CandidatureControllerTest.php         # Gestion candidatures côté recruteur
│   ├── Public/
│   │   ├── OffrePubliqueTest.php                 # Liste/détail/candidature publique
│   │   └── CandidatureNotificationTest.php
│   ├── Admin/ReferentielControllerTest.php
│   └── AlerteServiceTest.php
└── Unit/ExampleTest.php
```

### Pattern de base d'un test

```php
class MonTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class); // toujours requis
        Cache::flush();
    }

    public function test_exemple(): void
    {
        $user = User::factory()->candidat()->create();
        $user->assignRole('candidat');

        $this->actingAs($user)
            ->get(route('candidat.dashboard'))
            ->assertOk();
    }
}
```

### Migrations MySQL-only — garde SQLite

Certaines migrations utilisent du SQL MySQL-spécifique (`ALTER TABLE ... MODIFY COLUMN`) incompatible avec SQLite.  
Ces migrations **doivent** avoir la garde suivante :

```php
public function up(): void
{
    if (DB::getDriverName() !== 'sqlite') {
        DB::statement("ALTER TABLE ... MODIFY COLUMN ...");
    }
}
```

### Tester le middleware recruteur

Le middleware `RecruteurApprouve` est conditionnel. Activer/désactiver explicitement dans les tests :

```php
// Pour tester le blocage par dossier
\App\Models\ParametreApp::set('recruteur_validation_docs', '1');

// Pour tester l'accès libre (comportement par défaut en tests)
\App\Models\ParametreApp::set('recruteur_validation_docs', '0');
```

---

## 12. Variables d'environnement clés

| Variable | Description | Requis |
|---|---|---|
| `APP_KEY` | Clé de chiffrement Laravel | Toujours |
| `APP_ENV` | `local` / `production` | Toujours |
| `APP_URL` | URL complète du site | Toujours |
| `DB_CONNECTION` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | Connexion MySQL | Toujours |
| `MAIL_*` | Configuration SMTP | En production |
| `FEDAPAY_SECRET_KEY` / `FEDAPAY_PUBLIC_KEY` / `FEDAPAY_ENV` | FedaPay | Pour paiements |
| `KKIAPAY_PRIVATE_KEY` / `KKIAPAY_API_KEY` / `KKIAPAY_SECRET` | KKiaPay | Pour paiements |
| `QUEUE_CONNECTION` | `database` en prod, `sync` en local | Recommandé |
| `SESSION_DRIVER` | `file` ou `database` | Recommandé |

> Les clés reCAPTCHA et les clés de passerelles peuvent aussi être renseignées depuis le **panneau admin** (Admin > Paramètres), où elles sont stockées dans `parametres_app`. Les valeurs `.env` servent de fallback pour FedaPay/KKiaPay.

---

## 13. Déploiement

### Checklist de mise en production

```bash
# 1. Passer en mode maintenance
php artisan down

# 2. Récupérer le code
git pull origin main

# 3. Installer les dépendances (sans dépendances de développement)
composer install --no-dev --optimize-autoloader

# 4. Build des assets JS
npm ci && npm run build

# 5. Exécuter les migrations
php artisan migrate --force

# 6. Vider les caches
php artisan optimize:clear
php artisan permission:cache-reset

# 7. Reconstruire les caches de config/routes/vues
php artisan optimize

# 8. Relancer la queue si worker actif
php artisan queue:restart

# 9. Remettre en ligne
php artisan up
```

### Worker de queue (Supervisor en production)

```ini
[program:emploi-queue]
command=php /var/www/emploi/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/emploi-queue.log
```

### Variables d'environnement en production

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://emploibouge.bj

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_DRIVER=file  # ou redis si disponible
```

---

## Points d'attention pour les nouveaux développeurs

1. **Toujours utiliser les constantes d'enum** (`Permission::MANAGE_BLOG`, `Role::ADMIN`) — jamais les chaînes brutes dans le code PHP.

2. **CSS statique** — modifier directement `public/css/*.css`, aucune compilation n'est nécessaire.

3. **Après un `migrate:fresh`**, toujours relancer `RolesAndPermissionsSeeder` sinon l'app ne fonctionne pas (les routes avec `spatie.role` et `permission` bloquent tout).

4. **reCAPTCHA inactif en local** — comportement voulu, contrôlé par `!app()->isLocal()`. Ne pas essayer de le forcer en développement.

5. **Migrations SQLite** — les migrations qui modifient des colonnes ENUM doivent avoir la garde `if (DB::getDriverName() !== 'sqlite')` sinon les tests échouent.

6. **Validation docs recruteur** — le module est entièrement optionnel. L'admin active/désactive depuis Admin > Paramètres > "Demande de documents recruteurs". Le comportement change à la fois sur le middleware ET sur la redirection post-vérification email.

7. **Permissions Spatie** — utiliser `permission:cache-reset` après toute modification du seeder de rôles, sinon les changements ne sont pas reflétés immédiatement.
