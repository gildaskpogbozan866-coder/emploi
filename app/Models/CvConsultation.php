<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvConsultation extends Model
{
    protected $fillable = ['recruteur_id', 'cv_id'];

    public function recruteur()
    {
        return $this->belongsTo(User::class, 'recruteur_id');
    }

    public function cv()
    {
        return $this->belongsTo(CV::class, 'cv_id')->withTrashed();
    }
}
