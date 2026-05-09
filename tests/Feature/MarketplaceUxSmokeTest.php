<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MarketplaceUxSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_marketplace_pages_render_safely(): void
    {
        $data = $this->fixture();

        $this->get(route('homepage'))
            ->assertOk()
            ->assertSee('Featured Events');

        $this->get(route('all.events'))
            ->assertOk()
            ->assertSee('Events');

        $this->get(route('event.details', $data['event']->slug))
            ->assertOk()
            ->assertSee($data['event']->name)
            ->assertSee('Select Tickets');

        $this->get(route('public.organizers.show', $data['organizer']->slug))
            ->assertOk()
            ->assertSee($data['organizer']->organization_name)
            ->assertSee('Published Events');
    }

    public function test_customer_marketplace_pages_render_safely(): void
    {
        $data = $this->fixture();

        $this->actingAs($data['customer'])
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('Customer Dashboard');

        $this->actingAs($data['customer'])
            ->get(route('user.saved-events.index'))
            ->assertOk()
            ->assertSee('Saved');

        $this->actingAs($data['customer'])
            ->get(route('user.discovery.index'))
            ->assertOk()
            ->assertSee('Recommended');

        $this->actingAs($data['customer'])
            ->get(route('user.reviews.index'))
            ->assertOk()
            ->assertSee('Reviews');

        $this->actingAs($data['customer'])
            ->get(route('user.support-tickets.index'))
            ->assertOk()
            ->assertSee('Support');
    }

    public function test_organizer_marketplace_pages_render_safely(): void
    {
        $data = $this->fixture();

        $this->actingAs($data['owner'])
            ->get(route('organizer.dashboard'))
            ->assertOk()
            ->assertSee('Organizer Dashboard');

        $this->actingAs($data['owner'])
            ->get(route('organizer.reports.index'))
            ->assertOk()
            ->assertSee('Reports');

        $this->actingAs($data['owner'])
            ->get(route('organizer.reports.sales'))
            ->assertOk()
            ->assertSee('Sales');

        $this->actingAs($data['owner'])
            ->get(route('organizer.check-in.index'))
            ->assertOk()
            ->assertSee('Check');
    }

    public function test_admin_marketplace_pages_render_safely(): void
    {
        $data = $this->fixture();

        $this->actingAs($data['admin'], 'admin')
            ->get(route('admin.marketplace-reports.dashboard'))
            ->assertOk()
            ->assertSee('Marketplace Reporting Dashboard');

        $this->actingAs($data['admin'], 'admin')
            ->get(route('admin.support-tickets.index'))
            ->assertOk()
            ->assertSee('Support Tickets');

        $this->actingAs($data['admin'], 'admin')
            ->get(route('admin.audit-logs.index'))
            ->assertOk()
            ->assertSee('Audit Logs');

        $this->actingAs($data['admin'], 'admin')
            ->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertSee('Reviews');
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        foreach (['TemporaryBooking', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'] as $blocked) {
            $this->assertStringNotContainsString($blocked, $routeOutput);
        }
    }

    private function fixture(): array
    {
        $admin = Admin::create([
            'name' => 'UX Admin',
            'email' => 'ux-admin-' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
        ]);

        $owner = User::factory()->create(['email' => 'ux-owner-' . uniqid() . '@example.com']);
        $customer = User::factory()->create(['email' => 'ux-customer-' . uniqid() . '@example.com']);

        $organizer = OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => 'UX Organizer ' . uniqid(),
            'slug' => 'ux-organizer-' . uniqid(),
            'email' => 'ux-organizer-' . uniqid() . '@example.com',
            'description' => 'Organizer used for UX smoke testing.',
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $type = EventType::create([
            'name' => 'UX Category ' . uniqid(),
            'slug' => 'ux-category-' . uniqid(),
            'status' => 'active',
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'UX Venue ' . uniqid(),
            'address' => '123 UX Road',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 300,
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'name' => 'UX Smoke Event ' . uniqid(),
            'slug' => 'ux-smoke-event-' . uniqid(),
            'tagline' => 'UX smoke event tagline.',
            'description' => '<p>UX smoke event description.</p>',
            'status' => Event::STATUS_PUBLISHED,
            'start_date' => now()->addDays(14)->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'venue' => $venue->name,
            'is_featured' => true,
        ]);

        return compact('admin', 'owner', 'customer', 'organizer', 'type', 'venue', 'event');
    }
}
