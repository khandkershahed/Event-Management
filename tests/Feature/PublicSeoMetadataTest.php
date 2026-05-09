<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTrustBadge;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PublicSeoMetadataTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_detail_page_has_dynamic_metadata_and_json_ld(): void
    {
        $data = $this->fixture();
        EventTicket::create([
            'event_id' => $data['event']->id,
            'name' => 'General Admission',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 25,
            'currency' => 'USD',
            'quantity' => 100,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 5,
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $this->get(route('event.details', $data['event']->slug))
            ->assertOk()
            ->assertSee('<meta property="og:type" content="event"', false)
            ->assertSee('<link rel="canonical" href="' . route('event.details', $data['event']->slug) . '">', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('"@type": "Event"', false)
            ->assertSee($data['event']->name);
    }

    public function test_public_organizer_page_has_metadata_and_organization_json_ld(): void
    {
        $data = $this->fixture();

        OrganizerTrustBadge::create([
            'organizer_profile_id' => $data['organizer']->id,
            'badge_key' => OrganizerTrustBadge::BADGE_VERIFIED_ORGANIZER,
            'label' => 'Verified Organizer',
            'status' => OrganizerTrustBadge::STATUS_APPROVED,
            'is_public' => true,
        ]);

        $this->get(route('public.organizers.show', $data['organizer']->slug))
            ->assertOk()
            ->assertSee('<meta property="og:type" content="profile"', false)
            ->assertSee('<link rel="canonical" href="' . route('public.organizers.show', $data['organizer']->slug) . '">', false)
            ->assertSee('"@type": "Organization"', false)
            ->assertSee($data['organizer']->organization_name)
            ->assertSee('Verified Organizer');
    }

    public function test_sitemap_includes_only_public_events_and_approved_organizers(): void
    {
        $data = $this->fixture();
        $draftEvent = $this->event('Draft Sitemap Event ' . uniqid(), $data['organizer'], $data['type'], $data['venue'], Event::STATUS_DRAFT);
        $pendingOrganizer = $this->organizer('Pending Sitemap Organizer ' . uniqid(), OrganizerProfile::STATUS_PENDING);

        $this->get(route('seo.sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('homepage'), false)
            ->assertSee(route('all.events'), false)
            ->assertSee(route('event.details', $data['event']->slug), false)
            ->assertSee(route('public.organizers.show', $data['organizer']->slug), false)
            ->assertDontSee(route('event.details', $draftEvent->slug), false)
            ->assertDontSee(route('public.organizers.show', $pendingOrganizer->slug), false);
    }

    public function test_robots_response_points_to_sitemap_and_blocks_private_areas(): void
    {
        $this->get(route('seo.robots'))
            ->assertOk()
            ->assertSee('User-agent: *')
            ->assertSee('Disallow: /admin')
            ->assertSee('Disallow: /organizer')
            ->assertSee('Disallow: /user')
            ->assertSee('Sitemap: ' . url('/sitemap.xml'));
    }

    public function test_unpublished_event_metadata_is_not_publicly_accessible(): void
    {
        $data = $this->fixture();
        $draftEvent = $this->event('Hidden SEO Draft ' . uniqid(), $data['organizer'], $data['type'], $data['venue'], Event::STATUS_DRAFT);

        $this->get(route('event.details', $draftEvent->slug))->assertNotFound();
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
        $organizer = $this->organizer('SEO Organizer ' . uniqid());
        $type = EventType::create([
            'name' => 'SEO Category ' . uniqid(),
            'slug' => 'seo-category-' . uniqid(),
            'status' => 'active',
        ]);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'SEO Venue ' . uniqid(),
            'address' => '123 SEO Road',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 500,
        ]);
        $event = $this->event('SEO Published Event ' . uniqid(), $organizer, $type, $venue, Event::STATUS_PUBLISHED);

        return compact('organizer', 'type', 'venue', 'event');
    }

    private function organizer(string $name, string $status = OrganizerProfile::STATUS_APPROVED): OrganizerProfile
    {
        $owner = User::factory()->create(['email' => 'seo-owner-' . uniqid() . '@example.com']);

        return OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'description' => 'Organizer profile for SEO testing.',
            'email' => 'seo-organizer-' . uniqid() . '@example.com',
            'status' => $status,
            'approved_at' => $status === OrganizerProfile::STATUS_APPROVED ? now() : null,
        ]);
    }

    private function event(string $name, OrganizerProfile $organizer, EventType $type, Venue $venue, string $status): Event
    {
        return Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'tagline' => 'SEO friendly event tagline.',
            'description' => '<p>SEO friendly event description.</p>',
            'status' => $status,
            'start_date' => now()->addDays(15)->toDateString(),
            'end_date' => now()->addDays(15)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '13:00:00',
            'venue' => $venue->name,
            'event_type' => 'physical',
        ]);
    }
}
