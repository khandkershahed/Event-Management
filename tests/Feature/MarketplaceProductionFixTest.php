<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class MarketplaceProductionFixTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_organizer_login_opens_organizer_dashboard(): void
    {
        $this->seed();

        $organizer = User::where('email', 'demo.organizer@example.com')->firstOrFail();

        $response = $this->post('/login', [
            'email' => $organizer->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('organizer.dashboard', absolute: false));
    }

    public function test_organizer_can_open_visual_seating_plan_designer(): void
    {
        $this->seed();

        $organizer = User::where('email', 'demo.organizer@example.com')->firstOrFail();
        $profile = OrganizerProfile::where('user_id', $organizer->id)->firstOrFail();
        $plan = SeatingPlan::where('organizer_profile_id', $profile->id)->firstOrFail();

        $this->actingAs($organizer)
            ->get(route('organizer.seating-plans.designer', $plan))
            ->assertOk()
            ->assertSee('Seat Map Designer')
            ->assertSee('Save');
    }

    public function test_public_seat_selection_uses_visual_map_when_designer_json_exists(): void
    {
        $this->seed();

        $event = Event::where('slug', 'demo-marketplace-summit')->firstOrFail();

        $this->get(route('frontend.seats.select', $event))
            ->assertOk()
            ->assertSee('visual-seat-map')
            ->assertSee('Click an available seat to lock it')
            ->assertSee('cancel');
    }

    public function test_customer_dashboard_has_dynamic_marketplace_summary(): void
    {
        $this->seed();

        $customer = User::where('email', 'demo.customer@example.com')->firstOrFail();

        $this->actingAs($customer)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('Followed Organizers')
            ->assertSee('Support Tickets')
            ->assertSee('Refund Requests')
            ->assertSee('Unread Notifications');
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $blocked = ['TemporaryBooking', 'TemporaryBookingSeat', 'EventSeatType', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'];
        $routes = collect(Route::getRoutes())->map(fn ($route) => $route->uri().' '.$route->getActionName())->implode('\n');

        foreach ($blocked as $term) {
            $this->assertStringNotContainsString($term, $routes);
        }
    }
}
