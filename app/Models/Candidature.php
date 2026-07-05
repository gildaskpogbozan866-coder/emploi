<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'offre_id', 'candidat_id', 'message_motivation',
        'cv_id', 'cv_path', 'cv_snapshot', 'lettre_path', 'statut', 'note_recruteur',
    ];

    protected function casts(): array
    {
        return [
            'cv_snapshot' => 'array',
        ];
    }

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
        // withTrashed() : le CV joint à une candidature doit rester consultable
        // par le recruteur même si le candidat l'a supprimé depuis.
        return $this->belongsTo(CV::class)->withTrashed();
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'candidature_document');
    }
}
