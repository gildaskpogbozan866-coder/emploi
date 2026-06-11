<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talent_formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talent_profil_id')->constrained('talent_profils')->cascadeOnDelete();
            $table->string('diplome', 200);
            $table->string('etablissement', 200)->nullable();
            $table->string('annee_obtention', 4)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talent_formations');
    }
};
