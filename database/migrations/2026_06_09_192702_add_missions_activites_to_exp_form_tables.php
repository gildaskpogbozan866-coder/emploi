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
        Schema::table('experiences', function (Blueprint $table) {
            $table->json('missions')->nullable()->after('description');
        });
        Schema::table('formations', function (Blueprint $table) {
            $table->json('activites')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn('missions');
        });
        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn('activites');
        });
    }
};
