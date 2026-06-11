<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offre_competence', function (Blueprint $table) {
            $table->foreignId('offre_id')->constrained()->onDelete('cascade');
            $table->foreignId('competence_id')->constrained()->onDelete('cascade');
            $table->primary(['offre_id', 'competence_id']);
        });

        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn('competences');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offre_competence');
        Schema::table('offres', function (Blueprint $table) {
            $table->text('competences')->nullable()->after('description');
        });
    }
};
