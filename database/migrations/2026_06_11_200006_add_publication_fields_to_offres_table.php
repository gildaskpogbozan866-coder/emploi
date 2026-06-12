<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->foreignId('publication_plan_id')
                  ->nullable()
                  ->after('statut')
                  ->constrained('job_publication_plans')
                  ->nullOnDelete();
            $table->timestamp('published_at')->nullable()->after('publication_plan_id');
            $table->timestamp('expires_at')->nullable()->after('published_at');

            $table->index('publication_plan_id');
            $table->index(['published_at', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropIndex(['published_at', 'expires_at']);
            $table->dropForeign(['publication_plan_id']);
            $table->dropColumn(['publication_plan_id', 'published_at', 'expires_at']);
        });
    }
};
