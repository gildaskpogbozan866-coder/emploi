<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametreApp extends Model
{
    protected $table    = 'parametres_app';
    protected $fillable = ['cle', 'valeur', 'label'];

    public static function get(string $cle, string $default = ''): string
    {
        $row = static::where('cle', $cle)->first();
        return $row?->valeur ?? $default;
    }

    public static function set(string $cle, string $valeur): void
    {
        static::updateOrCreate(['cle' => $cle], ['valeur' => $valeur]);
    }
}
