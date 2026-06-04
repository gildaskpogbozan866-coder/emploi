# Emploi Bouge Bénin — Laravel

## Démarrage rapide

### 1. Créer la base de données MySQL
Ouvrir phpMyAdmin (XAMPP) et créer :
```sql
CREATE DATABASE emploi_bouge_benin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Lancer les migrations + seeders
```bash
php artisan migrate --seed
```

### 3. Lancer le serveur
```bash
php artisan serve
```
Ouvrir : http://localhost:8000

---

## Comptes de test (après le seeder)

| Rôle      | Email                          |
|-----------|-------------------------------|
| Admin     | admin@emploibougebenin.com    |
| Recruteur | recruteur@techbenin.com       |
| Candidat  | jb.kpossou@gmail.com          |
| Talent    | moussa.diarra@gmail.com       |

> Connexion par code OTP. En mode dev, le code s'affiche directement à l'écran.

---

## Structure du projet

```
app/
  Http/
    Controllers/
      Auth/           → AuthController (OTP, inscription, connexion)
      Public/         → HomeController, OffreController, BlogController...
      Candidat/       → Dashboard, CV, Candidature, Alertes...
      Recruteur/      → Dashboard, Offres, Candidatures, Cvtheque...
      Talent/         → Dashboard, Profil, Messagerie...
      Admin/          → Dashboard, Utilisateurs, Blog, Services...
    Middleware/
      CheckRole.php   → Vérifie le rôle (candidat/recruteur/talent/admin)
  Models/
    User, Offre, Candidature, CV, TalentProfil, Service,
    Commande, Article, Paiement, Abonnement, Message,
    Conversation, Notification, Alerte, Signalement
  Policies/
    OffrePolicy, CVPolicy

database/
  migrations/         → Toutes les tables
  seeders/            → Données de démo

resources/views/
  layouts/            → app.blade.php, dashboard.blade.php, admin.blade.php
  components/         → nav, footer, flash
  auth/               → connexion, inscription, verification-email, compte-confirme
  public/             → index, offre/, blog/, service/, cv/, contact, faq
  candidat/           → dashboard, cvs, candidatures...
  recruteur/          → dashboard, offres, candidatures...
  talent/             → dashboard, profil...
  admin/              → dashboard, blog/, utilisateurs/...
  errors/             → 404, 403

routes/web.php        → Toutes les routes organisées par rôle

public/
  css/                → Tous les styles (copiés du projet original)
  js/                 → Tous les scripts
  images/             → Toutes les images
```

## Système de rôles & permissions (Spatie)

Package : `spatie/laravel-permission`

### Rôles
| Rôle | Accès |
|---|---|
| `admin` | Tout — toutes les permissions |
| `recruteur` | publish-offre, view-candidatures, view-cvtheque, contact-candidats |
| `candidat` | deposit-cv, apply-offre, save-offre, create-alerte |
| `talent` | create-profil-talent, view-messages-talent |

### Permissions granulaires (extrait)
```
ADMIN : manage-users, manage-offres, manage-cvs, manage-blog,
        manage-services, manage-paiements, manage-abonnements,
        manage-signalements, manage-messagerie, manage-parametres, view-statistiques

RECRUTEUR : publish-offre, view-candidatures, view-cvtheque, contact-candidats

CANDIDAT : deposit-cv, apply-offre, save-offre, create-alerte

TALENT : create-profil-talent, view-messages-talent
```

### Gestion dans l'admin
URL : `/admin/permissions`
- Matrice rôles × permissions (cocher/décocher)
- Permissions individuelles par utilisateur
- Changement de rôle d'un utilisateur

### Vérification dans les vues Blade
```blade
@can('manage-blog')  ... @endcan         ← Spatie permission
@role('admin')       ... @endrole        ← Spatie role
<x-can permission="publish-offre">       ← Composant custom
```

### Vérification dans les contrôleurs
```php
$this->authorize('manage-blog');            // Gate
auth()->user()->can('publish-offre');       // Vérification
auth()->user()->hasRole('admin');           // Rôle
auth()->user()->hasPermissionTo('...');     // Permission
```

## Commandes utiles

```bash
# Vider les caches après modifications
php artisan config:clear && php artisan route:clear && php artisan view:clear

# Voir toutes les routes
php artisan route:list

# Relancer les migrations (⚠ supprime les données)
php artisan migrate:fresh --seed

# Créer un lien storage
php artisan storage:link
```
