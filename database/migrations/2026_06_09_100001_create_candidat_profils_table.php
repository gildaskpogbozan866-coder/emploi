<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidat_profils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('titre_professionnel')->nullable();
            $table->text('bio')->nullable();
            $table->string('ville')->nullable();
            $table->enum('disponibilite', ['immediatement', '1_mois', '2_mois', '3_mois', 'plus_3_mois'])->nullable();
            $table->json('types_contrat')->nullable();
            $table->unsignedInteger('salaire_min')->nullable();
            $table->unsignedInteger('salaire_max')->nullable();
            $table->enum('remote', ['non', 'partiel', 'total'])->default('non');
            $table->string('linkedin', 500)->nullable();
            $table->string('portfolio', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidat_profils');
    }
};
