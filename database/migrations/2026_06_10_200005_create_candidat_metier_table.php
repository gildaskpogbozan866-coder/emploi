<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidat_metier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('metier_id')->constrained('metiers')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['candidat_id', 'metier_id']);
            $table->index('metier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidat_metier');
    }
};
