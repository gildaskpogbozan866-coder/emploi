<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('talent_profils')->update(['types_mission' => '[]']);

        Schema::table('talent_profils', function (Blueprint $table) {
            $table->renameColumn('types_mission', 'types_contrat');
        });
    }

    public function down(): void
    {
        Schema::table('talent_profils', function (Blueprint $table) {
            $table->renameColumn('types_contrat', 'types_mission');
        });
    }
};
