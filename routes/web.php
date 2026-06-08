<?php

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\OffreController;
use App\Http\Controllers\Public\TalentController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Candidat\DashboardController as CandidatDashboard;
use App\Http\Controllers\Candidat\CandidatureController;
use App\Http\Controllers\Candidat\CVController;
use App\Http\Controllers\Candidat\ProfilController as CandidatProfil;
use App\Http\Controllers\Candidat\AlerteController;
use App\Http\Controllers\Candidat\MessageController as CandidatMessage;
use App\Http\Controllers\Candidat\AbonnementController as CandidatAbonnement;
use App\Http\Controllers\Candidat\NotificationController;
use App\Http\Controllers\Recruteur\DashboardController as RecruteurDashboard;
use App\Http\Controllers\Recruteur\OffreController as RecruteurOffre;
use App\Http\Controllers\Recruteur\CandidatureController as RecruteurCandidature;
use App\Http\Controllers\Recruteur\CvthequeController;
use App\Http\Controllers\Recruteur\MessageController as RecruteurMessage;
use App\Http\Controllers\Recruteur\AbonnementController as RecruteurAbonnement;
use App\Http\Controllers\Recruteur\StatistiqueController as RecruteurStat;
use App\Http\Controllers\Recruteur\ProfilController as RecruteurProfil;
use App\Http\Controllers\Talent\DashboardController as TalentDashboard;
use App\Http\Controllers\Talent\ProfilController as TalentProfilCtrl;
use App\Http\Controllers\Talent\MessageController as TalentMessage;
use App\Http\Controllers\Talent\AbonnementController as TalentAbonnement;
use App\Http\Controllers\Talent\ParametreController as TalentParametre;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\OffreController as AdminOffre;
use App\Http\Controllers\Admin\CVController as AdminCV;
use App\Http\Controllers\Admin\BlogController as AdminBlog;
use App\Http\Controllers\Admin\ServiceController as AdminService;
use App\Http\Controllers\Admin\PaiementController as AdminPaiement;
use App\Http\Controllers\Admin\AbonnementController as AdminAbonnement;
use App\Http\Controllers\Admin\MessageController as AdminMessage;
use App\Http\Controllers\Admin\SignalementController;
use App\Http\Controllers\Admin\StatistiqueController as AdminStat;
use App\Http\Controllers\Admin\ParametreController as AdminParametre;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\VerificationRecruteurController;
use App\Http\Controllers\Recruteur\VerificationController as RecruteurVerifCtrl;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// ════════════════════════════════════════════════════════
//  PAGES PUBLIQUES
// ════════════════════════════════════════════════════════
Route::get('/',          [HomeController::class, 'index'])->name('home');
Route::get('/a-propos',  [HomeController::class, 'aPropos'])->name('a-propos');
Route::get('/contact',   [HomeController::class, 'contact'])->name('contact');
Route::get('/faq',       [HomeController::class, 'faq'])->name('faq');
Route::post('/contact',  [ContactController::class, 'envoyer'])->name('contact.envoyer');

// Offres publiques
Route::prefix('offres')->name('offre.')->group(function () {
    Route::get('/',               [OffreController::class, 'index'])->name('list');
    Route::get('/{offre}',        [OffreController::class, 'detail'])->name('detail');
    Route::get('/{offre}/postuler',[OffreController::class, 'postuler'])->name('postuler');
    Route::post('/{offre}/postuler',[OffreController::class, 'storerCandidature'])->name('postuler.store')->middleware('auth');
    Route::get('/{offre}/succes', [OffreController::class, 'candidatureSucces'])->name('candidature-succes');
    Route::get('/publier/formulaire',[OffreController::class, 'publier'])->name('publier');
    Route::post('/publier',       [OffreController::class, 'storerOffre'])->name('publier.store')->middleware('auth');
    Route::get('/{offre}/publiee',[OffreController::class, 'offrePublieeSucces'])->name('publiee-succes');
});

