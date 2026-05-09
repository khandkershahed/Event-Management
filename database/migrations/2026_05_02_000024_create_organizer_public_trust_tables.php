<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('organizer_followers')) {
            Schema::create('organizer_followers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('organizer_profile_id')->constrained('organizer_profiles')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'organizer_profile_id'], 'of_user_org_uq');
                $table->index('organizer_profile_id', 'of_org_idx');
                $table->index('created_at', 'of_created_idx');
            });
        }

        if (! Schema::hasTable('organizer_trust_badges')) {
            Schema::create('organizer_trust_badges', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organizer_profile_id')->constrained('organizer_profiles')->cascadeOnDelete();
                $table->string('badge_key', 60);
                $table->string('label');
                $table->text('description')->nullable();
                $table->string('status', 30)->default('approved');
                $table->boolean('is_public')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->text('admin_note')->nullable();
                $table->timestamps();

                $table->unique(['organizer_profile_id', 'badge_key'], 'otb_org_key_uq');
                $table->index(['organizer_profile_id', 'status'], 'otb_org_status_idx');
                $table->index(['badge_key', 'status'], 'otb_key_status_idx');
                $table->index('created_by', 'otb_created_by_idx');
                $table->index('reviewed_by', 'otb_reviewed_by_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_trust_badges');
        Schema::dropIfExists('organizer_followers');
    }
};
