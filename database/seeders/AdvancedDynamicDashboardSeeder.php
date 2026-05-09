<?php

namespace Database\Seeders;

use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerFollower;
use App\Models\OrganizerLedger;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\PlatformCommissionLedger;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdvancedDynamicDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::query()
            ->with(['organizerProfile', 'tickets'])
            ->where('slug', 'advanced-ticket-section-matrix-demo')
            ->first()
            ?: Event::query()->with(['organizerProfile', 'tickets'])->where('status', Event::STATUS_PUBLISHED)->first();

        if (! $event || ! $event->organizerProfile) {
            return;
        }

        $organizer = $event->organizerProfile;
        $ticket = $event->tickets()->first();

        if (! $ticket) {
            $ticket = EventTicket::query()->create([
                'event_id' => $event->id,
                'name' => 'Advanced Dashboard General Ticket',
                'description' => 'Dashboard demo ticket.',
                'ticket_type' => EventTicket::TYPE_PAID,
                'price' => 1200,
                'currency' => 'BDT',
                'quantity' => 500,
                'sold_quantity' => 0,
                'min_per_order' => 1,
                'max_per_order' => 6,
                'sales_start_at' => now()->subMonth(),
                'sales_end_at' => now()->addMonth(),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'is_active' => true,
                'valid_section_ids' => [],
                'platform_fee_type' => EventTicket::FEE_PERCENT,
                'platform_fee_value' => 5,
                'organizer_absorbs_fee' => false,
            ]);
        }

        $customer = User::query()->updateOrCreate(
            ['email' => 'advanced.dashboard.customer@example.com'],
            [
                'name' => 'Advanced Dashboard Customer',
                'username' => 'advanced_dashboard_customer',
                'phone' => '01700000055',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        $this->cleanup($customer, $organizer);

        OrganizerFollower::query()->updateOrCreate(
            ['user_id' => $customer->id, 'organizer_profile_id' => $organizer->id],
            ['created_at' => now()->subDays(21), 'updated_at' => now()->subDays(21)]
        );

        CustomerSavedEvent::query()->updateOrCreate(
            ['user_id' => $customer->id, 'event_id' => $event->id],
            ['source' => 'advanced_dashboard_seed']
        );

        for ($day = 29; $day >= 0; $day--) {
            $date = now()->subDays($day);
            $isPaid = $day % 5 !== 0;
            $quantity = ($day % 3) + 1;
            $unitPrice = (float) ($ticket->price ?: 1000);
            $subtotal = $quantity * $unitPrice;
            $fee = round($subtotal * 0.05, 2);
            $total = $subtotal + $fee;

            $order = Order::query()->create([
                'user_id' => $customer->id,
                'event_id' => $event->id,
                'order_number' => 'A5DASH-' . $date->format('Ymd') . '-' . str_pad((string) $day, 2, '0', STR_PAD_LEFT),
                'subtotal' => $subtotal,
                'discount_total' => 0,
                'fee_total' => $fee,
                'total' => $total,
                'currency' => 'BDT',
                'status' => $isPaid ? Order::STATUS_COMPLETED : Order::STATUS_PENDING_PAYMENT,
                'payment_status' => $isPaid ? Order::PAYMENT_PAID : Order::PAYMENT_PENDING,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $item = OrderItem::query()->create([
                'order_id' => $order->id,
                'event_ticket_id' => $ticket->id,
                'ticket_type_id' => $ticket->id,
                'ticket_name' => $ticket->name,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            if ($isPaid) {
                for ($seat = 1; $seat <= $quantity; $seat++) {
                    OrderTicket::query()->create([
                        'event_id' => $event->id,
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'event_ticket_id' => $ticket->id,
                        'ticket_type_id' => $ticket->id,
                        'ticket_code' => 'A5T-' . $date->format('Ymd') . '-' . $day . '-' . $seat . '-' . strtoupper(Str::random(4)),
                        'attendee_name' => $customer->name,
                        'attendee_email' => $customer->email,
                        'qr_payload' => 'ADVANCED-DASHBOARD-' . $order->order_number . '-' . $seat,
                        'status' => OrderTicket::STATUS_ISSUED,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }

                OrganizerLedger::query()->create([
                    'organizer_profile_id' => $organizer->id,
                    'order_id' => $order->id,
                    'type' => OrganizerLedger::TYPE_ORDER_EARNING,
                    'direction' => OrganizerLedger::DIRECTION_CREDIT,
                    'amount' => $subtotal,
                    'currency' => 'BDT',
                    'status' => OrganizerLedger::STATUS_POSTED,
                    'description' => 'Advanced dashboard demo earning for ' . $order->order_number,
                    'meta' => ['seed' => 'advanced_dashboard'],
                    'posted_at' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                PlatformCommissionLedger::query()->create([
                    'organizer_profile_id' => $organizer->id,
                    'order_id' => $order->id,
                    'gross_amount' => $subtotal,
                    'commission_amount' => $fee,
                    'currency' => 'BDT',
                    'commission_type' => 'percent',
                    'commission_value' => 5,
                    'status' => PlatformCommissionLedger::STATUS_POSTED,
                    'meta' => ['seed' => 'advanced_dashboard'],
                    'posted_at' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        $paidOrder = Order::query()->where('user_id', $customer->id)->where('payment_status', Order::PAYMENT_PAID)->latest()->first();
        $pendingOrder = Order::query()->where('user_id', $customer->id)->where('payment_status', Order::PAYMENT_PENDING)->latest()->first();

        if ($paidOrder) {
            RefundRequest::query()->create([
                'order_id' => $paidOrder->id,
                'user_id' => $customer->id,
                'organizer_profile_id' => $organizer->id,
                'event_id' => $event->id,
                'requested_by_type' => User::class,
                'requested_by_id' => $customer->id,
                'reason' => 'Advanced dashboard demo refund request.',
                'amount' => min(500, (float) $paidOrder->total),
                'currency' => 'BDT',
                'status' => RefundRequest::STATUS_PENDING,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]);
        }

        MarketplaceSupportTicket::query()->create([
            'user_id' => $customer->id,
            'organizer_profile_id' => $organizer->id,
            'event_id' => $event->id,
            'order_id' => $paidOrder?->id,
            'created_by_type' => User::class,
            'created_by_id' => $customer->id,
            'type' => MarketplaceSupportTicket::TYPE_ORDER,
            'priority' => MarketplaceSupportTicket::PRIORITY_HIGH,
            'status' => MarketplaceSupportTicket::STATUS_OPEN,
            'subject' => 'Advanced Dashboard Order Support',
            'description' => 'Demo support ticket for customer dashboard activity.',
            'last_replied_at' => now()->subDay(),
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDay(),
        ]);

        MarketplaceSupportTicket::query()->create([
            'user_id' => $customer->id,
            'organizer_profile_id' => $organizer->id,
            'event_id' => $event->id,
            'order_id' => $pendingOrder?->id,
            'created_by_type' => User::class,
            'created_by_id' => $customer->id,
            'type' => MarketplaceSupportTicket::TYPE_GENERAL,
            'priority' => MarketplaceSupportTicket::PRIORITY_NORMAL,
            'status' => MarketplaceSupportTicket::STATUS_RESOLVED,
            'subject' => 'Advanced Dashboard Resolved Support',
            'description' => 'Resolved demo support ticket for dashboard history.',
            'resolved_at' => now()->subDays(3),
            'created_at' => now()->subDays(9),
            'updated_at' => now()->subDays(3),
        ]);

        OrganizerPayout::query()->create([
            'organizer_profile_id' => $organizer->id,
            'payout_number' => 'A5-DASH-' . now()->format('YmdHis'),
            'amount' => 15000,
            'currency' => 'BDT',
            'status' => OrganizerPayout::STATUS_PAID,
            'requested_by' => $organizer->user_id,
            'notes' => 'Advanced dashboard one-month demo payout.',
            'requested_at' => now()->subDays(12),
            'approved_at' => now()->subDays(10),
            'paid_at' => now()->subDays(8),
            'created_at' => now()->subDays(12),
            'updated_at' => now()->subDays(8),
        ]);

        $this->createNotifications($customer);
    }

    private function cleanup(User $customer, OrganizerProfile $organizer): void
    {
        $orderIds = Order::query()
            ->where('user_id', $customer->id)
            ->where('order_number', 'like', 'A5DASH-%')
            ->pluck('id');

        if ($orderIds->isNotEmpty()) {
            PlatformCommissionLedger::query()->whereIn('order_id', $orderIds)->delete();
            OrganizerLedger::query()->whereIn('order_id', $orderIds)->delete();
            RefundRequest::query()->whereIn('order_id', $orderIds)->delete();
            MarketplaceSupportTicket::query()->whereIn('order_id', $orderIds)->delete();
            Order::query()->whereIn('id', $orderIds)->delete();
        }

        MarketplaceSupportTicket::query()
            ->where('user_id', $customer->id)
            ->where('subject', 'like', 'Advanced Dashboard%')
            ->delete();

        RefundRequest::query()
            ->where('user_id', $customer->id)
            ->where('reason', 'like', 'Advanced dashboard%')
            ->delete();

        OrganizerLedger::query()
            ->where('organizer_profile_id', $organizer->id)
            ->where('description', 'like', 'Advanced dashboard demo%')
            ->delete();

        OrganizerPayout::query()
            ->where('organizer_profile_id', $organizer->id)
            ->where('payout_number', 'like', 'A5-DASH-%')
            ->delete();

        DB::table('notifications')
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $customer->id)
            ->where('type', 'advanced_dashboard_demo')
            ->delete();
    }

    private function createNotifications(User $customer): void
    {
        $notifications = [
            ['title' => 'Order confirmed', 'message' => 'Your demo order has been confirmed.', 'read_at' => null],
            ['title' => 'Ticket issued', 'message' => 'Your demo ticket is ready.', 'read_at' => now()->subDays(3)],
            ['title' => 'Refund request received', 'message' => 'Your refund request is under review.', 'read_at' => null],
            ['title' => 'Support reply received', 'message' => 'Support has replied to your ticket.', 'read_at' => null],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => 'advanced_dashboard_demo',
                'category' => 'customer_dashboard',
                'notifiable_type' => User::class,
                'notifiable_id' => $customer->id,
                'data' => json_encode([
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'url' => route('user.dashboard'),
                ]),
                'read_at' => $notification['read_at'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
