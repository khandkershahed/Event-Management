<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DynamicCustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_dashboard_shows_real_marketplace_counts(): void
    {
        $this->seed();

        $customer = User::query()->where('email', 'advanced.dashboard.customer@example.com')->firstOrFail();

        $this->actingAs($customer)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('Total Orders')
            ->assertSee('Paid / Completed')
            ->assertSee('Pending Payments')
            ->assertSee('Issued Tickets')
            ->assertSee('Upcoming Tickets')
            ->assertSee('Saved Events')
            ->assertSee('Followed Organizers')
            ->assertSee('Support Tickets')
            ->assertSee('Refund Requests')
            ->assertSee('Unread Notifications')
            ->assertSee('30');
    }

    public function test_dashboard_recent_orders_tickets_saved_followed_support_refunds_and_notifications_render(): void
    {
        $this->seed();

        $customer = User::query()->where('email', 'advanced.dashboard.customer@example.com')->firstOrFail();

        $this->actingAs($customer)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('Recent Orders')
            ->assertSee('Upcoming Tickets')
            ->assertSee('Recent Tickets')
            ->assertSee('Recently Saved Events')
            ->assertSee('Followed Organizers')
            ->assertSee('Recent Support Tickets')
            ->assertSee('Recent Refund Requests')
            ->assertSee('Recent Notifications')
            ->assertSee('Advanced Dashboard Order Support')
            ->assertSee('Advanced Ticket Section Matrix Demo')
            ->assertSee('Order confirmed');
    }

    public function test_guest_cannot_access_customer_dashboard(): void
    {
        $this->get(route('user.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_empty_customer_dashboard_state_is_safe(): void
    {
        $customer = User::query()->create([
            'name' => 'Empty Dashboard Customer',
            'email' => 'empty.dashboard.customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($customer)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('Welcome to your marketplace dashboard')
            ->assertSee('No orders yet')
            ->assertSee('No upcoming tickets yet')
            ->assertSee('No saved events yet')
            ->assertSee('No support tickets yet')
            ->assertSee('No refund requests yet')
            ->assertSee('Discover Events');
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $blocked = ['TemporaryBooking', 'TemporaryBookingSeat', 'EventSeatType', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'];
        $routes = collect(Route::getRoutes())->map(fn ($route) => $route->uri().' '.$route->getActionName())->implode('\n');

        foreach ($blocked as $term) {
            $this->assertStringNotContainsString($term, $routes);
        }
    }
}
