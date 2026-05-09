<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdvancedDashboardQrAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_real_stats_tables_and_chart_labels(): void
    {
        $this->seed();
        $admin = Admin::query()->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Admin Marketplace Operations Dashboard')
            ->assertSee('Total Users')
            ->assertSee('Pending Organizers')
            ->assertSee('Gross Sales')
            ->assertSee('Platform Commission')
            ->assertSee('Last 30 Days Sales')
            ->assertSee('Recent Orders')
            ->assertSee('Pending Event Approvals')
            ->assertSee('Open Support Tickets');
    }

    public function test_admin_sidebar_shows_active_links_and_safe_counters(): void
    {
        $this->seed();
        $admin = Admin::query()->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Pending Organizers')
            ->assertSee('Pending Event Approvals')
            ->assertSee('Payout Requests')
            ->assertSee('Support Desk')
            ->assertDontSee('Total Products')
            ->assertDontSee('Total Blogs');
    }

    public function test_organizer_dashboard_renders_scoped_stats_tables_and_chart_labels(): void
    {
        $this->seed();
        $organizer = OrganizerProfile::query()->where('status', OrganizerProfile::STATUS_APPROVED)->firstOrFail();

        $this->actingAs($organizer->user)
            ->get(route('organizer.dashboard'))
            ->assertOk()
            ->assertSee('Organizer Dashboard')
            ->assertSee('Total Events')
            ->assertSee('Revenue')
            ->assertSee('Net Earnings')
            ->assertSee('Issued Tickets')
            ->assertSee('Checked-In Tickets')
            ->assertSee('Last 30 Days Sales')
            ->assertSee('Recent Orders')
            ->assertSee('Recent Payouts')
            ->assertSee('Support Tickets');
    }

    public function test_organizer_sidebar_shows_active_links_and_staff_safe_counters(): void
    {
        $this->seed();
        $organizer = OrganizerProfile::query()->where('status', OrganizerProfile::STATUS_APPROVED)->firstOrFail();

        $this->actingAs($organizer->user)
            ->get(route('organizer.dashboard'))
            ->assertOk()
            ->assertSee('Notifications')
            ->assertSee('Support Tickets')
            ->assertSee('Orders')
            ->assertSee('Payouts')
            ->assertSee('Finance Profile');
    }

    public function test_ticket_qr_is_visible_on_ticket_order_print_and_success_pages(): void
    {
        $this->seed();
        $ticket = OrderTicket::query()->whereNotNull('qr_payload')->whereHas('order', fn ($query) => $query->whereNotNull('user_id'))->latest()->firstOrFail();
        $user = $ticket->order->user;

        $this->actingAs($user)->get(route('user.tickets.index'))->assertOk()->assertSee('ticket-qr-svg', false)->assertSee($ticket->ticket_code);
        $this->actingAs($user)->get(route('user.tickets.show', $ticket))->assertOk()->assertSee('ticket-qr-svg', false)->assertSee($ticket->qr_payload);
        $this->actingAs($user)->get(route('user.tickets.print', $ticket))->assertOk()->assertSee('ticket-qr-svg', false)->assertSee($ticket->ticket_code);
        $this->actingAs($user)->get(route('user.orders.show', $ticket->order))->assertOk()->assertSee('ticket-qr-svg', false)->assertSee($ticket->ticket_code);
        $this->actingAs($user)->get(route('frontend.order.success', $ticket->order))->assertOk()->assertSee('ticket-qr-svg', false)->assertSee($ticket->ticket_code);
    }

    public function test_advanced_dashboard_operations_seeder_is_idempotent(): void
    {
        $this->seed();
        $firstCount = Order::query()->where('order_number', 'like', 'A8OPS-%')->count();

        $this->seed(\Database\Seeders\AdvancedDashboardOperationsSeeder::class);
        $secondCount = Order::query()->where('order_number', 'like', 'A8OPS-%')->count();

        $this->assertSame($firstCount, $secondCount);
        $this->assertGreaterThan(0, $secondCount);
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->uri() . ' ' . $route->getActionName())->implode(' ');

        foreach (['BookingController', 'TemporaryBooking', 'TemporaryBookingSeat', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'] as $blocked) {
            $this->assertStringNotContainsString($blocked, $routeOutput);
        }
    }
}
