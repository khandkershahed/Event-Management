<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('marketplace_support_tickets')) {
            Schema::create('marketplace_support_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number', 40)->unique('mst_no_uq');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('organizer_profile_id')->nullable();
                $table->unsignedBigInteger('event_id')->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->unsignedBigInteger('refund_request_id')->nullable();
                $table->unsignedBigInteger('organizer_payout_id')->nullable();
                $table->nullableMorphs('created_by', 'mst_creator_idx');
                $table->unsignedBigInteger('assigned_admin_id')->nullable();
                $table->string('type', 50)->default('general')->index('mst_type_idx');
                $table->string('priority', 20)->default('normal')->index('mst_pri_idx');
                $table->string('status', 40)->default('open')->index('mst_status_idx');
                $table->string('subject');
                $table->text('description');
                $table->timestamp('last_replied_at')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->timestamps();

                $table->index('user_id', 'mst_user_idx');
                $table->index('organizer_profile_id', 'mst_org_idx');
                $table->index('event_id', 'mst_event_idx');
                $table->index('order_id', 'mst_order_idx');
                $table->index('refund_request_id', 'mst_ref_idx');
                $table->index('organizer_payout_id', 'mst_payout_idx');
                $table->index('assigned_admin_id', 'mst_admin_idx');
                $table->index('created_at', 'mst_created_idx');
            });
        }

        if (! Schema::hasTable('marketplace_support_messages')) {
            Schema::create('marketplace_support_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('marketplace_support_ticket_id');
                $table->nullableMorphs('sender', 'msm_sender_idx');
                $table->string('sender_guard', 30)->nullable()->index('msm_guard_idx');
                $table->text('body');
                $table->boolean('is_internal')->default(false);
                $table->timestamps();

                $table->index('marketplace_support_ticket_id', 'msm_ticket_idx');
                $table->foreign('marketplace_support_ticket_id', 'msm_ticket_fk')
                    ->references('id')->on('marketplace_support_tickets')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('marketplace_moderation_flags')) {
            Schema::create('marketplace_moderation_flags', function (Blueprint $table) {
                $table->id();
                $table->nullableMorphs('flaggable', 'mmf_flag_idx');
                $table->string('status', 30)->default('active')->index('mmf_status_idx');
                $table->text('reason');
                $table->text('admin_note')->nullable();
                $table->unsignedBigInteger('flagged_by')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->index('flagged_by', 'mmf_flagby_idx');
                $table->index('reviewed_by', 'mmf_review_idx');
                $table->index('created_at', 'mmf_created_idx');
            });
        }

        if (! Schema::hasTable('marketplace_disputes')) {
            Schema::create('marketplace_disputes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('marketplace_support_ticket_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('organizer_profile_id')->nullable();
                $table->unsignedBigInteger('event_id')->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->unsignedBigInteger('refund_request_id')->nullable();
                $table->string('status', 40)->default('open')->index('md_status_idx');
                $table->decimal('amount', 12, 2)->nullable();
                $table->string('currency', 10)->nullable();
                $table->text('reason')->nullable();
                $table->text('resolution')->nullable();
                $table->timestamps();

                $table->index('marketplace_support_ticket_id', 'md_ticket_idx');
                $table->index('user_id', 'md_user_idx');
                $table->index('organizer_profile_id', 'md_org_idx');
                $table->index('event_id', 'md_event_idx');
                $table->index('order_id', 'md_order_idx');
                $table->index('refund_request_id', 'md_ref_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_disputes');
        Schema::dropIfExists('marketplace_moderation_flags');
        Schema::dropIfExists('marketplace_support_messages');
        Schema::dropIfExists('marketplace_support_tickets');
    }
};
