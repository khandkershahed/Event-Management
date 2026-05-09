<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customer_saved_events')) {
            Schema::create('customer_saved_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('event_id')->constrained()->cascadeOnDelete();
                $table->string('source', 40)->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'event_id'], 'cse_user_event_uq');
                $table->index('event_id', 'cse_event_idx');
                $table->index('created_at', 'cse_created_idx');
            });
        }

        if (! Schema::hasTable('customer_event_interests')) {
            Schema::create('customer_event_interests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('interest_type', 40);
                $table->string('interest_value', 120);
                $table->unsignedInteger('weight')->default(1);
                $table->timestamp('last_recorded_at')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'interest_type', 'interest_value'], 'cei_user_type_val_uq');
                $table->index(['interest_type', 'interest_value'], 'cei_type_val_idx');
                $table->index('last_recorded_at', 'cei_last_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_event_interests');
        Schema::dropIfExists('customer_saved_events');
    }
};
