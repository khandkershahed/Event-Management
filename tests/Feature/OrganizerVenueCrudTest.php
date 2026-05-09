<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizerVenueCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_organizer_can_create_venue(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED);

        $this->actingAs($user, 'web')
            ->post(route('organizer.venues.store'), [
                'name' => 'Organizer Test Hall',
                'address' => 'Road 1',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 500,
                'description' => 'Test venue.',
            ])
            ->assertRedirect(route('organizer.venues.index'));

        $this->assertDatabaseHas('venues', [
            'name' => 'Organizer Test Hall',
            'organizer_profile_id' => $user->organizerProfile->id,
        ]);
    }

    public function test_approved_organizer_can_edit_own_venue(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED);
        $venue = Venue::create([
            'organizer_profile_id' => $user->organizerProfile->id,
            'organizer_id' => $user->id,
            'name' => 'Old Venue Name',
            'address' => 'Old address',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 100,
        ]);

        $this->actingAs($user, 'web')
            ->put(route('organizer.venues.update', $venue), [
                'name' => 'Updated Venue Name',
                'address' => 'Updated address',
                'city' => 'Chattogram',
                'country' => 'Bangladesh',
                'capacity' => 900,
                'description' => 'Updated.',
            ])
            ->assertRedirect(route('organizer.venues.index'));

        $this->assertDatabaseHas('venues', [
            'id' => $venue->id,
            'name' => 'Updated Venue Name',
            'city' => 'Chattogram',
        ]);
    }

    public function test_organizer_cannot_edit_another_organizer_venue(): void
    {
        $owner = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'owner@example.com');
        $intruder = $this->makeOrganizer(OrganizerProfile::STATUS_APPROVED, 'intruder@example.com');

        $venue = Venue::create([
            'organizer_profile_id' => $owner->organizerProfile->id,
            'organizer_id' => $owner->id,
            'name' => 'Owner Venue',
            'city' => 'Dhaka',
        ]);

        $this->actingAs($intruder, 'web')
            ->get(route('organizer.venues.edit', $venue))
            ->assertForbidden();

        $this->actingAs($intruder, 'web')
            ->put(route('organizer.venues.update', $venue), [
                'name' => 'Hacked Venue',
                'city' => 'Dhaka',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('venues', ['id' => $venue->id, 'name' => 'Hacked Venue']);
    }

    public function test_pending_organizer_cannot_access_organizer_venue_crud(): void
    {
        $user = $this->makeOrganizer(OrganizerProfile::STATUS_PENDING);

        $this->actingAs($user, 'web')
            ->get(route('organizer.venues.index'))
            ->assertRedirect(route('organizer.status'));

        $this->actingAs($user, 'web')
            ->get(route('organizer.venues.create'))
            ->assertRedirect(route('organizer.status'));
    }

    public function test_admin_venue_route_still_works(): void
    {
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.venue.index'))
            ->assertSuccessful();
    }

    private function makeOrganizer(string $status, string $email = 'organizer@example.com'): User
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
}
