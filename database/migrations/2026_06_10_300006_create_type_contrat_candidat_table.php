<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_contrat_candidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_contrat_id')->constrained('type_contrats')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['candidat_id', 'type_contrat_id']);
            $table->index('type_contrat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_contrat_candidat');
    }
};
