<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nom')->nullable();
            $table->string('mots_cles')->nullable();
            $table->string('localisation')->nullable();
            $table->string('type_contrat')->nullable();
            $table->string('secteur')->nullable();
            $table->enum('frequence', ['immediat', 'quotidien', 'hebdomadaire'])->default('quotidien');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
