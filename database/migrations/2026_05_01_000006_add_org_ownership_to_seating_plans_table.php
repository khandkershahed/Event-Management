<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seating_plans')) {
            return;
        }

        Schema::table('seating_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('seating_plans', 'organizer_profile_id')) {
                $table->unsignedBigInteger('organizer_profile_id')->nullable()->after('id');
                $table->index('organizer_profile_id', 'sp_org_idx');
                $table->foreign('organizer_profile_id', 'sp_org_fk')
                    ->references('id')
                    ->on('organizer_profiles')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('seating_plans', 'status')) {
                $table->string('status', 30)->default('draft')->after('name');
                $table->index('status', 'sp_status_idx');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('seating_plans')) {
            return;
        }

        Schema::table('seating_plans', function (Blueprint $table) {
            if (Schema::hasColumn('seating_plans', 'organizer_profile_id')) {
                $table->dropForeign('sp_org_fk');
                $table->dropIndex('sp_org_idx');
                $table->dropColumn('organizer_profile_id');
            }

            if (Schema::hasColumn('seating_plans', 'status')) {
                $table->dropIndex('sp_status_idx');
                $table->dropColumn('status');
            }
        });
    }
};
