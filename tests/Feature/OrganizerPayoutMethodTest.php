<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\OrganizerLedger;
use App\Models\OrganizerPayout;
use App\Models\OrganizerPayoutMethod;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizerPayoutMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_and_update_payout_method(): void
    {
        [$owner, $organizer] = $this->createOrganizer('Finance Owner');

        $this->actingAs($owner)
            ->get(route('organizer.finance-profile.show'))
            ->assertOk()
            ->assertSee('Finance Profile');

        $this->actingAs($owner)
            ->put(route('organizer.finance-profile.update'), $this->validBankPayload(['bank_name' => 'First Demo Bank']))
            ->assertRedirect(route('organizer.finance-profile.show'));

        $this->assertDatabaseHas('organizer_payout_methods', [
            'organizer_profile_id' => $organizer->id,
            'method_type' => OrganizerPayoutMethod::METHOD_BANK,
            'bank_name' => 'First Demo Bank',
            'status' => OrganizerPayoutMethod::STATUS_PENDING_REVIEW,
        ]);
    }

    public function test_finance_viewer_can_view_but_not_edit_payout_method(): void
    {
        $organizer = $this->createOrganizer('Finance Viewer Org')[1];
        $this->createVerifiedPayoutMethod($organizer);
        $financeViewer = User::factory()->create();
        $this->createMember($organizer, $financeViewer, OrganizerTeamMember::ROLE_FINANCE_VIEWER);

        $this->actingAs($financeViewer)
            ->get(route('organizer.finance-profile.show'))
            ->assertOk()
            ->assertSee('read-only');

        $this->actingAs($financeViewer)
            ->put(route('organizer.finance-profile.update'), $this->validBankPayload())
            ->assertForbidden();
    }

    public function test_manager_and_check_in_staff_cannot_access_finance_profile(): void
    {
        $organizer = $this->createOrganizer('Restricted Finance Org')[1];
        $manager = User::factory()->create();
        $checkIn = User::factory()->create();
        $this->createMember($organizer, $manager, OrganizerTeamMember::ROLE_MANAGER);
        $this->createMember($organizer, $checkIn, OrganizerTeamMember::ROLE_CHECK_IN_STAFF);

        $this->actingAs($manager)->get(route('organizer.finance-profile.show'))->assertForbidden();
        $this->actingAs($checkIn)->get(route('organizer.finance-profile.show'))->assertForbidden();
    }

    public function test_admin_can_verify_and_reject_payout_method(): void
    {
        $organizer = $this->createOrganizer('Admin Verify Org')[1];
        $method = $this->createPendingPayoutMethod($organizer);
        $admin = Admin::create(['name' => 'Finance Admin', 'email' => 'pm-admin@example.com', 'password' => bcrypt('password')]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.payout-methods.show', $method))
            ->assertOk()
            ->assertSee('Review Payout Method');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payout-methods.verify', $method), ['admin_note' => 'Verified account.'])
            ->assertRedirect(route('admin.payout-methods.show', $method));

        $this->assertDatabaseHas('organizer_payout_methods', [
            'id' => $method->id,
            'status' => OrganizerPayoutMethod::STATUS_VERIFIED,
            'admin_note' => 'Verified account.',
        ]);

        $second = $this->createPendingPayoutMethod($this->createOrganizer('Admin Reject Org')[1]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payout-methods.reject', $second), ['admin_note' => 'Account mismatch.'])
            ->assertRedirect(route('admin.payout-methods.show', $second));

        $this->assertDatabaseHas('organizer_payout_methods', [
            'id' => $second->id,
            'status' => OrganizerPayoutMethod::STATUS_REJECTED,
            'admin_note' => 'Account mismatch.',
        ]);
    }

    public function test_payout_request_is_blocked_without_valid_payout_method(): void
    {
        [$owner, $organizer] = $this->createOrganizer('Blocked Payout Org');
        $this->creditOrganizer($organizer, 300);

        $this->actingAs($owner)
            ->post(route('organizer.payouts.store'), ['amount' => 100])
            ->assertRedirect();

        $this->assertDatabaseMissing('organizer_payouts', [
            'organizer_profile_id' => $organizer->id,
            'amount' => 100,
        ]);

        $this->createPendingPayoutMethod($organizer);

        $this->actingAs($owner)
            ->post(route('organizer.payouts.store'), ['amount' => 100])
            ->assertRedirect();

        $this->assertDatabaseMissing('organizer_payouts', [
            'organizer_profile_id' => $organizer->id,
            'amount' => 100,
        ]);
    }

    public function test_payout_request_succeeds_with_verified_payout_method(): void
    {
        [$owner, $organizer] = $this->createOrganizer('Valid Payout Org');
        $this->createVerifiedPayoutMethod($organizer);
        $this->creditOrganizer($organizer, 300);

        $this->actingAs($owner)
            ->post(route('organizer.payouts.store'), ['amount' => 100, 'notes' => 'Ready for payout.'])
            ->assertRedirect(route('organizer.payouts.index'));

        $this->assertDatabaseHas('organizer_payouts', [
            'organizer_profile_id' => $organizer->id,
            'amount' => 100,
            'status' => OrganizerPayout::STATUS_PENDING,
        ]);
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
        $user = User::factory()->create(['email' => str()->slug($name) . '@example.com']);
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => $name,
            'slug' => str()->slug($name) . '-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        return [$user, $organizer];
    }

    private function createMember(OrganizerProfile $organizer, User $user, string $role): OrganizerTeamMember
    {
        return OrganizerTeamMember::create([
            'organizer_profile_id' => $organizer->id,
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $role,
            'status' => OrganizerTeamMember::STATUS_ACTIVE,
            'invited_by' => $organizer->user_id,
            'invited_at' => now(),
            'accepted_at' => now(),
        ]);
    }

    private function createPendingPayoutMethod(OrganizerProfile $organizer): OrganizerPayoutMethod
    {
        return OrganizerPayoutMethod::create(array_merge($this->validBankPayload(), [
            'organizer_profile_id' => $organizer->id,
            'status' => OrganizerPayoutMethod::STATUS_PENDING_REVIEW,
            'is_active' => true,
            'requires_verification' => true,
            'submitted_at' => now(),
        ]));
    }

    private function createVerifiedPayoutMethod(OrganizerProfile $organizer): OrganizerPayoutMethod
    {
        return OrganizerPayoutMethod::create(array_merge($this->validBankPayload(), [
            'organizer_profile_id' => $organizer->id,
            'status' => OrganizerPayoutMethod::STATUS_VERIFIED,
            'is_active' => true,
            'requires_verification' => true,
            'submitted_at' => now(),
            'reviewed_at' => now(),
        ]));
    }

    private function validBankPayload(array $overrides = []): array
    {
        return array_merge([
            'method_type' => OrganizerPayoutMethod::METHOD_BANK,
            'currency' => 'BDT',
            'account_holder_name' => 'Demo Account Holder',
            'bank_name' => 'Demo Bank',
            'branch_name' => 'Main Branch',
            'account_number' => '1234567890',
            'routing_number' => '000111222',
            'organizer_note' => 'Test payout method.',
        ], $overrides);
    }

    private function creditOrganizer(OrganizerProfile $organizer, float $amount): OrganizerLedger
    {
        return OrganizerLedger::create([
            'organizer_profile_id' => $organizer->id,
            'type' => OrganizerLedger::TYPE_ORDER_EARNING,
            'direction' => OrganizerLedger::DIRECTION_CREDIT,
            'amount' => $amount,
            'currency' => 'BDT',
            'status' => OrganizerLedger::STATUS_POSTED,
            'description' => 'Test earning credit.',
            'posted_at' => now(),
        ]);
    }
}
