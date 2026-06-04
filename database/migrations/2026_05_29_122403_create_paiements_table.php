<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('reference')->unique();
            $table->integer('montant');
            $table->string('devise')->default('FCFA');
            $table->string('type');  // abonnement_cv, abonnement_talent, abonnement_recruteur, service, offre
            $table->morphs('payable'); // polymorphique -> offre, commande, abonnement...
            $table->string('methode')->nullable(); // mobile_money, virement, carte
            $table->enum('statut', ['en_attente', 'confirme', 'echec', 'rembourse'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
