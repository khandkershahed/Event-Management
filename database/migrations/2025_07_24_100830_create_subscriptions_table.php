<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email', 100)->unique();
            $table->string('ip_address')->nullable();
            $table->string('country')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_banned')->default(0)->nullable()->comment('0 for inactive, 1 for active');
            $table->enum('status', ['subscribed', 'unsubscribed'])->default('subscribed');
            $table->boolean('promotional_email')->default(1)->nullable()->comment('0 for inactive, 1 for active');
            $table->boolean('newsletter')->default(1)->nullable()->comment('0 for inactive, 1 for active');
            $table->boolean('new_product')->default(1)->nullable()->comment('0 for inactive, 1 for active');
            $table->boolean('notification_email')->default(1)->nullable()->comment('0 for inactive, 1 for active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
