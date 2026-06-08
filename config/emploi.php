<?php

return [
    /*
     * Email qui reçoit les alertes admin (nouveaux dossiers recruteurs, etc.)
     * Peut être surchargé depuis l'interface admin (stocké en base).
     */
    'admin_notification_email' => env('ADMIN_NOTIFICATION_EMAIL', ''),
];