// Talents publics
Route::prefix('profiltheque')->name('talent.public.')->group(function () {
    Route::get('/',               [TalentController::class, 'index'])->name('list');
    Route::get('/tarif',          [TalentController::class, 'tarif'])->name('tarif');
    Route::get('/achat/{profil}', [TalentController::class, 'achat'])->name('achat');
    Route::get('/{profil}',       [TalentController::class, 'detail'])->name('detail');
});

// CVthèque et dépôt CV publics
Route::prefix('cvs')->name('cv.public.')->group(function () {
    Route::get('/',          [CVController::class, 'theque'])->name('theque');
    Route::get('/tarif',     [CVController::class, 'tarif'])->name('tarif');
    Route::get('/deposer',   [CVController::class, 'depot'])->name('depot');
    Route::post('/deposer',  [CVController::class, 'store'])->name('depot.store')->middleware('auth');
});

// Services
Route::prefix('services')->name('service.')->group(function () {
    Route::get('/',                   [ServiceController::class, 'index'])->name('list');
    Route::get('/{service}',          [ServiceController::class, 'detail'])->name('detail');
    Route::get('/{service}/commander',[ServiceController::class, 'commander'])->name('commande');
    Route::post('/{service}/commander',[ServiceController::class,'storerCommande'])->name('commande.store');
    Route::get('/commande/succes',    [ServiceController::class, 'succes'])->name('succes');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/',          [BlogController::class, 'index'])->name('list');
    Route::get('/{article}', [BlogController::class, 'detail'])->name('detail');
});

// ════════════════════════════════════════════════════════
//  AUTHENTIFICATION (email + mot de passe)
// ════════════════════════════════════════════════════════
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/connexion',           [AuthController::class, 'showConnexion'])->name('connexion');
    Route::get('/inscription',         [AuthController::class, 'showInscription'])->name('inscription');
    Route::get('/compte-confirme',     [AuthController::class, 'showCompteConfirme'])->name('compte-confirme');
    Route::get('/mot-de-passe-oublie', [AuthController::class, 'showMotDePasseOublie'])->name('mot-de-passe-oublie');
    Route::get('/reinitialiser/{token}', [AuthController::class, 'showReinitialisation'])->name('reinitialiser');

    Route::post('/connexion',           [AuthController::class, 'connecter'])->name('connexion.store')->middleware('throttle:10,1');
    Route::post('/inscription',         [AuthController::class, 'inscrire'])->name('inscription.store');
    Route::post('/mot-de-passe-oublie', [AuthController::class, 'envoyerLienReinitialisation'])->name('mot-de-passe-oublie.store');
    Route::post('/reinitialiser',       [AuthController::class, 'reinitialiserMotDePasse'])->name('reinitialiser.store');

    Route::get('/changer-mot-de-passe',  [AuthController::class, 'showChangerMotDePasse'])->name('changer-mot-de-passe')->middleware('auth');
    Route::post('/changer-mot-de-passe', [AuthController::class, 'changerMotDePasse'])->name('changer-mot-de-passe.store')->middleware('auth');

    Route::post('/deconnecter', [AuthController::class, 'deconnecter'])->name('deconnecter')->middleware('auth');
});

// ════════════════════════════════════════════════════════
//  VÉRIFICATION EMAIL (Laravel natif)
// ════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    Route::get('/email/verifier', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verifier/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        $user = $request->user();
        if ($user->role === 'recruteur') {
            return redirect()->route('recruteur.verification');
        }
        return redirect()->route('auth.compte-confirme');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/renvoyer', function (Illuminate\Http\Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    })->middleware('throttle:6,1')->name('verification.send');
});

