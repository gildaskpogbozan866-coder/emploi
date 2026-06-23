<?php

namespace App\Policies;

use App\Models\Candidature;
use App\Models\User;

class CandidaturePolicy
{
    public function view(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->offre->recruteur_id || $user->isAdmin();
    }

    public function updateStatut(User $user, Candidature $candidature): bool
    {
        return $user->id === $candidature->offre->recruteur_id || $user->isAdmin();
    }
}
