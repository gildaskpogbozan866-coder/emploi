<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profil_id')->constrained('talent_profils')->cascadeOnDelete();
            $table->string('poste', 200);
            $table->string('employeur', 200)->nullable();
            $table->string('date_debut', 20);
            $table->string('date_fin', 20)->nullable();
            $table->boolean('en_cours')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_experiences');
    }
};
