<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\TicketIssuanceService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDemoSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::publiclyVisible()->with('publicTickets')->first();
        $ticket = $event?->publicTickets?->first();

        if (! $event || ! $ticket || Order::where('order_number', 'ORD-DEMO-FREE-001')->exists()) {
            return;
        }

        DB::transaction(function () use ($event, $ticket) {
            $order = Order::create([
                'event_id' => $event->id,
                'session_id' => 'demo-order-session',
                'order_number' => 'ORD-DEMO-FREE-001',
                'subtotal' => (float) $ticket->price,
                'discount_total' => 0,
                'fee_total' => 0,
                'total' => (float) $ticket->price,
                'currency' => $ticket->currency ?: 'BDT',
                'status' => ((float) $ticket->price <= 0) ? Order::STATUS_COMPLETED : Order::STATUS_PENDING_PAYMENT,
                'payment_status' => ((float) $ticket->price <= 0) ? Order::PAYMENT_PAID : Order::PAYMENT_UNPAID,
                'customer_name' => 'Demo Customer',
                'customer_email' => 'demo.customer@example.com',
            ]);

            $item = OrderItem::create([
                'order_id' => $order->id,
                'event_ticket_id' => $ticket->id,
                'ticket_type_id' => $ticket->id,
                'ticket_name' => $ticket->name,
                'quantity' => 1,
                'unit_price' => $ticket->price,
                'subtotal' => $ticket->price,
            ]);

            app(TicketIssuanceService::class)->issue($order, $item, $ticket, null, 'Demo Customer', 'demo.customer@example.com');
        });

        CartItem::where('session_id', 'demo-order-session')->delete();
    }
}
