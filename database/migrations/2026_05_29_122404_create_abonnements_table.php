<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('plan');   // gratuit, premium, premium_30, premium_50
            $table->string('type');   // cv, talent, recruteur
            $table->integer('prix')->default(0);
            $table->enum('statut', ['actif', 'expire', 'annule'])->default('actif');
            $table->timestamp('debut_le')->nullable();
            $table->timestamp('expire_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
