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
        Schema::create('credit_cv_packs', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);
            $table->unsignedSmallInteger('credits');
            $table->unsignedInteger('prix');
            $table->string('badge', 60)->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('actif')->default(true);
            $table->unsignedTinyInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_cv_packs');
    }
};
