<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\Order;
use App\Models\OrganizerProfile;
use App\Models\SeatLock;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_place_a_free_order_from_cart(): void
    {
        $ticket = $this->createPublishedTicket(price: 0, type: EventTicket::TYPE_FREE, quantity: 20);

        $this->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 2,
        ])->assertRedirect(route('frontend.cart'));

        $response = $this->post(route('frontend.order.process'), [
            'customer_name' => 'Guest Customer',
            'customer_email' => 'guest@example.com',
        ]);

        $order = Order::first();
        $response->assertRedirect(route('frontend.order.success', $order));
        $this->assertSame(Order::STATUS_COMPLETED, $order->status);
        $this->assertSame(Order::PAYMENT_PAID, $order->payment_status);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseCount('order_tickets', 2);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_authenticated_user_can_place_an_order_from_cart(): void
    {
        $user = User::factory()->create();
        $ticket = $this->createPublishedTicket(price: 50, quantity: 20);

        $this->actingAs($user)->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 1,
        ])->assertRedirect(route('frontend.cart'));

        $this->actingAs($user)->post(route('frontend.order.process'), [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'event_id' => $ticket->event_id,
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_status' => Order::PAYMENT_UNPAID,
        ]);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseCount('order_tickets', 1);
    }

    public function test_order_items_are_created_from_cart_items_and_cart_is_cleared(): void
    {
        $ticket = $this->createPublishedTicket(price: 25, quantity: 20);

        $this->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 3,
        ]);

        $this->assertDatabaseCount('cart_items', 1);

        $this->post(route('frontend.order.process'), [
            'customer_name' => 'Guest Customer',
            'customer_email' => 'guest@example.com',
        ]);

        $this->assertDatabaseHas('order_items', [
            'event_ticket_id' => $ticket->id,
            'ticket_name' => $ticket->name,
            'quantity' => 3,
            'unit_price' => 25,
            'subtotal' => 75,
        ]);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_paid_order_remains_pending_payment(): void
    {
        $ticket = $this->createPublishedTicket(price: 100, quantity: 20);

        $this->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 1,
        ]);

        $this->post(route('frontend.order.process'), [
            'customer_name' => 'Guest Customer',
            'customer_email' => 'guest@example.com',
        ]);

        $this->assertDatabaseHas('orders', [
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_status' => Order::PAYMENT_UNPAID,
            'total' => 100,
        ]);
    }

    public function test_oversold_ticket_cannot_be_ordered(): void
    {
        $ticket = $this->createPublishedTicket(price: 10, quantity: 1, sold: 0);

        $this->post(route('frontend.cart.add'), [
            'event_ticket_id' => $ticket->id,
            'quantity' => 1,
        ])->assertRedirect(route('frontend.cart'));

        $ticket->update(['sold_quantity' => 1]);

        $this->post(route('frontend.order.process'), [
            'customer_name' => 'Guest Customer',
            'customer_email' => 'guest@example.com',
        ])->assertRedirect(route('frontend.cart'));

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_no_old_booking_or_temporary_booking_routes_are_used(): void
    {
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
    }

    protected function createPublishedTicket(float $price = 100, string $type = EventTicket::TYPE_PAID, int $quantity = 10, int $sold = 0): EventTicket
    {
        $user = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Checkout Organizer',
            'slug' => 'checkout-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
        $eventType = EventType::create(['name' => 'Checkout Type', 'slug' => 'checkout-type-' . uniqid(), 'status' => 'active']);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Checkout Venue',
            'slug' => 'checkout-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);
        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'name' => 'Checkout Event ' . uniqid(),
            'slug' => 'checkout-event-' . uniqid(),
            'description' => 'Checkout event description.',
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
            'name' => $price <= 0 ? 'Free Entry' : 'Paid Entry',
            'description' => 'Checkout ticket.',
            'ticket_type' => $type,
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
}
