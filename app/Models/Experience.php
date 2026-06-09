<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'candidat_id', 'poste', 'entreprise', 'lieu', 'secteur',
        'date_debut', 'date_fin', 'en_cours', 'description', 'ordre',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin'   => 'date',
            'en_cours'   => 'boolean',
        ];
    }

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function duree(): string
    {
        $debut = $this->date_debut->translatedFormat('M Y');
        $fin   = $this->en_cours ? "Aujourd'hui" : ($this->date_fin?->translatedFormat('M Y') ?? '');
        return "$debut — $fin";
    }
}
