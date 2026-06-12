<?php

namespace App\Enums;

class Permission
{
    // ── ADMIN ──────────────────────────────────────────────
    const MANAGE_USERS          = 'manage-users';
    const MANAGE_OFFRES         = 'manage-offres';
    const MANAGE_CVS            = 'manage-cvs';
    const MANAGE_BLOG           = 'manage-blog';
    const MANAGE_SERVICES       = 'manage-services';
    const MANAGE_COMMANDES      = 'manage-commandes';
    const MANAGE_PAIEMENTS      = 'manage-paiements';
    const MANAGE_ABONNEMENTS    = 'manage-abonnements';
    const MANAGE_MESSAGERIE     = 'manage-messagerie';
    const MANAGE_SIGNALEMENTS   = 'manage-signalements';
    const MANAGE_PARAMETRES     = 'manage-parametres';
    const MANAGE_REFERENTIELS   = 'manage-referentiels';
    const VIEW_STATISTIQUES     = 'view-statistiques';

    // ── RECRUTEUR ──────────────────────────────────────────
    const PUBLISH_OFFRE         = 'publish-offre';
    const VIEW_CANDIDATURES     = 'view-candidatures';
    const VIEW_CVTHEQUE         = 'view-cvtheque';
    const CONTACT_CANDIDATS     = 'contact-candidats';
    const MANAGE_ABONNEMENT_REC = 'manage-abonnement-recruteur';

    // ── CANDIDAT ───────────────────────────────────────────
    const DEPOSIT_CV            = 'deposit-cv';
    const APPLY_OFFRE           = 'apply-offre';
    const SAVE_OFFRE            = 'save-offre';
    const CREATE_ALERTE         = 'create-alerte';
    const MANAGE_ABONNEMENT_CAN = 'manage-abonnement-candidat';

    // ── Regroupements par rôle ─────────────────────────────
    public static function adminPermissions(): array
    {
        return [
            self::MANAGE_USERS,
            self::MANAGE_OFFRES,
            self::MANAGE_CVS,
            self::MANAGE_BLOG,
            self::MANAGE_SERVICES,
            self::MANAGE_COMMANDES,
            self::MANAGE_PAIEMENTS,
            self::MANAGE_ABONNEMENTS,
            self::MANAGE_MESSAGERIE,
            self::MANAGE_SIGNALEMENTS,
            self::MANAGE_PARAMETRES,
            self::MANAGE_REFERENTIELS,
            self::VIEW_STATISTIQUES,
            // + toutes les permissions des autres rôles
            self::PUBLISH_OFFRE,
            self::VIEW_CANDIDATURES,
            self::VIEW_CVTHEQUE,
            self::CONTACT_CANDIDATS,
            self::DEPOSIT_CV,
            self::APPLY_OFFRE,
            self::SAVE_OFFRE,
            self::CREATE_ALERTE,
        ];
    }

    public static function recruteurPermissions(): array
    {
        return [
            self::PUBLISH_OFFRE,
            self::VIEW_CANDIDATURES,
            self::VIEW_CVTHEQUE,
            self::CONTACT_CANDIDATS,
            self::MANAGE_ABONNEMENT_REC,
        ];
    }

    public static function candidatPermissions(): array
    {
        return [
            self::DEPOSIT_CV,
            self::APPLY_OFFRE,
            self::SAVE_OFFRE,
            self::CREATE_ALERTE,
            self::MANAGE_ABONNEMENT_CAN,
        ];
    }

    public static function all(): array
    {
        return array_unique(array_merge(
            self::adminPermissions(),
            self::recruteurPermissions(),
            self::candidatPermissions(),
        ));
    }
}
