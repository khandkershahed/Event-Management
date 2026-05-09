<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\MarketplaceEventReview;
use App\Models\MarketplaceModerationFlag;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerLedger;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\PlatformCommissionLedger;
use App\Models\RefundRequest;
use App\Models\RefundTransaction;
use App\Models\TicketCheckIn;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdvancedDashboardOperationsSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = OrganizerProfile::query()->where('status', OrganizerProfile::STATUS_APPROVED)->first();
        $event = Event::query()->with('tickets')->whereNotNull('organizer_profile_id')->where('status', Event::STATUS_PUBLISHED)->first();

        if (! $organizer || ! $event) {
            return;
        }

        $ticket = $event->tickets()->first() ?: EventTicket::query()->create([
            'event_id' => $event->id,
            'name' => 'A8 Operations General Admission',
            'description' => 'Dashboard operations seed ticket.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 750,
            'currency' => 'BDT',
            'quantity' => 1000,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 8,
            'sales_start_at' => now()->subMonth(),
            'sales_end_at' => now()->addMonth(),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
            'valid_section_ids' => [],
            'platform_fee_type' => EventTicket::FEE_PERCENT,
            'platform_fee_value' => 5,
        ]);

        $customer = User::query()->updateOrCreate(
            ['email' => 'a8.dashboard.customer@example.com'],
            [
                'name' => 'A8 Dashboard Customer',
                'username' => 'a8_dashboard_customer',
                'phone' => '01700000888',
                'city' => 'Chattogram',
                'country' => 'Bangladesh',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        $this->cleanup($customer, $organizer);
        $this->createPendingOperations($organizer, $event);

        $paidOrder = null;
        for ($day = 29; $day >= 0; $day--) {
            $date = now()->subDays($day);
            $isPaid = $day % 4 !== 0;
            $quantity = ($day % 4) + 1;
            $unitPrice = (float) ($ticket->price ?: 750);
            $subtotal = $quantity * $unitPrice;
            $fee = round($subtotal * 0.05, 2);
            $total = $subtotal + $fee;

            $order = Order::query()->create([
                'user_id' => $customer->id,
                'event_id' => $event->id,
                'order_number' => 'A8OPS-' . $date->format('Ymd') . '-' . str_pad((string) $day, 2, '0', STR_PAD_LEFT),
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
                $paidOrder = $order;
                for ($i = 1; $i <= $quantity; $i++) {
                    $orderTicket = OrderTicket::query()->create([
                        'event_id' => $event->id,
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'event_ticket_id' => $ticket->id,
                        'ticket_type_id' => $ticket->id,
                        'ticket_code' => 'A8T-' . $date->format('Ymd') . '-' . $day . '-' . $i . '-' . strtoupper(Str::random(4)),
                        'attendee_name' => $customer->name,
                        'attendee_email' => $customer->email,
                        'qr_payload' => 'A8-DASHBOARD|' . $order->order_number . '|' . $i,
                        'status' => OrderTicket::STATUS_ISSUED,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    if ($day % 6 === 0 && $i === 1) {
                        TicketCheckIn::query()->create([
                            'order_ticket_id' => $orderTicket->id,
                            'event_id' => $event->id,
                            'checked_in_by' => $organizer->user_id,
                            'checked_in_at' => $date->copy()->addHours(2),
                            'result' => TicketCheckIn::RESULT_VALID,
                            'scanned_code' => $orderTicket->ticket_code,
                            'notes' => 'A8 dashboard demo check-in.',
                            'ip_address' => '127.0.0.1',
                            'session_id' => 'a8-dashboard-seed',
                            'user_agent' => 'Seeder',
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);
                    }
                }

                OrganizerLedger::query()->create([
                    'organizer_profile_id' => $organizer->id,
                    'order_id' => $order->id,
                    'type' => OrganizerLedger::TYPE_ORDER_EARNING,
                    'direction' => OrganizerLedger::DIRECTION_CREDIT,
                    'amount' => $subtotal,
                    'currency' => 'BDT',
                    'status' => OrganizerLedger::STATUS_POSTED,
                    'description' => 'A8 dashboard operations earning for ' . $order->order_number,
                    'meta' => ['seed' => 'advanced_dashboard_operations'],
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
                    'meta' => ['seed' => 'advanced_dashboard_operations'],
                    'posted_at' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        if ($paidOrder) {
            $refund = RefundRequest::query()->create([
                'order_id' => $paidOrder->id,
                'user_id' => $customer->id,
                'organizer_profile_id' => $organizer->id,
                'event_id' => $event->id,
                'requested_by_type' => User::class,
                'requested_by_id' => $customer->id,
                'reason' => 'A8 dashboard operations pending refund request.',
                'amount' => min(500, (float) $paidOrder->total),
                'currency' => 'BDT',
                'status' => RefundRequest::STATUS_PENDING,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ]);

            RefundTransaction::query()->create([
                'refund_request_id' => $refund->id,
                'order_id' => $paidOrder->id,
                'provider' => 'manual',
                'amount' => $refund->amount,
                'currency' => 'BDT',
                'status' => RefundTransaction::STATUS_PENDING,
                'raw_payload' => ['seed' => 'advanced_dashboard_operations'],
                'processed_at' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
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
            'subject' => 'A8 Dashboard Open Support Ticket',
            'description' => 'Open support ticket for A8 dashboard testing.',
            'last_replied_at' => now()->subDay(),
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDay(),
        ]);

        OrganizerPayout::query()->create([
            'organizer_profile_id' => $organizer->id,
            'payout_number' => 'A8OPS-PENDING-' . now()->format('YmdHis'),
            'amount' => 6000,
            'currency' => 'BDT',
            'status' => OrganizerPayout::STATUS_PENDING,
            'requested_by' => $organizer->user_id,
            'notes' => 'A8 dashboard pending payout request.',
            'requested_at' => now()->subDays(2),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        OrganizerPayout::query()->create([
            'organizer_profile_id' => $organizer->id,
            'payout_number' => 'A8OPS-PAID-' . now()->format('YmdHis'),
            'amount' => 12000,
            'currency' => 'BDT',
            'status' => OrganizerPayout::STATUS_PAID,
            'requested_by' => $organizer->user_id,
            'reviewed_by' => Admin::query()->value('id'),
            'paid_by' => Admin::query()->value('id'),
            'notes' => 'A8 dashboard paid payout record.',
            'requested_at' => now()->subDays(14),
            'approved_at' => now()->subDays(12),
            'paid_at' => now()->subDays(10),
            'created_at' => now()->subDays(14),
            'updated_at' => now()->subDays(10),
        ]);

        MarketplaceEventReview::query()->updateOrCreate(
            ['user_id' => $customer->id, 'event_id' => $event->id, 'order_id' => $paidOrder?->id],
            [
                'organizer_profile_id' => $organizer->id,
                'rating' => 5,
                'title' => 'A8 Dashboard Review',
                'body' => 'Great event experience for dashboard testing.',
                'status' => MarketplaceEventReview::STATUS_APPROVED,
                'reviewed_by' => Admin::query()->value('id'),
                'reviewed_at' => now()->subDays(2),
            ]
        );

        $this->createNotifications($customer, $organizer);
    }

    private function cleanup(User $customer, OrganizerProfile $organizer): void
    {
        $orderIds = Order::query()->where('order_number', 'like', 'A8OPS-%')->pluck('id');
        if ($orderIds->isNotEmpty()) {
            RefundTransaction::query()->whereIn('order_id', $orderIds)->delete();
            RefundRequest::query()->whereIn('order_id', $orderIds)->delete();
            MarketplaceSupportTicket::query()->whereIn('order_id', $orderIds)->delete();
            PlatformCommissionLedger::query()->whereIn('order_id', $orderIds)->delete();
            OrganizerLedger::query()->whereIn('order_id', $orderIds)->delete();
            TicketCheckIn::query()->whereIn('order_ticket_id', OrderTicket::query()->whereIn('order_id', $orderIds)->pluck('id'))->delete();
            Order::query()->whereIn('id', $orderIds)->delete();
        }

        OrganizerPayout::query()->where('organizer_profile_id', $organizer->id)->where('payout_number', 'like', 'A8OPS-%')->delete();
        MarketplaceSupportTicket::query()->where('subject', 'like', 'A8 Dashboard%')->delete();
        MarketplaceModerationFlag::query()->where('reason', 'like', 'A8 dashboard%')->delete();
        Event::query()->whereIn('slug', ['a8-dashboard-pending-approval-event'])->delete();
        OrganizerProfile::query()->where('organization_name', 'A8 Pending Organizer Collective')->forceDelete();
        DB::table('notifications')->where('type', 'advanced_dashboard_operations')->delete();
    }

    private function createPendingOperations(OrganizerProfile $organizer, Event $baseEvent): void
    {
        $pendingUser = User::query()->updateOrCreate(
            ['email' => 'a8.pending.organizer@example.com'],
            ['name' => 'A8 Pending Organizer', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        OrganizerProfile::query()->updateOrCreate(
            ['user_id' => $pendingUser->id],
            [
                'organization_name' => 'A8 Pending Organizer Collective',
                'slug' => 'a8-pending-organizer-collective',
                'contact_person' => 'A8 Pending Organizer',
                'email' => $pendingUser->email,
                'phone' => '01700000999',
                'address' => 'Dhaka, Bangladesh',
                'description' => 'Pending organizer record for A8 admin dashboard counters.',
                'status' => OrganizerProfile::STATUS_PENDING,
                'submitted_at' => now()->subDay(),
            ]
        );

        $type = EventType::query()->first();
        Event::query()->updateOrCreate(
            ['slug' => 'a8-dashboard-pending-approval-event'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $type?->id,
                'venue_id' => $baseEvent->venue_id,
                'seating_plan_id' => $baseEvent->seating_plan_id,
                'name' => 'A8 Dashboard Pending Approval Event',
                'tagline' => 'Pending event for admin dashboard testing.',
                'description' => 'A8 pending event approval dashboard seed.',
                'start_date' => now()->addWeeks(3)->toDateString(),
                'end_date' => now()->addWeeks(3)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '21:00:00',
                'venue' => $baseEvent->venue,
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 300,
                'status' => Event::STATUS_SUBMITTED,
                'submitted_at' => now()->subHours(10),
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        MarketplaceModerationFlag::query()->create([
            'flaggable_type' => Event::class,
            'flaggable_id' => $baseEvent->id,
            'status' => 'active',
            'reason' => 'A8 dashboard moderation review sample.',
            'flagged_by' => Admin::query()->value('id'),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);
    }

    private function createNotifications(User $customer, OrganizerProfile $organizer): void
    {
        foreach ([
            [$customer, User::class, 'Ticket QR ready', 'Your A8 dashboard ticket QR is visible now.'],
            [$organizer->user, User::class, 'Payout request pending', 'A8 dashboard payout is waiting for admin review.'],
        ] as [$model, $type, $title, $message]) {
            if (! $model) {
                continue;
            }
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => 'advanced_dashboard_operations',
                'category' => 'dashboard_operations',
                'notifiable_type' => $type,
                'notifiable_id' => $model->id,
                'data' => json_encode(['title' => $title, 'message' => $message]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $adminId = Admin::query()->value('id');
        if ($adminId) {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => 'advanced_dashboard_operations',
                'category' => 'admin_dashboard',
                'notifiable_type' => Admin::class,
                'notifiable_id' => $adminId,
                'data' => json_encode(['title' => 'A8 review queue', 'message' => 'New organizer/event/payout/refund records are ready for review.']),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
