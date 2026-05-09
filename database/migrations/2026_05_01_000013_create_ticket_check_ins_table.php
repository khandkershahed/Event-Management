<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ticket_check_ins')) {
            Schema::create('ticket_check_ins', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_ticket_id')->nullable();
                $table->unsignedBigInteger('event_id')->nullable();
                $table->unsignedBigInteger('checked_in_by')->nullable();
                $table->timestamp('checked_in_at')->nullable();
                $table->string('result', 40)->default('valid');
                $table->string('scanned_code')->nullable();
                $table->text('notes')->nullable();
                $table->string('ip_address', 64)->nullable();
                $table->string('session_id')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
                $table->index(['order_ticket_id', 'result'], 'tci_ticket_result_idx');
                $table->index(['event_id', 'result'], 'tci_event_result_idx');
                $table->index('checked_in_by', 'tci_user_idx');
            });
        } else {
            Schema::table('ticket_check_ins', function (Blueprint $table) {
                if (! Schema::hasColumn('ticket_check_ins', 'order_ticket_id')) {$table->unsignedBigInteger('order_ticket_id')->nullable()->index('tci_ticket_idx');}
                if (! Schema::hasColumn('ticket_check_ins', 'event_id')) {$table->unsignedBigInteger('event_id')->nullable()->index('tci_event_idx');}
                if (! Schema::hasColumn('ticket_check_ins', 'checked_in_by')) {$table->unsignedBigInteger('checked_in_by')->nullable()->index('tci_user_idx');}
                if (! Schema::hasColumn('ticket_check_ins', 'checked_in_at')) {$table->timestamp('checked_in_at')->nullable();}
                if (! Schema::hasColumn('ticket_check_ins', 'result')) {$table->string('result', 40)->default('valid')->index('tci_result_idx');}
                if (! Schema::hasColumn('ticket_check_ins', 'scanned_code')) {$table->string('scanned_code')->nullable();}
                if (! Schema::hasColumn('ticket_check_ins', 'notes')) {$table->text('notes')->nullable();}
                if (! Schema::hasColumn('ticket_check_ins', 'ip_address')) {$table->string('ip_address', 64)->nullable();}
                if (! Schema::hasColumn('ticket_check_ins', 'session_id')) {$table->string('session_id')->nullable();}
                if (! Schema::hasColumn('ticket_check_ins', 'user_agent')) {$table->text('user_agent')->nullable();}
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_check_ins');
    }
};
