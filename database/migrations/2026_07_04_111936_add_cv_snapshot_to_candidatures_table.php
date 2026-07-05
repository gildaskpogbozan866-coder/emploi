<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            // Instantané du CV au moment de la candidature (métier, ville, fichier
            // propre à cette candidature) — le recruteur doit voir le CV tel qu'il
            // était à l'envoi, pas sa version live si le candidat le modifie ensuite.
            // Null pour les candidatures existantes (pas de reconstruction rétroactive
            // possible) : l'affichage retombe alors sur la relation cv() live.
            $table->json('cv_snapshot')->nullable()->after('cv_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn('cv_snapshot');
        });
    }
};
