<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_profils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('metier');
            $table->string('specialite')->nullable();
            $table->string('pays');
            $table->string('ville')->nullable();
            $table->text('bio')->nullable();
            $table->text('competences')->nullable();
            $table->text('experience')->nullable();
            $table->string('langues')->nullable();
            $table->string('photo')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->enum('plan', ['gratuit', 'premium'])->default('gratuit');
            $table->boolean('visible')->default(true);
            $table->integer('vues')->default(0);
            $table->timestamps();
        });

        Schema::create('talent_favoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruteur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('talent_id')->constrained('talent_profils')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['recruteur_id', 'talent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_favoris');
        Schema::dropIfExists('talent_profils');
    }
};
