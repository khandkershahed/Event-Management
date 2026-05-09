<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminEventApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_pending_events_and_approve_event(): void
    {
        $admin = $this->makeAdmin();
        $event = $this->makeSubmittedEvent('Admin Approval Event');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.event-approvals.index'))
            ->assertSuccessful()
            ->assertSee('Admin Approval Event');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.event-approvals.approve', $event))
            ->assertRedirect(route('admin.event-approvals.show', $event));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'status' => Event::STATUS_APPROVED,
            'approved_by' => $admin->id,
        ]);
    }

    public function test_admin_can_reject_event_with_reason(): void
    {
        $admin = $this->makeAdmin('reject-admin@example.com');
        $event = $this->makeSubmittedEvent('Admin Rejection Event');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.event-approvals.reject', $event), [
                'rejection_reason' => 'Please improve the event description.',
            ])
            ->assertRedirect(route('admin.event-approvals.show', $event));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'status' => Event::STATUS_REJECTED,
            'rejection_reason' => 'Please improve the event description.',
        ]);
    }

    public function test_admin_event_route_still_works(): void
    {
        $admin = $this->makeAdmin('event-route-admin@example.com');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.event.index'))
            ->assertSuccessful();
    }

    private function makeAdmin(string $email = 'admin@example.com'): Admin
    {
        return Admin::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make('password'),
        ]);
    }

    private function makeSubmittedEvent(string $name): Event
    {
        $user = User::create([
            'name' => 'Organizer User',
            'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
            'password' => Hash::make('password'),
        ]);

        $profile = $user->organizerProfile()->create([
            'organization_name' => 'Organizer ' . md5($name),
            'slug' => 'organizer-' . md5($name),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'submitted_at' => now(),
            'approved_at' => now(),
        ]);

        $venue = Venue::create([
            'organizer_profile_id' => $profile->id,
            'organizer_id' => $user->id,
            'name' => 'Approval Venue ' . md5($name),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
        ]);

        $plan = SeatingPlan::create([
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'name' => 'Approval Layout ' . md5($name),
            'status' => SeatingPlan::STATUS_ACTIVE,
        ]);

        $type = EventType::create([
            'name' => 'Approval Type ' . md5($name),
            'slug' => 'approval-type-' . md5($name),
            'code' => 'APV' . substr(md5($name), 0, 5),
            'status' => 'active',
        ]);

        return Event::create([
            'organizer_profile_id' => $profile->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'venue' => $venue->name,
            'description' => 'Submitted test event.',
            'status' => Event::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);
    }
}
