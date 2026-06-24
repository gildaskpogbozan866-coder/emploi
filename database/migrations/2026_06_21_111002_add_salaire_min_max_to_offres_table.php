<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->unsignedInteger('salaire_min')->nullable()->after('salaire');
            $table->unsignedInteger('salaire_max')->nullable()->after('salaire_min');
            $table->dropColumn('salaire');
        });
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->string('salaire')->nullable()->after('secteur');
            $table->dropColumn(['salaire_min', 'salaire_max']);
        });
    }
};
