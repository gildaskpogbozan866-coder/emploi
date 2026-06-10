<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveau_etude_candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('niveau_etude_id')->constrained('niveaux_etudes')->onDelete('restrict');
            $table->timestamps();

            $table->index('niveau_etude_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveau_etude_candidat');
    }
};
