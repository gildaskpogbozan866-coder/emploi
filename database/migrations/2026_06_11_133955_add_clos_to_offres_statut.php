<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite (tests) ne supporte pas MODIFY COLUMN et n'applique pas les contraintes ENUM
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE offres MODIFY COLUMN statut ENUM('en_attente','active','expiree','suspendue','brouillon','clos') NOT NULL DEFAULT 'en_attente'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE offres MODIFY COLUMN statut ENUM('en_attente','active','expiree','suspendue','brouillon') NOT NULL DEFAULT 'en_attente'");
        }
    }
};
