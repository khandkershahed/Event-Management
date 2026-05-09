<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceRateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_rate_limited_cart_endpoint_still_works_under_safe_usage(): void
    {
        $data = $this->createPublishedTicket();

        $this->actingAs($data['customer'])
            ->post(route('frontend.cart.add'), [
                'event_ticket_id' => $data['ticket']->id,
                'quantity' => 1,
            ])
            ->assertRedirect(route('frontend.cart'));
    }

    public function test_rate_limited_checkout_endpoint_still_redirects_normally_under_safe_usage(): void
    {
        $this->post(route('frontend.order.process'))
            ->assertRedirect(route('homepage'));
    }

    public function test_rate_limited_check_in_endpoint_still_validates_request_normally_under_safe_usage(): void
    {
        $data = $this->createPublishedTicket();

        $this->actingAs($data['owner'])
            ->post(route('organizer.check-in.validate'), [
                'event_id' => $data['event']->id,
                'ticket_code' => 'UNKNOWN-CODE',
            ])
            ->assertOk()
            ->assertSee('No ticket was found for this code.');
    }

    private function createPublishedTicket(): array
    {
        $owner = User::factory()->create(['email' => 'rate-owner-' . uniqid() . '@example.com']);
        $customer = User::factory()->create(['email' => 'rate-customer-' . uniqid() . '@example.com']);

        $organizer = OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => 'Rate Limit Organizer ' . uniqid(),
            'slug' => 'rate-limit-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Rate Limited Event ' . uniqid(),
            'slug' => 'rate-limited-event-' . uniqid(),
            'status' => Event::STATUS_PUBLISHED,
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'venue' => 'Rate Venue',
        ]);

        $ticket = EventTicket::create([
            'event_id' => $event->id,
            'name' => 'Rate Limit Ticket',
            'ticket_type' => EventTicket::TYPE_FREE,
            'price' => 0,
            'currency' => 'BDT',
            'quantity' => 20,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 5,
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        return compact('owner', 'customer', 'organizer', 'event', 'ticket');
    }
}
