<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'session_id')) {
                    $table->string('session_id')->nullable()->after('user_id')->index('ord_sid_idx');
                }
                if (! Schema::hasColumn('orders', 'subtotal')) {
                    $table->decimal('subtotal', 12, 2)->default(0)->after('order_number');
                }
                if (! Schema::hasColumn('orders', 'discount_total')) {
                    $table->decimal('discount_total', 12, 2)->default(0)->after('subtotal');
                }
                if (! Schema::hasColumn('orders', 'fee_total')) {
                    $table->decimal('fee_total', 12, 2)->default(0)->after('discount_total');
                }
                if (! Schema::hasColumn('orders', 'currency')) {
                    $table->string('currency', 10)->default('BDT')->after('total');
                }
                if (! Schema::hasColumn('orders', 'customer_name')) {
                    $table->string('customer_name')->nullable()->after('payment_status');
                }
                if (! Schema::hasColumn('orders', 'customer_email')) {
                    $table->string('customer_email')->nullable()->after('customer_name');
                }
                if (! Schema::hasColumn('orders', 'customer_phone')) {
                    $table->string('customer_phone')->nullable()->after('customer_email');
                }
                if (! Schema::hasColumn('orders', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable()->after('customer_phone');
                }
            });

            DB::table('orders')->whereNull('subtotal')->update(['subtotal' => DB::raw('total')]);
            DB::table('orders')->whereNull('currency')->update(['currency' => 'BDT']);
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (! Schema::hasColumn('order_items', 'event_ticket_id')) {
                    $table->unsignedBigInteger('event_ticket_id')->nullable()->after('order_id')->index('oi_et_idx');
                }
                if (! Schema::hasColumn('order_items', 'ticket_type_id')) {
                    $table->unsignedBigInteger('ticket_type_id')->nullable()->after('event_ticket_id')->index('oi_tt_idx');
                }
            });
        }

        if (Schema::hasTable('order_tickets')) {
            Schema::table('order_tickets', function (Blueprint $table) {
                if (! Schema::hasColumn('order_tickets', 'event_ticket_id')) {
                    $table->unsignedBigInteger('event_ticket_id')->nullable()->after('event_id')->index('ot_et_idx');
                }
                if (! Schema::hasColumn('order_tickets', 'ticket_type_id')) {
                    $table->unsignedBigInteger('ticket_type_id')->nullable()->after('event_ticket_id')->index('ot_tt_idx');
                }
                if (! Schema::hasColumn('order_tickets', 'qr_payload')) {
                    $table->text('qr_payload')->nullable()->after('ticket_code');
                }
                if (! Schema::hasColumn('order_tickets', 'attendee_email')) {
                    $table->string('attendee_email')->nullable()->after('attendee_name');
                }
                if (! Schema::hasColumn('order_tickets', 'status')) {
                    $table->string('status')->default('issued')->after('attendee_email')->index('ot_status_idx');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_tickets')) {
            Schema::table('order_tickets', function (Blueprint $table) {
                foreach (['status', 'attendee_email', 'qr_payload', 'ticket_type_id', 'event_ticket_id'] as $column) {
                    if (Schema::hasColumn('order_tickets', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                foreach (['ticket_type_id', 'event_ticket_id'] as $column) {
                    if (Schema::hasColumn('order_items', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                foreach (['expires_at', 'customer_phone', 'customer_email', 'customer_name', 'currency', 'fee_total', 'discount_total', 'subtotal', 'session_id'] as $column) {
                    if (Schema::hasColumn('orders', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
