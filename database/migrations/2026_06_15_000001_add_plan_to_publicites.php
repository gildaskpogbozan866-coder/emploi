<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicites', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('user_id')
                  ->constrained('job_publication_plans')->nullOnDelete();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE publicites MODIFY COLUMN statut ENUM('brouillon','en_attente','approuve','rejete','expire') DEFAULT 'brouillon'");
        }
    }

    public function down(): void
    {
        Schema::table('publicites', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE publicites MODIFY COLUMN statut ENUM('en_attente','approuve','rejete','expire') DEFAULT 'en_attente'");
        }
    }
};
