<?php

namespace App\Enums;

class Role
{
    const ADMIN      = 'admin';
    const RECRUTEUR  = 'recruteur';
    const CANDIDAT   = 'candidat';
    const ANNONCEUR  = 'annonceur';

    public static function all(): array
    {
        return [self::ADMIN, self::RECRUTEUR, self::CANDIDAT, self::ANNONCEUR];
    }
}
