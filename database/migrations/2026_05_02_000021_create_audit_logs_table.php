<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('actor', 'al_actor_idx');
            $table->string('actor_guard', 30)->nullable()->index('al_guard_idx');
            $table->string('action', 120)->index('al_action_idx');
            $table->nullableMorphs('auditable', 'al_aud_idx');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('url')->nullable();
            $table->string('method', 20)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('created_at', 'al_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
