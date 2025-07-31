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
        Schema::create('page_banners', function (Blueprint $table) {
            $table->id();
            $table->string('page_name', 100)->nullable();
            $table->string('slug', 150)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('bg_image', 255)->nullable();
            $table->string('badge', 191)->nullable();
            $table->string('button_name', 200)->nullable();
            $table->text('button_link')->nullable();
            $table->text('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('banner_link')->nullable();
            $table->string('status')->default('active')->comment('inactive,active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_banners');
    }
};
