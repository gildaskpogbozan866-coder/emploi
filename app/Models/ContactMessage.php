<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['prenom', 'nom', 'email', 'sujet', 'message', 'lu', 'lu_at'];

    protected $casts = ['lu' => 'boolean', 'lu_at' => 'datetime'];

    public function scopeNonLu($query)
    {
        return $query->where('lu', false);
    }

    public function marquerLu(): void
    {
        if (!$this->lu) {
            $this->update(['lu' => true, 'lu_at' => now()]);
        }
    }

    public function getSujetLabelAttribute(): string
    {
        return match($this->sujet) {
            'question'    => 'Question générale',
            'partenariat' => 'Proposition de partenariat',
            'signalement' => 'Signalement d\'un contenu',
            'technique'   => 'Problème technique',
            default       => 'Autre',
        };
    }
}
