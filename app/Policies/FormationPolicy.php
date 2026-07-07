<?php

namespace App\Policies;

use App\Models\Formation;
use App\Models\User;

class FormationPolicy
{
    public function modify(User $user, Formation $formation): bool
    {
        return (int) $user->id === (int) $formation->candidat_id;
    }
}
