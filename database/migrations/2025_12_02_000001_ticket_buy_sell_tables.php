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
        | 1. SEAT LOCKS (For guests + logged-in users)
        |--------------------------------------------------------------------------
        */
        Schema::create('seat_locks', function (Blueprint $table) {
            $table->id();

            // FIX 2: Foreign key linking to events table
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            $table->foreignId('seat_id')
                ->constrained('seating_seats')
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable(); // guest lock

            // FIX 1: Add index for faster cleanup
            $table->timestamp('expires_at')->index();

            $table->timestamps();

            // Prevent double-booking of the same seat
            $table->unique(['event_id', 'seat_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | 2. CART ITEMS (Guest & user)
        |--------------------------------------------------------------------------
        */
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // FIX 2: Strict FK
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable()->index();

            $table->foreignId('ticket_type_id')
                ->nullable()
                ->constrained('event_tickets')
                ->nullOnDelete();

            $table->foreignId('seat_id')
                ->nullable()
                ->constrained('seating_seats')
                ->nullOnDelete();

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. ORDERS
        |--------------------------------------------------------------------------
        */
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            // FIX 2: Foreign key
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            $table->string('order_number')->unique();
            $table->decimal('total', 10, 2);

            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 4. ORDER ITEMS
        |--------------------------------------------------------------------------
        */
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // snapshot data (very important in real ticketing)
            $table->string('ticket_name');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 5. ORDER TICKETS (Actual QR / seat)
        |--------------------------------------------------------------------------
        */
        Schema::create('order_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');

            $table->foreignId('seat_id')
                ->nullable()
                ->constrained('seating_seats')
                ->nullOnDelete();

            $table->string('ticket_code')->unique();

            $table->string('attendee_name')->nullable();

            $table->boolean('is_checked_in')->default(false);
            $table->timestamp('checked_in_at')->nullable();

            $table->timestamps();

            $table->index(['ticket_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_tickets');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('seat_locks');
    }
};
