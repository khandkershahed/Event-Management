<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PublicEventBrowseTest extends TestCase
{
    use RefreshDatabase;

    private EventType $conferenceType;
    private EventType $workshopType;
    private OrganizerProfile $organizer;
    private Venue $dhakaVenue;
    private Venue $chattogramVenue;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-05-01 10:00:00'));

        if (class_exists(\Database\Seeders\SettingSeeder::class)) {
            $this->seed(\Database\Seeders\SettingSeeder::class);
        }

        $this->conferenceType = EventType::create([
            'name' => 'Conference',
            'slug' => 'conference',
            'code' => 'CONF',
            'serial' => 1,
            'status' => 'active',
        ]);

        $this->workshopType = EventType::create([
            'name' => 'Workshop',
            'slug' => 'workshop',
            'code' => 'WORK',
            'serial' => 2,
            'status' => 'active',
        ]);

        $user = User::create([
            'name' => 'Public Organizer',
            'email' => 'public.organizer@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->organizer = OrganizerProfile::create([
            'user_id' => $user->id,
            'organization_name' => 'Public Organizer Ltd',
            'slug' => 'public-organizer-ltd',
            'contact_person' => 'Public Organizer',
            'email' => 'public.organizer@example.com',
            'phone' => '+8801700000000',
            'address' => 'Dhaka, Bangladesh',
            'description' => 'Organizer for public browse tests.',
            'status' => OrganizerProfile::STATUS_APPROVED,
            'submitted_at' => now()->subDays(3),
            'approved_at' => now()->subDays(2),
        ]);

        $this->dhakaVenue = Venue::create([
            'organizer_profile_id' => $this->organizer->id,
            'organizer_id' => $user->id,
            'name' => 'Dhaka Test Hall',
            'slug' => 'dhaka-test-hall',
            'address' => 'Gulshan',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
            'capacity' => 500,
            'description' => 'Dhaka public testing venue.',
        ]);

        $this->chattogramVenue = Venue::create([
            'organizer_profile_id' => $this->organizer->id,
            'organizer_id' => $user->id,
            'name' => 'Chattogram Test Hall',
            'slug' => 'chattogram-test-hall',
            'address' => 'Agrabad',
            'city' => 'Chattogram',
            'country' => 'Bangladesh',
            'capacity' => 300,
            'description' => 'Chattogram public testing venue.',
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_public_browse_page_loads_and_shows_only_published_events(): void
    {
        $published = $this->createEvent('Visible Public Summit', 'visible-public-summit', Event::STATUS_PUBLISHED, $this->conferenceType, $this->dhakaVenue, now()->addDays(7));
        $draft = $this->createEvent('Hidden Draft Summit', 'hidden-draft-summit', Event::STATUS_DRAFT, $this->conferenceType, $this->dhakaVenue, now()->addDays(8));

        $this->createTicket($published, 'General Admission', EventTicket::TYPE_PAID, 500);
        $this->createTicket($draft, 'Draft Ticket', EventTicket::TYPE_PAID, 500);

        $response = $this->get(route('all.events'));

        $response->assertOk();
        $response->assertSee('Visible Public Summit');
        $response->assertDontSee('Hidden Draft Summit');
    }

    public function test_search_filter_finds_matching_event(): void
    {
        $match = $this->createEvent('Laravel Marketplace Bootcamp', 'laravel-marketplace-bootcamp', Event::STATUS_PUBLISHED, $this->workshopType, $this->dhakaVenue, now()->addDays(3));
        $other = $this->createEvent('Music Night', 'music-night', Event::STATUS_PUBLISHED, $this->conferenceType, $this->dhakaVenue, now()->addDays(4));

        $this->createTicket($match, 'Free Pass', EventTicket::TYPE_FREE, 0);
        $this->createTicket($other, 'Music Pass', EventTicket::TYPE_PAID, 300);

        $response = $this->get(route('all.events', ['search' => 'Laravel']));

        $response->assertOk();
        $response->assertSee('Laravel Marketplace Bootcamp');
        $response->assertDontSee('Music Night');
    }

    public function test_category_and_date_filters_work(): void
    {
        $todayWorkshop = $this->createEvent('Today Workshop', 'today-workshop', Event::STATUS_PUBLISHED, $this->workshopType, $this->dhakaVenue, now());
        $futureConference = $this->createEvent('Future Conference', 'future-conference', Event::STATUS_PUBLISHED, $this->conferenceType, $this->dhakaVenue, now()->addDays(20));

        $this->createTicket($todayWorkshop, 'Workshop Pass', EventTicket::TYPE_FREE, 0);
        $this->createTicket($futureConference, 'Conference Pass', EventTicket::TYPE_PAID, 700);

        $response = $this->get(route('all.events', [
            'event_type_id' => $this->workshopType->id,
            'date_filter' => 'today',
        ]));

        $response->assertOk();
        $response->assertSee('Today Workshop');
        $response->assertDontSee('Future Conference');
    }

    public function test_city_and_price_filters_work(): void
    {
        $freeDhaka = $this->createEvent('Free Dhaka Meetup', 'free-dhaka-meetup', Event::STATUS_PUBLISHED, $this->workshopType, $this->dhakaVenue, now()->addDays(2));
        $paidChattogram = $this->createEvent('Paid Chattogram Expo', 'paid-chattogram-expo', Event::STATUS_PUBLISHED, $this->conferenceType, $this->chattogramVenue, now()->addDays(2));

        $this->createTicket($freeDhaka, 'Free Entry', EventTicket::TYPE_FREE, 0);
        $this->createTicket($paidChattogram, 'Paid Entry', EventTicket::TYPE_PAID, 900);

        $response = $this->get(route('all.events', [
            'city' => 'Dhaka',
            'price' => 'free',
        ]));

        $response->assertOk();
        $response->assertSee('Free Dhaka Meetup');
        $response->assertDontSee('Paid Chattogram Expo');
    }

    public function test_event_detail_page_loads_for_published_event_and_404s_for_unpublished_event(): void
    {
        $published = $this->createEvent('Published Detail Event', 'published-detail-event', Event::STATUS_PUBLISHED, $this->conferenceType, $this->dhakaVenue, now()->addDays(5));
        $draft = $this->createEvent('Draft Detail Event', 'draft-detail-event', Event::STATUS_DRAFT, $this->conferenceType, $this->dhakaVenue, now()->addDays(5));

        $this->createTicket($published, 'Visible Ticket', EventTicket::TYPE_PAID, 1200);
        $this->createTicket($draft, 'Draft Ticket', EventTicket::TYPE_PAID, 1200);

        $this->get(route('event.details', $published->slug))
            ->assertOk()
            ->assertSee('Published Detail Event')
            ->assertSee('Public Organizer Ltd')
            ->assertSee('Dhaka Test Hall')
            ->assertSee('Visible Ticket');

        $this->get(route('event.details', $draft->slug))->assertNotFound();
    }

    public function test_event_detail_shows_only_active_public_on_sale_ticket_types(): void
    {
        $event = $this->createEvent('Ticket Visibility Event', 'ticket-visibility-event', Event::STATUS_PUBLISHED, $this->conferenceType, $this->dhakaVenue, now()->addDays(5));

        $this->createTicket($event, 'Visible Public Ticket', EventTicket::TYPE_PAID, 500, EventTicket::VISIBILITY_PUBLIC, EventTicket::STATUS_ACTIVE, now()->subDay(), now()->addDay());
        $this->createTicket($event, 'Hidden Ticket', EventTicket::TYPE_PAID, 600, EventTicket::VISIBILITY_HIDDEN, EventTicket::STATUS_ACTIVE, now()->subDay(), now()->addDay());
        $this->createTicket($event, 'Paused Ticket', EventTicket::TYPE_PAID, 700, EventTicket::VISIBILITY_PUBLIC, EventTicket::STATUS_PAUSED, now()->subDay(), now()->addDay());
        $this->createTicket($event, 'Future Ticket', EventTicket::TYPE_PAID, 800, EventTicket::VISIBILITY_PUBLIC, EventTicket::STATUS_ACTIVE, now()->addDay(), now()->addDays(2));

        $response = $this->get(route('event.details', $event->slug));

        $response->assertOk();
        $response->assertSee('Visible Public Ticket');
        $response->assertDontSee('Hidden Ticket');
        $response->assertDontSee('Paused Ticket');
        $response->assertDontSee('Future Ticket');
    }

    private function createEvent(string $name, string $slug, string $status, EventType $type, Venue $venue, Carbon $startDate): Event
    {
        return Event::create([
            'organizer_profile_id' => $this->organizer->id,
            'event_type_id' => $type->id,
            'venue_id' => $venue->id,
            'name' => $name,
            'slug' => $slug,
            'tagline' => $name . ' tagline',
            'description' => $name . ' description for public discovery testing.',
            'start_date' => $startDate->toDateString(),
            'end_date' => $startDate->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '14:00:00',
            'venue' => $venue->name,
            'organizer_name' => $this->organizer->organization_name,
            'organizer_brand' => $this->organizer->organization_name,
            'total_capacity' => 200,
            'status' => $status,
            'approved_at' => $status === Event::STATUS_PUBLISHED ? now()->subDay() : null,
            'is_featured' => false,
            'added_by' => 'test',
            'updated_by' => 'test',
        ]);
    }

    private function createTicket(
        Event $event,
        string $name,
        string $type,
        float $price,
        string $visibility = EventTicket::VISIBILITY_PUBLIC,
        string $status = EventTicket::STATUS_ACTIVE,
        ?Carbon $salesStart = null,
        ?Carbon $salesEnd = null
    ): EventTicket {
        return EventTicket::create([
            'event_id' => $event->id,
            'name' => $name,
            'description' => $name . ' description.',
            'ticket_type' => $type,
            'price' => $price,
            'currency' => 'BDT',
            'quantity' => 100,
            'sold_quantity' => 10,
            'min_per_order' => 1,
            'max_per_order' => 4,
            'sales_start_at' => $salesStart ?: now()->subDay(),
            'sales_end_at' => $salesEnd ?: now()->addDays(10),
            'visibility' => $visibility,
            'status' => $status,
            'platform_fee_type' => EventTicket::FEE_NONE,
            'platform_fee_value' => 0,
            'organizer_absorbs_fee' => false,
        ]);
    }
}
