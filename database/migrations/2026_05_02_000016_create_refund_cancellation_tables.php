<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('refund_requests')) {
            Schema::create('refund_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('organizer_profile_id')->nullable()->constrained('organizer_profiles')->nullOnDelete();
                $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
                $table->string('requested_by_type')->nullable();
                $table->unsignedBigInteger('requested_by_id')->nullable();
                $table->text('reason')->nullable();
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('currency', 10)->default('BDT');
                $table->string('status', 30)->default('pending');
                $table->text('admin_note')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
                $table->index(['order_id', 'status'], 'rr_order_status_idx');
                $table->index(['user_id', 'status'], 'rr_user_status_idx');
                $table->index(['event_id', 'status'], 'rr_event_status_idx');
            });
        }

        if (! Schema::hasTable('refund_transactions')) {
            Schema::create('refund_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('refund_request_id')->constrained('refund_requests')->cascadeOnDelete();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('payment_transaction_id')->nullable()->constrained('payment_transactions')->nullOnDelete();
                $table->string('provider')->nullable();
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('currency', 10)->default('BDT');
                $table->string('status', 30)->default('pending');
                $table->json('raw_payload')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
                $table->index(['order_id', 'status'], 'rt_order_status_idx');
                $table->index(['refund_request_id', 'status'], 'rt_ref_status_idx');
            });
        }

        if (! Schema::hasTable('event_cancellation_requests')) {
            Schema::create('event_cancellation_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
                $table->foreignId('organizer_profile_id')->constrained('organizer_profiles')->cascadeOnDelete();
                $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('reason')->nullable();
                $table->string('status', 30)->default('pending');
                $table->text('admin_note')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
                $table->index(['event_id', 'status'], 'ecr_event_status_idx');
                $table->index(['organizer_profile_id', 'status'], 'ecr_org_status_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('event_cancellation_requests');
        Schema::dropIfExists('refund_transactions');
        Schema::dropIfExists('refund_requests');
    }
};
