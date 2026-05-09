<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerLedger;
use App\Models\OrganizerPayout;
use App\Models\OrganizerPayoutMethod;
use App\Models\OrganizerProfile;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MarketplaceSecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'array']);
    }

    public function test_audit_record_is_created_when_admin_approves_event(): void
    {
        $admin = $this->admin();
        $data = $this->createOrganizerEventData(Event::STATUS_SUBMITTED);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.event-approvals.approve', $data['event']))
            ->assertRedirect(route('admin.event-approvals.show', $data['event']));

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.event.approved',
            'actor_type' => Admin::class,
            'actor_id' => $admin->id,
            'auditable_type' => Event::class,
            'auditable_id' => $data['event']->id,
        ]);
    }

    public function test_audit_record_is_created_when_admin_rejects_event(): void
    {
        $admin = $this->admin();
        $data = $this->createOrganizerEventData(Event::STATUS_SUBMITTED);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.event-approvals.reject', $data['event']), ['rejection_reason' => 'Need better details.'])
            ->assertRedirect(route('admin.event-approvals.show', $data['event']));

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.event.rejected',
            'actor_type' => Admin::class,
            'actor_id' => $admin->id,
            'auditable_type' => Event::class,
            'auditable_id' => $data['event']->id,
        ]);
    }

    public function test_audit_record_is_created_when_admin_approves_and_rejects_refund(): void
    {
        $admin = $this->admin();
        $approved = $this->createRefundReadyData('approve');
        $rejected = $this->createRefundReadyData('reject');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.refunds.approve', $approved['refund']), ['admin_note' => 'Approved.'])
            ->assertRedirect(route('admin.refunds.show', $approved['refund']));

        $this->actingAs($admin, 'admin')
            ->post(route('admin.refunds.reject', $rejected['refund']), ['admin_note' => 'Rejected.'])
            ->assertRedirect(route('admin.refunds.show', $rejected['refund']));

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.refund.approved',
            'auditable_type' => RefundRequest::class,
            'auditable_id' => $approved['refund']->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.refund.rejected',
            'auditable_type' => RefundRequest::class,
            'auditable_id' => $rejected['refund']->id,
        ]);
    }

    public function test_audit_record_is_created_when_admin_approves_rejects_and_marks_payout_paid(): void
    {
        $admin = $this->admin();
        $approved = $this->createPayoutReadyData('approve');
        $rejected = $this->createPayoutReadyData('reject');
        $paid = $this->createPayoutReadyData('paid');

        $this->actingAs($admin, 'admin')->post(route('admin.payouts.approve', $approved['payout']))->assertRedirect(route('admin.payouts.show', $approved['payout']));
        $this->actingAs($admin, 'admin')->post(route('admin.payouts.reject', $rejected['payout']), ['rejection_reason' => 'Invalid bank.'])->assertRedirect(route('admin.payouts.show', $rejected['payout']));
        $this->actingAs($admin, 'admin')->post(route('admin.payouts.mark-paid', $paid['payout']))->assertRedirect(route('admin.payouts.show', $paid['payout']));

        $this->assertDatabaseHas('audit_logs', ['action' => 'admin.payout.approved', 'auditable_type' => OrganizerPayout::class, 'auditable_id' => $approved['payout']->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'admin.payout.rejected', 'auditable_type' => OrganizerPayout::class, 'auditable_id' => $rejected['payout']->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'admin.payout.paid', 'auditable_type' => OrganizerPayout::class, 'auditable_id' => $paid['payout']->id]);
    }

    public function test_audit_record_is_created_when_organizer_requests_payout(): void
    {
        $data = $this->createOrganizerEventData(Event::STATUS_PUBLISHED);
        $this->createVerifiedPayoutMethod($data['organizer']);
        $this->creditOrganizer($data['organizer'], 500);

        $this->actingAs($data['owner'])
            ->post(route('organizer.payouts.store'), ['amount' => 100, 'notes' => 'Security audit payout.'])
            ->assertRedirect(route('organizer.payouts.index'));

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'organizer.payout.requested',
            'actor_type' => User::class,
            'actor_id' => $data['owner']->id,
        ]);
    }

    public function test_audit_record_is_created_when_organizer_submits_and_publishes_event(): void
    {
        $draft = $this->createOrganizerEventData(Event::STATUS_DRAFT);
        $approved = $this->createOrganizerEventData(Event::STATUS_APPROVED, 'approved-publish');

        $this->actingAs($draft['owner'])
            ->post(route('organizer.events.submit', $draft['event']))
            ->assertRedirect(route('organizer.events.show', $draft['event']));

        $this->actingAs($approved['owner'])
            ->post(route('organizer.events.publish', $approved['event']))
            ->assertRedirect(route('organizer.events.show', $approved['event']));

        $this->assertDatabaseHas('audit_logs', ['action' => 'organizer.event.submitted', 'auditable_type' => Event::class, 'auditable_id' => $draft['event']->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'organizer.event.published', 'auditable_type' => Event::class, 'auditable_id' => $approved['event']->id]);
    }

    public function test_admin_can_view_audit_log_page(): void
    {
        $admin = $this->admin();
        AuditLog::create([
            'actor_type' => Admin::class,
            'actor_id' => $admin->id,
            'actor_guard' => 'admin',
            'action' => 'test.audit.visible',
            'description' => 'Visible audit row.',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.audit-logs.index', ['action' => 'test.audit', 'actor_guard' => 'admin']))
            ->assertOk()
            ->assertSee('Audit Logs')
            ->assertSee('test.audit.visible');
    }

    public function test_guest_cannot_view_audit_log_page(): void
    {
        $this->get(route('admin.audit-logs.index'))->assertRedirect(route('login'));
    }

    public function test_organizer_cannot_access_another_organizers_protected_resources(): void
    {
        $ownerData = $this->createOrganizerEventData(Event::STATUS_DRAFT, 'owner-event');
        $intruderData = $this->createOrganizerEventData(Event::STATUS_DRAFT, 'intruder-event');

        $this->actingAs($intruderData['owner'])
            ->get(route('organizer.events.edit', $ownerData['event']))
            ->assertForbidden();

        $this->actingAs($intruderData['owner'])
            ->post(route('organizer.events.submit', $ownerData['event']))
            ->assertForbidden();
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
            'name' => 'Security Admin',
            'email' => 'security-admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    private function createOrganizerEventData(string $eventStatus = Event::STATUS_PUBLISHED, string $suffix = 'default'): array
    {
        $owner = User::factory()->create(['email' => 'security-owner-' . $suffix . '-' . uniqid() . '@example.com']);
        $customer = User::factory()->create(['email' => 'security-customer-' . $suffix . '-' . uniqid() . '@example.com']);

        $organizer = OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => 'Step 21 Organizer ' . $suffix . ' ' . uniqid(),
            'slug' => 'step-21-organizer-' . $suffix . '-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Step 21 Event ' . $suffix . ' ' . uniqid(),
            'slug' => 'step-21-event-' . $suffix . '-' . uniqid(),
            'status' => $eventStatus,
            'submitted_at' => in_array($eventStatus, [Event::STATUS_SUBMITTED, Event::STATUS_UNDER_REVIEW], true) ? now() : null,
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'venue' => 'Step 21 Venue',
        ]);

        return compact('owner', 'customer', 'organizer', 'event');
    }

    private function createRefundReadyData(string $suffix = 'approve'): array
    {
        $data = $this->createOrganizerEventData(Event::STATUS_PUBLISHED, 'refund-' . $suffix);
        $order = Order::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'order_number' => 'ORD-AUDIT-' . strtoupper($suffix) . '-' . strtoupper(substr(uniqid(), -5)),
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
            'ticket_name' => 'Audit Refund Ticket',
            'unit_price' => 500,
            'quantity' => 1,
            'subtotal' => 500,
        ]);

        OrderTicket::create([
            'event_id' => $data['event']->id,
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'ticket_code' => 'TKT-AUDIT-' . strtoupper($suffix) . '-' . strtoupper(substr(uniqid(), -5)),
            'qr_payload' => 'audit-refund-payload',
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
            'reason' => 'Step 21 refund request.',
            'amount' => 500,
            'currency' => 'BDT',
            'status' => RefundRequest::STATUS_PENDING,
        ]);

        return array_merge($data, compact('order', 'refund'));
    }

    private function createPayoutReadyData(string $suffix): array
    {
        $data = $this->createOrganizerEventData(Event::STATUS_PUBLISHED, 'payout-' . $suffix);
        $payout = OrganizerPayout::create([
            'organizer_profile_id' => $data['organizer']->id,
            'payout_number' => 'PO-AUDIT-' . strtoupper($suffix) . '-' . strtoupper(substr(uniqid(), -5)),
            'amount' => 100,
            'currency' => 'BDT',
            'status' => OrganizerPayout::STATUS_PENDING,
            'requested_by' => $data['owner']->id,
            'requested_at' => now(),
        ]);

        return array_merge($data, compact('payout'));
    }

    private function createVerifiedPayoutMethod(OrganizerProfile $organizer): OrganizerPayoutMethod
    {
        return OrganizerPayoutMethod::create([
            'organizer_profile_id' => $organizer->id,
            'method_type' => OrganizerPayoutMethod::METHOD_BANK,
            'status' => OrganizerPayoutMethod::STATUS_VERIFIED,
            'is_active' => true,
            'requires_verification' => true,
            'currency' => 'BDT',
            'account_holder_name' => 'Security Organizer',
            'bank_name' => 'Audit Bank',
            'branch_name' => 'Main Branch',
            'account_number' => '1234567890',
            'submitted_at' => now(),
            'reviewed_at' => now(),
        ]);
    }

    private function creditOrganizer(OrganizerProfile $organizer, float $amount): OrganizerLedger
    {
        return OrganizerLedger::create([
            'organizer_profile_id' => $organizer->id,
            'type' => OrganizerLedger::TYPE_ORDER_EARNING,
            'direction' => OrganizerLedger::DIRECTION_CREDIT,
            'amount' => $amount,
            'currency' => 'BDT',
            'status' => OrganizerLedger::STATUS_POSTED,
            'description' => 'Audit test earning credit.',
            'posted_at' => now(),
        ]);
    }
}
