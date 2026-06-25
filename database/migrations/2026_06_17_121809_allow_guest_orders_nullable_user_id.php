<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $fkNames = collect(Schema::getForeignKeys('commandes'))->pluck('name');
            if ($fkNames->contains('commandes_user_id_foreign')) {
                $table->dropForeign(['user_id']);
            }
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $fkNames = collect(Schema::getForeignKeys('commandes'))->pluck('name');
            if (! $fkNames->contains('commandes_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
            if (! Schema::hasColumn('commandes', 'email_contact')) {
                $table->string('email_contact')->nullable()->after('user_id');
            }
        });

        Schema::table('paiements', function (Blueprint $table) {
            $fkNames = collect(Schema::getForeignKeys('paiements'))->pluck('name');
            if ($fkNames->contains('paiements_user_id_foreign')) {
                $table->dropForeign(['user_id']);
            }
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $fkNames = collect(Schema::getForeignKeys('paiements'))->pluck('name');
            if (! $fkNames->contains('paiements_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('email_contact');
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
