<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('target_type', ['candidat', 'recruteur', 'both']);
            $table->unsignedInteger('price')->default(0);
            $table->string('currency', 10)->default('FCFA');
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->boolean('is_free')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'target_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
