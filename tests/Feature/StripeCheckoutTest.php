<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StripeCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_paid_pending_order_can_start_stripe_checkout(): void
    {
        $order = $this->createOrder(total: 150, status: Order::STATUS_PENDING_PAYMENT, paymentStatus: Order::PAYMENT_UNPAID);

        $response = $this->get(route('frontend.payment.stripe', $order));

        $response->assertRedirect();
        $this->assertStringContainsString('/payment/success/' . $order->id, $response->headers->get('Location'));
        $this->assertDatabaseHas('payment_transactions', [
            'order_id' => $order->id,
            'provider' => PaymentTransaction::PROVIDER_STRIPE,
            'amount' => 150,
            'currency' => 'BDT',
            'status' => PaymentTransaction::STATUS_PENDING,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_status' => Order::PAYMENT_PENDING,
        ]);
    }

    public function test_free_order_cannot_start_stripe_checkout(): void
    {
        $order = $this->createOrder(total: 0, status: Order::STATUS_COMPLETED, paymentStatus: Order::PAYMENT_PAID);

        $this->get(route('frontend.payment.stripe', $order))
            ->assertRedirect(route('frontend.order.success', $order));

        $this->assertDatabaseCount('payment_transactions', 0);
    }

    public function test_paid_order_success_updates_order_and_payment_status(): void
    {
        $order = $this->createOrder(total: 200, status: Order::STATUS_PENDING_PAYMENT, paymentStatus: Order::PAYMENT_UNPAID);

        $this->get(route('frontend.payment.stripe', $order))->assertRedirect();
        $transaction = PaymentTransaction::firstOrFail();

        $this->get(route('frontend.payment.success', ['order' => $order, 'session_id' => $transaction->provider_session_id]))
            ->assertRedirect(route('frontend.order.success', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
        ]);
        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transaction->id,
            'status' => PaymentTransaction::STATUS_PAID,
        ]);
    }

    public function test_cancelled_payment_does_not_mark_order_paid(): void
    {
        $order = $this->createOrder(total: 200, status: Order::STATUS_PENDING_PAYMENT, paymentStatus: Order::PAYMENT_UNPAID);

        $this->get(route('frontend.payment.stripe', $order))->assertRedirect();
        $transaction = PaymentTransaction::firstOrFail();

        $this->get(route('frontend.payment.cancel', ['order' => $order, 'session_id' => $transaction->provider_session_id]))
            ->assertRedirect(route('frontend.order.success', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_status' => Order::PAYMENT_FAILED,
        ]);
        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transaction->id,
            'status' => PaymentTransaction::STATUS_FAILED,
        ]);
    }

    public function test_old_booking_or_temp_booking_routes_are_not_used(): void
    {
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
    }

    protected function createOrder(float $total, string $status, string $paymentStatus): Order
    {
        $ticket = $this->createPublishedTicket(price: max($total, 0));
        $user = User::factory()->create();
        $this->actingAs($user);
        $sessionId = 'test-session-' . uniqid();
        $order = Order::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'event_id' => $ticket->event_id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'subtotal' => $total,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => $total,
            'currency' => 'BDT',
            'status' => $status,
            'payment_status' => $paymentStatus,
            'customer_name' => 'Stripe Customer',
            'customer_email' => 'stripe@example.com',
        ]);

        $orderItem = OrderItem::create([
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
            'order_item_id' => $orderItem->id,
            'event_id' => $ticket->event_id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_code' => 'TKT-' . strtoupper(uniqid()),
            'qr_payload' => 'test-payload',
            'attendee_name' => 'Stripe Customer',
            'attendee_email' => 'stripe@example.com',
            'status' => OrderTicket::STATUS_ISSUED,
        ]);

        $this->withSession(['_token' => csrf_token()]);
        session()->setId($sessionId);

        return $order;
    }

    protected function createPublishedTicket(float $price = 100): EventTicket
    {
        $user = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Stripe Organizer',
            'slug' => 'stripe-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
        $eventType = EventType::create(['name' => 'Stripe Type', 'slug' => 'stripe-type-' . uniqid(), 'status' => 'active']);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Stripe Venue',
            'slug' => 'stripe-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);
        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'name' => 'Stripe Event ' . uniqid(),
            'slug' => 'stripe-event-' . uniqid(),
            'description' => 'Stripe event description.',
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
            'name' => 'Stripe Paid Ticket',
            'ticket_type' => $price > 0 ? EventTicket::TYPE_PAID : EventTicket::TYPE_FREE,
            'price' => $price,
            'currency' => 'BDT',
            'quantity' => 20,
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
            'is_active' => true,
        ]);
    }
}
