<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizerTeamAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_invite_and_manage_staff(): void
    {
        [$owner, $organizer] = $this->createOrganizer('Owner Team Organizer');
        $staff = User::factory()->create(['email' => 'manager.staff@example.com']);

        $this->actingAs($owner)
            ->get(route('organizer.team-members.index'))
            ->assertOk()
            ->assertSee('Invite Team Member');

        $this->actingAs($owner)
            ->post(route('organizer.team-members.store'), [
                'email' => $staff->email,
                'name' => 'Manager Staff',
                'role' => OrganizerTeamMember::ROLE_MANAGER,
            ])
            ->assertRedirect(route('organizer.team-members.index'));

        $member = OrganizerTeamMember::where('organizer_profile_id', $organizer->id)->where('email', $staff->email)->firstOrFail();

        $this->assertSame(OrganizerTeamMember::STATUS_ACTIVE, $member->status);
        $this->assertSame(OrganizerTeamMember::ROLE_MANAGER, $member->role);

        $this->actingAs($owner)
            ->put(route('organizer.team-members.update', $member), [
                'role' => OrganizerTeamMember::ROLE_CHECK_IN_STAFF,
                'status' => OrganizerTeamMember::STATUS_ACTIVE,
            ])
            ->assertRedirect(route('organizer.team-members.index'));

        $this->assertDatabaseHas('organizer_team_members', [
            'id' => $member->id,
            'role' => OrganizerTeamMember::ROLE_CHECK_IN_STAFF,
            'status' => OrganizerTeamMember::STATUS_ACTIVE,
        ]);
    }

    public function test_manager_can_access_allowed_operational_pages(): void
    {
        [$owner, $organizer] = $this->createOrganizer('Manager Access Organizer');
        $manager = User::factory()->create();
        $this->createMember($organizer, $manager, OrganizerTeamMember::ROLE_MANAGER);

        $this->actingAs($manager)->get(route('organizer.dashboard'))->assertOk();
        $this->actingAs($manager)->get(route('organizer.events.index'))->assertOk();
        $this->actingAs($manager)->get(route('organizer.venues.index'))->assertOk();
        $this->actingAs($manager)->get(route('organizer.team-members.index'))->assertOk();
        $this->actingAs($manager)->get(route('organizer.payouts.index'))->assertForbidden();

        $other = $this->createOrganizer('Other Organizer')[1];
        $otherEvent = $this->createEvent($other, 'Other Event');

        $this->actingAs($manager)
            ->get(route('organizer.events.show', $otherEvent))
            ->assertForbidden();
    }

    public function test_check_in_staff_can_access_only_check_in(): void
    {
        $organizer = $this->createOrganizer('Check In Team Organizer')[1];
        $staff = User::factory()->create();
        $this->createMember($organizer, $staff, OrganizerTeamMember::ROLE_CHECK_IN_STAFF);

        $this->actingAs($staff)->get(route('organizer.check-in.index'))->assertOk();
        $this->actingAs($staff)->get(route('organizer.dashboard'))->assertForbidden();
        $this->actingAs($staff)->get(route('organizer.events.index'))->assertForbidden();
        $this->actingAs($staff)->get(route('organizer.payouts.index'))->assertForbidden();
    }

    public function test_finance_viewer_can_access_reports_and_payouts_only(): void
    {
        $organizer = $this->createOrganizer('Finance Team Organizer')[1];
        $finance = User::factory()->create();
        $this->createMember($organizer, $finance, OrganizerTeamMember::ROLE_FINANCE_VIEWER);

        $this->actingAs($finance)->get(route('organizer.reports.sales'))->assertOk();
        $this->actingAs($finance)->get(route('organizer.payouts.index'))->assertOk();
        $this->actingAs($finance)->post(route('organizer.payouts.store'), ['amount' => 10])->assertForbidden();
        $this->actingAs($finance)->get(route('organizer.events.index'))->assertForbidden();
        $this->actingAs($finance)->get(route('organizer.check-in.index'))->assertForbidden();
    }

    public function test_inactive_staff_cannot_access_organizer_panel(): void
    {
        $organizer = $this->createOrganizer('Inactive Team Organizer')[1];
        $staff = User::factory()->create();
        $this->createMember($organizer, $staff, OrganizerTeamMember::ROLE_MANAGER, OrganizerTeamMember::STATUS_INACTIVE);

        $this->actingAs($staff)
            ->get(route('organizer.dashboard'))
            ->assertRedirect(route('organizer.status'));
    }

    public function test_no_old_booking_or_temporary_booking_routes_are_used(): void
    {
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
    }

    private function createOrganizer(string $name): array
    {
        $user = User::factory()->create([
            'email' => str()->slug($name) . '@example.com',
            'password' => Hash::make('password'),
        ]);

        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => $name,
            'slug' => str()->slug($name) . '-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'submitted_at' => now()->subDay(),
            'approved_at' => now(),
        ]);

        return [$user, $organizer];
    }

    private function createMember(OrganizerProfile $organizer, User $user, string $role, string $status = OrganizerTeamMember::STATUS_ACTIVE): OrganizerTeamMember
    {
        return OrganizerTeamMember::create([
            'organizer_profile_id' => $organizer->id,
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $role,
            'status' => $status,
            'invited_by' => $organizer->user_id,
            'invited_at' => now(),
            'accepted_at' => $status === OrganizerTeamMember::STATUS_ACTIVE ? now() : null,
        ]);
    }

    private function createEvent(OrganizerProfile $organizer, string $name): Event
    {
        $eventType = EventType::create(['name' => $name . ' Type', 'slug' => str()->slug($name . ' Type')]);

        return Event::create([
            'organizer_profile_id' => $organizer->id,
            'event_type_id' => $eventType->id,
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => 'Test event description.',
            'start_date' => now()->addDays(10)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'venue' => 'Test Venue',
            'organizer_name' => $organizer->organization_name,
            'status' => Event::STATUS_PUBLISHED,
        ]);
    }
}
