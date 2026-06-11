<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secteur_activite_candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('secteur_activite_id')->constrained('secteurs_activite')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['candidat_id', 'secteur_activite_id']);
            $table->index('secteur_activite_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secteur_activite_candidat');
    }
};
