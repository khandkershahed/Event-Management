<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->index('pt_order_idx');
                $table->string('provider', 50)->default('stripe')->index('pt_provider_idx');
                $table->string('provider_session_id')->nullable()->index('pt_session_idx');
                $table->string('provider_payment_intent_id')->nullable()->index('pt_pi_idx');
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('currency', 10)->default('BDT');
                $table->string('status', 50)->default('pending')->index('pt_status_idx');
                $table->json('raw_payload')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->foreign('order_id', 'pt_order_fk')
                    ->references('id')
                    ->on('orders')
                    ->cascadeOnDelete();
            });

            return;
        }

        Schema::table('payment_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('payment_transactions', 'order_id')) {
                $table->unsignedBigInteger('order_id')->index('pt_order_idx');
            }
            if (! Schema::hasColumn('payment_transactions', 'provider')) {
                $table->string('provider', 50)->default('stripe')->index('pt_provider_idx');
            }
            if (! Schema::hasColumn('payment_transactions', 'provider_session_id')) {
                $table->string('provider_session_id')->nullable()->index('pt_session_idx');
            }
            if (! Schema::hasColumn('payment_transactions', 'provider_payment_intent_id')) {
                $table->string('provider_payment_intent_id')->nullable()->index('pt_pi_idx');
            }
            if (! Schema::hasColumn('payment_transactions', 'amount')) {
                $table->decimal('amount', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('payment_transactions', 'currency')) {
                $table->string('currency', 10)->default('BDT');
            }
            if (! Schema::hasColumn('payment_transactions', 'status')) {
                $table->string('status', 50)->default('pending')->index('pt_status_idx');
            }
            if (! Schema::hasColumn('payment_transactions', 'raw_payload')) {
                $table->json('raw_payload')->nullable();
            }
            if (! Schema::hasColumn('payment_transactions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
