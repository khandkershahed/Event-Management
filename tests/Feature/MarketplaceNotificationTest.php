<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\RefundRequest;
use App\Models\User;
use App\Notifications\EventApprovalDecisionNotification;
use App\Notifications\OrderConfirmationNotification;
use App\Notifications\PayoutDecisionNotification;
use App\Notifications\RefundDecisionNotification;
use App\Notifications\TicketDeliveryNotification;
use App\Services\OrderPlacementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MarketplaceNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'array']);
    }

    public function test_order_confirmation_creates_notification_record(): void
    {
        $data = $this->createCartReadyData();

        $this->actingAs($data['customer']);
        app(OrderPlacementService::class)->placeFromCart('step20-session', [
            'customer_name' => $data['customer']->name,
            'customer_email' => $data['customer']->email,
        ], $data['customer']->id);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $data['customer']->id,
            'type' => OrderConfirmationNotification::class,
        ]);
    }

    public function test_ticket_delivery_creates_notification_record(): void
    {
        $data = $this->createCartReadyData();

        $this->actingAs($data['customer']);
        app(OrderPlacementService::class)->placeFromCart('step20-session', [
            'customer_name' => $data['customer']->name,
            'customer_email' => $data['customer']->email,
        ], $data['customer']->id);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $data['customer']->id,
            'type' => TicketDeliveryNotification::class,
        ]);
    }

    public function test_event_approval_creates_organizer_notification(): void
    {
        $admin = $this->admin();
        $data = $this->createOrganizerEventData(Event::STATUS_SUBMITTED);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.event-approvals.approve', $data['event']))
            ->assertRedirect(route('admin.event-approvals.show', $data['event']));

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $data['owner']->id,
            'type' => EventApprovalDecisionNotification::class,
        ]);
    }

    public function test_event_rejection_creates_organizer_notification(): void
    {
        $admin = $this->admin();
        $data = $this->createOrganizerEventData(Event::STATUS_SUBMITTED);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.event-approvals.reject', $data['event']), ['rejection_reason' => 'Please improve event details.'])
            ->assertRedirect(route('admin.event-approvals.show', $data['event']));

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $data['owner']->id,
            'type' => EventApprovalDecisionNotification::class,
        ]);
    }

    public function test_refund_approval_and_rejection_create_notifications(): void
    {
        $admin = $this->admin();
        $approved = $this->createRefundReadyData();
        $rejected = $this->createRefundReadyData('reject');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.refunds.approve', $approved['refund']), ['admin_note' => 'Approved for test.'])
            ->assertRedirect(route('admin.refunds.show', $approved['refund']));

        $this->actingAs($admin, 'admin')
            ->post(route('admin.refunds.reject', $rejected['refund']), ['admin_note' => 'Rejected for test.'])
            ->assertRedirect(route('admin.refunds.show', $rejected['refund']));

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $approved['customer']->id,
            'type' => RefundDecisionNotification::class,
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $rejected['customer']->id,
            'type' => RefundDecisionNotification::class,
        ]);
    }

    public function test_payout_approval_rejection_and_paid_create_notifications(): void
    {
        $admin = $this->admin();
        $approved = $this->createPayoutReadyData('approved');
        $rejected = $this->createPayoutReadyData('rejected');
        $paid = $this->createPayoutReadyData('paid');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payouts.approve', $approved['payout']))
            ->assertRedirect(route('admin.payouts.show', $approved['payout']));

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payouts.reject', $rejected['payout']), ['rejection_reason' => 'Bank details need review.'])
            ->assertRedirect(route('admin.payouts.show', $rejected['payout']));

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payouts.mark-paid', $paid['payout']))
            ->assertRedirect(route('admin.payouts.show', $paid['payout']));

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $approved['owner']->id,
            'type' => PayoutDecisionNotification::class,
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $rejected['owner']->id,
            'type' => PayoutDecisionNotification::class,
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $paid['owner']->id,
            'type' => PayoutDecisionNotification::class,
        ]);
    }

    public function test_email_notifications_are_fakeable_without_real_smtp(): void
    {
        Notification::fake();
        $data = $this->createOrganizerEventData(Event::STATUS_SUBMITTED);

        $this->actingAs($this->admin(), 'admin')
            ->post(route('admin.event-approvals.approve', $data['event']))
            ->assertRedirect(route('admin.event-approvals.show', $data['event']));

        Notification::assertSentTo($data['owner'], EventApprovalDecisionNotification::class);
    }

    public function test_notification_pages_have_safe_access_rules(): void
    {
        $admin = $this->admin();
        $data = $this->createOrganizerEventData(Event::STATUS_APPROVED);

        $this->get(route('user.notifications.index'))->assertRedirect(route('login'));
        $this->actingAs($data['customer'])->get(route('user.notifications.index'))->assertOk()->assertSee('My Notifications');
        $this->actingAs($data['owner'])->get(route('organizer.notifications.index'))->assertOk()->assertSee('Notifications');
        $this->get(route('admin.notifications.index'))->assertRedirect(route('login'));
        $this->actingAs($admin, 'admin')->get(route('admin.notifications.index'))->assertOk()->assertSee('Admin Notifications');
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));

        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        foreach (['TemporaryBooking', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'] as $blocked) {
            $this->assertStringNotContainsString($blocked, $routeOutput);
        }
    }

    private function admin(): Admin
    {
        return Admin::create([
            'name' => 'Notification Admin',
            'email' => 'notification-admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    private function createOrganizerEventData(string $eventStatus = Event::STATUS_PUBLISHED): array
    {
        $owner = User::factory()->create(['email' => 'organizer-owner-' . uniqid() . '@example.com']);
        $customer = User::factory()->create(['email' => 'customer-' . uniqid() . '@example.com']);

        $organizer = OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => 'Step 20 Organizer ' . uniqid(),
            'slug' => 'step-20-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Step 20 Event ' . uniqid(),
            'slug' => 'step-20-event-' . uniqid(),
            'status' => $eventStatus,
            'submitted_at' => in_array($eventStatus, [Event::STATUS_SUBMITTED, Event::STATUS_UNDER_REVIEW], true) ? now() : null,
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'venue' => 'Step 20 Venue',
        ]);

        return compact('owner', 'customer', 'organizer', 'event');
    }

    private function createCartReadyData(): array
    {
        $data = $this->createOrganizerEventData(Event::STATUS_PUBLISHED);
        $ticket = EventTicket::create([
            'event_id' => $data['event']->id,
            'name' => 'Step 20 Free Ticket',
            'ticket_type' => EventTicket::TYPE_FREE,
            'price' => 0,
            'currency' => 'BDT',
            'quantity' => 20,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 5,
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        CartItem::create([
            'event_id' => $data['event']->id,
            'user_id' => $data['customer']->id,
            'session_id' => 'step20-session',
            'ticket_type_id' => $ticket->id,
            'quantity' => 1,
            'unit_price' => 0,
            'subtotal' => 0,
        ]);

        return array_merge($data, compact('ticket'));
    }

    private function createRefundReadyData(string $suffix = 'approve'): array
    {
        $data = $this->createOrganizerEventData(Event::STATUS_PUBLISHED);
        $order = Order::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'order_number' => 'ORD-NOTIFY-' . strtoupper($suffix) . '-' . strtoupper(substr(uniqid(), -5)),
            'subtotal' => 500,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => 500,
            'currency' => 'BDT',
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
            'customer_name' => $data['customer']->name,
            'customer_email' => $data['customer']->email,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'ticket_name' => 'Refund Ticket',
            'unit_price' => 500,
            'quantity' => 1,
            'subtotal' => 500,
        ]);

        OrderTicket::create([
            'event_id' => $data['event']->id,
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'ticket_code' => 'TKT-NOTIFY-' . strtoupper($suffix) . '-' . strtoupper(substr(uniqid(), -5)),
            'qr_payload' => 'refund-test-payload',
            'attendee_name' => $data['customer']->name,
            'attendee_email' => $data['customer']->email,
            'status' => OrderTicket::STATUS_ISSUED,
        ]);

        $refund = RefundRequest::create([
            'order_id' => $order->id,
            'user_id' => $data['customer']->id,
            'organizer_profile_id' => $data['organizer']->id,
            'event_id' => $data['event']->id,
            'requested_by_type' => User::class,
            'requested_by_id' => $data['customer']->id,
            'reason' => 'Step 20 refund request.',
            'amount' => 500,
            'currency' => 'BDT',
            'status' => RefundRequest::STATUS_PENDING,
        ]);

        return array_merge($data, compact('order', 'refund'));
    }

    private function createPayoutReadyData(string $suffix): array
    {
        $data = $this->createOrganizerEventData(Event::STATUS_PUBLISHED);
        $payout = OrganizerPayout::create([
            'organizer_profile_id' => $data['organizer']->id,
            'payout_number' => 'PO-NOTIFY-' . strtoupper($suffix) . '-' . strtoupper(substr(uniqid(), -5)),
            'amount' => 100,
            'currency' => 'BDT',
            'status' => OrganizerPayout::STATUS_PENDING,
            'requested_by' => $data['owner']->id,
            'requested_at' => now(),
        ]);

        return array_merge($data, compact('payout'));
    }
}
