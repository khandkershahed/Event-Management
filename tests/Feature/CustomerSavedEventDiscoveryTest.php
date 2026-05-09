<?php

namespace Tests\Feature;

use App\Models\CustomerEventInterest;
use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Models\EventType;
use App\Models\OrganizerFollower;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CustomerSavedEventDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_customer_can_save_and_unsave_published_event(): void
    {
        $data = $this->fixture();

        $this->actingAs($data['customer'])->post(route('public.events.save', $data['event']), [
            'source' => 'test',
        ])->assertRedirect();

        $this->assertDatabaseHas('customer_saved_events', [
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
        ]);
        $this->assertDatabaseHas('customer_event_interests', [
            'user_id' => $data['customer']->id,
            'interest_type' => CustomerEventInterest::TYPE_EVENT_TYPE,
            'interest_value' => (string) $data['eventType']->id,
        ]);

        $this->actingAs($data['customer'])->delete(route('public.events.unsave', $data['event']))
            ->assertRedirect();

        $this->assertDatabaseMissing('customer_saved_events', [
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
        ]);
    }

    public function test_guest_cannot_save_event(): void
    {
        $data = $this->fixture();

        $this->post(route('public.events.save', $data['event']))
            ->assertRedirect(route('login'));
    }

    public function test_customer_can_view_saved_event_list(): void
    {
        $data = $this->fixture();

        CustomerSavedEvent::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'source' => 'test',
        ]);

        $this->actingAs($data['customer'])->get(route('user.saved-events.index'))
            ->assertOk()
            ->assertSee('Saved Events')
            ->assertSee($data['event']->name);
    }

    public function test_public_event_pages_show_save_and_saved_actions(): void
    {
        $data = $this->fixture();

        $this->actingAs($data['customer'])->get(route('event.details', $data['event']->slug))
            ->assertOk()
            ->assertSee('Save Event');

        CustomerSavedEvent::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'source' => 'test',
        ]);

        $this->actingAs($data['customer'])->get(route('event.details', $data['event']->slug))
            ->assertOk()
            ->assertSee('Saved');

        $this->actingAs($data['customer'])->get(route('all.events'))
            ->assertOk()
            ->assertSee('Saved');
    }

    public function test_personalized_recommendations_use_saved_events_and_followed_organizers(): void
    {
        $data = $this->fixture();
        $recommended = $this->event('Recommended Event ' . uniqid(), $data['organizer'], $data['eventType'], $data['venue']);
        $followedOrganizer = $this->organizer('Followed Organizer ' . uniqid());
        $followedEvent = $this->event('Followed Organizer Event ' . uniqid(), $followedOrganizer, null, null);

        CustomerSavedEvent::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'source' => 'test',
        ]);
        CustomerEventInterest::create([
            'user_id' => $data['customer']->id,
            'interest_type' => CustomerEventInterest::TYPE_EVENT_TYPE,
            'interest_value' => (string) $data['eventType']->id,
            'weight' => 5,
            'last_recorded_at' => now(),
        ]);
        OrganizerFollower::create([
            'user_id' => $data['customer']->id,
            'organizer_profile_id' => $followedOrganizer->id,
        ]);

        $this->actingAs($data['customer'])->get(route('user.discovery.index'))
            ->assertOk()
            ->assertSee('Recommended Events')
            ->assertSee($recommended->name)
            ->assertSee($followedEvent->name)
            ->assertDontSee($data['event']->name);
    }

    public function test_unpublished_event_cannot_be_saved(): void
    {
        $data = $this->fixture();
        $draft = $this->event('Draft Save Test ' . uniqid(), $data['organizer'], null, null, Event::STATUS_DRAFT);

        $this->actingAs($data['customer'])->post(route('public.events.save', $draft))
            ->assertNotFound();
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
        $customer = User::factory()->create(['email' => 'saved-customer-' . uniqid() . '@example.com']);
        $organizer = $this->organizer('Saved Organizer ' . uniqid());
        $eventType = EventType::create(['name' => 'Saved Type ' . uniqid(), 'slug' => 'saved-type-' . uniqid(), 'status' => 'active']);
        $venue = Venue::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Saved Venue ' . uniqid(),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 100,
        ]);
        $event = $this->event('Saved Event ' . uniqid(), $organizer, $eventType, $venue);

        return compact('customer', 'organizer', 'eventType', 'venue', 'event');
    }

    private function organizer(string $name): OrganizerProfile
    {
        $owner = User::factory()->create(['email' => 'saved-owner-' . uniqid() . '@example.com']);

        return OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'email' => 'organizer-' . uniqid() . '@example.com',
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
    }

    private function event(string $name, OrganizerProfile $organizer, ?EventType $eventType = null, ?Venue $venue = null, string $status = Event::STATUS_PUBLISHED): Event
    {
        return Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType?->id,
            'venue_id' => $venue?->id,
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'status' => $status,
            'start_date' => now()->addWeeks(2)->toDateString(),
            'end_date' => now()->addWeeks(2)->toDateString(),
            'venue' => $venue?->name ?? 'Online',
        ]);
    }
}
