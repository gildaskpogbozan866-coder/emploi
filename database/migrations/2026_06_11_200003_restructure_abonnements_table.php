<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer les colonnes de l'ancienne structure
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropColumn(['plan', 'type', 'prix', 'statut', 'debut_le', 'expire_le']);
        });

        // Ajouter la nouvelle structure
        Schema::table('abonnements', function (Blueprint $table) {
            $table->foreignId('plan_id')->after('user_id')->constrained('plans')->restrictOnDelete();
            $table->timestamp('starts_at')->nullable()->after('plan_id');
            $table->timestamp('ends_at')->nullable()->after('starts_at');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active')->after('ends_at');
            $table->boolean('auto_renew')->default(false)->after('status');

            $table->index('plan_id');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'starts_at', 'ends_at', 'status', 'auto_renew']);
        });

        Schema::table('abonnements', function (Blueprint $table) {
            $table->string('plan')->after('user_id');
            $table->string('type')->after('plan');
            $table->integer('prix')->default(0)->after('type');
            $table->enum('statut', ['actif', 'expire', 'annule'])->default('actif')->after('prix');
            $table->timestamp('debut_le')->nullable()->after('statut');
            $table->timestamp('expire_le')->nullable()->after('debut_le');
        });
    }
};
