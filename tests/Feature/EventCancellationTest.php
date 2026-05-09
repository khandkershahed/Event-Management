<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventCancellationRequest;
use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\RefundTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCancellationTest extends TestCase
{
    use RefreshDatabase;

    private function eventWithOrganizer(): Event
    {
        $this->seed();
        return Event::query()->whereNotNull('organizer_profile_id')->firstOrFail();
    }

    public function test_organizer_can_request_cancellation_for_own_event(): void
    {
        $event = $this->eventWithOrganizer();
        $user = $event->organizerProfile->user;
        $this->actingAs($user)->post(route('organizer.events.cancellation.store', $event), ['reason' => 'Weather issue'])->assertRedirect();
        $this->assertDatabaseHas('event_cancellation_requests', ['event_id' => $event->id, 'status' => EventCancellationRequest::STATUS_PENDING]);
    }

    public function test_organizer_cannot_request_cancellation_for_another_organizers_event(): void
    {
        $event = $this->eventWithOrganizer();
        $other = User::factory()->create();
        OrganizerProfile::query()->create([
            'user_id' => $other->id,
            'organization_name' => 'Other Organizer',
            'slug' => 'other-organizer-' . uniqid(),
            'contact_person' => 'Other',
            'email' => $other->email,
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $this->actingAs($other)->post(route('organizer.events.cancellation.store', $event), ['reason' => 'Test'])->assertNotFound();
    }

    public function test_duplicate_pending_cancellation_request_is_blocked(): void
    {
        $event = $this->eventWithOrganizer();
        $user = $event->organizerProfile->user;
        $this->actingAs($user)->post(route('organizer.events.cancellation.store', $event), ['reason' => 'First']);
        $this->actingAs($user)->post(route('organizer.events.cancellation.store', $event), ['reason' => 'Second'])->assertSessionHasErrors();
    }

    public function test_admin_can_approve_event_cancellation(): void
    {
        $event = $this->eventWithOrganizer();
        $admin = Admin::query()->firstOrFail();
        $request = EventCancellationRequest::query()->create([
            'event_id' => $event->id,
            'organizer_profile_id' => $event->organizer_profile_id,
            'requested_by' => $event->organizerProfile->user_id,
            'reason' => 'Test',
            'status' => EventCancellationRequest::STATUS_PENDING,
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.event-cancellations.approve', $request), ['admin_note' => 'Approved'])->assertRedirect();

        $this->assertDatabaseHas('events', ['id' => $event->id, 'status' => Event::STATUS_CANCELLED]);
        $this->assertDatabaseHas('event_cancellation_requests', ['id' => $request->id, 'status' => EventCancellationRequest::STATUS_APPROVED]);
    }

    public function test_approved_cancellation_updates_paid_and_unpaid_orders_safely(): void
    {
        $event = $this->eventWithOrganizer();
        $admin = Admin::query()->firstOrFail();
        $user = User::query()->firstOrFail();
        $ticket = EventTicket::query()->where('event_id', $event->id)->firstOrFail();

        $paid = Order::query()->create([
            'user_id' => $user->id, 'event_id' => $event->id, 'order_number' => 'EVC-PAID-' . uniqid(),
            'subtotal' => 100, 'discount_total' => 0, 'fee_total' => 0, 'total' => 100, 'currency' => 'BDT',
            'status' => Order::STATUS_COMPLETED, 'payment_status' => Order::PAYMENT_PAID,
            'customer_name' => $user->name, 'customer_email' => $user->email,
        ]);
        $paidItem = OrderItem::query()->create([
            'order_id' => $paid->id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_name' => $ticket->name,
            'unit_price' => 100,
            'quantity' => 1,
            'subtotal' => 100,
        ]);

        OrderTicket::query()->create([
            'order_id' => $paid->id,
            'order_item_id' => $paidItem->id,
            'event_id' => $event->id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_code' => 'EVCP' . uniqid(),
            'qr_payload' => 'EVCP|' . uniqid(),
            'status' => OrderTicket::STATUS_ISSUED,
        ]);

        $unpaid = Order::query()->create([
            'user_id' => $user->id, 'event_id' => $event->id, 'order_number' => 'EVC-UNPAID-' . uniqid(),
            'subtotal' => 100, 'discount_total' => 0, 'fee_total' => 0, 'total' => 100, 'currency' => 'BDT',
            'status' => Order::STATUS_PENDING_PAYMENT, 'payment_status' => Order::PAYMENT_UNPAID,
            'customer_name' => $user->name, 'customer_email' => $user->email,
        ]);

        $request = EventCancellationRequest::query()->create([
            'event_id' => $event->id, 'organizer_profile_id' => $event->organizer_profile_id,
            'requested_by' => $event->organizerProfile->user_id, 'reason' => 'Test', 'status' => EventCancellationRequest::STATUS_PENDING,
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.event-cancellations.approve', $request))->assertRedirect();

        $this->assertDatabaseHas('orders', ['id' => $paid->id, 'payment_status' => Order::PAYMENT_REFUNDED]);
        $this->assertDatabaseHas('orders', ['id' => $unpaid->id, 'status' => Order::STATUS_CANCELLED]);
        $this->assertDatabaseHas('refund_transactions', ['order_id' => $paid->id, 'status' => RefundTransaction::STATUS_MANUAL]);
        $this->assertDatabaseMissing('refund_transactions', ['order_id' => $unpaid->id]);
    }

    public function test_admin_can_reject_event_cancellation_without_cancelling_event(): void
    {
        $event = $this->eventWithOrganizer();
        $admin = Admin::query()->firstOrFail();
        $request = EventCancellationRequest::query()->create([
            'event_id' => $event->id, 'organizer_profile_id' => $event->organizer_profile_id,
            'requested_by' => $event->organizerProfile->user_id, 'reason' => 'Test', 'status' => EventCancellationRequest::STATUS_PENDING,
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.event-cancellations.reject', $request), ['admin_note' => 'Rejected'])->assertRedirect();

        $this->assertDatabaseHas('event_cancellation_requests', ['id' => $request->id, 'status' => EventCancellationRequest::STATUS_REJECTED]);
        $this->assertDatabaseMissing('events', ['id' => $event->id, 'status' => Event::STATUS_CANCELLED]);
    }

    public function test_no_old_booking_or_temporary_booking_routes_are_used(): void
    {
        $routes = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');
        $this->assertStringNotContainsString('BookingController', $routes);
        $this->assertStringNotContainsString('TemporaryBooking', $routes);
        $this->assertStringNotContainsString('EventSeatController', $routes);
    }
}
