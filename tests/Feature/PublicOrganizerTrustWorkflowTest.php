<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Event;
use App\Models\MarketplaceEventReview;
use App\Models\MarketplaceOrganizerRating;
use App\Models\OrganizerFollower;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTrustBadge;
use App\Models\User;
use App\Notifications\OrganizerTrustBadgeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PublicOrganizerTrustWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_organizer_page_shows_reputation_badges_and_published_events(): void
    {
        $data = $this->fixture();

        MarketplaceOrganizerRating::create([
            'organizer_profile_id' => $data['organizer']->id,
            'approved_reviews_count' => 2,
            'average_rating' => 4.50,
            'last_reviewed_at' => now(),
        ]);

        MarketplaceEventReview::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'organizer_profile_id' => $data['organizer']->id,
            'rating' => 5,
            'title' => 'Trusted review',
            'body' => 'Excellent organizer.',
            'status' => MarketplaceEventReview::STATUS_APPROVED,
        ]);

        OrganizerTrustBadge::create([
            'organizer_profile_id' => $data['organizer']->id,
            'badge_key' => OrganizerTrustBadge::BADGE_TRUSTED_ORGANIZER,
            'label' => 'Trusted Organizer',
            'status' => OrganizerTrustBadge::STATUS_APPROVED,
            'is_public' => true,
        ]);

        OrganizerTrustBadge::create([
            'organizer_profile_id' => $data['organizer']->id,
            'badge_key' => OrganizerTrustBadge::BADGE_RESPONSIVE_SUPPORT,
            'label' => 'Hidden Internal Badge',
            'status' => OrganizerTrustBadge::STATUS_HIDDEN,
            'is_public' => false,
        ]);

        $this->get(route('public.organizers.show', $data['organizer']->slug))
            ->assertOk()
            ->assertSee($data['organizer']->organization_name)
            ->assertSee('4.50/5')
            ->assertSee('Trusted Organizer')
            ->assertSee($data['event']->name)
            ->assertSee('Trusted review')
            ->assertDontSee('Hidden Internal Badge');
    }

    public function test_logged_in_customer_can_follow_and_unfollow_organizer(): void
    {
        $data = $this->fixture();

        $this->actingAs($data['customer'])->post(route('public.organizers.follow', $data['organizer']))
            ->assertRedirect();

        $this->assertDatabaseHas('organizer_followers', [
            'user_id' => $data['customer']->id,
            'organizer_profile_id' => $data['organizer']->id,
        ]);

        $this->actingAs($data['customer'])->delete(route('public.organizers.unfollow', $data['organizer']))
            ->assertRedirect();

        $this->assertDatabaseMissing('organizer_followers', [
            'user_id' => $data['customer']->id,
            'organizer_profile_id' => $data['organizer']->id,
        ]);
    }

    public function test_customer_can_view_followed_organizer_list(): void
    {
        $data = $this->fixture();

        OrganizerFollower::create([
            'user_id' => $data['customer']->id,
            'organizer_profile_id' => $data['organizer']->id,
        ]);

        $this->actingAs($data['customer'])->get(route('user.followed-organizers.index'))
            ->assertOk()
            ->assertSee($data['organizer']->organization_name);
    }

    public function test_admin_can_manage_trust_badges_and_public_page_shows_approved_badge(): void
    {
        $data = $this->fixture();
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->get(route('admin.organizer-trust-badges.index', ['search' => $data['organizer']->organization_name]))
            ->assertOk()
            ->assertSee($data['organizer']->organization_name);

        $this->actingAs($admin, 'admin')->post(route('admin.organizer-trust-badges.store', $data['organizer']), [
            'badge_key' => OrganizerTrustBadge::BADGE_VERIFIED_ORGANIZER,
            'label' => 'Verified Organizer',
            'status' => OrganizerTrustBadge::STATUS_APPROVED,
            'is_public' => 1,
            'description' => 'Manually verified by platform admin.',
            'admin_note' => 'Looks good.',
        ])->assertRedirect();

        $badge = OrganizerTrustBadge::where('organizer_profile_id', $data['organizer']->id)->firstOrFail();

        $this->assertDatabaseHas('organizer_trust_badges', [
            'id' => $badge->id,
            'badge_key' => OrganizerTrustBadge::BADGE_VERIFIED_ORGANIZER,
            'status' => OrganizerTrustBadge::STATUS_APPROVED,
            'is_public' => true,
        ]);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin.organizer_trust_badge.saved',
            'auditable_type' => OrganizerTrustBadge::class,
            'auditable_id' => $badge->id,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $data['owner']->id,
            'type' => OrganizerTrustBadgeNotification::class,
        ]);

        $this->get(route('public.organizers.show', $data['organizer']->slug))
            ->assertOk()
            ->assertSee('Verified Organizer');

        $this->actingAs($admin, 'admin')->delete(route('admin.organizer-trust-badges.destroy', $badge))
            ->assertRedirect();

        $this->assertDatabaseMissing('organizer_trust_badges', ['id' => $badge->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'admin.organizer_trust_badge.removed']);
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        foreach (['TemporaryBooking', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'] as $blocked) {
            $this->assertStringNotContainsString($blocked, $routeOutput);
        }
    }

    private function fixture(): array
    {
        $owner = User::factory()->create(['email' => 'trust-owner-' . uniqid() . '@example.com']);
        $customer = User::factory()->create(['email' => 'trust-customer-' . uniqid() . '@example.com']);

        $organizer = OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => 'Trust Organizer ' . uniqid(),
            'slug' => 'trust-organizer-' . uniqid(),
            'email' => 'organizer@example.com',
            'phone' => '123456789',
            'website' => 'https://example.com',
            'description' => 'Organizer public description.',
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Trust Event ' . uniqid(),
            'slug' => 'trust-event-' . uniqid(),
            'status' => Event::STATUS_PUBLISHED,
            'start_date' => now()->addWeek()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'venue' => 'Trust Venue',
        ]);

        return compact('owner', 'customer', 'organizer', 'event');
    }

    private function admin(): Admin
    {
        return Admin::create([
            'name' => 'Trust Admin',
            'email' => 'trust-admin-' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
