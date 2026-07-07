<?php

namespace App\Policies;

use App\Models\Offre;
use App\Models\User;

class OffrePolicy
{
    public function update(User $user, Offre $offre): bool
    {
        return (int) $user->id === (int) $offre->recruteur_id || $user->isAdmin();
    }

    public function delete(User $user, Offre $offre): bool
    {
        return (int) $user->id === (int) $offre->recruteur_id || $user->isAdmin();
    }
}
