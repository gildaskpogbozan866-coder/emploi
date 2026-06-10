<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatProfil extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'titre_professionnel', 'bio', 'ville',
        'disponibilite', 'salaire_min', 'salaire_max',
        'remote', 'linkedin', 'portfolio',
    ];

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
        ];
    }
}
