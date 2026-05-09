<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\TicketCheckIn;
use Illuminate\Database\Seeder;

class OrganizerReportSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::publiclyVisible()->first();

        if (! $event) {
            return;
        }

        $ticket = OrderTicket::where('event_id', $event->id)
            ->whereHas('order', fn ($query) => $query->where('payment_status', Order::PAYMENT_PAID))
            ->first();

        if (! $ticket || TicketCheckIn::where('order_ticket_id', $ticket->id)->exists()) {
            return;
        }

        $ticket->forceFill([
            'status' => OrderTicket::STATUS_USED,
            'is_checked_in' => true,
            'checked_in_at' => now(),
        ])->save();

        TicketCheckIn::create([
            'order_ticket_id' => $ticket->id,
            'event_id' => $event->id,
            'checked_in_at' => $ticket->checked_in_at,
            'result' => TicketCheckIn::RESULT_VALID,
            'scanned_code' => $ticket->ticket_code,
            'notes' => 'Demo attendee check-in for organizer reports.',
        ]);
    }
}
