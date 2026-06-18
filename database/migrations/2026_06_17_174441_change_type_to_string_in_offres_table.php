<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->foreignId('type_contrat_id')->constrained('type_contrats')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->enum('type', ['CDI','CDD','Stage','Bourse','Freelance','Temps partiel'])->change();
        });
    }
};
