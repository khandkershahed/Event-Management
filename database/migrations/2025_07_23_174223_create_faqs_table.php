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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('faq_categories')->onDelete('set null');
            $table->string('question');
            $table->text('answer');
            $table->string('tag')->nullable();
            $table->integer('order')->default(0);
            $table->string('status')->default('active')->comment('inactive,active');
            $table->integer('views')->default(0)->nullable();
            $table->json('related_faqs')->nullable(); // IDs of related FAQs
            $table->boolean('is_featured')->default(false)->nullable(); // Mark FAQ as featured
            $table->text('additional_info')->nullable(); // Additional information or notes
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
