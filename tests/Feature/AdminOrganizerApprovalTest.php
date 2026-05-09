<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminOrganizerApprovalTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin-test@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    private function pendingOrganizer(): OrganizerProfile
    {
        $user = User::factory()->create();

        return OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Pending Admin Organizer',
            'slug' => 'pending-admin-organizer',
            'status' => OrganizerProfile::STATUS_PENDING,
            'submitted_at' => now(),
        ]);
    }

    public function test_admin_can_view_organizer_pages(): void
    {
        $admin = $this->admin();
        $organizer = $this->pendingOrganizer();

        $this->actingAs($admin, 'admin')->get(route('admin.organizers.index'))->assertOk()->assertSee('Organizers');
        $this->actingAs($admin, 'admin')->get(route('admin.organizers.pending'))->assertOk()->assertSee('Pending Organizers');
        $this->actingAs($admin, 'admin')->get(route('admin.organizers.show', $organizer))->assertOk()->assertSee('Pending Admin Organizer');
    }

    public function test_admin_can_approve_reject_and_suspend_organizer(): void
    {
        $admin = $this->admin();
        $organizer = $this->pendingOrganizer();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.organizers.approve', $organizer))
            ->assertRedirect(route('admin.organizers.show', $organizer));

        $this->assertDatabaseHas('organizer_profiles', [
            'id' => $organizer->id,
            'status' => OrganizerProfile::STATUS_APPROVED,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.organizers.reject', $organizer), ['rejection_reason' => 'Need more documents.'])
            ->assertRedirect(route('admin.organizers.show', $organizer));

        $this->assertDatabaseHas('organizer_profiles', [
            'id' => $organizer->id,
            'status' => OrganizerProfile::STATUS_REJECTED,
            'rejection_reason' => 'Need more documents.',
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.organizers.suspend', $organizer))
            ->assertRedirect(route('admin.organizers.show', $organizer));

        $this->assertDatabaseHas('organizer_profiles', [
            'id' => $organizer->id,
            'status' => OrganizerProfile::STATUS_SUSPENDED,
        ]);
    }
}
