<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seating_seats')) {
            return;
        }

        Schema::table('seating_seats', function (Blueprint $table) {
            if (! Schema::hasColumn('seating_seats', 'seating_section_id')) {
                $table->unsignedBigInteger('seating_section_id')->nullable()->after('section_id');
                $table->index('seating_section_id', 'ss_alias_idx');
            }
        });

        if (Schema::hasColumn('seating_seats', 'seating_section_id') && Schema::hasColumn('seating_seats', 'section_id')) {
            DB::table('seating_seats')
                ->whereNull('seating_section_id')
                ->update(['seating_section_id' => DB::raw('section_id')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seating_seats') && Schema::hasColumn('seating_seats', 'seating_section_id')) {
            Schema::table('seating_seats', function (Blueprint $table) {
                try {
                    $table->dropIndex('ss_alias_idx');
                } catch (Throwable $e) {
                    // Ignore missing index during rollback on partially repaired databases.
                }

                $table->dropColumn('seating_section_id');
            });
        }
    }
};
