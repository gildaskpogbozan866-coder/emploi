<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LangueCandidat extends Model
{
    use HasFactory;

    protected $table = 'langues_candidat';

    protected $fillable = ['candidat_id', 'langue', 'niveau'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public static function niveauxLibelles(): array
    {
        return [
            'A1'    => 'A1 — Débutant',
            'A2'    => 'A2 — Élémentaire',
            'B1'    => 'B1 — Intermédiaire',
            'B2'    => 'B2 — Intermédiaire supérieur',
            'C1'    => 'C1 — Avancé',
            'C2'    => 'C2 — Maîtrise',
            'natif' => 'Langue natale',
        ];
    }
}
