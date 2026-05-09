<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\SeatLock;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketIssuanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_tickets_are_issued_with_unique_ticket_codes(): void
    {
        $ticket = $this->createPublishedTicket(price: 0, quantity: 20);

        $this->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 3,
        ]);

        $this->post(route('frontend.order.process'), [
            'customer_name' => 'Ticket Customer',
            'customer_email' => 'ticket@example.com',
        ]);

        $codes = OrderTicket::pluck('ticket_code')->all();

        $this->assertCount(3, $codes);
        $this->assertCount(3, array_unique($codes));
        $this->assertNotEmpty(OrderTicket::first()->qr_payload);
    }

    public function test_reserved_seat_cart_item_creates_ticket_with_seat_id_and_clears_lock(): void
    {
        [$event, $ticket, $seat] = $this->createReservedSeatScenario();

        $this->postJson(route('frontend.seats.lock', $event->slug), [
            'event_ticket_id' => $ticket->id,
            'seat_id' => $seat->id,
        ])->assertOk();

        $this->assertDatabaseHas('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'quantity' => 1,
        ]);

        $this->post(route('frontend.order.process'), [
            'customer_name' => 'Seat Customer',
            'customer_email' => 'seat@example.com',
        ])->assertRedirect();

        $this->assertDatabaseHas('order_tickets', [
            'event_id' => $event->id,
            'event_ticket_id' => $ticket->id,
            'seat_id' => $seat->id,
            'status' => OrderTicket::STATUS_ISSUED,
        ]);
        $this->assertDatabaseMissing('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
        ]);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_ticket_sold_quantity_increments_after_order_placement(): void
    {
        $ticket = $this->createPublishedTicket(price: 0, quantity: 20, sold: 2);

        $this->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 4,
        ]);

        $this->post(route('frontend.order.process'), [
            'customer_name' => 'Ticket Customer',
            'customer_email' => 'ticket@example.com',
        ]);

        $this->assertSame(6, (int) $ticket->fresh()->sold_quantity);
    }

    protected function createPublishedTicket(float $price = 100, int $quantity = 10, int $sold = 0): EventTicket
    {
        $user = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Ticket Issuance Organizer',
            'slug' => 'ticket-issuance-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
        $eventType = EventType::create(['name' => 'Ticket Issuance Type', 'slug' => 'ticket-issuance-type-' . uniqid(), 'status' => 'active']);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Ticket Issuance Venue',
            'slug' => 'ticket-issuance-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);
        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'name' => 'Ticket Issuance Event ' . uniqid(),
            'slug' => 'ticket-issuance-event-' . uniqid(),
            'description' => 'Ticket issuance event description.',
            'start_date' => now()->addWeek()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => $venue->name,
            'organizer_name' => $organizer->organization_name,
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);

        return EventTicket::create([
            'event_id' => $event->id,
            'name' => 'Free Test Ticket',
            'description' => 'Ticket issuance ticket.',
            'ticket_type' => $price <= 0 ? EventTicket::TYPE_FREE : EventTicket::TYPE_PAID,
            'price' => $price,
            'currency' => 'BDT',
            'quantity' => $quantity,
            'sold_quantity' => $sold,
            'min_per_order' => 1,
            'max_per_order' => 5,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addDays(10),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
            'is_active' => true,
        ]);
    }

    protected function createReservedSeatScenario(): array
    {
        $user = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Reserved Ticket Organizer',
            'slug' => 'reserved-ticket-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
        $eventType = EventType::create(['name' => 'Reserved Ticket Type', 'slug' => 'reserved-ticket-type-' . uniqid(), 'status' => 'active']);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Reserved Ticket Venue',
            'slug' => 'reserved-ticket-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);
        $plan = SeatingPlan::create([
            'organizer_profile_id' => $organizer->id,
            'venue_id' => $venue->id,
            'name' => 'Reserved Plan ' . uniqid(),
            'status' => SeatingPlan::STATUS_ACTIVE,
        ]);
        $section = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'Front Section',
            'code' => 'FRONT',
            'seat_type' => 'reserved',
            'rows_count' => 1,
            'seats_per_row' => 2,
            'base_price' => 0,
            'position' => 1,
        ]);
        $seat = SeatingSeat::create([
            'seating_section_id' => $section->id,
            'section_id' => $section->id,
            'row_label' => 'A',
            'seat_number' => 1,
            'label' => 'A1',
            'status' => 'available',
        ]);
        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => 'Reserved Ticket Event ' . uniqid(),
            'slug' => 'reserved-ticket-event-' . uniqid(),
            'description' => 'Reserved ticket event description.',
            'start_date' => now()->addWeek()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => $venue->name,
            'organizer_name' => $organizer->organization_name,
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);
        $ticket = EventTicket::create([
            'event_id' => $event->id,
            'name' => 'Reserved Free Seat',
            'description' => 'Reserved seat ticket.',
            'ticket_type' => EventTicket::TYPE_FREE,
            'price' => 0,
            'currency' => 'BDT',
            'quantity' => 10,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 1,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addDays(10),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'valid_section_ids' => [$section->id],
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
            'is_active' => true,
        ]);

        return [$event, $ticket, $seat];
    }
}
