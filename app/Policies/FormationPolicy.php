<?php

namespace App\Policies;

use App\Models\Formation;
use App\Models\User;

class FormationPolicy
{
    public function modify(User $user, Formation $formation): bool
    {
        return $user->id === $formation->candidat_id;
    }
}
