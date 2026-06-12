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
        Schema::table('paiements', function (Blueprint $table) {
            $table->string('gateway')->default('manuel')->after('methode');
            $table->string('gateway_transaction_id')->nullable()->after('gateway');
            $table->string('gateway_status')->nullable()->after('gateway_transaction_id');
            $table->unsignedInteger('gateway_fees')->default(0)->after('gateway_status');
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['gateway', 'gateway_transaction_id', 'gateway_status', 'gateway_fees']);
        });
    }
};
