<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidat_id', 'diplome', 'etablissement', 'domaine',
        'date_debut', 'date_fin', 'en_cours', 'description', 'activites', 'ordre',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin'   => 'date',
            'en_cours'   => 'boolean',
            'activites'  => 'array',
        ];
    }

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }
}
