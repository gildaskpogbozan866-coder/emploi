<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruteur_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');

            // Documents uploadés (PDF ou image)
            $table->string('carte_biometrique')->nullable();
            $table->string('cip')->nullable();
            $table->string('ifu_fichier')->nullable();
            $table->string('rccm_fichier')->nullable();

            // Numéros saisis directement
            $table->string('ifu_numero', 50)->nullable();
            $table->string('rccm_numero', 100)->nullable();

            // Décision admin
            $table->text('note_admin')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruteur_verifications');
    }
};
