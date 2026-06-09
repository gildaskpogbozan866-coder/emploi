<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetenceCandidat extends Model
{
    use HasFactory;

    protected $table = 'competences_candidat';

    protected $fillable = ['candidat_id', 'nom', 'niveau'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public static function niveauxLibelles(): array
    {
        return [
            'debutant'      => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance'        => 'Avancé',
            'expert'        => 'Expert',
        ];
    }
}
