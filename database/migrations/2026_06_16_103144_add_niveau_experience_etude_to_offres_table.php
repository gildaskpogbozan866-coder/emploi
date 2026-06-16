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
        Schema::table('offres', function (Blueprint $table) {
            $table->string('niveau_experience', 100)->nullable()->after('secteur');
            $table->string('niveau_etude', 100)->nullable()->after('niveau_experience');
            $table->string('metier', 150)->nullable()->after('niveau_etude');
        });
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn(['niveau_experience', 'niveau_etude', 'metier']);
        });
    }
};