// ════════════════════════════════════════════════════════
//  ESPACE CANDIDAT — protégé par rôle + permissions
// ════════════════════════════════════════════════════════
Route::prefix('candidat')->name('candidat.')->middleware(['auth', 'verified', 'spatie.role:'.Role::CANDIDAT])->group(function () {
    Route::get('/tableau-de-bord', [CandidatDashboard::class, 'index'])->name('dashboard');

    // CVs — nécessite permission deposit-cv
    Route::middleware('permission:'.Permission::DEPOSIT_CV)->group(function () {
        Route::get('/mes-cvs',               [CVController::class, 'index'])->name('cvs');
        Route::get('/mes-cvs/modifier/{cv}', [CVController::class, 'edit'])->name('cvs.edit');
        Route::put('/mes-cvs/{cv}',          [CVController::class, 'update'])->name('cvs.update');
        Route::delete('/mes-cvs/{cv}',       [CVController::class, 'destroy'])->name('cvs.destroy');
    });

    // Candidatures — nécessite apply-offre
    Route::middleware('permission:'.Permission::APPLY_OFFRE)->group(function () {
        Route::get('/mes-candidatures',               [CandidatureController::class, 'index'])->name('candidatures');
        Route::get('/mes-candidatures/{candidature}', [CandidatureController::class, 'detail'])->name('candidatures.detail');
    });

    // Offres sauvegardées — nécessite save-offre
    Route::middleware('permission:'.Permission::SAVE_OFFRE)->group(function () {
        Route::get('/offres-sauvegardees',           [CandidatureController::class, 'offresSauvegardees'])->name('offres-sauvegardees');
        Route::post('/offres-sauvegardees/{offre}',  [CandidatureController::class, 'sauvegarder'])->name('offres-sauvegardees.toggle');
    });

    // Alertes — nécessite create-alerte
    Route::middleware('permission:'.Permission::CREATE_ALERTE)->group(function () {
        Route::get('/mes-alertes',              [AlerteController::class, 'index'])->name('alertes');
        Route::post('/mes-alertes',             [AlerteController::class, 'store'])->name('alertes.store');
        Route::delete('/mes-alertes/{alerte}',  [AlerteController::class, 'destroy'])->name('alertes.destroy');
    });

    // Abonnement — nécessite manage-abonnement-candidat
    Route::middleware('permission:'.Permission::MANAGE_ABONNEMENT_CAN)->group(function () {
        Route::get('/abonnement',  [CandidatAbonnement::class, 'index'])->name('abonnement');
        Route::post('/abonnement', [CandidatAbonnement::class, 'souscrire'])->name('abonnement.store');
        Route::get('/historique-paiements', [CandidatAbonnement::class, 'historique'])->name('paiements');
    });

    // Messagerie, Notifications, Profil (accessibles à tous les candidats authentifiés)
    Route::get('/messagerie',              [CandidatMessage::class, 'index'])->name('messagerie');
    Route::get('/messagerie/{conversation}',[CandidatMessage::class, 'show'])->name('messagerie.show');
    Route::post('/messagerie/{conversation}',[CandidatMessage::class, 'store'])->name('messagerie.store');
    Route::get('/notifications',           [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/marquer',  [NotificationController::class, 'marquerLues'])->name('notifications.lues');
    Route::get('/profil',                  [CandidatProfil::class, 'edit'])->name('profil');
    Route::put('/profil',                  [CandidatProfil::class, 'update'])->name('profil.update');
    Route::get('/parametres',              [CandidatProfil::class, 'parametres'])->name('parametres');
    Route::put('/parametres',              [CandidatProfil::class, 'updateParametres'])->name('parametres.update');
});

// ════════════════════════════════════════════════════════
//  ESPACE RECRUTEUR — protégé par rôle + permissions
// ════════════════════════════════════════════════════════
Route::prefix('recruteur')->name('recruteur.')->middleware(['auth', 'verified', 'spatie.role:'.Role::RECRUTEUR])->group(function () {

    // Vérification du compte entreprise (accessible avant approbation admin)
    Route::get('/verification',             [RecruteurVerifCtrl::class, 'soumettre'])->name('verification');
    Route::post('/verification',            [RecruteurVerifCtrl::class, 'store'])->name('verification.store');
    Route::get('/verification/en-attente',  [RecruteurVerifCtrl::class, 'enAttente'])->name('verification.en-attente');
    Route::get('/verification/rejete',      [RecruteurVerifCtrl::class, 'rejete'])->name('verification.rejete');

    // ── Tout ce qui suit nécessite l'approbation du dossier par l'admin ──
    Route::middleware('recruteur.approuve')->group(function () {

    Route::get('/tableau-de-bord', [RecruteurDashboard::class, 'index'])->name('dashboard');

    // Publication d'offres — nécessite publish-offre
    Route::middleware('permission:'.Permission::PUBLISH_OFFRE)->group(function () {
        Route::get('/mes-offres',                     [RecruteurOffre::class, 'index'])->name('offres');
        Route::get('/mes-offres/creer',               [RecruteurOffre::class, 'create'])->name('offres.create');
        Route::post('/mes-offres',                    [RecruteurOffre::class, 'store'])->name('offres.store');
        Route::get('/mes-offres/{offre}/modifier',    [RecruteurOffre::class, 'edit'])->name('offres.edit');
        Route::put('/mes-offres/{offre}',             [RecruteurOffre::class, 'update'])->name('offres.update');
        Route::delete('/mes-offres/{offre}',          [RecruteurOffre::class, 'destroy'])->name('offres.destroy');
    });

    // Candidatures — nécessite view-candidatures
    Route::middleware('permission:'.Permission::VIEW_CANDIDATURES)->group(function () {
        Route::get('/candidatures',                   [RecruteurCandidature::class, 'index'])->name('candidatures');
        Route::get('/candidatures/{candidature}',     [RecruteurCandidature::class, 'show'])->name('candidatures.show');
        Route::patch('/candidatures/{candidature}/statut', [RecruteurCandidature::class, 'updateStatut'])->name('candidatures.statut');
    });

    // CVthèque — nécessite view-cvtheque
    Route::middleware('permission:'.Permission::VIEW_CVTHEQUE)->group(function () {
        Route::get('/cvtheque',                [CvthequeController::class, 'index'])->name('cvtheque');
        Route::post('/cvtheque/{cv}/favoris',  [CvthequeController::class, 'toggleFavoris'])->name('cvtheque.favoris');
    });

    // Contact candidats (messagerie) — nécessite contact-candidats
    Route::middleware('permission:'.Permission::CONTACT_CANDIDATS)->group(function () {
        Route::get('/messagerie',              [RecruteurMessage::class, 'index'])->name('messagerie');
        Route::get('/messagerie/{conversation}',[RecruteurMessage::class, 'show'])->name('messagerie.show');
        Route::post('/messagerie/{conversation}',[RecruteurMessage::class, 'store'])->name('messagerie.store');
    });

    // Abonnement recruteur
    Route::middleware('permission:'.Permission::MANAGE_ABONNEMENT_REC)->group(function () {
        Route::get('/abonnement',  [RecruteurAbonnement::class, 'index'])->name('abonnement');
        Route::post('/abonnement', [RecruteurAbonnement::class, 'souscrire'])->name('abonnement.store');
    });

    Route::get('/statistiques',  [RecruteurStat::class, 'index'])->name('statistiques');
    Route::get('/profil',        [RecruteurProfil::class, 'edit'])->name('profil');
    Route::put('/profil',        [RecruteurProfil::class, 'update'])->name('profil.update');
    Route::get('/parametres',    [RecruteurProfil::class, 'parametres'])->name('parametres');
    Route::put('/parametres',    [RecruteurProfil::class, 'updateParametres'])->name('parametres.update');

    }); // fin recruteur.approuve
});

// ════════════════════════════════════════════════════════
//  ESPACE TALENT — protégé par rôle + permissions
// ════════════════════════════════════════════════════════
Route::prefix('talent')->name('talent.')->middleware(['auth', 'verified', 'spatie.role:'.Role::TALENT])->group(function () {
    Route::get('/tableau-de-bord', [TalentDashboard::class, 'index'])->name('dashboard');

    // Profil talent — nécessite create-profil-talent
    Route::middleware('permission:'.Permission::CREATE_PROFIL_TALENT)->group(function () {
        Route::get('/mon-profil',          [TalentProfilCtrl::class, 'show'])->name('profil');
        Route::get('/mon-profil/creer',    [TalentProfilCtrl::class, 'create'])->name('profil.create');
        Route::post('/mon-profil',         [TalentProfilCtrl::class, 'store'])->name('profil.store');
        Route::get('/mon-profil/modifier', [TalentProfilCtrl::class, 'edit'])->name('profil.edit');
        Route::put('/mon-profil',          [TalentProfilCtrl::class, 'update'])->name('profil.update');
    });

    // Messagerie — nécessite view-messages-talent
    Route::middleware('permission:'.Permission::VIEW_MESSAGES_TALENT)->group(function () {
        Route::get('/messagerie',               [TalentMessage::class, 'index'])->name('messagerie');
        Route::get('/messagerie/{conversation}',[TalentMessage::class, 'show'])->name('messagerie.show');
        Route::post('/messagerie/{conversation}',[TalentMessage::class, 'store'])->name('messagerie.store');
    });

    // Abonnement
    Route::middleware('permission:'.Permission::MANAGE_ABONNEMENT_TAL)->group(function () {
        Route::get('/abonnement',  [TalentAbonnement::class, 'index'])->name('abonnement');
        Route::post('/abonnement', [TalentAbonnement::class, 'souscrire'])->name('abonnement.store');
    });

    Route::get('/parametres',  [TalentParametre::class, 'index'])->name('parametres');
    Route::put('/parametres',  [TalentParametre::class, 'update'])->name('parametres.update');
});

// ════════════════════════════════════════════════════════
//  ADMINISTRATION — protégé rôle admin + permissions granulaires
// ════════════════════════════════════════════════════════
Route::prefix('admin')->name('admin.')->middleware(['auth', 'spatie.role:'.Role::ADMIN])->group(function () {

    Route::get('/tableau-de-bord', [AdminDashboard::class, 'index'])->name('dashboard');

    // Vérification des comptes recruteurs
    Route::middleware('permission:'.Permission::MANAGE_USERS)->prefix('verifications')->name('verifications.')->group(function () {
        Route::get('/',                                  [VerificationRecruteurController::class, 'index'])->name('list');
        Route::get('/{verification}',                    [VerificationRecruteurController::class, 'show'])->name('show');
        Route::patch('/{verification}/approuver',        [VerificationRecruteurController::class, 'approuver'])->name('approuver');
        Route::patch('/{verification}/rejeter',          [VerificationRecruteurController::class, 'rejeter'])->name('rejeter');
        Route::get('/{verification}/document/{field}',   [VerificationRecruteurController::class, 'servirDocument'])->name('document');
    });

    // Gestion utilisateurs
    Route::middleware('permission:'.Permission::MANAGE_USERS)->prefix('utilisateurs')->name('utilisateurs.')->group(function () {
        Route::get('/',                 [UtilisateurController::class, 'index'])->name('list');
        Route::get('/candidats',        [UtilisateurController::class, 'candidats'])->name('candidats');
        Route::get('/recruteurs',       [UtilisateurController::class, 'recruteurs'])->name('recruteurs');
        Route::get('/candidats/{user}', [UtilisateurController::class, 'showCandidat'])->name('candidats.detail');
        Route::get('/recruteurs/{user}',[UtilisateurController::class, 'showRecruteur'])->name('recruteurs.detail');
        Route::patch('/{user}/statut',  [UtilisateurController::class, 'toggleStatut'])->name('statut');
        Route::delete('/{user}',        [UtilisateurController::class, 'destroy'])->name('destroy');
    });

    // Gestion offres
    Route::middleware('permission:'.Permission::MANAGE_OFFRES)->prefix('offres')->name('offres.')->group(function () {
        Route::get('/',                    [AdminOffre::class, 'index'])->name('list');
        Route::get('/{offre}',             [AdminOffre::class, 'show'])->name('detail');
        Route::patch('/{offre}/statut',    [AdminOffre::class, 'updateStatut'])->name('statut');
        Route::delete('/{offre}',          [AdminOffre::class, 'destroy'])->name('destroy');
    });

    // Gestion CVs
    Route::middleware('permission:'.Permission::MANAGE_CVS)->prefix('cvs')->name('cvs.')->group(function () {
        Route::get('/',        [AdminCV::class, 'index'])->name('list');
        Route::get('/{cv}',    [AdminCV::class, 'show'])->name('detail');
        Route::delete('/{cv}', [AdminCV::class, 'destroy'])->name('destroy');
    });

    // Gestion blog
    Route::middleware('permission:'.Permission::MANAGE_BLOG)->prefix('blog')->name('blog.')->group(function () {
        Route::get('/',                   [AdminBlog::class, 'index'])->name('list');
        Route::get('/creer',              [AdminBlog::class, 'create'])->name('create');
        Route::post('/',                  [AdminBlog::class, 'store'])->name('store');
        Route::get('/{article}/modifier', [AdminBlog::class, 'edit'])->name('edit');
        Route::put('/{article}',          [AdminBlog::class, 'update'])->name('update');
        Route::delete('/{article}',       [AdminBlog::class, 'destroy'])->name('destroy');
    });

    // Gestion services & commandes
    Route::middleware('permission:'.Permission::MANAGE_SERVICES)->group(function () {
        Route::prefix('services')->name('services.')->group(function () {
            Route::get('/',          [AdminService::class, 'index'])->name('list');
            Route::get('/creer',     [AdminService::class, 'create'])->name('create');
            Route::post('/',         [AdminService::class, 'store'])->name('store');
            Route::get('/{service}/modifier', [AdminService::class, 'edit'])->name('edit');
            Route::put('/{service}', [AdminService::class, 'update'])->name('update');
        });
        Route::middleware('permission:'.Permission::MANAGE_COMMANDES)->prefix('commandes')->name('commandes.')->group(function () {
            Route::get('/',                   [AdminService::class, 'commandes'])->name('list');
            Route::get('/{commande}',         [AdminService::class, 'showCommande'])->name('detail');
            Route::patch('/{commande}/statut',[AdminService::class, 'updateStatut'])->name('statut');
        });
    });

    // Paiements
    Route::middleware('permission:'.Permission::MANAGE_PAIEMENTS)->prefix('paiements')->name('paiements.')->group(function () {
        Route::get('/',           [AdminPaiement::class, 'index'])->name('list');
        Route::get('/{paiement}', [AdminPaiement::class, 'show'])->name('detail');
        Route::patch('/{paiement}/statut', [AdminPaiement::class, 'updateStatut'])->name('statut');
    });

    // Abonnements
    Route::middleware('permission:'.Permission::MANAGE_ABONNEMENTS)
        ->get('/abonnements', [AdminAbonnement::class, 'index'])->name('abonnements');

    // Messagerie admin
    Route::middleware('permission:'.Permission::MANAGE_MESSAGERIE)
        ->get('/messagerie', [AdminMessage::class, 'index'])->name('messagerie');

    // Signalements
    Route::middleware('permission:'.Permission::MANAGE_SIGNALEMENTS)->prefix('signalements')->name('signalements.')->group(function () {
        Route::get('/',                   [SignalementController::class, 'index'])->name('list');
        Route::get('/{signalement}',      [SignalementController::class, 'show'])->name('detail');
        Route::patch('/{signalement}/statut',[SignalementController::class, 'updateStatut'])->name('statut');
    });

    // Statistiques
    Route::middleware('permission:'.Permission::VIEW_STATISTIQUES)
        ->get('/statistiques', [AdminStat::class, 'index'])->name('statistiques');

    // Paramètres
    Route::middleware('permission:'.Permission::MANAGE_PARAMETRES)->group(function () {
        Route::get('/parametres',  [AdminParametre::class, 'index'])->name('parametres');
        Route::put('/parametres',  [AdminParametre::class, 'update'])->name('parametres.update');
    });

    // Gestion des rôles et permissions (super admin uniquement)
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/',                                [PermissionController::class, 'index'])->name('index');
        Route::put('/roles/{role}',                    [PermissionController::class, 'updateRole'])->name('role.update');
        Route::put('/users/{user}/role',               [PermissionController::class, 'updateUserRole'])->name('user.role');
        Route::post('/users/{user}/give',              [PermissionController::class, 'givePermissionToUser'])->name('user.give');
        Route::delete('/users/{user}/revoke',          [PermissionController::class, 'revokePermissionFromUser'])->name('user.revoke');
    });
});
