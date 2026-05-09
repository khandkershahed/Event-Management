<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('organizer_team_members')) {
            return;
        }

        Schema::create('organizer_team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organizer_profile_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('role', 40)->default('check_in_staff');
            $table->string('status', 40)->default('pending');
            $table->string('invite_token', 80)->nullable();
            $table->unsignedBigInteger('invited_by')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organizer_profile_id', 'status'], 'otm_org_status_idx');
            $table->index(['user_id', 'status'], 'otm_user_status_idx');
            $table->unique(['organizer_profile_id', 'email'], 'otm_org_email_uq');
            $table->foreign('organizer_profile_id', 'otm_org_fk')->references('id')->on('organizer_profiles')->cascadeOnDelete();
            $table->foreign('user_id', 'otm_user_fk')->references('id')->on('users')->nullOnDelete();
            $table->foreign('invited_by', 'otm_invited_by_fk')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_team_members');
    }
};
