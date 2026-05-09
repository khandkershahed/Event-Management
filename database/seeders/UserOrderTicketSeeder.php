<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\TicketIssuanceService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserOrderTicketSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::where('order_number', 'ORD-CUSTOMER-DEMO-001')->exists()) {
            return;
        }

        $event = Event::publiclyVisible()->with('publicTickets')->first();
        $ticket = $event?->publicTickets?->first();

        if (! $event || ! $ticket) {
            return;
        }

        $user = User::firstOrCreate(
            ['email' => 'customer.demo@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
            ]
        );

        $order = Order::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'order_number' => 'ORD-CUSTOMER-DEMO-001',
            'subtotal' => (float) $ticket->price,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => (float) $ticket->price,
            'currency' => $ticket->currency ?: 'BDT',
            'status' => ((float) $ticket->price <= 0) ? Order::STATUS_COMPLETED : Order::STATUS_PENDING_PAYMENT,
            'payment_status' => ((float) $ticket->price <= 0) ? Order::PAYMENT_PAID : Order::PAYMENT_UNPAID,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
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

        app(TicketIssuanceService::class)->issue($order, $item, $ticket, null, $user->name, $user->email);
    }
}
