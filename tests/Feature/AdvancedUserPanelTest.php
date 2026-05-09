<?php

namespace Tests\Feature;

use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdvancedUserPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sidebar_has_active_states_and_dynamic_counters(): void
    {
        $this->seed();
        $customer = User::query()->where('email', 'advanced.dashboard.customer@example.com')->firstOrFail();

        $this->actingAs($customer)
            ->get(route('user.notifications.index'))
            ->assertOk()
            ->assertSee('aria-current="page"', false)
            ->assertSee('Notifications')
            ->assertSee('My Orders')
            ->assertSee('My Tickets')
            ->assertSee('Support')
            ->assertDontSee('Bank Cards')
            ->assertDontSee('Subscription')
            ->assertDontSee('Coupons');
    }

    public function test_all_customer_panel_pages_render_for_authenticated_customer(): void
    {
        $this->seed();
        $customer = User::query()->where('email', 'advanced.dashboard.customer@example.com')->firstOrFail();

        $routes = [
            'user.dashboard' => [],
            'user.orders.index' => [],
            'user.tickets.index' => [],
            'user.notifications.index' => [],
            'user.reviews.index' => [],
            'user.followed-organizers.index' => [],
            'user.saved-events.index' => [],
            'user.discovery.index' => [],
            'user.support-tickets.index' => [],
            'user.refunds.index' => [],
            'user.my.events' => [],
            'user.my.coupons' => [],
            'user.my.cards' => [],
            'user.my.reports' => [],
            'user.my.subscription' => [],
            'user.my.information' => [],
            'user.my.team' => [],
            'user.my.profile' => [],
            'user.profile' => [],
        ];

        foreach ($routes as $route => $parameters) {
            $this->actingAs($customer)
                ->get(route($route, $parameters))
                ->assertOk();
        }
    }

    public function test_guest_access_is_blocked_from_customer_panel_pages(): void
    {
        foreach ([
            'user.dashboard',
            'user.orders.index',
            'user.tickets.index',
            'user.saved-events.index',
            'user.followed-organizers.index',
            'user.support-tickets.index',
            'user.refunds.index',
            'user.notifications.index',
            'user.my.events',
            'user.my.profile',
        ] as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
    }

    public function test_customer_cannot_access_another_customers_private_records(): void
    {
        $this->seed();

        $owner = User::query()->where('email', 'advanced.dashboard.customer@example.com')->firstOrFail();
        $other = User::query()->create([
            'name' => 'Other Private Customer',
            'email' => 'other-private-customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $order = Order::query()->where('user_id', $owner->id)->latest()->firstOrFail();
        $ticket = OrderTicket::query()->whereHas('order', fn ($query) => $query->where('user_id', $owner->id))->latest()->firstOrFail();
        $supportTicket = MarketplaceSupportTicket::query()->where('user_id', $owner->id)->latest()->firstOrFail();
        $refund = RefundRequest::query()->where('user_id', $owner->id)->latest()->firstOrFail();

        $this->actingAs($other)->get(route('user.orders.show', $order))->assertNotFound();
        $this->actingAs($other)->get(route('user.tickets.show', $ticket))->assertNotFound();
        $this->actingAs($other)->get(route('user.support-tickets.show', $supportTicket))->assertNotFound();
        $this->actingAs($other)->get(route('user.orders.refund.create', $order))->assertNotFound();

        $this->actingAs($other)
            ->get(route('user.refunds.index'))
            ->assertOk()
            ->assertDontSee((string) $refund->id);
    }

    public function test_empty_customer_panel_pages_are_safe(): void
    {
        $customer = User::query()->create([
            'name' => 'Empty Panel Customer',
            'email' => 'empty-panel-customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        foreach (['user.my.events', 'user.my.coupons', 'user.my.cards', 'user.my.reports', 'user.my.subscription', 'user.my.information', 'user.my.team', 'user.my.profile'] as $route) {
            $this->actingAs($customer)
                ->get(route($route))
                ->assertOk()
                ->assertDontSee('Tutorial on Canvas Painting for Beginners')
                ->assertDontSee('Step Up Open Mic Show');
        }
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
