<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Corriger les alertes qui utilisent l'ancien code TEMPS_PARTIEL
        DB::table('alertes')
            ->where('type_contrat', 'TEMPS_PARTIEL')
            ->update(['type_contrat' => 'Temps partiel']);

        // Supprimer le doublon de type_contrats
        DB::table('type_contrats')
            ->where('code', 'TEMPS_PARTIEL')
            ->delete();
    }

    public function down(): void
    {
        DB::table('type_contrats')->insert([
            'code'    => 'TEMPS_PARTIEL',
            'libelle' => 'Temps partiel',
        ]);

        DB::table('alertes')
            ->where('type_contrat', 'Temps partiel')
            ->update(['type_contrat' => 'TEMPS_PARTIEL']);
    }
};
