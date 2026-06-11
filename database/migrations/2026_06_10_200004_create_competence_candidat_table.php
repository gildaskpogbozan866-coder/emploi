<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competence_candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('competence_id')->constrained('competences')->onDelete('cascade');
            $table->tinyInteger('annees_experience')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['candidat_id', 'competence_id']);
            $table->index('competence_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competence_candidat');
    }
};
