<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_tickets', function (Blueprint $table) {
            if (! Schema::hasColumn('event_tickets', 'ticket_type')) {
                $table->string('ticket_type', 30)->default('paid')->after('description');
            }

            if (! Schema::hasColumn('event_tickets', 'currency')) {
                $table->string('currency', 10)->default('BDT')->after('price');
            }

            if (! Schema::hasColumn('event_tickets', 'sold_quantity')) {
                $table->unsignedInteger('sold_quantity')->default(0)->after('quantity');
            }

            if (! Schema::hasColumn('event_tickets', 'sales_start_at')) {
                $table->timestamp('sales_start_at')->nullable()->after('max_per_order');
            }

            if (! Schema::hasColumn('event_tickets', 'sales_end_at')) {
                $table->timestamp('sales_end_at')->nullable()->after('sales_start_at');
            }

            if (! Schema::hasColumn('event_tickets', 'visibility')) {
                $table->string('visibility', 30)->default('public')->after('sales_end_at');
            }

            if (! Schema::hasColumn('event_tickets', 'status')) {
                $table->string('status', 30)->default('active')->after('visibility');
            }

            if (! Schema::hasColumn('event_tickets', 'platform_fee_type')) {
                $table->string('platform_fee_type', 30)->default('none')->after('status');
            }

            if (! Schema::hasColumn('event_tickets', 'platform_fee_value')) {
                $table->decimal('platform_fee_value', 10, 2)->default(0)->after('platform_fee_type');
            }

            if (! Schema::hasColumn('event_tickets', 'organizer_absorbs_fee')) {
                $table->boolean('organizer_absorbs_fee')->default(false)->after('platform_fee_value');
            }

            if (! Schema::hasColumn('event_tickets', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            $table->index(['event_id', 'status'], 'et_event_status_idx');
            $table->index(['event_id', 'visibility'], 'et_event_visibility_idx');
            $table->index(['sales_start_at', 'sales_end_at'], 'et_sales_window_idx');
        });
    }

    public function down(): void
    {
        Schema::table('event_tickets', function (Blueprint $table) {
            $table->dropIndex('et_event_status_idx');
            $table->dropIndex('et_event_visibility_idx');
            $table->dropIndex('et_sales_window_idx');
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            $dropColumns = [
                'ticket_type',
                'currency',
                'sold_quantity',
                'sales_start_at',
                'sales_end_at',
                'visibility',
                'status',
                'platform_fee_type',
                'platform_fee_value',
                'organizer_absorbs_fee',
                'deleted_at',
            ];

            foreach ($dropColumns as $column) {
                if (Schema::hasColumn('event_tickets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
