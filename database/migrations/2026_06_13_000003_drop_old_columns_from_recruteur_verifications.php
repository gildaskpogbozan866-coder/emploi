<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recruteur_verifications', function (Blueprint $table) {
            $table->dropColumn([
                'carte_biometrique',
                'cip',
                'ifu_fichier',
                'ifu_numero',
                'rccm_fichier',
                'rccm_numero',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('recruteur_verifications', function (Blueprint $table) {
            $table->string('carte_biometrique')->nullable();
            $table->string('cip')->nullable();
            $table->string('ifu_fichier')->nullable();
            $table->string('rccm_fichier')->nullable();
            $table->string('ifu_numero', 50)->nullable();
            $table->string('rccm_numero', 100)->nullable();
        });
    }
};
