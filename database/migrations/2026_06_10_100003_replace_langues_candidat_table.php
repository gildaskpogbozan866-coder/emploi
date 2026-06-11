<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('langues_candidat');

        Schema::create('langues_candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('langue_id')->constrained('langues')->onDelete('cascade');
            $table->foreignId('niveau_id')->constrained('niveaux_langue')->onDelete('restrict');
            $table->timestamps();

            $table->unique(['candidat_id', 'langue_id']);
            $table->index(['langue_id', 'niveau_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langues_candidat');

        Schema::create('langues_candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->string('langue', 80);
            $table->enum('niveau', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'natif'])->default('B2');
            $table->timestamps();

            $table->index('candidat_id');
        });
    }
};
