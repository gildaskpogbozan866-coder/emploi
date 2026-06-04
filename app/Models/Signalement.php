<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'cible_id', 'raison',
        'description', 'statut', 'note_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
