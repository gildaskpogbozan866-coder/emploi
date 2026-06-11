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
        Schema::table('documents', function (Blueprint $table) {
            $table->string('pays', 100)->nullable()->after('nom');
            $table->string('ville', 100)->nullable()->after('pays');
            $table->text('competences')->nullable()->after('ville');
            $table->text('experience')->nullable()->after('competences');
            $table->text('formation')->nullable()->after('experience');
            $table->string('langues', 500)->nullable()->after('formation');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['pays', 'ville', 'competences', 'experience', 'formation', 'langues']);
        });
    }
};
