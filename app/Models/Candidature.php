<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'offre_id', 'candidat_id', 'message_motivation',
        'cv_path', 'cv_id', 'statut', 'note_recruteur',
    ];

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function cv()
    {
        return $this->belongsTo(CV::class);
    }
}
