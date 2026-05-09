<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('event_tickets')) {
            Schema::table('event_tickets', function (Blueprint $table) {
                if (! Schema::hasColumn('event_tickets', 'ticket_type')) {
                    $table->string('ticket_type')->default('paid')->after('description');
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
                    $table->string('visibility')->default('public')->after('sales_end_at');
                }
                if (! Schema::hasColumn('event_tickets', 'status')) {
                    $table->string('status')->default('active')->after('visibility');
                }
                if (! Schema::hasColumn('event_tickets', 'platform_fee_type')) {
                    $table->string('platform_fee_type')->default('none')->after('valid_section_ids');
                }
                if (! Schema::hasColumn('event_tickets', 'platform_fee_value')) {
                    $table->decimal('platform_fee_value', 10, 2)->default(0)->after('platform_fee_type');
                }
                if (! Schema::hasColumn('event_tickets', 'organizer_absorbs_fee')) {
                    $table->boolean('organizer_absorbs_fee')->default(false)->after('platform_fee_value');
                }
            });

            DB::table('event_tickets')->whereNull('ticket_type')->update(['ticket_type' => 'paid']);
            DB::table('event_tickets')->where('price', '<=', 0)->update(['ticket_type' => 'free']);
            DB::table('event_tickets')->whereNull('currency')->update(['currency' => 'BDT']);
            DB::table('event_tickets')->whereNull('visibility')->update(['visibility' => 'public']);
            DB::table('event_tickets')->whereNull('status')->update(['status' => 'active']);
        }

        if (Schema::hasTable('seat_locks')) {
            Schema::table('seat_locks', function (Blueprint $table) {
                if (! Schema::hasColumn('seat_locks', 'ticket_type_id')) {
                    $table->unsignedBigInteger('ticket_type_id')->nullable()->after('seat_id');
                }
            });
        }

        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                if (! Schema::hasColumn('cart_items', 'subtotal')) {
                    $table->decimal('subtotal', 10, 2)->default(0)->after('unit_price');
                }
                if (! Schema::hasColumn('cart_items', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable()->after('subtotal');
                }
            });

            DB::table('cart_items')->whereNull('subtotal')->update([
                'subtotal' => DB::raw('unit_price * quantity'),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                if (Schema::hasColumn('cart_items', 'expires_at')) {
                    $table->dropColumn('expires_at');
                }
                if (Schema::hasColumn('cart_items', 'subtotal')) {
                    $table->dropColumn('subtotal');
                }
            });
        }

        if (Schema::hasTable('seat_locks')) {
            Schema::table('seat_locks', function (Blueprint $table) {
                if (Schema::hasColumn('seat_locks', 'ticket_type_id')) {
                    $table->dropColumn('ticket_type_id');
                }
            });
        }
    }
};
