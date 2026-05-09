<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_general_admission_ticket_quantity_to_cart(): void
    {
        $ticket = $this->createPublishedTicket(quantity: 20, min: 1, max: 5);

        $response = $this->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('frontend.cart'));
        $this->assertDatabaseHas('cart_items', [
            'event_id' => $ticket->event_id,
            'ticket_type_id' => $ticket->id,
            'quantity' => 2,
        ]);
    }

    public function test_authenticated_user_can_add_ticket_quantity_to_cart(): void
    {
        $user = User::factory()->create();
        $ticket = $this->createPublishedTicket(quantity: 20, min: 1, max: 5);

        $response = $this->actingAs($user)->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('frontend.cart'));
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'event_id' => $ticket->event_id,
            'ticket_type_id' => $ticket->id,
            'quantity' => 2,
        ]);
    }

    public function test_quantity_cannot_exceed_remaining_ticket_quantity(): void
    {
        $ticket = $this->createPublishedTicket(quantity: 2, sold: 1, min: 1, max: 5);

        $response = $this->from(route('event.details', $ticket->event->slug))->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('event.details', $ticket->event->slug));
        $this->assertDatabaseMissing('cart_items', [
            'ticket_type_id' => $ticket->id,
        ]);
    }

    public function test_quantity_must_respect_min_and_max_per_order(): void
    {
        $ticket = $this->createPublishedTicket(quantity: 20, min: 2, max: 3);

        $tooLow = $this->from(route('event.details', $ticket->event->slug))->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 1,
        ]);

        $tooLow->assertRedirect(route('event.details', $ticket->event->slug));

        $tooHigh = $this->from(route('event.details', $ticket->event->slug))->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 4,
        ]);

        $tooHigh->assertRedirect(route('event.details', $ticket->event->slug));
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_public_event_detail_route_remains_stable(): void
    {
        $ticket = $this->createPublishedTicket(quantity: 20, min: 1, max: 5);

        $response = $this->get(route('event.details', $ticket->event->slug));

        $response->assertOk();
        $response->assertSee($ticket->event->name);
        $response->assertSee($ticket->name);
    }

    private function createPublishedTicket(int $quantity = 10, int $sold = 0, int $min = 1, ?int $max = null): EventTicket
    {
        $user = User::factory()->create();

        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Cart Test Organizer',
            'slug' => 'cart-test-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $type = EventType::create([
            'name' => 'Cart Test Type',
            'slug' => 'cart-test-type-' . uniqid(),
            'status' => 'active',
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Cart Test Venue',
            'slug' => 'cart-test-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'name' => 'Cart Test Published Event ' . uniqid(),
            'slug' => 'cart-test-published-event-' . uniqid(),
            'description' => 'Cart test event description.',
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
            'name' => 'General Admission',
            'description' => 'General admission ticket.',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 100,
            'currency' => 'BDT',
            'quantity' => $quantity,
            'sold_quantity' => $sold,
            'min_per_order' => $min,
            'max_per_order' => $max,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addDays(10),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'platform_fee_type' => 'none',
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
            'is_active' => true,
        ]);
    }
}
