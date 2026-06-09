<?php

namespace App\Policies;

use App\Models\Experience;
use App\Models\User;

class ExperiencePolicy
{
    public function modify(User $user, Experience $experience): bool
    {
        return $user->id === $experience->candidat_id;
    }
}
