<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            'STAGE'         => 'Stage',
            'ALTERNANCE'    => 'Alternance',
            'FREELANCE'     => 'Freelance',
            'INTERIM'       => 'Intérim',
            'TEMPS_PARTIEL' => 'Temps partiel',
            'BOURSE'        => 'Bourse',
            'CONSULTING'    => 'Consulting',
        ];
        foreach ($updates as $ancien => $nouveau) {
            DB::table('type_contrats')->where('code', $ancien)->update(['code' => $nouveau]);
        }
    }

    public function down(): void
    {
        $updates = [
            'Stage'         => 'STAGE',
            'Alternance'    => 'ALTERNANCE',
            'Freelance'     => 'FREELANCE',
            'Intérim'       => 'INTERIM',
            'Temps partiel' => 'TEMPS_PARTIEL',
            'Bourse'        => 'BOURSE',
            'Consulting'    => 'CONSULTING',
        ];
        foreach ($updates as $ancien => $nouveau) {
            DB::table('type_contrats')->where('code', $ancien)->update(['code' => $nouveau]);
        }
    }
};
