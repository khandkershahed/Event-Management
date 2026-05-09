<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventCancellationRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\PlatformCommissionLedger;
use App\Models\RefundRequest;
use App\Models\RefundTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdminMarketplaceReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_marketplace_report_dashboard(): void
    {
        $admin = $this->admin();
        $this->createReportData();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.marketplace-reports.dashboard'))
            ->assertOk()
            ->assertSee('Marketplace Reporting Dashboard');
    }

    public function test_dashboard_shows_kpi_labels(): void
    {
        $admin = $this->admin();
        $this->createReportData();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.marketplace-reports.dashboard'))
            ->assertOk()
            ->assertSee('Total Organizers')
            ->assertSee('Pending Organizers')
            ->assertSee('Total Events')
            ->assertSee('Pending Event Approvals')
            ->assertSee('Published Events')
            ->assertSee('Total Orders')
            ->assertSee('Paid Orders')
            ->assertSee('Gross Sales')
            ->assertSee('Platform Commission')
            ->assertSee('Pending Payouts')
            ->assertSee('Refund Requests')
            ->assertSee('Event Cancellation Requests');
    }

    public function test_admin_can_open_sales_by_date_report(): void
    {
        $admin = $this->admin();
        $this->createReportData();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.marketplace-reports.sales-by-date'))
            ->assertOk()
            ->assertSee('Sales by Date')
            ->assertSee('Gross Sales');
    }

    public function test_admin_can_open_sales_by_organizer_report(): void
    {
        $admin = $this->admin();
        $this->createReportData();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.marketplace-reports.sales-by-organizer'))
            ->assertOk()
            ->assertSee('Sales by Organizer')
            ->assertSee('Report Organizer');
    }

    public function test_admin_can_open_sales_by_event_report(): void
    {
        $admin = $this->admin();
        $data = $this->createReportData();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.marketplace-reports.sales-by-event'))
            ->assertOk()
            ->assertSee('Sales by Event')
            ->assertSee($data['event']->name);
    }

    public function test_admin_can_open_commission_report(): void
    {
        $admin = $this->admin();
        $this->createReportData();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.marketplace-reports.commissions'))
            ->assertOk()
            ->assertSee('Commission Report')
            ->assertSee('posted');
    }

    public function test_admin_can_open_payout_report(): void
    {
        $admin = $this->admin();
        $this->createReportData();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.marketplace-reports.payouts'))
            ->assertOk()
            ->assertSee('Payout Report')
            ->assertSee('PO-TEST-001');
    }

    public function test_admin_can_open_refund_report(): void
    {
        $admin = $this->admin();
        $this->createReportData();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.marketplace-reports.refunds'))
            ->assertOk()
            ->assertSee('Refund Report')
            ->assertSee('Refund Status');
    }

    public function test_filters_do_not_crash(): void
    {
        $admin = $this->admin();
        $data = $this->createReportData();

        $query = [
            'date_from' => now()->subDay()->toDateString(),
            'date_to' => now()->addDay()->toDateString(),
            'organizer_id' => $data['organizer']->id,
            'event_id' => $data['event']->id,
            'status' => 'posted',
        ];

        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.dashboard', $query))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.sales-by-date', $query))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.sales-by-organizer', $query))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.sales-by-event', $query))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.commissions', $query))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.payouts', array_merge($query, ['status' => OrganizerPayout::STATUS_PENDING])))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.refunds', array_merge($query, ['status' => RefundRequest::STATUS_PENDING])))->assertOk();

        $badQuery = ['date_from' => 'not-a-date', 'date_to' => 'also-bad', 'organizer_id' => 'bad', 'event_id' => 'bad', 'status' => 'bad'];
        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.refunds', $badQuery))->assertOk();
    }

    public function test_guest_cannot_access_admin_marketplace_reports(): void
    {
        $this->get(route('admin.marketplace-reports.dashboard'))
            ->assertRedirect(route('login'));
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
            'name' => 'Marketplace Admin',
            'email' => 'marketplace-report-admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    private function createReportData(): array
    {
        $owner = User::factory()->create(['email' => 'report-organizer-owner@example.com']);
        $customer = User::factory()->create(['email' => 'report-customer@example.com']);

        $organizer = OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => 'Report Organizer',
            'slug' => 'report-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        OrganizerProfile::create([
            'user_id' => User::factory()->create()->id,
            'organization_name' => 'Pending Report Organizer',
            'slug' => 'pending-report-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_PENDING,
            'submitted_at' => now(),
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Report Event',
            'slug' => 'report-event-' . uniqid(),
            'status' => Event::STATUS_PUBLISHED,
            'start_date' => now()->addDays(10)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'venue' => 'Report Venue',
        ]);

        Event::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Report Pending Event',
            'slug' => 'report-pending-event-' . uniqid(),
            'status' => Event::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'event_id' => $event->id,
            'order_number' => 'ORD-REPORT-001',
            'subtotal' => 500,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => 500,
            'currency' => 'BDT',
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
            'customer_name' => 'Report Customer',
            'customer_email' => 'report-customer@example.com',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'ticket_name' => 'General Admission',
            'unit_price' => 500,
            'quantity' => 1,
            'subtotal' => 500,
        ]);

        OrderTicket::create([
            'event_id' => $event->id,
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'ticket_code' => 'REPORT-TICKET-001',
            'qr_payload' => 'REPORT-TICKET-001',
            'attendee_name' => 'Report Attendee',
            'attendee_email' => 'attendee@example.com',
            'status' => OrderTicket::STATUS_ISSUED,
        ]);

        PlatformCommissionLedger::create([
            'organizer_profile_id' => $organizer->id,
            'order_id' => $order->id,
            'gross_amount' => 500,
            'commission_amount' => 50,
            'currency' => 'BDT',
            'commission_type' => 'percent',
            'commission_value' => 10,
            'status' => PlatformCommissionLedger::STATUS_POSTED,
            'posted_at' => now(),
        ]);

        OrganizerPayout::create([
            'organizer_profile_id' => $organizer->id,
            'payout_number' => 'PO-TEST-001',
            'amount' => 100,
            'currency' => 'BDT',
            'status' => OrganizerPayout::STATUS_PENDING,
            'requested_by' => $owner->id,
            'requested_at' => now(),
        ]);

        $refund = RefundRequest::create([
            'order_id' => $order->id,
            'user_id' => $customer->id,
            'organizer_profile_id' => $organizer->id,
            'event_id' => $event->id,
            'requested_by_type' => User::class,
            'requested_by_id' => $customer->id,
            'reason' => 'Test refund request.',
            'amount' => 100,
            'currency' => 'BDT',
            'status' => RefundRequest::STATUS_PENDING,
        ]);

        RefundTransaction::create([
            'refund_request_id' => $refund->id,
            'order_id' => $order->id,
            'provider' => 'manual',
            'amount' => 100,
            'currency' => 'BDT',
            'status' => RefundTransaction::STATUS_PENDING,
        ]);

        EventCancellationRequest::create([
            'event_id' => $event->id,
            'organizer_profile_id' => $organizer->id,
            'requested_by' => $owner->id,
            'reason' => 'Test cancellation request.',
            'status' => EventCancellationRequest::STATUS_PENDING,
        ]);

        return compact('organizer', 'event', 'order');
    }
}
