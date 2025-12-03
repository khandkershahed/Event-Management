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
        Schema::table('event_tickets', function (Blueprint $table) {
            // Platform fees (your commission)
            $table->decimal('platform_fee_fixed', 10, 2)->default(0);
            $table->decimal('platform_fee_percent', 5, 2)->default(0);

            // Processing fee (service/booking)
            $table->decimal('processing_fee_fixed', 10, 2)->default(0);
            $table->decimal('processing_fee_percent', 5, 2)->default(0);

            // Payment gateway fee (Stripe/PayPal/Bkash/Nagad/etc.)
            $table->decimal('payment_gateway_fee_percent', 5, 2)->default(0);
            $table->decimal('payment_gateway_fee_fixed', 10, 2)->default(0);

            // Fee distribution (who pays what)
            $table->integer('fee_customer_percent')->default(100); // customer pays all fees
            $table->integer('fee_organizer_percent')->default(0);  // organizer pays zero
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tickets', function (Blueprint $table) {
            $table->dropColumn([
                'platform_fee_fixed',
                'platform_fee_percent',
                'processing_fee_fixed',
                'processing_fee_percent',
                'payment_gateway_fee_percent',
                'payment_gateway_fee_fixed',
                'fee_customer_percent',
                'fee_organizer_percent',
            ]);
        });
    }
};
