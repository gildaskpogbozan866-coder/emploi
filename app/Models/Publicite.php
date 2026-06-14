<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Publicite extends Model
{
    protected $fillable = [
        'user_id', 'titre', 'image', 'lien',
        'note_annonceur', 'note_admin',
        'statut', 'date_debut', 'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActives(Builder $query): Builder
    {
        return $query->where('statut', 'approuve')
            ->where(function ($q) {
                $q->whereNull('date_debut')->orWhere('date_debut', '<=', now()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('date_fin')->orWhere('date_fin', '>=', now()->toDateString());
            });
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'approuve'   => 'Approuvée',
            'rejete'     => 'Rejetée',
            'expire'     => 'Expirée',
            default      => $this->statut,
        };
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'yellow',
            'approuve'   => 'green',
            'rejete'     => 'red',
            'expire'     => 'gray',
            default      => 'gray',
        };
    }
}
