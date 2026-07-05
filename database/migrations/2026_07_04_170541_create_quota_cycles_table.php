<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quota_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('quota_key'); // 'job_apply_limit' | 'job_post_limit'
            $table->timestamp('cycle_starts_at');
            $table->timestamp('cycle_ends_at')->nullable(); // null = plan sans durée
            $table->timestamps();

            $table->unique(['user_id', 'quota_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_cycles');
    }
};
