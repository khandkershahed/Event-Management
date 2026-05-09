<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerLedger;
use App\Models\PlatformCommissionLedger;
use App\Models\RefundRequest;
use App\Models\RefundTransaction;
use App\Models\User;
use App\Services\RefundService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefundWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function makePaidOrder(): Order
    {
        $this->seed();
        $user = User::query()->first() ?: User::factory()->create();
        $event = Event::query()->whereNotNull('organizer_profile_id')->firstOrFail();
        $ticketType = EventTicket::query()->where('event_id', $event->id)->firstOrFail();

        $order = Order::query()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'order_number' => 'TEST-REF-' . uniqid(),
            'subtotal' => 100,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => 100,
            'currency' => 'BDT',
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
        ]);

        OrderTicket::query()->create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'ticket_code' => 'TREF' . uniqid(),
            'qr_payload' => 'TREF|' . uniqid(),
            'status' => OrderTicket::STATUS_ISSUED,
            'attendee_name' => $user->name,
            'attendee_email' => $user->email,
        ]);

        OrganizerLedger::query()->create([
            'organizer_profile_id' => $event->organizer_profile_id,
            'order_id' => $order->id,
            'type' => OrganizerLedger::TYPE_ORDER_EARNING,
            'direction' => OrganizerLedger::DIRECTION_CREDIT,
            'amount' => 90,
            'currency' => 'BDT',
            'status' => OrganizerLedger::STATUS_POSTED,
            'description' => 'Test earning',
            'posted_at' => now(),
        ]);

        PlatformCommissionLedger::query()->create([
            'organizer_profile_id' => $event->organizer_profile_id,
            'order_id' => $order->id,
            'gross_amount' => 100,
            'commission_amount' => 10,
            'currency' => 'BDT',
            'commission_type' => 'percent',
            'commission_value' => 10,
            'status' => PlatformCommissionLedger::STATUS_POSTED,
            'posted_at' => now(),
        ]);

        return $order->fresh(['tickets', 'event']);
    }

    public function test_customer_can_open_refund_request_form_for_own_paid_order(): void
    {
        $order = $this->makePaidOrder();
        $this->actingAs($order->user)->get(route('user.orders.refund.create', $order))->assertOk();
    }

    public function test_customer_can_submit_refund_request_for_own_paid_order(): void
    {
        $order = $this->makePaidOrder();
        $this->actingAs($order->user)->post(route('user.orders.refund.store', $order), ['reason' => 'Cannot attend'])->assertRedirect(route('user.refunds.index'));
        $this->assertDatabaseHas('refund_requests', ['order_id' => $order->id, 'status' => RefundRequest::STATUS_PENDING]);
    }

    public function test_customer_cannot_request_refund_for_another_users_order(): void
    {
        $order = $this->makePaidOrder();
        $other = User::factory()->create();
        $this->actingAs($other)->get(route('user.orders.refund.create', $order))->assertNotFound();
    }

    public function test_customer_cannot_request_refund_for_unpaid_order(): void
    {
        $order = $this->makePaidOrder();
        $order->forceFill(['status' => Order::STATUS_PENDING_PAYMENT, 'payment_status' => Order::PAYMENT_UNPAID])->save();
        $this->actingAs($order->user)->post(route('user.orders.refund.store', $order), ['reason' => 'Test'])->assertSessionHasErrors();
    }

    public function test_duplicate_pending_refund_request_is_blocked(): void
    {
        $order = $this->makePaidOrder();
        $service = app(RefundService::class);
        $service->createCustomerRequest($order, $order->user, 'First');
        $this->actingAs($order->user)->post(route('user.orders.refund.store', $order), ['reason' => 'Second'])->assertSessionHasErrors();
    }

    public function test_admin_can_approve_refund_request_and_update_records(): void
    {
        $order = $this->makePaidOrder();
        $refund = app(RefundService::class)->createCustomerRequest($order, $order->user, 'Test');
        $admin = Admin::query()->firstOrFail();

        $this->actingAs($admin, 'admin')->post(route('admin.refunds.approve', $refund), ['admin_note' => 'Approved'])->assertRedirect();

        $this->assertDatabaseHas('refund_requests', ['id' => $refund->id, 'status' => RefundRequest::STATUS_APPROVED]);
        $this->assertDatabaseHas('refund_transactions', ['refund_request_id' => $refund->id, 'status' => RefundTransaction::STATUS_MANUAL]);
        $this->assertDatabaseHas('order_tickets', ['order_id' => $order->id, 'status' => OrderTicket::STATUS_REFUNDED]);
        $this->assertDatabaseHas('organizer_ledgers', ['order_id' => $order->id, 'type' => 'refund_reversal', 'direction' => OrganizerLedger::DIRECTION_DEBIT]);
    }

    public function test_admin_can_reject_refund_without_changing_tickets(): void
    {
        $order = $this->makePaidOrder();
        $refund = app(RefundService::class)->createCustomerRequest($order, $order->user, 'Test');
        $admin = Admin::query()->firstOrFail();

        $this->actingAs($admin, 'admin')->post(route('admin.refunds.reject', $refund), ['admin_note' => 'Rejected'])->assertRedirect();

        $this->assertDatabaseHas('refund_requests', ['id' => $refund->id, 'status' => RefundRequest::STATUS_REJECTED]);
        $this->assertDatabaseHas('order_tickets', ['order_id' => $order->id, 'status' => OrderTicket::STATUS_ISSUED]);
    }

    public function test_no_old_booking_or_temporary_booking_routes_are_used(): void
    {
        $routes = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');
        $this->assertStringNotContainsString('BookingController', $routes);
        $this->assertStringNotContainsString('TemporaryBooking', $routes);
        $this->assertStringNotContainsString('EventSeatController', $routes);
    }
}
