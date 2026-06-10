<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metier_competence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metier_id')->constrained('metiers')->onDelete('cascade');
            $table->foreignId('competence_id')->constrained('competences')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['metier_id', 'competence_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metier_competence');
    }
};
