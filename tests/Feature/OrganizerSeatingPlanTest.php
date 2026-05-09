<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizerSeatingPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_organizer_can_create_seating_plan_for_own_venue(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED);
        $venue = $this->makeVenue($user->organizerProfile, 'Own Venue');

        $this->actingAs($user, 'web')
            ->post(route('organizer.seating-plans.store'), [
                'venue_id' => $venue->id,
                'name' => 'Organizer Main Layout',
                'status' => SeatingPlan::STATUS_DRAFT,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('seating_plans', [
            'organizer_profile_id' => $user->organizerProfile->id,
            'venue_id' => $venue->id,
            'name' => 'Organizer Main Layout',
            'status' => SeatingPlan::STATUS_DRAFT,
        ]);
    }

    public function test_approved_organizer_can_edit_own_seating_plan(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED);
        $venue = $this->makeVenue($user->organizerProfile, 'Editable Venue');
        $plan = $this->makePlan($user->organizerProfile, $venue, 'Old Layout');

        $this->actingAs($user, 'web')
            ->put(route('organizer.seating-plans.update', $plan), [
                'venue_id' => $venue->id,
                'name' => 'Updated Layout',
                'status' => SeatingPlan::STATUS_ACTIVE,
            ])
            ->assertRedirect(route('organizer.seating-plans.index'));

        $this->assertDatabaseHas('seating_plans', [
            'id' => $plan->id,
            'name' => 'Updated Layout',
            'status' => SeatingPlan::STATUS_ACTIVE,
        ]);
    }

    public function test_organizer_cannot_access_another_organizer_seating_plan(): void
    {
        $owner = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'owner-seating@example.com');
        $intruder = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'intruder-seating@example.com');
        $venue = $this->makeVenue($owner->organizerProfile, 'Owner Venue');
        $plan = $this->makePlan($owner->organizerProfile, $venue, 'Owner Layout');

        $this->actingAs($intruder, 'web')
            ->get(route('organizer.seating-plans.edit', $plan))
            ->assertForbidden();

        $this->actingAs($intruder, 'web')
            ->put(route('organizer.seating-plans.update', $plan), [
                'venue_id' => $venue->id,
                'name' => 'Hacked Layout',
                'status' => SeatingPlan::STATUS_ACTIVE,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('seating_plans', [
            'id' => $plan->id,
            'name' => 'Hacked Layout',
        ]);
    }

    public function test_pending_organizer_cannot_access_seating_plan_crud(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_PENDING);

        $this->actingAs($user, 'web')
            ->get(route('organizer.seating-plans.index'))
            ->assertRedirect(route('organizer.status'));

        $this->actingAs($user, 'web')
            ->get(route('organizer.seating-plans.create'))
            ->assertRedirect(route('organizer.status'));
    }

    public function test_locked_seating_plan_cannot_be_edited(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED);
        $venue = $this->makeVenue($user->organizerProfile, 'Locked Venue');
        $plan = $this->makePlan($user->organizerProfile, $venue, 'Locked Layout', SeatingPlan::STATUS_LOCKED);

        $this->actingAs($user, 'web')
            ->get(route('organizer.seating-plans.edit', $plan))
            ->assertForbidden();

        $this->actingAs($user, 'web')
            ->put(route('organizer.seating-plans.update', $plan), [
                'venue_id' => $venue->id,
                'name' => 'Should Not Update',
                'status' => SeatingPlan::STATUS_ACTIVE,
            ])
            ->assertForbidden();
    }

    public function test_approved_organizer_can_duplicate_own_seating_plan(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED);
        $venue = $this->makeVenue($user->organizerProfile, 'Clone Venue');
        $plan = $this->makePlan($user->organizerProfile, $venue, 'Original Layout');

        $this->actingAs($user, 'web')
            ->post(route('organizer.seating-plans.duplicate', $plan), ['name' => 'Copied Layout'])
            ->assertRedirect();

        $this->assertDatabaseHas('seating_plans', [
            'organizer_profile_id' => $user->organizerProfile->id,
            'venue_id' => $venue->id,
            'name' => 'Copied Layout',
            'status' => SeatingPlan::STATUS_DRAFT,
        ]);
    }


    public function test_admin_can_save_visual_design_and_rebuild_real_sections_and_seats(): void
    {
        $admin = Admin::create([
            'name' => 'Seatmap Admin',
            'email' => 'seatmap-admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $owner = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'admin-seatmap-owner@example.com');
        $venue = $this->makeVenue($owner->organizerProfile, 'Admin Designer Venue');
        $plan = $this->makePlan($owner->organizerProfile, $venue, 'Admin Designer Layout');
        $payload = $this->visualDesignPayload();

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.seating-plans.designer.save', $plan->id), [
                'design_json' => $payload,
            ])
            ->assertOk()
            ->assertJson(['status' => 'success']);

        $plan->refresh();

        $this->assertNotEmpty($plan->design_json);
        $this->assertSame(2, SeatingSection::where('seating_plan_id', $plan->id)->count());
        $this->assertSame(5, $plan->seats()->count());
        $this->assertDatabaseHas('seating_sections', [
            'seating_plan_id' => $plan->id,
            'name' => 'VIP Front',
            'capacity' => 4,
        ]);
        $this->assertDatabaseHas('seating_seats', [
            'label' => 'A-1',
            'row_label' => 'A',
            'seat_number' => 1,
            'status' => 'available',
        ]);
    }

    public function test_organizer_can_save_visual_design_for_owned_plan(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'designer-owner@example.com');
        $venue = $this->makeVenue($user->organizerProfile, 'Organizer Designer Venue');
        $plan = $this->makePlan($user->organizerProfile, $venue, 'Organizer Designer Layout');

        $this->actingAs($user, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $plan), [
                'design_json' => $this->visualDesignPayload(),
            ])
            ->assertOk()
            ->assertJson(['status' => 'success']);

        $this->assertSame(2, SeatingSection::where('seating_plan_id', $plan->id)->count());
        $this->assertSame(5, $plan->fresh()->seats()->count());
    }

    public function test_organizer_cannot_save_visual_design_for_another_organizers_plan(): void
    {
        $owner = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'designer-real-owner@example.com');
        $intruder = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'designer-intruder@example.com');
        $venue = $this->makeVenue($owner->organizerProfile, 'Private Designer Venue');
        $plan = $this->makePlan($owner->organizerProfile, $venue, 'Private Designer Layout');

        $this->actingAs($intruder, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $plan), [
                'design_json' => $this->visualDesignPayload(),
            ])
            ->assertForbidden();

        $this->assertSame(0, SeatingSection::where('seating_plan_id', $plan->id)->count());
    }

    public function test_re_saving_visual_design_does_not_duplicate_sections_or_seats(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'designer-repeat@example.com');
        $venue = $this->makeVenue($user->organizerProfile, 'Repeat Designer Venue');
        $plan = $this->makePlan($user->organizerProfile, $venue, 'Repeat Designer Layout');
        $payload = $this->visualDesignPayload();

        $this->actingAs($user, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $plan), ['design_json' => $payload])
            ->assertOk();

        $this->actingAs($user, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $plan), ['design_json' => $payload])
            ->assertOk();

        $this->assertSame(2, SeatingSection::where('seating_plan_id', $plan->id)->count());
        $this->assertSame(5, $plan->fresh()->seats()->count());
    }

    public function test_locked_plan_cannot_be_saved_from_visual_designer(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'designer-locked@example.com');
        $venue = $this->makeVenue($user->organizerProfile, 'Locked Designer Venue');
        $plan = $this->makePlan($user->organizerProfile, $venue, 'Locked Designer Layout', SeatingPlan::STATUS_LOCKED);

        $this->actingAs($user, 'web')
            ->postJson(route('organizer.seating-plans.designer.save', $plan), [
                'design_json' => $this->visualDesignPayload(),
            ])
            ->assertStatus(422);

        $this->assertSame(0, SeatingSection::where('seating_plan_id', $plan->id)->count());
    }

    public function test_admin_seating_plan_route_still_works(): void
    {
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin-seating@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.seating-plans.index'))
            ->assertSuccessful();
    }


    private function visualDesignPayload(): array
    {
        return [
            [
                'id' => 'stage-main',
                'type' => 'stage',
                'name' => 'Main Stage',
                'x' => 240,
                'y' => 20,
                'width' => 280,
                'height' => 80,
                'rotation' => 0,
                'seats' => [],
            ],
            [
                'id' => 'vip-front',
                'type' => 'seat',
                'name' => 'VIP Front',
                'x' => 100,
                'y' => 130,
                'width' => 260,
                'height' => 160,
                'rotation' => 0,
                'capacity' => 4,
                'seats' => [
                    ['id' => 'a1', 'label' => 'A-1', 'x' => 20, 'y' => 30, 'disabled' => false],
                    ['id' => 'a2', 'label' => 'A-2', 'x' => 70, 'y' => 30, 'disabled' => false],
                    ['id' => 'a3', 'label' => 'A-3', 'x' => 120, 'y' => 30, 'disabled' => false],
                    ['id' => 'a4', 'label' => 'A-4', 'x' => 170, 'y' => 30, 'disabled' => true],
                ],
            ],
            [
                'id' => 'table-one',
                'type' => 'table',
                'name' => 'Table One',
                'x' => 420,
                'y' => 130,
                'width' => 160,
                'height' => 160,
                'rotation' => 0,
                'capacity' => 1,
                'seats' => [
                    ['id' => 't1', 'label' => 'T-1', 'x' => 50, 'y' => 35, 'disabled' => false],
                ],
            ],
        ];
    }

    private function makeOrganizer(string $status, string $email = 'seating-organizer@example.com'): User
    {
        $user = User::create([
            'name' => 'Organizer User',
            'email' => $email,
            'password' => Hash::make('password'),
        ]);

        $user->organizerProfile()->create([
            'organization_name' => 'Organizer ' . md5($email),
            'slug' => 'organizer-' . md5($email),
            'status' => $status,
            'submitted_at' => now(),
            'approved_at' => $status === OrganizerProfile::STATUS_APPROVED ? now() : null,
        ]);

        return $user->refresh();
    }

    private function makeVenue(OrganizerProfile $profile, string $name): Venue
    {
        return Venue::create([
            'organizer_profile_id' => $profile->id,
            'organizer_id' => $profile->user_id,
            'name' => $name,
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 300,
        ]);
    }

    private function makePlan(OrganizerProfile $profile, Venue $venue, string $name, string $status = SeatingPlan::STATUS_DRAFT): SeatingPlan
    {
        return SeatingPlan::create([
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'name' => $name,
            'status' => $status,
            'design_json' => null,
        ]);
    }
}
