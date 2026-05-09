<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('platform_commission_settings')) {
            Schema::create('platform_commission_settings', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('Default Commission');
                $table->string('commission_type', 20)->default('percent')->index('pcs_type_idx');
                $table->decimal('commission_value', 12, 2)->default(5);
                $table->boolean('is_active')->default(true)->index('pcs_active_idx');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('platform_commission_settings', function (Blueprint $table) {
                if (! Schema::hasColumn('platform_commission_settings', 'name')) {
                    $table->string('name')->default('Default Commission');
                }
                if (! Schema::hasColumn('platform_commission_settings', 'commission_type')) {
                    $table->string('commission_type', 20)->default('percent')->index('pcs_type_idx');
                }
                if (! Schema::hasColumn('platform_commission_settings', 'commission_value')) {
                    $table->decimal('commission_value', 12, 2)->default(5);
                }
                if (! Schema::hasColumn('platform_commission_settings', 'is_active')) {
                    $table->boolean('is_active')->default(true)->index('pcs_active_idx');
                }
                if (! Schema::hasColumn('platform_commission_settings', 'description')) {
                    $table->text('description')->nullable();
                }
            });
        }

        if (! DB::table('platform_commission_settings')->where('is_active', true)->exists()) {
            DB::table('platform_commission_settings')->insert([
                'name' => 'Default Commission',
                'commission_type' => 'percent',
                'commission_value' => 5,
                'is_active' => true,
                'description' => 'Default marketplace commission used for organizer earnings.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! Schema::hasTable('organizer_ledgers')) {
            Schema::create('organizer_ledgers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organizer_profile_id')->index('ol_org_idx');
                $table->unsignedBigInteger('order_id')->nullable()->unique('ol_order_uq');
                $table->unsignedBigInteger('organizer_payout_id')->nullable()->index('ol_payout_idx');
                $table->string('type', 50)->index('ol_type_idx');
                $table->string('direction', 20)->default('credit')->index('ol_dir_idx');
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('currency', 10)->default('BDT');
                $table->string('status', 30)->default('posted')->index('ol_status_idx');
                $table->text('description')->nullable();
                $table->json('meta')->nullable();
                $table->timestamp('posted_at')->nullable();
                $table->timestamps();

                $table->foreign('organizer_profile_id', 'ol_org_fk')->references('id')->on('organizer_profiles')->cascadeOnDelete();
                $table->foreign('order_id', 'ol_order_fk')->references('id')->on('orders')->nullOnDelete();
            });
        }

        if (! Schema::hasTable('platform_commission_ledgers')) {
            Schema::create('platform_commission_ledgers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organizer_profile_id')->index('pcl_org_idx');
                $table->unsignedBigInteger('order_id')->unique('pcl_order_uq');
                $table->decimal('gross_amount', 12, 2)->default(0);
                $table->decimal('commission_amount', 12, 2)->default(0);
                $table->string('currency', 10)->default('BDT');
                $table->string('commission_type', 20)->default('percent');
                $table->decimal('commission_value', 12, 2)->default(0);
                $table->string('status', 30)->default('posted')->index('pcl_status_idx');
                $table->json('meta')->nullable();
                $table->timestamp('posted_at')->nullable();
                $table->timestamps();

                $table->foreign('organizer_profile_id', 'pcl_org_fk')->references('id')->on('organizer_profiles')->cascadeOnDelete();
                $table->foreign('order_id', 'pcl_order_fk')->references('id')->on('orders')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('organizer_payouts')) {
            Schema::create('organizer_payouts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organizer_profile_id')->index('op_org_idx');
                $table->string('payout_number')->unique('op_no_uq');
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('currency', 10)->default('BDT');
                $table->string('status', 30)->default('pending')->index('op_status_idx');
                $table->unsignedBigInteger('requested_by')->nullable()->index('op_req_by_idx');
                $table->unsignedBigInteger('reviewed_by')->nullable()->index('op_rev_by_idx');
                $table->unsignedBigInteger('paid_by')->nullable()->index('op_paid_by_idx');
                $table->text('notes')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamp('requested_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->foreign('organizer_profile_id', 'op_org_fk')->references('id')->on('organizer_profiles')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_payouts');
        Schema::dropIfExists('platform_commission_ledgers');
        Schema::dropIfExists('organizer_ledgers');
        Schema::dropIfExists('platform_commission_settings');
    }
};
