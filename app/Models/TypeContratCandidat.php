<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeContratCandidat extends Model
{
    protected $table = 'type_contrat_candidat';

    protected $fillable = ['candidat_id', 'type_contrat_id'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function typeContrat()
    {
        return $this->belongsTo(TypeContrat::class);
    }
}
