<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * cv_downloads.cv_id was cascadeOnDelete(): a candidat deleting their CV
     * silently wiped the recruteur's download/credit-spend history for it.
     * Make cv_id nullable + nullOnDelete() so that history survives.
     */
    public function up(): void
    {
        if ($this->foreignKeyExists('cv_downloads', 'cv_downloads_cv_id_foreign')) {
            Schema::table('cv_downloads', function (Blueprint $table) {
                $table->dropForeign(['cv_id']);
            });
        }

        Schema::table('cv_downloads', function (Blueprint $table) {
            $table->unsignedBigInteger('cv_id')->nullable()->change();
        });

        if (! $this->foreignKeyExists('cv_downloads', 'cv_downloads_cv_id_foreign')) {
            Schema::table('cv_downloads', function (Blueprint $table) {
                $table->foreign('cv_id')->references('id')->on('cvs')->nullOnDelete();
            });
        }
    }

    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $connection = Schema::getConnection();

        // information_schema.TABLE_CONSTRAINTS n'existe que sur MySQL — sur les
        // autres drivers (SQLite en test), on considère la contrainte absente et
        // on laisse les blocs appelants la (re)créer via le Schema Builder.
        if ($connection->getDriverName() !== 'mysql') {
            return false;
        }

        $result = $connection->select(
            "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$connection->getDatabaseName(), $table, $constraintName]
        );

        return count($result) > 0;
    }

    public function down(): void
    {
        if ($this->foreignKeyExists('cv_downloads', 'cv_downloads_cv_id_foreign')) {
            Schema::table('cv_downloads', function (Blueprint $table) {
                $table->dropForeign(['cv_id']);
            });
        }

        Schema::table('cv_downloads', function (Blueprint $table) {
            $table->unsignedBigInteger('cv_id')->nullable(false)->change();
        });

        if (! $this->foreignKeyExists('cv_downloads', 'cv_downloads_cv_id_foreign')) {
            Schema::table('cv_downloads', function (Blueprint $table) {
                $table->foreign('cv_id')->references('id')->on('cvs')->cascadeOnDelete();
            });
        }
    }
};