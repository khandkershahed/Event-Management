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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->json('category_id')->nullable()->comment('multi_id');
            $table->json('tag_id')->nullable()->comment('multi_id');
            $table->enum('featured', ['0', '1'])->default('0')->nullable();
            $table->string('type', 255)->nullable();
            $table->string('badge')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->unique()->nullable();
            $table->text('header')->nullable();
            $table->longText('short_description')->comment('text-editor')->nullable();
            $table->longText('long_description')->comment('text-editor')->nullable();
            $table->string('author')->nullable();
            $table->text('address')->nullable();
            $table->json('tags')->nullable();
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('additional_url')->nullable();
            $table->text('footer')->nullable()->comment('text-editor')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
