<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('venues') || Schema::hasColumn('venues', 'organizer_profile_id')) {
            return;
        }

        Schema::table('venues', function (Blueprint $table) {
            $table->unsignedBigInteger('organizer_profile_id')->nullable()->after('id');
            $table->index('organizer_profile_id', 'ven_org_idx');
            $table->foreign('organizer_profile_id', 'ven_org_fk')
                ->references('id')
                ->on('organizer_profiles')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('venues') || ! Schema::hasColumn('venues', 'organizer_profile_id')) {
            return;
        }

        Schema::table('venues', function (Blueprint $table) {
            $table->dropForeign('ven_org_fk');
            $table->dropIndex('ven_org_idx');
            $table->dropColumn('organizer_profile_id');
        });
    }
};
