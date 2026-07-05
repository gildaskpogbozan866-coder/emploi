<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Un candidat qui "supprime" son CV ne doit pas casser la traçabilité :
     * l'historique d'achat du recruteur (cv_downloads) et le CV attaché à
     * une candidature doivent rester consultables. Le CV disparaît de tous
     * les listings publics/recruteur (SoftDeletes exclut automatiquement
     * les lignes supprimées des requêtes normales) sans effacer la ligne.
     */
    public function up(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
