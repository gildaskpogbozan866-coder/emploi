<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruteurDocument extends Model
{
    protected $table = 'recruteur_documents';

    protected $fillable = ['user_id', 'type_id', 'fichier', 'texte'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(RecruteurDocumentType::class, 'type_id');
    }

    public function estImage(): bool
    {
        if (!$this->fichier) return false;
        $ext = strtolower(pathinfo($this->fichier, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
    }
}
