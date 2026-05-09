<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserOrderTicketDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_own_orders(): void
    {
        [$user, $order] = $this->createCustomerOrder();

        $this->actingAs($user)
            ->get(route('user.orders.index'))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee($order->event->name);
    }

    public function test_authenticated_user_cannot_view_another_users_order(): void
    {
        [, $order] = $this->createCustomerOrder();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('user.orders.show', $order))
            ->assertNotFound();
    }

    public function test_authenticated_user_can_view_own_tickets(): void
    {
        [$user, , $ticket] = $this->createCustomerOrder();

        $this->actingAs($user)
            ->get(route('user.tickets.index'))
            ->assertOk()
            ->assertSee($ticket->ticket_code)
            ->assertSee($ticket->event->name);
    }

    public function test_authenticated_user_cannot_view_another_users_ticket(): void
    {
        [, , $ticket] = $this->createCustomerOrder();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)
            ->get(route('user.tickets.show', $ticket))
            ->assertNotFound();
    }

    public function test_pending_paid_order_detail_shows_pay_now_link(): void
    {
        [$user, $order] = $this->createCustomerOrder(price: 500, orderStatus: Order::STATUS_PENDING_PAYMENT, paymentStatus: Order::PAYMENT_UNPAID);

        $this->actingAs($user)
            ->get(route('user.orders.show', $order))
            ->assertOk()
            ->assertSee('Pay Now with Stripe')
            ->assertSee(route('frontend.payment.stripe', $order), false);
    }

    public function test_completed_order_detail_shows_ticket_codes(): void
    {
        [$user, $order, $ticket] = $this->createCustomerOrder(price: 0, orderStatus: Order::STATUS_COMPLETED, paymentStatus: Order::PAYMENT_PAID);

        $this->actingAs($user)
            ->get(route('user.orders.show', $order))
            ->assertOk()
            ->assertSee($ticket->ticket_code)
            ->assertSee('General admission');
    }

    public function test_guest_is_redirected_to_login_for_customer_dashboard_routes(): void
    {
        [, $order, $ticket] = $this->createCustomerOrder();

        $this->get(route('user.dashboard'))->assertRedirect(route('login'));
        $this->get(route('user.orders.index'))->assertRedirect(route('login'));
        $this->get(route('user.orders.show', $order))->assertRedirect(route('login'));
        $this->get(route('user.tickets.index'))->assertRedirect(route('login'));
        $this->get(route('user.tickets.show', $ticket))->assertRedirect(route('login'));
    }

    public function test_no_old_booking_or_temporary_booking_routes_are_used(): void
    {
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
    }

    protected function createCustomerOrder(float $price = 0, string $orderStatus = Order::STATUS_COMPLETED, string $paymentStatus = Order::PAYMENT_PAID): array
    {
        $user = User::factory()->create();
        $organizerUser = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $organizerUser->id,
            'organization_name' => 'Customer Test Organizer',
            'slug' => 'customer-test-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $eventType = EventType::create([
            'name' => 'Customer Test Type',
            'slug' => 'customer-test-type-' . uniqid(),
            'status' => 'active',
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizerUser->id,
            'name' => 'Customer Test Venue',
            'slug' => 'customer-test-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'name' => 'Customer Test Event ' . uniqid(),
            'slug' => 'customer-test-event-' . uniqid(),
            'description' => 'Customer order ticket dashboard test event.',
            'start_date' => now()->addWeek()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => $venue->name,
            'organizer_name' => $organizer->organization_name,
            'status' => Event::STATUS_PUBLISHED,
            'approved_at' => now(),
        ]);

        $eventTicket = EventTicket::create([
            'event_id' => $event->id,
            'name' => $price > 0 ? 'Paid Ticket' : 'Free Ticket',
            'ticket_type' => $price > 0 ? EventTicket::TYPE_PAID : EventTicket::TYPE_FREE,
            'price' => $price,
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
            'user_id' => $user->id,
            'event_id' => $event->id,
            'order_number' => 'ORD-USER-TEST-' . strtoupper(uniqid()),
            'subtotal' => $price,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => $price,
            'currency' => 'BDT',
            'status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'event_ticket_id' => $eventTicket->id,
            'ticket_type_id' => $eventTicket->id,
            'ticket_name' => $eventTicket->name,
            'quantity' => 1,
            'unit_price' => $price,
            'subtotal' => $price,
        ]);

        $ticket = OrderTicket::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'event_id' => $event->id,
            'event_ticket_id' => $eventTicket->id,
            'ticket_type_id' => $eventTicket->id,
            'ticket_code' => 'TKT-USER-' . strtoupper(uniqid()),
            'qr_payload' => 'ORDER:' . $order->order_number . '|EVENT:' . $event->id,
            'attendee_name' => $user->name,
            'attendee_email' => $user->email,
            'status' => OrderTicket::STATUS_ISSUED,
        ]);

        return [$user, $order->fresh('event'), $ticket->fresh('event')];
    }
}
