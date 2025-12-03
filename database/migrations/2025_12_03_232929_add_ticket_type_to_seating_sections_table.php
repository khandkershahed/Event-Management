<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('seating_sections', function (Blueprint $table) {
            $table->foreignId('ticket_type_id')
                ->nullable()
                ->constrained('event_tickets')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('seating_sections', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ticket_type_id');
        });
    }
};
