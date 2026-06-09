<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competences_candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->string('nom', 100);
            $table->enum('niveau', ['debutant', 'intermediaire', 'avance', 'expert'])->default('intermediaire');
            $table->timestamps();

            $table->index('candidat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competences_candidat');
    }
};
