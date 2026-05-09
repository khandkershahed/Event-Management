<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\OrganizerLedger;
use App\Models\OrganizerPayout;
use App\Models\OrganizerPayoutMethod;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Services\OrganizerLedgerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizerPayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_view_available_balance(): void
    {
        [$user, $organizer] = $this->createOrganizer();
        $this->creditOrganizer($organizer, 250);

        $this->actingAs($user)
            ->get(route('organizer.payouts.index'))
            ->assertOk()
            ->assertSee('Available Balance')
            ->assertSee('250.00');
    }

    public function test_organizer_can_request_payout(): void
    {
        [$user, $organizer] = $this->createOrganizer();
        $this->createVerifiedPayoutMethod($organizer);
        $this->creditOrganizer($organizer, 300);

        $this->actingAs($user)
            ->post(route('organizer.payouts.store'), [
                'amount' => 120,
                'notes' => 'Please pay manually.',
            ])
            ->assertRedirect(route('organizer.payouts.index'));

        $this->assertDatabaseHas('organizer_payouts', [
            'organizer_profile_id' => $organizer->id,
            'amount' => 120,
            'status' => OrganizerPayout::STATUS_PENDING,
        ]);
    }

    public function test_admin_can_approve_reject_and_mark_payout_paid(): void
    {
        [$user, $organizer] = $this->createOrganizer();
        $admin = Admin::create(['name' => 'Finance Admin', 'email' => 'finance-admin@example.com', 'password' => bcrypt('password')]);
        $this->creditOrganizer($organizer, 500);

        $payout = app(OrganizerLedgerService::class)->requestPayout($organizer, 200, $user->id);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payouts.approve', $payout))
            ->assertRedirect(route('admin.payouts.show', $payout));

        $this->assertDatabaseHas('organizer_payouts', [
            'id' => $payout->id,
            'status' => OrganizerPayout::STATUS_APPROVED,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.payouts.mark-paid', $payout))
            ->assertRedirect(route('admin.payouts.show', $payout));

        $this->assertDatabaseHas('organizer_payouts', [
            'id' => $payout->id,
            'status' => OrganizerPayout::STATUS_PAID,
        ]);
        $this->assertDatabaseHas('organizer_ledgers', [
            'organizer_profile_id' => $organizer->id,
            'organizer_payout_id' => $payout->id,
            'type' => OrganizerLedger::TYPE_PAYOUT_PAID,
            'direction' => OrganizerLedger::DIRECTION_DEBIT,
            'amount' => 200,
        ]);

        $second = app(OrganizerLedgerService::class)->requestPayout($organizer, 50, $user->id);
        $this->actingAs($admin, 'admin')
            ->post(route('admin.payouts.reject', $second), ['rejection_reason' => 'Bank details missing.'])
            ->assertRedirect(route('admin.payouts.show', $second));

        $this->assertDatabaseHas('organizer_payouts', [
            'id' => $second->id,
            'status' => OrganizerPayout::STATUS_REJECTED,
            'rejection_reason' => 'Bank details missing.',
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

    protected function createOrganizer(string $name = 'Payout Organizer'): array
    {
        $user = User::factory()->create();
        $organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => $name,
            'slug' => str()->slug($name) . '-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        return [$user, $organizer];
    }

    protected function createVerifiedPayoutMethod(OrganizerProfile $organizer): OrganizerPayoutMethod
    {
        return OrganizerPayoutMethod::create([
            'organizer_profile_id' => $organizer->id,
            'method_type' => OrganizerPayoutMethod::METHOD_BANK,
            'status' => OrganizerPayoutMethod::STATUS_VERIFIED,
            'is_active' => true,
            'requires_verification' => true,
            'currency' => 'BDT',
            'account_holder_name' => 'Payout Organizer',
            'bank_name' => 'Demo Bank',
            'branch_name' => 'Main Branch',
            'account_number' => '1234567890',
            'submitted_at' => now(),
            'reviewed_at' => now(),
        ]);
    }

    protected function creditOrganizer(OrganizerProfile $organizer, float $amount): OrganizerLedger
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
