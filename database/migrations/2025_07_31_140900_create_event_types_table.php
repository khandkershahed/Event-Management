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
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('code', 220)->nullable();
            $table->string('serial', 220)->nullable();
            $table->string('logo', 220)->nullable();
            $table->string('image', 220)->nullable();
            $table->string('banner_image', 220)->nullable();
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
        Schema::dropIfExists('event_types');
    }
};
