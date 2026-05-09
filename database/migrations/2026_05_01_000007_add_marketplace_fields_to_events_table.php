<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'organizer_profile_id')) {
                $table->unsignedBigInteger('organizer_profile_id')->nullable()->after('id');
                $table->index('organizer_profile_id', 'evt_org_idx');
                $table->foreign('organizer_profile_id', 'evt_org_fk')->references('id')->on('organizer_profiles')->nullOnDelete();
            }
            if (! Schema::hasColumn('events', 'venue_id')) {
                $table->unsignedBigInteger('venue_id')->nullable()->after('venue');
                $table->index('venue_id', 'evt_ven_idx');
                $table->foreign('venue_id', 'evt_ven_fk')->references('id')->on('venues')->nullOnDelete();
            }
            if (! Schema::hasColumn('events', 'seating_plan_id')) {
                $table->unsignedBigInteger('seating_plan_id')->nullable()->after('venue_id');
                $table->index('seating_plan_id', 'evt_sp_idx');
                $table->foreign('seating_plan_id', 'evt_sp_fk')->references('id')->on('seating_plans')->nullOnDelete();
            }
            if (! Schema::hasColumn('events', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('events', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('submitted_at');
            }
            if (! Schema::hasColumn('events', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
                $table->index('approved_by', 'evt_apv_idx');
                $table->foreign('approved_by', 'evt_apv_fk')->references('id')->on('admins')->nullOnDelete();
            }
            if (! Schema::hasColumn('events', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approved_by');
            }
        });

        DB::table('events')->where('status', 'active')->update(['status' => 'published']);
        DB::table('events')->where('status', 'inactive')->update(['status' => 'draft']);
        DB::table('events')->whereNull('status')->update(['status' => 'draft']);
    }

    public function down(): void
    {
        if (! Schema::hasTable('events')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'approved_by')) {
                $table->dropForeign('evt_apv_fk');
                $table->dropIndex('evt_apv_idx');
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('events', 'organizer_profile_id')) {
                $table->dropForeign('evt_org_fk');
                $table->dropIndex('evt_org_idx');
                $table->dropColumn('organizer_profile_id');
            }
            if (Schema::hasColumn('events', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('events', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('events', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }
        });
    }
};
