<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. VENUES
        |--------------------------------------------------------------------------
        */
        Schema::create('venues', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();

            $table->integer('capacity')->nullable();

            $table->unsignedBigInteger('organizer_id')->nullable()->index();

            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        /*
        |--------------------------------------------------------------------------
        | 2. SEATING PLANS (Reusable per venue)
        |--------------------------------------------------------------------------
        */
        Schema::create('seating_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venue_id')
                ->constrained('venues')
                ->onDelete('cascade');

            $table->string('name'); // “Theater Layout”, “Concert Layout”
            $table->json('design_json')->nullable(); // JSON from seat designer UI

            $table->timestamps();
            $table->softDeletes();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. SEATING SECTIONS
        |--------------------------------------------------------------------------
        */
        Schema::create('seating_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('seating_plan_id')
                ->constrained('seating_plans')
                ->onDelete('cascade');

            $table->string('name');             // VIP, Balcony Left, GA Floor
            $table->string('type')->default('seat'); // seat/table/general_admission
            $table->integer('capacity')->default(0);

            // visual coordinates
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            $table->integer('rotation')->default(0);

            // optimization index for rendering & searching
            $table->index(['seating_plan_id', 'type']);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 4. SEATING SEATS
        |--------------------------------------------------------------------------
        */
        Schema::create('seating_seats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('section_id')
                ->constrained('seating_sections')
                ->onDelete('cascade');

            $table->string('label');            // “A-4”
            $table->string('row_label')->nullable();
            $table->integer('seat_number')->nullable();

            $table->integer('x');
            $table->integer('y');

            // for fast sorting/grouping
            $table->index(['section_id', 'row_label']);
            $table->index(['section_id', 'seat_number']);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 5. EVENTS — add seating_plan_id
        |--------------------------------------------------------------------------
        */
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'seating_plan_id')) {
                $table->foreignId('venue_id')
                    ->nullable()
                    ->after('venue')
                    ->constrained('venues')
                    ->nullOnDelete();  // event can be unlinked if plan deleted
                $table->foreignId('seating_plan_id')
                    ->nullable()
                    ->after('venue_id')
                    ->constrained('seating_plans')
                    ->nullOnDelete();  // event can be unlinked if plan deleted
            }
        });

        /*
        |--------------------------------------------------------------------------
        | 6. EVENT TICKET TYPES
        |--------------------------------------------------------------------------
        */
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');
            // Core data
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->nullable();
            $table->text('description')->nullable();
            // Seating restrictions
            $table->json('valid_section_ids')->nullable();
            // Ticket availability
            $table->boolean('is_active')->default(true);
            $table->integer('min_per_order')->default(1);
            $table->integer('max_per_order')->nullable();
            // Optional time-based pricing
            $table->timestamp('early_bird_ends_at')->nullable();

            // Index
            $table->index(['event_id', 'price']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        // drop columns from events table
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'seating_plan_id')) {
                $table->dropConstrainedForeignId('seating_plan_id');
            }
        });

        Schema::dropIfExists('event_tickets');
        Schema::dropIfExists('seating_seats');
        Schema::dropIfExists('seating_sections');
        Schema::dropIfExists('seating_plans');
        Schema::dropIfExists('venues');
    }
};
