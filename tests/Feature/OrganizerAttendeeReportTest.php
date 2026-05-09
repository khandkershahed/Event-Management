<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\TicketCheckIn;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizerAttendeeReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_view_attendees_for_own_event(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [$event, $order, $ticket] = $this->createPaidOrderWithTicket($organizer);

        $this->actingAs($organizerUser)
            ->get(route('organizer.events.attendees.index', $event))
            ->assertOk()
            ->assertSee('Attendees')
            ->assertSee($ticket->ticket_code)
            ->assertSee($order->customer_email);
    }

    public function test_organizer_cannot_view_another_organizers_attendees(): void
    {
        [$organizerUser] = $this->createOrganizer('Owner Organizer');
        [, $otherOrganizer] = $this->createOrganizer('Other Organizer');
        [$otherEvent] = $this->createPaidOrderWithTicket($otherOrganizer);

        $this->actingAs($organizerUser)
            ->get(route('organizer.events.attendees.index', $otherEvent))
            ->assertNotFound();
    }

    public function test_organizer_can_export_own_event_attendees_csv(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [$event, , $ticket] = $this->createPaidOrderWithTicket($organizer);

        $response = $this->actingAs($organizerUser)
            ->get(route('organizer.events.attendees.export', $event));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
        $this->assertStringContainsString($ticket->ticket_code, $response->streamedContent());
    }

    public function test_organizer_sales_report_shows_paid_order_and_ticket_counts(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [$event] = $this->createPaidOrderWithTicket($organizer, 2, 100);

        $this->actingAs($organizerUser)
            ->get(route('organizer.reports.events.show', $event))
            ->assertOk()
            ->assertSee('Event Sales Report')
            ->assertSee('200.00')
            ->assertSee('Paid Orders')
            ->assertSee('Issued Tickets');
    }

    public function test_check_in_counts_are_included(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [$event, , $ticket] = $this->createPaidOrderWithTicket($organizer);

        $ticket->forceFill([
            'is_checked_in' => true,
            'checked_in_at' => now(),
            'status' => OrderTicket::STATUS_USED,
        ])->save();

        TicketCheckIn::create([
            'order_ticket_id' => $ticket->id,
            'event_id' => $event->id,
            'checked_in_at' => now(),
            'result' => TicketCheckIn::RESULT_VALID,
            'scanned_code' => $ticket->ticket_code,
        ]);

        $this->actingAs($organizerUser)
            ->get(route('organizer.reports.events.show', $event))
            ->assertOk()
            ->assertSee('Checked-In')
            ->assertSee('1');
    }

    public function test_pending_payment_orders_are_not_counted_as_paid_revenue(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [$event] = $this->createPaidOrderWithTicket($organizer, 1, 100, Order::PAYMENT_PAID, Order::STATUS_COMPLETED);
        $this->createPaidOrderWithTicket($organizer, 1, 500, Order::PAYMENT_UNPAID, Order::STATUS_PENDING_PAYMENT, $event);

        $this->actingAs($organizerUser)
            ->get(route('organizer.reports.events.show', $event))
            ->assertOk()
            ->assertSee('100.00')
            ->assertSee('500.00');
    }

    public function test_no_old_booking_or_temporary_booking_routes_are_used(): void
    {
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
    }

    protected function createOrganizer(string $name = 'Report Organizer'): array
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

    protected function createPaidOrderWithTicket(
        OrganizerProfile $organizer,
        int $quantity = 1,
        float $unitPrice = 100,
        string $paymentStatus = Order::PAYMENT_PAID,
        string $orderStatus = Order::STATUS_COMPLETED,
        ?Event $event = null
    ): array {
        $customer = User::factory()->create();
        $event = $event ?: $this->createEvent($organizer);

        $eventTicket = EventTicket::create([
            'event_id' => $event->id,
            'name' => 'General Admission ' . uniqid(),
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => $unitPrice,
            'currency' => 'BDT',
            'quantity' => 100,
            'sold_quantity' => $quantity,
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

        $order = Order::create([
            'user_id' => $customer->id,
            'event_id' => $event->id,
            'order_number' => 'ORD-REPORT-' . strtoupper(uniqid()),
            'subtotal' => $unitPrice * $quantity,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => $unitPrice * $quantity,
            'currency' => 'BDT',
            'status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'event_ticket_id' => $eventTicket->id,
            'ticket_type_id' => $eventTicket->id,
            'ticket_name' => $eventTicket->name,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $unitPrice * $quantity,
        ]);

        $firstTicket = null;
        for ($i = 1; $i <= $quantity; $i++) {
            $firstTicket = OrderTicket::create([
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'event_id' => $event->id,
                'event_ticket_id' => $eventTicket->id,
                'ticket_type_id' => $eventTicket->id,
                'ticket_code' => 'TKT-REPORT-' . strtoupper(uniqid()),
                'qr_payload' => 'ORDER:' . $order->order_number . '|EVENT:' . $event->id . '|NO:' . $i,
                'attendee_name' => $customer->name,
                'attendee_email' => $customer->email,
                'status' => OrderTicket::STATUS_ISSUED,
            ]);
        }

        return [$event, $order, $firstTicket, $eventTicket];
    }

    protected function createEvent(OrganizerProfile $organizer): Event
    {
        $eventType = EventType::create([
            'name' => 'Report Type ' . uniqid(),
            'slug' => 'report-type-' . uniqid(),
            'status' => 'active',
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Report Venue ' . uniqid(),
            'slug' => 'report-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);

        return Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'name' => 'Report Event ' . uniqid(),
            'slug' => 'report-event-' . uniqid(),
            'description' => 'Organizer report test event.',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => $venue->name,
            'organizer_name' => $organizer->organization_name,
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);
    }
}
