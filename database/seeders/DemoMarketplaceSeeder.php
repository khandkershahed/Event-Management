<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\CustomerEventInterest;
use App\Models\CustomerSavedEvent;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\MarketplaceEventReview;
use App\Models\MarketplaceOrganizerRating;
use App\Models\MarketplaceSupportMessage;
use App\Models\MarketplaceSupportTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrganizerFollower;
use App\Models\OrganizerLedger;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTrustBadge;
use App\Models\RefundRequest;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use App\Services\OrganizerLedgerService;
use App\Services\TicketIssuanceService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoMarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = $this->admin();
        $customer = $this->customer('demo.customer@example.com', 'Demo Customer');
        $secondCustomer = $this->customer('jane.customer@example.com', 'Jane Customer');
        $organizer = $this->organizer();
        $venue = $this->venue($organizer);
        $plan = $this->seatingPlan($organizer, $venue);
        $sections = $this->sectionsAndSeats($plan);
        $eventType = $this->eventType('marketplace-conference', 'Marketplace Conference');
        $onlineType = $this->eventType('online-workshop', 'Online Workshop');
        $event = $this->event($organizer, $venue, $plan, $eventType, 'demo-marketplace-summit', 'Demo Marketplace Summit', true, 'physical');
        $onlineEvent = $this->event($organizer, $venue, null, $onlineType, 'demo-online-growth-workshop', 'Demo Online Growth Workshop', false, 'online');

        $ticket = $this->ticket($event, 'Demo General Admission', EventTicket::TYPE_PAID, 750, $sections->pluck('id')->all());
        $this->ticket($event, 'Demo Community Pass', EventTicket::TYPE_FREE, 0, []);
        $this->ticket($onlineEvent, 'Demo Online Access', EventTicket::TYPE_FREE, 0, []);

        $order = $this->paidOrder($customer, $event, $ticket);
        $this->postLedger($order);

        $this->savedAndFollowed($customer, $event, $onlineEvent, $organizer);
        $this->reviews($customer, $secondCustomer, $event, $order, $organizer, $admin);
        $this->trustBadges($organizer, $admin);
        $this->supportTicket($customer, $event, $order, $organizer);
        $this->refundDemo($customer, $event, $order, $organizer);
        $this->payoutDemo($organizer);
    }

    private function admin(): Admin
    {
        return Admin::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
    }

    private function customer(string $email, string $name): User
    {
        return User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );
    }

    private function organizer(): OrganizerProfile
    {
        $owner = User::query()->updateOrCreate(
            ['email' => 'demo.organizer@example.com'],
            ['name' => 'Demo Organizer Owner', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        return OrganizerProfile::query()->updateOrCreate(
            ['user_id' => $owner->id],
            [
                'organization_name' => 'Demo Marketplace Events',
                'slug' => 'demo-marketplace-events',
                'contact_person' => 'Demo Organizer Owner',
                'email' => $owner->email,
                'phone' => '+8801700000100',
                'website' => 'https://example.com',
                'address' => 'Dhaka, Bangladesh',
                'description' => 'A complete approved organizer profile for marketplace demos, public trust, reviews, payouts, and support testing.',
                'status' => OrganizerProfile::STATUS_APPROVED,
                'submitted_at' => now()->subDays(12),
                'approved_at' => now()->subDays(10),
                'rejection_reason' => null,
            ]
        );
    }

    private function venue(OrganizerProfile $organizer): Venue
    {
        return Venue::query()->updateOrCreate(
            ['slug' => 'demo-marketplace-hall'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizer->user_id,
                'name' => 'Demo Marketplace Hall',
                'address' => '100 Marketplace Avenue',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 800,
                'description' => 'Demo venue for published events, seating, checkout, reports, SEO, and public discovery.',
            ]
        );
    }

    private function seatingPlan(OrganizerProfile $organizer, Venue $venue): SeatingPlan
    {
        return SeatingPlan::query()->updateOrCreate(
            ['venue_id' => $venue->id, 'name' => 'Demo Marketplace Layout'],
            [
                'organizer_profile_id' => $organizer->id,
                'status' => SeatingPlan::STATUS_ACTIVE,
                'design_json' => [
                    ['type' => 'section', 'name' => 'VIP', 'capacity' => 20, 'x' => 100, 'y' => 80],
                    ['type' => 'section', 'name' => 'General', 'capacity' => 100, 'x' => 100, 'y' => 220],
                ],
            ]
        );
    }

    private function sectionsAndSeats(SeatingPlan $plan)
    {
        $vip = SeatingSection::query()->updateOrCreate(
            ['seating_plan_id' => $plan->id, 'name' => 'VIP'],
            ['type' => 'seat', 'capacity' => 20, 'x' => 100, 'y' => 80, 'rotation' => 0]
        );

        $general = SeatingSection::query()->updateOrCreate(
            ['seating_plan_id' => $plan->id, 'name' => 'General'],
            ['type' => 'seat', 'capacity' => 100, 'x' => 100, 'y' => 220, 'rotation' => 0]
        );

        foreach ([$vip, $general] as $section) {
            foreach (range(1, 5) as $number) {
                SeatingSeat::query()->updateOrCreate(
                    ['section_id' => $section->id, 'label' => $section->name[0] . '-' . $number],
                    ['row_label' => $section->name[0], 'seat_number' => $number, 'x' => 40 * $number, 'y' => 40, 'status' => 'available', 'is_disabled' => false]
                );
            }
        }

        return collect([$vip, $general]);
    }

    private function eventType(string $slug, string $name): EventType
    {
        return EventType::query()->updateOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'code' => Str::upper(Str::substr($slug, 0, 8)), 'serial' => 10, 'status' => 'active', 'added_by' => 'system', 'updated_by' => 'system']
        );
    }

    private function event(OrganizerProfile $organizer, Venue $venue, ?SeatingPlan $plan, EventType $type, string $slug, string $name, bool $featured, string $format): Event
    {
        return Event::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $type->id,
                'venue_id' => $venue->id,
                'seating_plan_id' => $plan?->id,
                'name' => $name,
                'tagline' => 'Demo event for final marketplace QA and client presentation.',
                'description' => '<p>This published demo event powers homepage, browse, checkout, reviews, SEO, reports, and customer demo flows.</p>',
                'start_date' => now()->addWeeks($featured ? 3 : 5)->toDateString(),
                'end_date' => now()->addWeeks($featured ? 3 : 5)->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'venue' => $format === 'online' ? 'Online Event' : $venue->name,
                'event_type' => $format,
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 500,
                'status' => Event::STATUS_PUBLISHED,
                'submitted_at' => now()->subDays(7),
                'approved_at' => now()->subDays(6),
                'rejection_reason' => null,
                'is_featured' => $featured,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );
    }

    private function ticket(Event $event, string $name, string $type, float $price, array $sectionIds): EventTicket
    {
        return EventTicket::query()->updateOrCreate(
            ['event_id' => $event->id, 'name' => $name],
            [
                'ticket_type' => $type,
                'description' => 'Demo ticket for final QA and public purchase flows.',
                'price' => $price,
                'currency' => 'BDT',
                'quantity' => 200,
                'sold_quantity' => 1,
                'min_per_order' => 1,
                'max_per_order' => 6,
                'sales_start_at' => now()->subDays(5),
                'sales_end_at' => now()->addWeeks(2),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'is_active' => true,
                'valid_section_ids' => $sectionIds,
                'platform_fee_type' => $price > 0 ? EventTicket::FEE_PERCENT : EventTicket::FEE_NONE,
                'platform_fee_value' => $price > 0 ? 5 : 0,
                'organizer_absorbs_fee' => false,
            ]
        );
    }

    private function paidOrder(User $customer, Event $event, EventTicket $ticket): Order
    {
        $order = Order::query()->firstOrCreate(
            ['order_number' => 'ORD-DEMO-E2E-001'],
            [
                'user_id' => $customer->id,
                'event_id' => $event->id,
                'subtotal' => (float) $ticket->price,
                'discount_total' => 0,
                'fee_total' => 0,
                'total' => (float) $ticket->price,
                'currency' => $ticket->currency ?: 'BDT',
                'status' => Order::STATUS_COMPLETED,
                'payment_status' => Order::PAYMENT_PAID,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
            ]
        );

        $order->forceFill([
            'user_id' => $customer->id,
            'event_id' => $event->id,
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
        ])->save();

        $item = OrderItem::query()->firstOrCreate(
            ['order_id' => $order->id, 'event_ticket_id' => $ticket->id],
            ['ticket_type_id' => $ticket->id, 'ticket_name' => $ticket->name, 'quantity' => 1, 'unit_price' => $ticket->price, 'subtotal' => $ticket->price]
        );

        if (! $order->tickets()->exists()) {
            app(TicketIssuanceService::class)->issue($order, $item, $ticket, null, $customer->name, $customer->email);
        }

        return $order->fresh(['tickets', 'items', 'event']);
    }

    private function postLedger(Order $order): void
    {
        if ((float) $order->total <= 0 || ! $order->event?->organizerProfile) {
            return;
        }

        app(OrganizerLedgerService::class)->postPaidOrder($order);
    }

    private function savedAndFollowed(User $customer, Event $event, Event $onlineEvent, OrganizerProfile $organizer): void
    {
        if (Schema::hasTable('customer_saved_events')) {
            CustomerSavedEvent::query()->updateOrCreate(['user_id' => $customer->id, 'event_id' => $event->id], ['source' => 'demo']);
        }

        if (Schema::hasTable('organizer_followers')) {
            OrganizerFollower::query()->updateOrCreate(['user_id' => $customer->id, 'organizer_profile_id' => $organizer->id]);
        }

        if (Schema::hasTable('customer_event_interests')) {
            CustomerEventInterest::query()->updateOrCreate(
                ['user_id' => $customer->id, 'interest_type' => CustomerEventInterest::TYPE_ORGANIZER, 'interest_value' => (string) $organizer->id],
                ['weight' => 5, 'last_recorded_at' => now()]
            );
            CustomerEventInterest::query()->updateOrCreate(
                ['user_id' => $customer->id, 'interest_type' => CustomerEventInterest::TYPE_EVENT_TYPE, 'interest_value' => (string) $onlineEvent->event_type_id],
                ['weight' => 4, 'last_recorded_at' => now()]
            );
            CustomerEventInterest::query()->updateOrCreate(
                ['user_id' => $customer->id, 'interest_type' => CustomerEventInterest::TYPE_CITY, 'interest_value' => 'Dhaka'],
                ['weight' => 3, 'last_recorded_at' => now()]
            );
        }
    }

    private function reviews(User $customer, User $secondCustomer, Event $event, Order $order, OrganizerProfile $organizer, Admin $admin): void
    {
        if (! Schema::hasTable('marketplace_event_reviews')) {
            return;
        }

        MarketplaceEventReview::query()->updateOrCreate(
            ['user_id' => $customer->id, 'event_id' => $event->id, 'order_id' => $order->id],
            [
                'organizer_profile_id' => $organizer->id,
                'rating' => 5,
                'title' => 'Great demo event experience',
                'body' => 'The event experience was smooth from discovery to ticket check-in.',
                'status' => MarketplaceEventReview::STATUS_APPROVED,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now()->subDay(),
                'admin_note' => 'Approved demo review.',
            ]
        );

        MarketplaceEventReview::query()->updateOrCreate(
            ['user_id' => $secondCustomer->id, 'event_id' => $event->id, 'order_id' => null],
            [
                'organizer_profile_id' => $organizer->id,
                'rating' => 4,
                'title' => 'Helpful organizer',
                'body' => 'Good communication and a clear event listing.',
                'status' => MarketplaceEventReview::STATUS_PENDING,
            ]
        );

        if (Schema::hasTable('marketplace_organizer_ratings')) {
            MarketplaceOrganizerRating::query()->updateOrCreate(
                ['organizer_profile_id' => $organizer->id],
                ['approved_reviews_count' => 1, 'average_rating' => 5.00, 'last_reviewed_at' => now()->subDay()]
            );
        }
    }

    private function trustBadges(OrganizerProfile $organizer, Admin $admin): void
    {
        if (! Schema::hasTable('organizer_trust_badges')) {
            return;
        }

        OrganizerTrustBadge::query()->updateOrCreate(
            ['organizer_profile_id' => $organizer->id, 'badge_key' => OrganizerTrustBadge::BADGE_VERIFIED_ORGANIZER],
            ['label' => 'Verified Organizer', 'description' => 'Organizer profile reviewed by platform admin.', 'status' => OrganizerTrustBadge::STATUS_APPROVED, 'is_public' => true, 'created_by' => $admin->id, 'reviewed_by' => $admin->id, 'reviewed_at' => now()]
        );

        OrganizerTrustBadge::query()->updateOrCreate(
            ['organizer_profile_id' => $organizer->id, 'badge_key' => OrganizerTrustBadge::BADGE_HIGHLY_RATED],
            ['label' => 'Highly Rated', 'description' => 'Organizer has approved high-quality customer feedback.', 'status' => OrganizerTrustBadge::STATUS_APPROVED, 'is_public' => true, 'created_by' => $admin->id, 'reviewed_by' => $admin->id, 'reviewed_at' => now()]
        );
    }

    private function supportTicket(User $customer, Event $event, Order $order, OrganizerProfile $organizer): void
    {
        if (! Schema::hasTable('marketplace_support_tickets')) {
            return;
        }

        $ticket = MarketplaceSupportTicket::query()->updateOrCreate(
            ['ticket_number' => 'ST-DEMO-0001'],
            [
                'user_id' => $customer->id,
                'organizer_profile_id' => $organizer->id,
                'event_id' => $event->id,
                'order_id' => $order->id,
                'created_by_type' => User::class,
                'created_by_id' => $customer->id,
                'type' => MarketplaceSupportTicket::TYPE_ORDER,
                'priority' => MarketplaceSupportTicket::PRIORITY_NORMAL,
                'status' => MarketplaceSupportTicket::STATUS_OPEN,
                'subject' => 'Demo order support question',
                'description' => 'Seeded support ticket for demo support desk workflows.',
                'last_replied_at' => now(),
            ]
        );

        if (Schema::hasTable('marketplace_support_messages')) {
            MarketplaceSupportMessage::query()->firstOrCreate(
                ['marketplace_support_ticket_id' => $ticket->id, 'body' => 'Seeded customer support message for the demo marketplace.'],
                ['sender_type' => User::class, 'sender_id' => $customer->id, 'sender_guard' => 'web', 'is_internal' => false]
            );
        }
    }

    private function refundDemo(User $customer, Event $event, Order $order, OrganizerProfile $organizer): void
    {
        if (! Schema::hasTable('refund_requests')) {
            return;
        }

        RefundRequest::query()->firstOrCreate(
            ['order_id' => $order->id, 'status' => RefundRequest::STATUS_PENDING],
            [
                'user_id' => $customer->id,
                'organizer_profile_id' => $organizer->id,
                'event_id' => $event->id,
                'requested_by_type' => User::class,
                'requested_by_id' => $customer->id,
                'reason' => 'Seeded refund request for final QA demo.',
                'amount' => $order->total,
                'currency' => $order->currency ?: 'BDT',
            ]
        );
    }

    private function payoutDemo(OrganizerProfile $organizer): void
    {
        if (! Schema::hasTable('organizer_payouts')) {
            return;
        }

        $available = OrganizerLedger::query()
            ->where('organizer_profile_id', $organizer->id)
            ->where('direction', OrganizerLedger::DIRECTION_CREDIT)
            ->sum('amount');

        OrganizerPayout::query()->updateOrCreate(
            ['payout_number' => 'PO-DEMO-0001'],
            [
                'organizer_profile_id' => $organizer->id,
                'amount' => max(100, min((float) $available, 500)),
                'currency' => 'BDT',
                'status' => OrganizerPayout::STATUS_PENDING,
                'requested_by' => $organizer->user_id,
                'notes' => 'Seeded payout request for demo marketplace finance workflow.',
                'requested_at' => now()->subDay(),
            ]
        );
    }
}
