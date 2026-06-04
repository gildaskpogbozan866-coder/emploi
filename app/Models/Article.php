<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'auteur_id', 'titre', 'slug', 'extrait', 'contenu',
        'categorie', 'image', 'vues', 'temps_lecture', 'statut', 'publie_le',
    ];

    protected function casts(): array
    {
        return [
            'publie_le' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->titre);
            }
        });
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function scopePublie($query)
    {
        return $query->where('statut', 'publie');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
