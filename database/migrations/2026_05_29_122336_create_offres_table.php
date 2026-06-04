<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruteur_id')->constrained('users')->onDelete('cascade');
            $table->string('titre');
            $table->string('entreprise');
            $table->string('localisation');
            $table->enum('type', ['CDI', 'CDD', 'Stage', 'Bourse', 'Freelance', 'Temps partiel'])->default('CDI');
            $table->string('secteur')->nullable();
            $table->string('salaire')->nullable();
            $table->text('description');
            $table->text('competences')->nullable();
            $table->text('exigences')->nullable();
            $table->date('date_limite')->nullable();
            $table->enum('statut', ['en_attente', 'active', 'expiree', 'suspendue'])->default('en_attente');
            $table->boolean('premium')->default(false);
            $table->integer('vues')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
