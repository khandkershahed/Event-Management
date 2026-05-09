<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Models\MarketplaceEventReview;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrganizerFollower;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTrustBadge;
use App\Models\User;
use Database\Seeders\DemoMarketplaceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MarketplaceEndToEndRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_marketplace_seeder_is_idempotent_and_covers_core_demo_data(): void
    {
        $this->seed();
        $this->seed(DemoMarketplaceSeeder::class);
        $this->seed(DemoMarketplaceSeeder::class);

        $this->assertSame(1, Event::where('slug', 'demo-marketplace-summit')->count());
        $this->assertSame(1, Event::where('slug', 'demo-online-growth-workshop')->count());
        $this->assertSame(1, Order::where('order_number', 'ORD-DEMO-E2E-001')->count());
        $this->assertSame(1, MarketplaceSupportTicket::where('ticket_number', 'ST-DEMO-0001')->count());
        $this->assertSame(1, OrganizerPayout::where('payout_number', 'PO-DEMO-0001')->count());
        $this->assertDatabaseHas('organizer_trust_badges', ['badge_key' => OrganizerTrustBadge::BADGE_VERIFIED_ORGANIZER]);
        $this->assertDatabaseHas('customer_saved_events', ['source' => 'demo']);
        $this->assertGreaterThan(0, OrganizerFollower::query()->count());
        $this->assertDatabaseHas('marketplace_event_reviews', ['status' => MarketplaceEventReview::STATUS_APPROVED]);
    }

    public function test_public_customer_organizer_and_admin_paths_render_after_demo_seed(): void
    {
        $this->seed();
        $this->seed(DemoMarketplaceSeeder::class);

        $event = Event::where('slug', 'demo-marketplace-summit')->firstOrFail();
        $organizer = OrganizerProfile::where('slug', 'demo-marketplace-events')->firstOrFail();
        $customer = User::where('email', 'demo.customer@example.com')->firstOrFail();
        $owner = User::where('email', 'demo.organizer@example.com')->firstOrFail();
        $admin = Admin::where('email', 'admin@example.com')->firstOrFail();

        $this->get(route('homepage'))->assertOk()->assertSee('Featured Events');
        $this->get(route('all.events'))->assertOk()->assertSee('Events');
        $this->get(route('event.details', $event->slug))->assertOk()->assertSee($event->name);
        $this->get(route('public.organizers.show', $organizer->slug))->assertOk()->assertSee($organizer->organization_name);
        $this->get(route('seo.sitemap'))->assertOk()->assertSee(route('event.details', $event->slug), false);
        $this->get(route('seo.robots'))->assertOk()->assertSee('Sitemap:');

        $this->actingAs($customer)->get(route('user.dashboard'))->assertOk();
        $this->actingAs($customer)->get(route('user.saved-events.index'))->assertOk()->assertSee($event->name);
        $this->actingAs($customer)->get(route('user.discovery.index'))->assertOk()->assertSee('Recommended');
        $this->actingAs($customer)->get(route('user.orders.index'))->assertOk();
        $this->actingAs($customer)->get(route('user.tickets.index'))->assertOk();
        $this->actingAs($customer)->get(route('user.support-tickets.index'))->assertOk();

        $this->actingAs($owner)->get(route('organizer.dashboard'))->assertOk();
        $this->actingAs($owner)->get(route('organizer.reports.index'))->assertOk();
        $this->actingAs($owner)->get(route('organizer.reports.sales'))->assertOk();
        $this->actingAs($owner)->get(route('organizer.check-in.index'))->assertOk();

        $this->actingAs($admin, 'admin')->get(route('admin.marketplace-reports.dashboard'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.support-tickets.index'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.audit-logs.index'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.reviews.index'))->assertOk();
    }

    public function test_customer_save_and_personalized_discovery_remain_safe_after_demo_seed(): void
    {
        $this->seed();
        $this->seed(DemoMarketplaceSeeder::class);

        $customer = User::where('email', 'demo.customer@example.com')->firstOrFail();
        $event = Event::where('slug', 'demo-online-growth-workshop')->firstOrFail();
        $organizer = OrganizerProfile::where('slug', 'demo-marketplace-events')->firstOrFail();

        $this->actingAs($customer)->post(route('public.events.save', $event), ['source' => 'regression'])->assertRedirect();
        $this->assertDatabaseHas('customer_saved_events', ['user_id' => $customer->id, 'event_id' => $event->id]);

        OrganizerFollower::query()->updateOrCreate(['user_id' => $customer->id, 'organizer_profile_id' => $organizer->id]);

        $this->actingAs($customer)->get(route('user.discovery.index'))
            ->assertOk()
            ->assertSee('Recommended');

        $this->actingAs($customer)->delete(route('public.events.unsave', $event))->assertRedirect();
        $this->assertDatabaseMissing('customer_saved_events', ['user_id' => $customer->id, 'event_id' => $event->id]);
    }

    public function test_homepage_does_not_crash_when_optional_customer_discovery_tables_are_absent(): void
    {
        $this->seed();

        Schema::dropIfExists('customer_event_interests');
        Schema::dropIfExists('customer_saved_events');

        $this->get(route('homepage'))
            ->assertOk()
            ->assertSee('Featured Events');
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        foreach (['TemporaryBooking', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'] as $blocked) {
            $this->assertStringNotContainsString($blocked, $routeOutput);
        }
    }
}
