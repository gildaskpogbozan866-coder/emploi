<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatProfil extends Model
{
    protected $fillable = [
        'user_id', 'titre_professionnel', 'bio', 'ville',
        'disponibilite', 'types_contrat', 'salaire_min', 'salaire_max',
        'remote', 'linkedin', 'portfolio',
    ];

    protected function casts(): array
    {
        return [
            'types_contrat' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function libelles(): array
    {
        return [
            'disponibilite' => [
                'immediatement' => 'Immédiatement',
                '1_mois'        => 'Dans 1 mois',
                '2_mois'        => 'Dans 2 mois',
                '3_mois'        => 'Dans 3 mois',
                'plus_3_mois'   => 'Plus de 3 mois',
            ],
            'remote' => [
                'non'     => 'Présentiel',
                'partiel' => 'Hybride',
                'total'   => 'Full remote',
            ],
            'types_contrat' => [
                'cdi'        => 'CDI',
                'cdd'        => 'CDD',
                'freelance'  => 'Freelance',
                'stage'      => 'Stage',
                'alternance' => 'Alternance',
            ],
        ];
    }
}
