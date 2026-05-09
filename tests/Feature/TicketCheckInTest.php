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

class TicketCheckInTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_open_check_in_page(): void
    {
        [$organizerUser] = $this->createOrganizer();

        $this->actingAs($organizerUser)
            ->get(route('organizer.check-in.index'))
            ->assertOk()
            ->assertSee('Ticket Check-In');
    }

    public function test_organizer_can_validate_a_valid_ticket_for_own_event(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [, $event, $ticket] = $this->createIssuedTicket($organizer);

        $this->actingAs($organizerUser)
            ->post(route('organizer.check-in.validate'), [
                'event_id' => $event->id,
                'ticket_code' => $ticket->ticket_code,
            ])
            ->assertOk()
            ->assertSee('Check-in successful.');

        $this->assertDatabaseHas('order_tickets', [
            'id' => $ticket->id,
            'status' => OrderTicket::STATUS_USED,
            'is_checked_in' => true,
        ]);

        $this->assertDatabaseHas('ticket_check_ins', [
            'order_ticket_id' => $ticket->id,
            'event_id' => $event->id,
            'result' => TicketCheckIn::RESULT_VALID,
        ]);
    }

    public function test_duplicate_check_in_is_blocked(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [, $event, $ticket] = $this->createIssuedTicket($organizer);

        $this->actingAs($organizerUser)->post(route('organizer.check-in.validate'), [
            'event_id' => $event->id,
            'ticket_code' => $ticket->ticket_code,
        ])->assertOk();

        $this->actingAs($organizerUser)->post(route('organizer.check-in.validate'), [
            'event_id' => $event->id,
            'ticket_code' => $ticket->ticket_code,
        ])->assertOk()->assertSee('This ticket has already been checked in.');

        $this->assertSame(1, TicketCheckIn::where('order_ticket_id', $ticket->id)->where('result', TicketCheckIn::RESULT_VALID)->count());
        $this->assertSame(1, TicketCheckIn::where('order_ticket_id', $ticket->id)->where('result', TicketCheckIn::RESULT_ALREADY_CHECKED_IN)->count());
    }

    public function test_cancelled_and_refunded_ticket_cannot_be_checked_in(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [, $event, $cancelledTicket] = $this->createIssuedTicket($organizer, OrderTicket::STATUS_CANCELLED);
        [, , $refundedTicket] = $this->createIssuedTicket($organizer, OrderTicket::STATUS_REFUNDED, 'TKT-REFUND-');

        $this->actingAs($organizerUser)->post(route('organizer.check-in.validate'), [
            'event_id' => $event->id,
            'ticket_code' => $cancelledTicket->ticket_code,
        ])->assertOk()->assertSee('This ticket is cancelled.');

        $this->actingAs($organizerUser)->post(route('organizer.check-in.validate'), [
            'event_id' => $event->id,
            'ticket_code' => $refundedTicket->ticket_code,
        ])->assertOk()->assertSee('This ticket is refunded.');
    }

    public function test_organizer_cannot_check_in_another_organizers_event_ticket(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [, $ownEvent] = $this->createIssuedTicket($organizer);

        [, $otherOrganizer] = $this->createOrganizer('Other Organizer');
        [, , $otherTicket] = $this->createIssuedTicket($otherOrganizer, OrderTicket::STATUS_ISSUED, 'TKT-OTHER-');

        $this->actingAs($organizerUser)
            ->post(route('organizer.check-in.validate'), [
                'event_id' => $ownEvent->id,
                'ticket_code' => $otherTicket->ticket_code,
            ])
            ->assertOk()
            ->assertSee('Ticket belongs to a different event.');

        $this->assertDatabaseHas('ticket_check_ins', [
            'order_ticket_id' => $otherTicket->id,
            'event_id' => $ownEvent->id,
            'result' => TicketCheckIn::RESULT_WRONG_EVENT,
        ]);
    }

    public function test_invalid_ticket_code_returns_not_found_result(): void
    {
        [$organizerUser, $organizer] = $this->createOrganizer();
        [, $event] = $this->createIssuedTicket($organizer);

        $this->actingAs($organizerUser)
            ->post(route('organizer.check-in.validate'), [
                'event_id' => $event->id,
                'ticket_code' => 'INVALID-TICKET-CODE',
            ])
            ->assertOk()
            ->assertSee('No ticket was found for this code.');

        $this->assertDatabaseHas('ticket_check_ins', [
            'event_id' => $event->id,
            'result' => TicketCheckIn::RESULT_NOT_FOUND,
            'scanned_code' => 'INVALID-TICKET-CODE',
        ]);
    }

    public function test_no_old_booking_or_temporary_booking_routes_are_used(): void
    {
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
    }

    protected function createOrganizer(string $name = 'Check In Organizer'): array
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

    protected function createIssuedTicket(OrganizerProfile $organizer, string $ticketStatus = OrderTicket::STATUS_ISSUED, string $ticketPrefix = 'TKT-CHECK-'): array
    {
        $customer = User::factory()->create();
        $eventType = EventType::create([
            'name' => 'Check In Type ' . uniqid(),
            'slug' => 'check-in-type-' . uniqid(),
            'status' => 'active',
        ]);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Check In Venue ' . uniqid(),
            'slug' => 'check-in-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);
        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'name' => 'Check In Event ' . uniqid(),
            'slug' => 'check-in-event-' . uniqid(),
            'description' => 'Check-in test event.',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => $venue->name,
            'organizer_name' => $organizer->organization_name,
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);
        $eventTicket = EventTicket::create([
            'event_id' => $event->id,
            'name' => 'General Admission',
            'ticket_type' => EventTicket::TYPE_FREE,
            'price' => 0,
            'currency' => 'BDT',
            'quantity' => 50,
            'sold_quantity' => 1,
            'min_per_order' => 1,
            'max_per_order' => 5,
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
            'order_number' => 'ORD-CHECK-' . strtoupper(uniqid()),
            'subtotal' => 0,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => 0,
            'currency' => 'BDT',
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
        ]);
        $item = OrderItem::create([
            'order_id' => $order->id,
            'event_ticket_id' => $eventTicket->id,
            'ticket_type_id' => $eventTicket->id,
            'ticket_name' => $eventTicket->name,
            'quantity' => 1,
            'unit_price' => 0,
            'subtotal' => 0,
        ]);
        $ticket = OrderTicket::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'event_id' => $event->id,
            'event_ticket_id' => $eventTicket->id,
            'ticket_type_id' => $eventTicket->id,
            'ticket_code' => $ticketPrefix . strtoupper(uniqid()),
            'qr_payload' => 'ORDER:' . $order->order_number . '|EVENT:' . $event->id,
            'attendee_name' => $customer->name,
            'attendee_email' => $customer->email,
            'status' => $ticketStatus,
        ]);

        return [$order, $event, $ticket];
    }
}
