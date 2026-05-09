<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdvancedLoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_customer_login_redirects_to_user_dashboard(): void
    {
        $user = User::factory()->create(['email' => 'customer-login@example.com']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('user.dashboard', absolute: false));

        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_approved_organizer_owner_login_redirects_to_organizer_dashboard(): void
    {
        [$owner] = $this->createOrganizerOwner(OrganizerProfile::STATUS_APPROVED, 'approved-owner@example.com');

        $this->post('/login', [
            'email' => $owner->email,
            'password' => 'password',
        ])->assertRedirect(route('organizer.dashboard', absolute: false));

        $this->assertAuthenticatedAs($owner, 'web');
    }

    public function test_pending_organizer_owner_does_not_enter_organizer_dashboard(): void
    {
        [$owner] = $this->createOrganizerOwner(OrganizerProfile::STATUS_PENDING, 'pending-owner@example.com');

        $this->post('/login', [
            'email' => $owner->email,
            'password' => 'password',
        ])->assertRedirect(route('organizer.status', absolute: false));

        $this->actingAs($owner)
            ->get(route('organizer.dashboard'))
            ->assertRedirect(route('organizer.status'));
    }

    public function test_rejected_and_suspended_organizer_owners_do_not_enter_organizer_dashboard(): void
    {
        foreach ([OrganizerProfile::STATUS_REJECTED, OrganizerProfile::STATUS_SUSPENDED] as $status) {
            [$owner] = $this->createOrganizerOwner($status, $status . '-owner@example.com');

            $this->post('/login', [
                'email' => $owner->email,
                'password' => 'password',
            ])->assertRedirect(route('organizer.status', absolute: false));

            $this->post('/logout');
        }
    }

    public function test_active_organizer_manager_login_redirects_to_allowed_organizer_dashboard(): void
    {
        $organizer = $this->createApprovedOrganizerProfile('Manager Login Organizer');
        $manager = User::factory()->create(['email' => 'organizer.manager@example.com']);
        $this->createMember($organizer, $manager, OrganizerTeamMember::ROLE_MANAGER);

        $this->post('/login', [
            'email' => $manager->email,
            'password' => 'password',
        ])->assertRedirect(route('organizer.dashboard', absolute: false));
    }

    public function test_active_check_in_staff_login_redirects_to_check_in_area(): void
    {
        $organizer = $this->createApprovedOrganizerProfile('Check In Login Organizer');
        $staff = User::factory()->create(['email' => 'organizer.checkin@example.com']);
        $this->createMember($organizer, $staff, OrganizerTeamMember::ROLE_CHECK_IN_STAFF);

        $this->withSession(['url.intended' => url('/organizer/dashboard')])
            ->post('/login', [
                'email' => $staff->email,
                'password' => 'password',
            ])->assertRedirect(route('organizer.check-in.index', absolute: false));
    }

    public function test_active_finance_viewer_login_redirects_to_reports_area(): void
    {
        $organizer = $this->createApprovedOrganizerProfile('Finance Login Organizer');
        $finance = User::factory()->create(['email' => 'organizer.finance@example.com']);
        $this->createMember($organizer, $finance, OrganizerTeamMember::ROLE_FINANCE_VIEWER);

        $this->post('/login', [
            'email' => $finance->email,
            'password' => 'password',
        ])->assertRedirect(route('organizer.reports.index', absolute: false));
    }

    public function test_inactive_organizer_staff_login_redirects_safely_outside_organizer_panel(): void
    {
        $organizer = $this->createApprovedOrganizerProfile('Inactive Staff Login Organizer');
        $staff = User::factory()->create(['email' => 'inactive.organizer.staff@example.com']);
        $this->createMember($organizer, $staff, OrganizerTeamMember::ROLE_MANAGER, OrganizerTeamMember::STATUS_INACTIVE);

        $this->post('/login', [
            'email' => $staff->email,
            'password' => 'password',
        ])->assertRedirect(route('user.dashboard', absolute: false));
    }

    public function test_admin_login_route_remains_separate_and_redirects_to_admin_dashboard(): void
    {
        $admin = Admin::create([
            'name' => 'A1 Admin',
            'email' => 'a1-admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard', absolute: false));

        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_route_safety_still_avoids_old_booking_architecture(): void
    {
        $routeOutput = collect(app('router')->getRoutes())
            ->map(fn ($route) => $route->uri() . ' ' . $route->getActionName())
            ->implode(' ');

        $this->assertStringNotContainsString('TemporaryBooking', $routeOutput);
        $this->assertStringNotContainsString('TemporaryBookingSeat', $routeOutput);
        $this->assertStringNotContainsString('BookingController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatController', $routeOutput);
        $this->assertStringNotContainsString('EventSeatTypeController', $routeOutput);
        $this->assertStringNotContainsString('ClearExpiredTemporaryBookings', $routeOutput);
    }

    private function createOrganizerOwner(string $status, string $email): array
    {
        $user = User::factory()->create(['email' => $email]);

        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => ucfirst($status) . ' Organizer',
            'slug' => $status . '-organizer-' . uniqid(),
            'status' => $status,
            'submitted_at' => now()->subDay(),
            'approved_at' => $status === OrganizerProfile::STATUS_APPROVED ? now() : null,
        ]);

        return [$user, $profile];
    }

    private function createApprovedOrganizerProfile(string $name): OrganizerProfile
    {
        $owner = User::factory()->create([
            'email' => str()->slug($name) . '@example.com',
        ]);

        return OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => $name,
            'slug' => str()->slug($name) . '-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'submitted_at' => now()->subDay(),
            'approved_at' => now(),
        ]);
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
}
