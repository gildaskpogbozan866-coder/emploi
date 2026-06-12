<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            // FK directe vers abonnements (plus efficace que le polymorphique pour ce cas)
            $table->foreignId('subscription_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('abonnements')
                  ->nullOnDelete();

            // Référence externe de la passerelle de paiement (FedaPay, CinetPay…)
            $table->string('transaction_reference')->nullable()->after('reference');

            // Horodatage de confirmation du paiement
            $table->timestamp('paid_at')->nullable()->after('notes');

            $table->index('subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropIndex(['subscription_id']);
            $table->dropForeign(['subscription_id']);
            $table->dropColumn(['subscription_id', 'transaction_reference', 'paid_at']);
        });
    }
};
