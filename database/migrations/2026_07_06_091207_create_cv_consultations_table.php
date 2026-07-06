<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cv_consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruteur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cv_id')->constrained('cvs')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['recruteur_id', 'cv_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_consultations');
    }
};
