<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('reference')->unique();
            $table->text('details_demande')->nullable();
            $table->string('fichier_joint')->nullable();
            $table->integer('montant');
            $table->enum('statut', ['en_attente', 'en_cours', 'livree', 'annulee'])->default('en_attente');
            $table->enum('paiement_statut', ['non_paye', 'paye', 'rembourse'])->default('non_paye');
            $table->string('paiement_methode')->nullable();
            $table->text('note_admin')->nullable();
            $table->string('fichier_livraison')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
