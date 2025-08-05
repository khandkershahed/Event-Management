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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_type_id')->nullable()->constrained('event_types')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->text('video_teaser_url')->nullable();
            $table->text('location_map_url')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('venue')->nullable();
            $table->string('organizer_name')->nullable();
            $table->string('organizer_brand')->nullable();
            $table->dateTime('purchase_deadline')->nullable();
            $table->integer('total_capacity')->nullable();
            $table->string('age_restriction')->nullable(); // e.g., "18+", "All Ages"
            $table->string('event_type')->nullable(); // Consider a separate `event_types` table if needed
            $table->text('terms_and_conditions')->nullable();
            $table->string('status')->nullable();
            $table->string('added_by', 220)->nullable();
            $table->string('updated_by', 220)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
