<?php

namespace App\Enums;

enum StatutCandidature: string
{
    case Envoyee   = 'envoyee';
    case Vue       = 'vue';
    case Retenue   = 'retenue';
    case Entretien = 'entretien';
    case Refusee   = 'refusee';

    public function label(): string
    {
        return match($this) {
            self::Envoyee   => 'Nouvelle',
            self::Vue       => 'Vue',
            self::Retenue   => 'Retenue',
            self::Entretien => 'Entretien',
            self::Refusee   => 'Refusée',
        };
    }

    public function couleur(): string
    {
        return match($this) {
            self::Envoyee   => 'blue',
            self::Vue       => 'yellow',
            self::Retenue   => 'green',
            self::Entretien => 'green',
            self::Refusee   => 'red',
        };
    }

    public static function labels(): array
    {
        return array_column(
            array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], self::cases()),
            'label', 'value'
        );
    }
}
