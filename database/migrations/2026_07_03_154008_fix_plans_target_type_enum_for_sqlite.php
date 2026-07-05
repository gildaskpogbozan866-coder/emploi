<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 2026_06_14_112712_add_annonceur_to_plans_target_type.php ne faisait rien
     * du tout sur SQLite (`if (driver !== sqlite)` — mêmes symptômes que le bug
     * ENUM sur users.role corrigé dans 2026_07_02_180000) : sur une base SQLite
     * fraîche, la contrainte CHECK générée par le Schema Builder original
     * n'autorise toujours pas 'annonceur', donc PlansSeeder plante à l'insertion
     * du premier plan annonceur. Même correctif : ALTER natif sur MySQL,
     * Schema Builder ->change() (natif Laravel, sans doctrine/dbal) ailleurs.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE plans MODIFY COLUMN target_type ENUM('candidat','recruteur','both','annonceur') NOT NULL");
            return;
        }

        Schema::table('plans', function (Blueprint $table) {
            $table->enum('target_type', ['candidat', 'recruteur', 'both', 'annonceur'])->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE plans MODIFY COLUMN target_type ENUM('candidat','recruteur','both') NOT NULL");
            return;
        }

        Schema::table('plans', function (Blueprint $table) {
            $table->enum('target_type', ['candidat', 'recruteur', 'both'])->change();
        });
    }
};
