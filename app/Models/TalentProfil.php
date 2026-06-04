<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentProfil extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'metier', 'specialite', 'pays', 'ville', 'bio',
        'competences', 'experience', 'langues', 'photo',
        'portfolio_url', 'plan', 'visible', 'vues',
    ];

    protected function casts(): array
    {
        return [
            'visible' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorisParRecruteurs()
    {
        return $this->belongsToMany(User::class, 'talent_favoris', 'talent_id', 'recruteur_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}
