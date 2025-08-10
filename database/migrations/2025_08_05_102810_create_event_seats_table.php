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
        Schema::create('event_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->foreignId('seat_type_id')->nullable()->constrained('event_seat_types')->onDelete('cascade');
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('code', 220)->nullable();
            $table->string('price', 220)->nullable();
            $table->string('row', 220)->nullable();
            $table->string('column', 220)->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active')->comment('inactive,active');
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
        Schema::dropIfExists('event_seats');
    }
};
