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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->string('booking_id')->unique(); //unique id auto generated
            $table->string('user_name');
            $table->string('user_email');
            $table->string('invoice_number')->unique();
            $table->json('event_seats');
            $table->dateTime('event_datetime');
            $table->string('status')->default('confirmed'); // or enum
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_type')->nullable(); //Credit Card, Bank Transfer
            $table->string('card_type')->nullable(); //Visa, Master card
            $table->string('purchase_date')->nullable();
            $table->string('billing_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_address')->nullable();
            $table->timestamp('paid_at')->nullable(); // time payment was made
            $table->string('payment_transaction_id')->nullable(); // transaction reference from stripe
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
