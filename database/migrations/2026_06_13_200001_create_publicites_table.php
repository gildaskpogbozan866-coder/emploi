<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publicites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('titre');
            $table->string('image');
            $table->string('lien')->nullable();
            $table->text('note_annonceur')->nullable();
            $table->text('note_admin')->nullable();
            $table->enum('statut', ['en_attente', 'approuve', 'rejete', 'expire'])->default('en_attente');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicites');
    }
};
