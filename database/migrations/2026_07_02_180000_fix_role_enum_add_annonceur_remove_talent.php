<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Le rôle "talent" a été fusionné dans "candidat" (voir migration
     * 2026_06_10_000001_migrate_talent_users_to_candidat.php) mais la
     * définition ENUM de la colonne n'avait jamais été mise à jour : elle
     * autorisait encore "talent" et n'a jamais inclus "annonceur", alors que
     * toute l'application (inscription, routes, dashboards) traite déjà
     * "annonceur" comme un rôle valide. Résultat : impossible de créer un
     * compte annonceur en base (violation de contrainte).
     *
     * ALTER ... MODIFY COLUMN est une syntaxe MySQL ; sur les autres pilotes
     * (SQLite en test), on repasse par le Schema Builder pour rester portable.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('candidat', 'recruteur', 'admin', 'annonceur') NOT NULL DEFAULT 'candidat'");
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['candidat', 'recruteur', 'admin', 'annonceur'])->default('candidat')->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('candidat', 'talent', 'recruteur', 'admin') NOT NULL DEFAULT 'candidat'");
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['candidat', 'talent', 'recruteur', 'admin'])->default('candidat')->change();
        });
    }
};
