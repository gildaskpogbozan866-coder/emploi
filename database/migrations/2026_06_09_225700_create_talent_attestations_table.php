<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_attestations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profil_id')->constrained('talent_profils')->cascadeOnDelete();
            $table->string('nom', 200);
            $table->string('fichier');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_attestations');
    }
};
