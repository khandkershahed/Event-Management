<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\SeatingPlan;
use App\Models\User;
use App\Models\Venue;
use Database\Seeders\AdvancedOrganizerAuthEventPanelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdvancedOrganizerAuthEventPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_auth_pages_render(): void
    {
        $this->get(route('organizer.login'))->assertOk()->assertSee('Organizer Login');
        $this->get(route('organizer.register'))->assertOk()->assertSee('Create Organizer Account');
    }

    public function test_organizer_registration_creates_user_and_pending_profile(): void
    {
        $this->post(route('organizer.register.store'), [
            'name' => 'New Organizer',
            'email' => 'new-organizer@example.com',
            'phone' => '01700009999',
            'password' => 'password',
            'password_confirmation' => 'password',
            'organization_name' => 'New Organizer Company',
            'contact_person' => 'New Organizer',
        ])->assertRedirect(route('organizer.status'));

        $this->assertDatabaseHas('users', ['email' => 'new-organizer@example.com']);
        $this->assertDatabaseHas('organizer_profiles', [
            'organization_name' => 'New Organizer Company',
            'status' => OrganizerProfile::STATUS_PENDING,
        ]);
    }

    public function test_organizer_login_rejects_customer_and_redirects_roles(): void
    {
        $customer = User::factory()->create(['email' => 'plain-customer@example.com']);
        $this->post(route('organizer.login.store'), ['email' => $customer->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        [$owner, $profile] = $this->approvedOrganizer('owner-login@example.com');
        $this->post(route('organizer.login.store'), ['email' => $owner->email, 'password' => 'password'])
            ->assertRedirect(route('organizer.dashboard', absolute: false));
        $this->post(route('logout'));

        [$pending] = $this->organizerOwner(OrganizerProfile::STATUS_PENDING, 'pending-login@example.com');
        $this->post(route('organizer.login.store'), ['email' => $pending->email, 'password' => 'password'])
            ->assertRedirect(route('organizer.status', absolute: false));
        $this->post(route('logout'));

        $checkIn = User::factory()->create(['email' => 'checkin-role@example.com']);
        $this->member($profile, $checkIn, OrganizerTeamMember::ROLE_CHECK_IN_STAFF);
        $this->post(route('organizer.login.store'), ['email' => $checkIn->email, 'password' => 'password'])
            ->assertRedirect(route('organizer.check-in.index', absolute: false));
        $this->post(route('logout'));

        $finance = User::factory()->create(['email' => 'finance-role@example.com']);
        $this->member($profile, $finance, OrganizerTeamMember::ROLE_FINANCE_VIEWER);
        $this->post(route('organizer.login.store'), ['email' => $finance->email, 'password' => 'password'])
            ->assertRedirect(route('organizer.reports.index', absolute: false));
    }

    public function test_customer_and_admin_login_remain_separate(): void
    {
        $customer = User::factory()->create(['email' => 'customer-still@example.com']);
        $this->post('/login', ['email' => $customer->email, 'password' => 'password'])
            ->assertRedirect(route('user.dashboard', absolute: false));
        $this->post(route('logout'));

        $admin = Admin::create(['name' => 'A9 Admin', 'email' => 'admin-a9@example.com', 'password' => Hash::make('password')]);
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_admin_and_organizer_event_control_panel_access_and_content(): void
    {
        $admin = Admin::create(['name' => 'A9 Admin', 'email' => 'panel-admin@example.com', 'password' => Hash::make('password')]);
        [$owner, $profile] = $this->approvedOrganizer('panel-owner@example.com');
        $event = $this->eventFor($profile);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.events.control', $event))
            ->assertOk()
            ->assertSee('Event Control Actions')
            ->assertSee('Manage Ticket Types')
            ->assertSee('Open Seat Map Designer')
            ->assertSee('Guided Event Setup Checklist');

        $this->actingAs($owner, 'web')
            ->get(route('organizer.events.control', $event))
            ->assertOk()
            ->assertSee('Event Control Actions')
            ->assertSee('View Attendees')
            ->assertSee('View Sales Report');

        [$otherOwner, $otherProfile] = $this->approvedOrganizer('other-owner@example.com');
        $this->actingAs($otherOwner, 'web')
            ->get(route('organizer.events.control', $event))
            ->assertForbidden();
    }

    public function test_improved_forms_render_and_routes_avoid_legacy_architecture(): void
    {
        [$owner, $profile] = $this->approvedOrganizer('form-owner@example.com');
        $event = $this->eventFor($profile);

        $this->actingAs($owner, 'web')->get(route('organizer.events.create'))
            ->assertOk()->assertSee('Media')->assertSee('Organizer Branding');
        $this->actingAs($owner, 'web')->get(route('organizer.venues.create'))
            ->assertOk()->assertSee('Venue Image');
        $this->actingAs($owner, 'web')->get(route('organizer.seating-plans.create'))
            ->assertOk()->assertSee('Simple seating plan setup');
        $this->actingAs($owner, 'web')->get(route('organizer.events.ticket-types.index', $event))
            ->assertOk()->assertSee('Ticket');

        $routes = collect(app('router')->getRoutes())->map(fn ($route) => $route->uri() . ' ' . $route->getActionName())->implode(' ');
        foreach (['TemporaryBooking', 'TemporaryBookingSeat', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'] as $legacy) {
            $this->assertStringNotContainsString($legacy, $routes);
        }
    }

    public function test_a9_seeder_is_idempotent(): void
    {
        $this->seed(AdvancedOrganizerAuthEventPanelSeeder::class);
        $this->seed(AdvancedOrganizerAuthEventPanelSeeder::class);

        $this->assertDatabaseHas('organizer_profiles', ['slug' => 'a9-marketplace-events']);
        $this->assertEquals(1, Event::where('slug', 'a9-published-event')->count());
    }

    private function organizerOwner(string $status, string $email): array
    {
        $user = User::factory()->create(['email' => $email]);
        $profile = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Organizer ' . uniqid(),
            'slug' => 'organizer-' . uniqid(),
            'status' => $status,
            'submitted_at' => now(),
            'approved_at' => $status === OrganizerProfile::STATUS_APPROVED ? now() : null,
        ]);

        return [$user, $profile];
    }

    private function approvedOrganizer(string $email): array
    {
        return $this->organizerOwner(OrganizerProfile::STATUS_APPROVED, $email);
    }

    private function member(OrganizerProfile $profile, User $user, string $role): OrganizerTeamMember
    {
        return OrganizerTeamMember::create([
            'organizer_profile_id' => $profile->id,
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $role,
            'status' => OrganizerTeamMember::STATUS_ACTIVE,
            'accepted_at' => now(),
        ]);
    }

    private function eventFor(OrganizerProfile $profile): Event
    {
        $type = EventType::create(['name' => 'A9 Test Type', 'status' => 'active']);
        $venue = Venue::create([
            'organizer_profile_id' => $profile->id,
            'organizer_id' => $profile->user_id,
            'name' => 'A9 Test Venue',
            'slug' => 'a9-test-venue-' . uniqid(),
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 200,
        ]);
        $plan = SeatingPlan::create([
            'organizer_profile_id' => $profile->id,
            'venue_id' => $venue->id,
            'name' => 'A9 Test Plan',
            'status' => SeatingPlan::STATUS_ACTIVE,
            'design_json' => ['items' => []],
        ]);
        $event = Event::create([
            'organizer_profile_id' => $profile->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'seating_plan_id' => $plan->id,
            'name' => 'A9 Test Event ' . uniqid(),
            'slug' => 'a9-test-event-' . uniqid(),
            'description' => 'Test event control panel.',
            'venue' => $venue->name,
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'status' => Event::STATUS_DRAFT,
        ]);
        EventTicket::create([
            'event_id' => $event->id,
            'name' => 'Standard',
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => 100,
            'currency' => 'BDT',
            'quantity' => 50,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 5,
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        return $event;
    }
}
