<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->string('titre_poste');
            $table->string('pays');
            $table->string('ville')->nullable();
            $table->text('competences')->nullable();
            $table->text('experience')->nullable();
            $table->text('formation')->nullable();
            $table->string('langues')->nullable();
            $table->string('fichier_path')->nullable();
            $table->string('photo')->nullable();
            $table->enum('plan', ['gratuit', 'premium'])->default('gratuit');
            $table->boolean('visible')->default(true);
            $table->integer('vues')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
