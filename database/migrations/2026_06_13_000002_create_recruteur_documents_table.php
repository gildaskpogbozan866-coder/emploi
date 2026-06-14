<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruteur_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('type_id')->constrained('recruteur_document_types')->cascadeOnDelete();
            $table->string('fichier')->nullable();
            $table->string('texte', 500)->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruteur_documents');
    }
};
