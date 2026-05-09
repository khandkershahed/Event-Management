<?php

namespace App\Services;

use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\SeatingSeat;

class TicketIssuanceService
{
    public function issue(Order $order, OrderItem $item, EventTicket $ticket, ?SeatingSeat $seat = null, ?string $attendeeName = null, ?string $attendeeEmail = null): OrderTicket
    {
        $ticketCode = $this->generateTicketCode();

        return OrderTicket::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'event_id' => $order->event_id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'seat_id' => $seat?->id,
            'ticket_code' => $ticketCode,
            'qr_payload' => $this->buildQrPayload($order, $item, $ticket, $ticketCode, $seat),
            'attendee_name' => $attendeeName,
            'attendee_email' => $attendeeEmail,
            'status' => OrderTicket::STATUS_ISSUED,
            'is_checked_in' => false,
        ]);
    }

    public function buildQrPayload(Order $order, OrderItem $item, EventTicket $ticket, string $ticketCode, ?SeatingSeat $seat = null): string
    {
        return json_encode([
            'ticket_code' => $ticketCode,
            'order_number' => $order->order_number,
            'order_id' => $order->id,
            'event_id' => $order->event_id,
            'event_ticket_id' => $ticket->id,
            'order_item_id' => $item->id,
            'seat_id' => $seat?->id,
            'issued_at' => now()->toIso8601String(),
        ], JSON_UNESCAPED_SLASHES);
    }

    protected function generateTicketCode(): string
    {
        do {
            $code = 'TKT-' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        } while (OrderTicket::where('ticket_code', $code)->exists());

        return $code;
    }
}
