<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    use HasFactory;

    protected $table = 'cvs';

    protected $fillable = [
        'candidat_id', 'titre_poste', 'pays', 'ville', 'competences',
        'experience', 'formation', 'langues', 'fichier_path',
        'photo', 'plan', 'visible', 'vues',
    ];

    protected function casts(): array
    {
        return [
            'visible' => 'boolean',
        ];
    }

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}
