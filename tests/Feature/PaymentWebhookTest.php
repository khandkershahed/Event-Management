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

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_success_updates_transaction_and_order(): void
    {
        $order = $this->createPendingOrder();
        $transaction = PaymentTransaction::create([
            'order_id' => $order->id,
            'provider' => PaymentTransaction::PROVIDER_STRIPE,
            'provider_session_id' => 'cs_test_success_' . uniqid(),
            'amount' => $order->total,
            'currency' => $order->currency,
            'status' => PaymentTransaction::STATUS_PENDING,
        ]);

        $payload = [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => $transaction->provider_session_id,
                    'payment_intent' => 'pi_test_' . uniqid(),
                    'client_reference_id' => (string) $order->id,
                    'metadata' => [
                        'order_id' => (string) $order->id,
                        'order_number' => $order->order_number,
                    ],
                ],
            ],
        ];

        $this->postJson(route('stripe.webhook'), $payload)->assertOk()->assertJson(['handled' => true]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
        ]);
        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transaction->id,
            'status' => PaymentTransaction::STATUS_PAID,
            'provider_payment_intent_id' => $payload['data']['object']['payment_intent'],
        ]);
    }

    public function test_failed_webhook_does_not_mark_order_paid(): void
    {
        $order = $this->createPendingOrder();
        $transaction = PaymentTransaction::create([
            'order_id' => $order->id,
            'provider' => PaymentTransaction::PROVIDER_STRIPE,
            'provider_session_id' => 'cs_test_failed_' . uniqid(),
            'amount' => $order->total,
            'currency' => $order->currency,
            'status' => PaymentTransaction::STATUS_PENDING,
        ]);

        $payload = [
            'type' => 'checkout.session.expired',
            'data' => [
                'object' => [
                    'id' => $transaction->provider_session_id,
                    'client_reference_id' => (string) $order->id,
                    'metadata' => ['order_id' => (string) $order->id],
                ],
            ],
        ];

        $this->postJson(route('stripe.webhook'), $payload)->assertOk()->assertJson(['handled' => true]);

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

    public function test_unknown_webhook_is_ignored_safely(): void
    {
        $payload = [
            'type' => 'customer.created',
            'data' => ['object' => ['id' => 'cus_test']],
        ];

        $this->postJson(route('stripe.webhook'), $payload)
            ->assertOk()
            ->assertJson(['handled' => false]);
    }

    protected function createPendingOrder(): Order
    {
        $ticket = $this->createPublishedTicket();
        $order = Order::create([
            'event_id' => $ticket->event_id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'subtotal' => 120,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => 120,
            'currency' => 'BDT',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_status' => Order::PAYMENT_UNPAID,
            'customer_name' => 'Webhook Customer',
            'customer_email' => 'webhook@example.com',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_name' => $ticket->name,
            'quantity' => 1,
            'unit_price' => 120,
            'subtotal' => 120,
        ]);

        OrderTicket::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'event_id' => $ticket->event_id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_code' => 'TKT-' . strtoupper(uniqid()),
            'qr_payload' => 'webhook-payload',
            'status' => OrderTicket::STATUS_ISSUED,
        ]);

        return $order;
    }

    protected function createPublishedTicket(): EventTicket
    {
        $user = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Webhook Organizer',
            'slug' => 'webhook-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
        $eventType = EventType::create(['name' => 'Webhook Type', 'slug' => 'webhook-type-' . uniqid(), 'status' => 'active']);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'organizer_id' => $organizer->user_id,
            'name' => 'Webhook Venue',
            'slug' => 'webhook-venue-' . uniqid(),
            'city' => 'Dhaka',
        ]);
        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'venue_id' => $venue->id,
            'name' => 'Webhook Event ' . uniqid(),
            'slug' => 'webhook-event-' . uniqid(),
            'description' => 'Webhook event description.',
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
            'name' => 'Webhook Paid Ticket',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 120,
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
