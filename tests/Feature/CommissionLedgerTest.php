<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerLedger;
use App\Models\OrganizerProfile;
use App\Models\PlatformCommissionLedger;
use App\Models\PlatformCommissionSetting;
use App\Models\User;
use App\Models\Venue;
use App\Services\OrganizerLedgerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommissionLedgerTest extends TestCase
{
    use RefreshDatabase;

    public function test_paid_order_creates_organizer_and_platform_commission_ledgers(): void
    {
        PlatformCommissionSetting::create([
            'name' => 'Test Commission',
            'commission_type' => PlatformCommissionSetting::TYPE_PERCENT,
            'commission_value' => 10,
            'is_active' => true,
        ]);
        [, $organizer] = $this->createOrganizer();
        $order = $this->createOrder($organizer, 100, Order::PAYMENT_PAID, Order::STATUS_COMPLETED);

        $result = app(OrganizerLedgerService::class)->postPaidOrder($order);

        $this->assertTrue($result['posted']);
        $this->assertDatabaseHas('organizer_ledgers', [
            'organizer_profile_id' => $organizer->id,
            'order_id' => $order->id,
            'type' => OrganizerLedger::TYPE_ORDER_EARNING,
            'direction' => OrganizerLedger::DIRECTION_CREDIT,
            'amount' => 90,
        ]);
        $this->assertDatabaseHas('platform_commission_ledgers', [
            'organizer_profile_id' => $organizer->id,
            'order_id' => $order->id,
            'gross_amount' => 100,
            'commission_amount' => 10,
        ]);
    }

    public function test_same_order_is_not_posted_twice(): void
    {
        [, $organizer] = $this->createOrganizer();
        $order = $this->createOrder($organizer, 150, Order::PAYMENT_PAID, Order::STATUS_COMPLETED);

        app(OrganizerLedgerService::class)->postPaidOrder($order);
        app(OrganizerLedgerService::class)->postPaidOrder($order);

        $this->assertSame(1, OrganizerLedger::where('order_id', $order->id)->count());
        $this->assertSame(1, PlatformCommissionLedger::where('order_id', $order->id)->count());
    }

    public function test_pending_unpaid_orders_do_not_create_earnings(): void
    {
        [, $organizer] = $this->createOrganizer();
        $order = $this->createOrder($organizer, 300, Order::PAYMENT_UNPAID, Order::STATUS_PENDING_PAYMENT);

        $result = app(OrganizerLedgerService::class)->postPaidOrder($order);

        $this->assertFalse($result['posted']);
        $this->assertDatabaseMissing('organizer_ledgers', ['order_id' => $order->id]);
        $this->assertDatabaseMissing('platform_commission_ledgers', ['order_id' => $order->id]);
    }

    public function test_no_old_booking_or_temporary_booking_routes_are_used(): void
    {
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
    }

    protected function createOrganizer(string $name = 'Finance Organizer'): array
    {
        $user = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => $name,
            'slug' => str()->slug($name) . '-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        return [$user, $organizer];
    }

    protected function createOrder(OrganizerProfile $organizer, float $total, string $paymentStatus, string $status): Order
    {
        $eventType = EventType::create(['name' => 'Finance Type ' . uniqid(), 'slug' => 'finance-type-' . uniqid(), 'status' => 'active']);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Finance Venue ' . uniqid(),
            'slug' => 'finance-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);
        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'name' => 'Finance Event ' . uniqid(),
            'slug' => 'finance-event-' . uniqid(),
            'description' => 'Finance test event.',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => $venue->name,
            'organizer_name' => $organizer->organization_name,
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);
        $ticket = EventTicket::create([
            'event_id' => $event->id,
            'name' => 'Finance Ticket',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => $total,
            'currency' => 'BDT',
            'quantity' => 20,
            'sold_quantity' => 1,
            'min_per_order' => 1,
            'max_per_order' => 10,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addDays(10),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
        ]);
        $customer = User::factory()->create();
        $order = Order::create([
            'user_id' => $customer->id,
            'event_id' => $event->id,
            'order_number' => 'ORD-FIN-' . strtoupper(uniqid()),
            'subtotal' => $total,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => $total,
            'currency' => 'BDT',
            'status' => $status,
            'payment_status' => $paymentStatus,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
        ]);
        $item = OrderItem::create([
            'order_id' => $order->id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_name' => $ticket->name,
            'quantity' => 1,
            'unit_price' => $total,
            'subtotal' => $total,
        ]);
        OrderTicket::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'event_id' => $event->id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_code' => 'TKT-FIN-' . strtoupper(uniqid()),
            'qr_payload' => 'finance-test-payload',
            'attendee_name' => $customer->name,
            'attendee_email' => $customer->email,
            'status' => OrderTicket::STATUS_ISSUED,
        ]);

        return $order;
    }
}
