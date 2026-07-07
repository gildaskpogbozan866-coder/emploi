<?php

namespace App\Policies;

use App\Models\Candidature;
use App\Models\User;

class CandidaturePolicy
{
    public function view(User $user, Candidature $candidature): bool
    {
        return (int) $user->id === (int) $candidature->offre->recruteur_id || $user->isAdmin();
    }

    public function updateStatut(User $user, Candidature $candidature): bool
    {
        return (int) $user->id === (int) $candidature->offre->recruteur_id || $user->isAdmin();
    }
}
