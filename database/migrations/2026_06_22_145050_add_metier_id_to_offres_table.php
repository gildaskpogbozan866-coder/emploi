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
        Schema::table('offres', function (Blueprint $table) {
            $table->unsignedBigInteger('metier_id')->nullable()->after('secteur');
            $table->foreign('metier_id')->references('id')->on('metiers')->nullOnDelete();
            $table->dropColumn('metier');
        });
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropForeign(['metier_id']);
            $table->dropColumn('metier_id');
            $table->string('metier')->nullable()->after('secteur');
        });
    }
};
