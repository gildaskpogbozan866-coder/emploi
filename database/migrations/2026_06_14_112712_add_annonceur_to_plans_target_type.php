<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE plans MODIFY COLUMN target_type ENUM('candidat','recruteur','both','annonceur') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE plans MODIFY COLUMN target_type ENUM('candidat','recruteur','both') NOT NULL");
        }
    }
};
