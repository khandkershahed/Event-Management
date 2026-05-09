<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('organizer_payout_methods')) {
            Schema::create('organizer_payout_methods', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organizer_profile_id')->unique('opm_org_uq');
                $table->string('method_type', 30)->default('bank')->index('opm_type_idx');
                $table->string('status', 30)->default('draft')->index('opm_status_idx');
                $table->boolean('is_active')->default(true)->index('opm_active_idx');
                $table->boolean('requires_verification')->default(true);
                $table->string('currency', 10)->default('BDT');
                $table->string('account_holder_name')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('branch_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('routing_number')->nullable();
                $table->string('mobile_wallet_provider')->nullable();
                $table->string('mobile_wallet_number')->nullable();
                $table->text('organizer_note')->nullable();
                $table->text('admin_note')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable()->index('opm_rev_by_idx');
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->foreign('organizer_profile_id', 'opm_org_fk')->references('id')->on('organizer_profiles')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_payout_methods');
    }
};
