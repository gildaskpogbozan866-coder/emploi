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
        Schema::table('cvs', function (Blueprint $table) {
            $table->string('metier', 150)->nullable()->after('secteur');
            $table->string('niveau_etude', 100)->nullable()->after('metier');
            $table->string('type_contrat', 50)->nullable()->after('niveau_etude');
            $table->string('niveau_experience', 100)->nullable()->after('type_contrat');
        });
    }

    public function down(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->dropColumn(['metier', 'niveau_etude', 'type_contrat', 'niveau_experience']);
        });
    }
};
