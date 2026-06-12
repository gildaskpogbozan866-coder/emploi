<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── conversations ─────────────────────────────────────
        Schema::table('conversations', function (Blueprint $table) {
            // Archivage par participant (indépendant l'un de l'autre)
            $table->boolean('archived_by_user1')->default(false)->after('dernier_message_at');
            $table->boolean('archived_by_user2')->default(false)->after('archived_by_user1');
        });

        // ── messages ──────────────────────────────────────────
        Schema::table('messages', function (Blueprint $table) {
            // Type MIME pour distinguer image / PDF / autre à l'affichage
            $table->string('mime_type')->nullable()->after('fichier');
            // contenu peut être null quand seule une pièce jointe est envoyée
            $table->text('contenu')->nullable()->change();

            // Index pour les requêtes non-lus et le polling (id > X)
            $table->index(['conversation_id', 'created_at'], 'idx_messages_conv_date');
            $table->index('lu', 'idx_messages_lu');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['archived_by_user1', 'archived_by_user2']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('idx_messages_conv_date');
            $table->dropIndex('idx_messages_lu');
            $table->dropColumn('mime_type');
            $table->text('contenu')->nullable(false)->change();
        });
    }
};
