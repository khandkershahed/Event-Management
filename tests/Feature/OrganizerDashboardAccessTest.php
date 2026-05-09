<?php

namespace Tests\Feature;

use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrganizerDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_organizer_dashboard(): void
    {
        $this->get(route('organizer.dashboard'))->assertRedirect(route('login'));
    }

    public function test_normal_user_without_organizer_profile_is_blocked(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web')
            ->get(route('organizer.dashboard'))
            ->assertRedirect(route('organizer.status'));
    }

    public function test_pending_organizer_is_blocked(): void
    {
        $user = User::factory()->create();
        OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Pending Test Organizer',
            'slug' => 'pending-test-organizer',
            'status' => OrganizerProfile::STATUS_PENDING,
            'submitted_at' => now(),
        ]);

        $this->actingAs($user, 'web')
            ->get(route('organizer.dashboard'))
            ->assertRedirect(route('organizer.status'));
    }

    public function test_rejected_organizer_is_blocked(): void
    {
        $user = User::factory()->create();
        OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Rejected Test Organizer',
            'slug' => 'rejected-test-organizer',
            'status' => OrganizerProfile::STATUS_REJECTED,
            'rejection_reason' => 'Incomplete information.',
        ]);

        $this->actingAs($user, 'web')
            ->get(route('organizer.dashboard'))
            ->assertRedirect(route('organizer.status'));
    }

    public function test_suspended_organizer_is_blocked(): void
    {
        $user = User::factory()->create();
        OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Suspended Test Organizer',
            'slug' => 'suspended-test-organizer',
            'status' => OrganizerProfile::STATUS_SUSPENDED,
        ]);

        $this->actingAs($user, 'web')
            ->get(route('organizer.dashboard'))
            ->assertRedirect(route('organizer.status'));
    }

    public function test_approved_organizer_can_access_dashboard_orders_and_reports(): void
    {
        $user = User::factory()->create([
            'email' => 'approved.dashboard@example.com',
            'password' => Hash::make('password'),
        ]);

        OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Approved Dashboard Organizer',
            'slug' => 'approved-dashboard-organizer',
            'status' => OrganizerProfile::STATUS_APPROVED,
            'submitted_at' => now()->subDay(),
            'approved_at' => now(),
        ]);

        $this->actingAs($user, 'web')
            ->get(route('organizer.dashboard'))
            ->assertOk()
            ->assertSee('Organizer Dashboard')
            ->assertSee('Total Events')
            ->assertSee('Pending Payout');

        $this->actingAs($user, 'web')->get(route('organizer.orders.index'))->assertOk()->assertSee('Orders');
        $this->actingAs($user, 'web')->get(route('organizer.reports.index'))->assertOk()->assertSee('Reports');
    }
}
