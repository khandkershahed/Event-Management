<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AuditLog;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\MarketplaceEventReview;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Notifications\MarketplaceReviewNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MarketplaceReviewWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_review_eligible_event(): void
    {
        $data = $this->reviewFixture();

        $this->actingAs($data['customer'])->post(route('user.event-reviews.store', $data['event']), [
            'order_id' => $data['order']->id,
            'rating' => 5,
            'title' => 'Great event',
            'body' => 'Very organized and enjoyable.',
        ])->assertRedirect(route('user.reviews.index'));

        $this->assertDatabaseHas('marketplace_event_reviews', [
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'order_id' => $data['order']->id,
            'rating' => 5,
            'status' => MarketplaceEventReview::STATUS_PENDING,
        ]);
    }

    public function test_customer_cannot_review_event_without_completed_order_or_ticket(): void
    {
        $data = $this->reviewFixture(false);

        $this->actingAs($data['customer'])->post(route('user.event-reviews.store', $data['event']), [
            'rating' => 4,
            'title' => 'Not eligible',
        ])->assertSessionHasErrors('event_id');
    }

    public function test_duplicate_review_is_blocked(): void
    {
        $data = $this->reviewFixture();

        MarketplaceEventReview::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'organizer_profile_id' => $data['organizer']->id,
            'order_id' => $data['order']->id,
            'rating' => 5,
            'status' => MarketplaceEventReview::STATUS_PENDING,
        ]);

        $this->actingAs($data['customer'])->post(route('user.event-reviews.store', $data['event']), [
            'order_id' => $data['order']->id,
            'rating' => 3,
        ])->assertSessionHasErrors('event_id');
    }

    public function test_public_page_shows_approved_reviews_only(): void
    {
        $data = $this->reviewFixture();

        MarketplaceEventReview::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'organizer_profile_id' => $data['organizer']->id,
            'order_id' => $data['order']->id,
            'rating' => 5,
            'title' => 'Visible review',
            'body' => 'This review should be visible.',
            'status' => MarketplaceEventReview::STATUS_APPROVED,
        ]);

        MarketplaceEventReview::create([
            'user_id' => $data['otherCustomer']->id,
            'event_id' => $data['event']->id,
            'organizer_profile_id' => $data['organizer']->id,
            'rating' => 1,
            'title' => 'Hidden pending review',
            'status' => MarketplaceEventReview::STATUS_PENDING,
        ]);

        $this->get(route('event.details', $data['event']->slug))
            ->assertOk()
            ->assertSee('Visible review')
            ->assertDontSee('Hidden pending review');
    }

    public function test_organizer_can_view_own_reviews(): void
    {
        $data = $this->reviewFixture();

        MarketplaceEventReview::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'organizer_profile_id' => $data['organizer']->id,
            'order_id' => $data['order']->id,
            'rating' => 4,
            'title' => 'Organizer visible review',
            'status' => MarketplaceEventReview::STATUS_APPROVED,
        ]);

        $this->actingAs($data['owner'])
            ->get(route('organizer.reviews.index'))
            ->assertOk()
            ->assertSee('Organizer visible review');
    }

    public function test_admin_can_approve_reject_and_hide_reviews(): void
    {
        $data = $this->reviewFixture();
        $admin = $this->admin();

        $review = MarketplaceEventReview::create([
            'user_id' => $data['customer']->id,
            'event_id' => $data['event']->id,
            'organizer_profile_id' => $data['organizer']->id,
            'order_id' => $data['order']->id,
            'rating' => 5,
            'title' => 'Moderated review',
            'status' => MarketplaceEventReview::STATUS_PENDING,
        ]);

        $this->actingAs($admin, 'admin')->get(route('admin.reviews.index', ['status' => 'pending', 'rating' => 5, 'event_id' => $data['event']->id, 'organizer_id' => $data['organizer']->id, 'user_id' => $data['customer']->id]))
            ->assertOk()
            ->assertSee('Moderated review');

        $this->actingAs($admin, 'admin')->post(route('admin.reviews.approve', $review), ['admin_note' => 'Approved'])
            ->assertRedirect();
        $this->assertDatabaseHas('marketplace_event_reviews', ['id' => $review->id, 'status' => MarketplaceEventReview::STATUS_APPROVED]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'admin.review.approved', 'auditable_type' => MarketplaceEventReview::class, 'auditable_id' => $review->id]);
        $this->assertDatabaseHas('notifications', ['notifiable_type' => User::class, 'notifiable_id' => $data['owner']->id, 'type' => MarketplaceReviewNotification::class]);

        $this->actingAs($admin, 'admin')->post(route('admin.reviews.reject', $review), ['admin_note' => 'Rejected'])
            ->assertRedirect();
        $this->assertDatabaseHas('marketplace_event_reviews', ['id' => $review->id, 'status' => MarketplaceEventReview::STATUS_REJECTED]);

        $this->actingAs($admin, 'admin')->post(route('admin.reviews.hide', $review), ['admin_note' => 'Hidden'])
            ->assertRedirect();
        $this->assertDatabaseHas('marketplace_event_reviews', ['id' => $review->id, 'status' => MarketplaceEventReview::STATUS_HIDDEN]);
    }

    public function test_route_safety_still_avoids_old_architecture(): void
    {
        $this->assertSame(0, Artisan::call('route:list'));
        $routeOutput = collect(app('router')->getRoutes())->map(fn ($route) => $route->getActionName())->implode(' ');

        foreach (['TemporaryBooking', 'BookingController', 'EventSeatController', 'EventSeatTypeController', 'ClearExpiredTemporaryBookings'] as $blocked) {
            $this->assertStringNotContainsString($blocked, $routeOutput);
        }
    }

    private function reviewFixture(bool $eligibleOrder = true): array
    {
        $owner = User::factory()->create(['email' => 'review-owner-' . uniqid() . '@example.com']);
        $customer = User::factory()->create(['email' => 'review-customer-' . uniqid() . '@example.com']);
        $otherCustomer = User::factory()->create(['email' => 'review-other-' . uniqid() . '@example.com']);

        $organizer = OrganizerProfile::create([
            'user_id' => $owner->id,
            'organization_name' => 'Review Organizer ' . uniqid(),
            'slug' => 'review-organizer-' . uniqid(),
            'status' => OrganizerProfile::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $event = Event::create([
            'organizer_profile_id' => $organizer->id,
            'name' => 'Review Event ' . uniqid(),
            'slug' => 'review-event-' . uniqid(),
            'status' => Event::STATUS_PUBLISHED,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->subDay()->toDateString(),
            'venue' => 'Review Venue',
        ]);

        $ticket = EventTicket::create([
            'event_id' => $event->id,
            'name' => 'Review Ticket',
            'ticket_type' => EventTicket::TYPE_FREE,
            'price' => 0,
            'currency' => 'BDT',
            'quantity' => 20,
            'sold_quantity' => 1,
            'min_per_order' => 1,
            'max_per_order' => 5,
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'event_id' => $event->id,
            'order_number' => 'REV-' . strtoupper(uniqid()),
            'subtotal' => 0,
            'discount_total' => 0,
            'fee_total' => 0,
            'total' => 0,
            'currency' => 'BDT',
            'status' => $eligibleOrder ? Order::STATUS_COMPLETED : Order::STATUS_PENDING_PAYMENT,
            'payment_status' => $eligibleOrder ? Order::PAYMENT_PAID : Order::PAYMENT_UNPAID,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_name' => $ticket->name,
            'quantity' => 1,
            'unit_price' => 0,
            'subtotal' => 0,
        ]);

        OrderTicket::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'event_ticket_id' => $ticket->id,
            'ticket_type_id' => $ticket->id,
            'ticket_code' => 'REV-TICKET-' . strtoupper(uniqid()),
            'qr_payload' => 'review-test-payload',
            'attendee_name' => $customer->name,
            'attendee_email' => $customer->email,
            'status' => $eligibleOrder ? OrderTicket::STATUS_USED : OrderTicket::STATUS_ISSUED,
            'is_checked_in' => $eligibleOrder,
            'checked_in_at' => $eligibleOrder ? now() : null,
        ]);

        return compact('owner', 'customer', 'otherCustomer', 'organizer', 'event', 'ticket', 'order');
    }

    private function admin(): Admin
    {
        return Admin::create([
            'name' => 'Review Admin',
            'email' => 'review-admin-' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
