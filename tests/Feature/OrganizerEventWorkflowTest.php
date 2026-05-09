<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizerEventWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_organizer_can_create_draft_event(): void
    {
        [$user, $venue, $plan, $type] = $this->makeOrganizerStack();

        $this->actingAs($user, 'web')
            ->post(route('organizer.events.store'), [
                'name' => 'Organizer Draft Event',
                'event_type_id' => $type->id,
                'venue_id' => $venue->id,
                'seating_plan_id' => $plan->id,
                'start_date' => now()->addWeek()->toDateString(),
                'end_date' => now()->addWeek()->toDateString(),
                'description' => 'Draft event description.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('events', [
            'name' => 'Organizer Draft Event',
            'organizer_profile_id' => $user->organizerProfile->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'status' => Event::STATUS_DRAFT,
        ]);
    }

    public function test_organizer_can_edit_own_draft_event(): void
    {
        [$user, $venue, $plan, $type] = $this->makeOrganizerStack('edit-event@example.com');
        $event = $this->makeEvent($user->organizerProfile, $venue, $plan, $type, Event::STATUS_DRAFT, 'Old Event Name');

        $this->actingAs($user, 'web')
            ->put(route('organizer.events.update', $event), [
                'name' => 'Updated Event Name',
                'event_type_id' => $type->id,
                'venue_id' => $venue->id,
                'seating_plan_id' => $plan->id,
                'description' => 'Updated description.',
            ])
            ->assertRedirect(route('organizer.events.show', $event));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Updated Event Name',
        ]);
    }

    public function test_organizer_can_submit_event_for_admin_review(): void
    {
        [$user, $venue, $plan, $type] = $this->makeOrganizerStack('submit-event@example.com');
        $event = $this->makeEvent($user->organizerProfile, $venue, $plan, $type, Event::STATUS_DRAFT, 'Submission Event');

        $this->actingAs($user, 'web')
            ->post(route('organizer.events.submit', $event))
            ->assertRedirect(route('organizer.events.show', $event));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'status' => Event::STATUS_SUBMITTED,
        ]);
    }

    public function test_organizer_cannot_access_another_organizer_event(): void
    {
        [$owner, $venue, $plan, $type] = $this->makeOrganizerStack('owner-event@example.com');
        [$intruder] = $this->makeOrganizerStack('intruder-event@example.com');
        $event = $this->makeEvent($owner->organizerProfile, $venue, $plan, $type, Event::STATUS_DRAFT, 'Owner Event');

        $this->actingAs($intruder, 'web')
            ->get(route('organizer.events.edit', $event))
            ->assertForbidden();

        $this->actingAs($intruder, 'web')
            ->put(route('organizer.events.update', $event), [
                'name' => 'Hacked Event',
                'event_type_id' => $type->id,
                'venue_id' => $venue->id,
                'seating_plan_id' => $plan->id,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('events', ['id' => $event->id, 'name' => 'Hacked Event']);
    }

    public function test_public_listing_hides_unpublished_and_shows_published_events(): void
    {
        [$user, $venue, $plan, $type] = $this->makeOrganizerStack('public-event@example.com');
        $this->makeEvent($user->organizerProfile, $venue, $plan, $type, Event::STATUS_DRAFT, 'Hidden Draft Event');
        $this->makeEvent($user->organizerProfile, $venue, $plan, $type, Event::STATUS_PUBLISHED, 'Visible Published Event');

        $this->get(route('all.events'))
            ->assertSuccessful()
            ->assertSee('Visible Published Event')
            ->assertDontSee('Hidden Draft Event');
    }

    private function makeOrganizerStack(string $email = 'event-organizer@example.com'): array
    {
        $user = User::create([
            'name' => 'Event Organizer',
            'email' => $email,
            'password' => Hash::make('password'),
        ]);

        $profile = $user->organizerProfile()->create([
            'organization_name' => 'Organizer ' . md5($email),
            'slug' => 'organizer-' . md5($email),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'submitted_at' => now(),
            'approved_at' => now(),
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $profile->id,
            'organizer_id' => $user->id,
            'name' => 'Event Test Venue ' . md5($email),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 300,
        ]);

        $plan = SeatingPlan::create([
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'name' => 'Event Test Layout ' . md5($email),
            'status' => SeatingPlan::STATUS_ACTIVE,
        ]);

        $type = EventType::create([
            'name' => 'Conference ' . md5($email),
            'slug' => 'conference-' . md5($email),
            'code' => 'CONF' . substr(md5($email), 0, 5),
            'status' => 'active',
        ]);

        return [$user->refresh(), $venue, $plan, $type];
    }

    private function makeEvent(OrganizerProfile $profile, Venue $venue, SeatingPlan $plan, EventType $type, string $status, string $name): Event
    {
        return Event::create([
            'organizer_profile_id' => $profile->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'venue' => $venue->name,
            'organizer_name' => $profile->organization_name,
            'description' => 'Test event.',
            'start_date' => now()->addWeek()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'status' => $status,
        ]);
    }
}
