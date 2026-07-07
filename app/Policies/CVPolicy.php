<?php

namespace App\Policies;

use App\Models\CV;
use App\Models\User;

class CVPolicy
{
    public function update(User $user, CV $cv): bool
    {
        return (int) $user->id === (int) $cv->candidat_id || $user->isAdmin();
    }

    public function delete(User $user, CV $cv): bool
    {
        return (int) $user->id === (int) $cv->candidat_id || $user->isAdmin();
    }
}
