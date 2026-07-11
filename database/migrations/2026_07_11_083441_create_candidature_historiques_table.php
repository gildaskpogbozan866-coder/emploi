<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidature_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidature_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recruteur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('statut');
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['candidature_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidature_historiques');
    }
};
