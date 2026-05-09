<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatLock;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeatLockTest extends TestCase
{
    use RefreshDatabase;

    public function test_seat_can_be_locked_for_one_session_or_user(): void
    {
        [$event, $ticket, $seat] = $this->createReservedSeatScenario();

        $response = $this->postJson(route('frontend.seats.lock', $event->slug), [
            'event_ticket_id' => $ticket->id,
            'seat_id' => $seat->id,
        ]);

        $response->assertOk()->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'ticket_type_id' => $ticket->id,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'event_id' => $event->id,
            'ticket_type_id' => $ticket->id,
            'seat_id' => $seat->id,
            'quantity' => 1,
        ]);
    }

    public function test_another_user_cannot_lock_an_actively_locked_seat(): void
    {
        [$event, $ticket, $seat] = $this->createReservedSeatScenario();
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->actingAs($firstUser)->postJson(route('frontend.seats.lock', $event->slug), [
            'event_ticket_id' => $ticket->id,
            'seat_id' => $seat->id,
        ])->assertOk();

        $response = $this->actingAs($secondUser)->postJson(route('frontend.seats.lock', $event->slug), [
            'event_ticket_id' => $ticket->id,
            'seat_id' => $seat->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => 'error']);
    }

    public function test_expired_seat_lock_can_be_cleaned_and_seat_becomes_available(): void
    {
        [$event, $ticket, $seat] = $this->createReservedSeatScenario();

        SeatLock::create([
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'ticket_type_id' => $ticket->id,
            'session_id' => 'expired-session',
            'expires_at' => now()->subMinute(),
        ]);

        $this->artisan('seat-locks:clear-expired')->assertExitCode(0);
        $this->assertDatabaseMissing('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $seat->id,
            'session_id' => 'expired-session',
        ]);

        $this->postJson(route('frontend.seats.lock', $event->slug), [
            'event_ticket_id' => $ticket->id,
            'seat_id' => $seat->id,
        ])->assertOk();
    }

    public function test_seat_outside_valid_ticket_section_cannot_be_locked(): void
    {
        [$event, $ticket, $seat, $outsideSeat] = $this->createReservedSeatScenario();

        $response = $this->postJson(route('frontend.seats.lock', $event->slug), [
            'event_ticket_id' => $ticket->id,
            'seat_id' => $outsideSeat->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['status' => 'error']);
        $this->assertDatabaseMissing('seat_locks', [
            'event_id' => $event->id,
            'seat_id' => $outsideSeat->id,
        ]);
    }

    public function test_no_legacy_temporary_booking_routes_are_used(): void
    {
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
    }

    private function createReservedSeatScenario(): array
    {
        $user = User::factory()->create();

        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Seat Lock Organizer',
            'slug' => 'seat-lock-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $type = EventType::create([
            'name' => 'Seat Lock Type',
            'slug' => 'seat-lock-type-' . uniqid(),
            'status' => 'active',
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Seat Lock Venue',
            'slug' => 'seat-lock-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);

        $plan = SeatingPlan::create([
            'organizer_profile_id' => $organizer->id,
            'venue_id' => $venue->id,
            'name' => 'Seat Lock Plan',
            'status' => SeatingPlan::STATUS_ACTIVE,
            'design_json' => null,
        ]);

        $validSection = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'VIP',
            'type' => 'seat',
            'capacity' => 10,
            'x' => 0,
            'y' => 0,
            'rotation' => 0,
        ]);

        $outsideSection = SeatingSection::create([
            'seating_plan_id' => $plan->id,
            'name' => 'Balcony',
            'type' => 'seat',
            'capacity' => 10,
            'x' => 0,
            'y' => 120,
            'rotation' => 0,
        ]);

        $seat = SeatingSeat::create([
            'section_id' => $validSection->id,
            'label' => 'VIP-1',
            'row_label' => 'VIP',
            'seat_number' => 1,
            'x' => 10,
            'y' => 10,
        ]);

        $outsideSeat = SeatingSeat::create([
            'section_id' => $outsideSection->id,
            'label' => 'BAL-1',
            'row_label' => 'BAL',
            'seat_number' => 1,
            'x' => 10,
            'y' => 120,
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => 'Seat Lock Published Event ' . uniqid(),
            'slug' => 'seat-lock-published-event-' . uniqid(),
            'description' => 'Seat lock test event description.',
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
            'name' => 'VIP Reserved Seat',
            'description' => 'Reserved seat ticket.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 500,
            'currency' => 'BDT',
            'quantity' => 10,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 2,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addDays(10),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'valid_section_ids' => [$validSection->id],
            'platform_fee_type' => 'none',
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
            'is_active' => true,
        ]);

        return [$event, $ticket, $seat, $outsideSeat];
    }
}
