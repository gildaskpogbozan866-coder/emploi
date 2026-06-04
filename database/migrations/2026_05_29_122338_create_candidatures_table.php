<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_id')->constrained('offres')->onDelete('cascade');
            $table->foreignId('candidat_id')->constrained('users')->onDelete('cascade');
            $table->text('message_motivation')->nullable();
            $table->string('cv_path')->nullable();
            $table->enum('statut', ['envoyee', 'vue', 'retenue', 'refusee', 'entretien'])->default('envoyee');
            $table->text('note_recruteur')->nullable();
            $table->timestamps();

            $table->unique(['offre_id', 'candidat_id']);
        });

        Schema::create('offres_sauvegardees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('offre_id')->constrained('offres')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'offre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres_sauvegardees');
        Schema::dropIfExists('candidatures');
    }
};
