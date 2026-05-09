<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('marketplace_event_reviews')) {
            Schema::create('marketplace_event_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('event_id')->constrained()->cascadeOnDelete();
                $table->foreignId('organizer_profile_id')->constrained('organizer_profiles')->cascadeOnDelete();
                $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->string('title')->nullable();
                $table->text('body')->nullable();
                $table->string('status', 30)->default('pending');
                $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->text('admin_note')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'event_id', 'order_id'], 'mer_user_evt_ord_uq');
                $table->index(['event_id', 'status'], 'mer_evt_status_idx');
                $table->index(['organizer_profile_id', 'status'], 'mer_org_status_idx');
                $table->index(['rating', 'status'], 'mer_rating_status_idx');
                $table->index('reviewed_by', 'mer_rev_by_idx');
            });
        }
        if (! Schema::hasTable('marketplace_organizer_ratings')) {
            Schema::create('marketplace_organizer_ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('organizer_profile_id')->constrained('organizer_profiles')->cascadeOnDelete();
                $table->unsignedInteger('approved_reviews_count')->default(0);
                $table->decimal('average_rating', 4, 2)->default(0);
                $table->timestamp('last_reviewed_at')->nullable();
                $table->timestamps();
                $table->unique('organizer_profile_id', 'mor_org_uq');
                $table->index('average_rating', 'mor_avg_idx');
            });
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('marketplace_organizer_ratings');
        Schema::dropIfExists('marketplace_event_reviews');
    }
};
