<?php

namespace Tests\Feature;

use App\Models\CustomerEventInterest;
use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Models\EventType;
use App\Models\OrganizerFollower;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTrustBadge;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class HomepageMarketplaceDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_marketplace_discovery_sections_for_guests(): void
    {
        $data = $this->fixture();
        $featured = $this->event('Featured Homepage Event ' . uniqid(), $data['organizer'], $data['type'], $data['venue'], ['is_featured' => true]);
        $online = $this->event('Online Homepage Event ' . uniqid(), $data['organizer'], $data['type'], null, ['event_type' => 'online', 'venue' => 'Online']);
        $draft = $this->event('Hidden Homepage Draft ' . uniqid(), $data['organizer'], $data['type'], $data['venue'], ['status' => Event::STATUS_DRAFT]);

        OrganizerTrustBadge::create([
            'organizer_profile_id' => $data['organizer']->id,
            'badge_key' => OrganizerTrustBadge::BADGE_VERIFIED_ORGANIZER,
            'label' => 'Verified Organizer',
            'status' => OrganizerTrustBadge::STATUS_APPROVED,
            'is_public' => true,
        ]);

        $response = $this->get(route('homepage'));

        $response->assertOk()
            ->assertSee('Featured Events')
            ->assertSee('Trending Now')
            ->assertSee('Upcoming Events')
            ->assertSee('Online Events')
            ->assertSee('Browse by Category')
            ->assertSee('Browse by City')
            ->assertSee('Trusted Organizers')
            ->assertSee($featured->name)
            ->assertSee($online->name)
            ->assertSee('Dhaka')
            ->assertSee('Verified Organizer')
            ->assertDontSee($draft->name);
    }

    public function test_logged_in_customer_sees_personalized_homepage_section(): void
    {
        $data = $this->fixture();
        $saved = $this->event('Saved Homepage Event ' . uniqid(), $data['organizer'], $data['type'], $data['venue']);
        $recommended = $this->event('Personalized Homepage Event ' . uniqid(), $data['organizer'], $data['type'], $data['venue']);
        $followedOrganizer = $this->organizer('Homepage Followed Organizer ' . uniqid());
        $followedEvent = $this->event('Followed Homepage Event ' . uniqid(), $followedOrganizer, null, null);

        CustomerSavedEvent::create([
            'user_id' => $data['customer']->id,
            'event_id' => $saved->id,
            'source' => 'test',
        ]);
        CustomerEventInterest::create([
            'user_id' => $data['customer']->id,
            'interest_type' => CustomerEventInterest::TYPE_EVENT_TYPE,
            'interest_value' => (string) $data['type']->id,
            'weight' => 5,
            'last_recorded_at' => now(),
        ]);
        OrganizerFollower::create([
            'user_id' => $data['customer']->id,
            'organizer_profile_id' => $followedOrganizer->id,
        ]);

        $response = $this->actingAs($data['customer'])->get(route('homepage'));

        $response->assertOk()
            ->assertSee('Recommended for You')
            ->assertSee($recommended->name)
            ->assertSee($followedEvent->name);
    }

    public function test_homepage_has_safe_empty_states(): void
    {
        $this->get(route('homepage'))
            ->assertOk()
            ->assertSee('No featured events yet')
            ->assertSee('No trending events yet')
            ->assertSee('No trusted organizer highlights yet');
    }

    public function test_homepage_links_to_filtered_event_discovery_pages(): void
    {
        $data = $this->fixture();
        $this->event('Filter Link Homepage Event ' . uniqid(), $data['organizer'], $data['type'], $data['venue']);

        $this->get(route('homepage'))
            ->assertOk()
            ->assertSee(route('all.events', ['date_filter' => 'upcoming']), false)
            ->assertSee(route('all.events', ['format' => 'online']), false)
            ->assertSee(route('all.events', ['category_slug' => $data['type']->slug]), false)
            ->assertSee(route('all.events', ['city' => 'Dhaka']), false);
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
        $customer = User::factory()->create(['email' => 'homepage-customer-' . uniqid() . '@example.com']);
        $organizer = $this->organizer('Homepage Organizer ' . uniqid());
        $type = EventType::create([
            'name' => 'Homepage Type ' . uniqid(),
            'slug' => 'homepage-type-' . uniqid(),
            'status' => 'active',
        ]);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Homepage Venue ' . uniqid(),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 250,
            'type' => 'physical',
        ]);

        return compact('customer', 'organizer', 'type', 'venue');
    }

    private function organizer(string $name): OrganizerProfile
    {
        $owner = User::factory()->create(['email' => 'homepage-owner-' . uniqid() . '@example.com']);

        return OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'email' => 'homepage-organizer-' . uniqid() . '@example.com',
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
    }

    private function event(string $name, OrganizerProfile $organizer, ?EventType $type = null, ?Venue $venue = null, array $overrides = []): Event
    {
        return Event::create(array_merge([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $type?->id,
            'venue_id' => $venue?->id,
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'status' => Event::STATUS_PUBLISHED,
            'start_date' => now()->addDays(10)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '13:00:00',
            'venue' => $venue?->name ?? 'Online',
            'is_featured' => false,
        ], $overrides));
    }
}
