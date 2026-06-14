<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruteur_document_types', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->boolean('accepte_fichier')->default(true);
            $table->boolean('accepte_texte')->default(false);
            $table->boolean('est_requis')->default(true);
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruteur_document_types');
    }
};
