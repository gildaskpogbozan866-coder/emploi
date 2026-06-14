<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer les tables enfants avant la table parente (contraintes FK)
        Schema::dropIfExists('talent_attestations');
        Schema::dropIfExists('talent_formations');
        Schema::dropIfExists('talent_experiences');
        Schema::dropIfExists('talent_travaux');
        Schema::dropIfExists('talent_favoris');
        Schema::dropIfExists('talent_profils');
    }

    public function down(): void
    {
        Schema::create('talent_profils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('titre_professionnel')->nullable();
            $table->string('metier')->nullable();
            $table->timestamps();
        });

        Schema::create('talent_travaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profil_id')->constrained('talent_profils')->cascadeOnDelete();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        Schema::create('talent_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profil_id')->constrained('talent_profils')->cascadeOnDelete();
            $table->string('poste');
            $table->string('entreprise')->nullable();
            $table->string('date_debut', 20)->nullable();
            $table->string('date_fin', 20)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('talent_formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profil_id')->constrained('talent_profils')->cascadeOnDelete();
            $table->string('diplome');
            $table->string('etablissement')->nullable();
            $table->string('date_debut', 20)->nullable();
            $table->string('date_fin', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('talent_attestations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profil_id')->constrained('talent_profils')->cascadeOnDelete();
            $table->string('titre');
            $table->string('organisme')->nullable();
            $table->string('fichier')->nullable();
            $table->timestamps();
        });
    }
};
