<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PublicEventDiscoverySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'public.discovery.organizer@example.com'],
            ['name' => 'Public Discovery Organizer', 'password' => Hash::make('password')]
        );

        $organizer = OrganizerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'organization_name' => 'Discovery Events Bangladesh',
                'slug' => 'discovery-events-bangladesh',
                'contact_person' => 'Discovery Organizer',
                'email' => $user->email,
                'phone' => '+8801711111111',
                'address' => 'Dhaka, Bangladesh',
                'description' => 'Demo organizer for public event discovery pages.',
                'status' => OrganizerProfile::STATUS_APPROVED,
                'submitted_at' => now()->subDays(7),
                'approved_at' => now()->subDays(6),
            ]
        );

        $conference = EventType::updateOrCreate(
            ['slug' => 'public-conference'],
            ['name' => 'Public Conference', 'code' => 'PUBCONF', 'serial' => 10, 'status' => 'active']
        );

        $workshop = EventType::updateOrCreate(
            ['slug' => 'public-workshop'],
            ['name' => 'Public Workshop', 'code' => 'PUBWORK', 'serial' => 11, 'status' => 'active']
        );

        $dhakaVenue = Venue::updateOrCreate(
            ['slug' => 'dhaka-marketplace-auditorium'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizer->user_id,
                'name' => 'Dhaka Marketplace Auditorium',
                'address' => 'Gulshan Avenue',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 700,
                'description' => 'Demo venue for public discovery filters.',
            ]
        );

        $chattogramVenue = Venue::updateOrCreate(
            ['slug' => 'chattogram-event-hub'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizer->user_id,
                'name' => 'Chattogram Event Hub',
                'address' => 'Agrabad Access Road',
                'city' => 'Chattogram',
                'country' => 'Bangladesh',
                'capacity' => 450,
                'description' => 'Demo venue for city-based public discovery.',
            ]
        );

        $publishedConference = Event::updateOrCreate(
            ['slug' => 'public-discovery-tech-summit'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $conference->id,
                'venue_id' => $dhakaVenue->id,
                'name' => 'Public Discovery Tech Summit',
                'tagline' => 'A searchable published event for public discovery testing.',
                'description' => 'This event demonstrates public browsing, filters, ticket previews, organizer information, and venue information.',
                'start_date' => now()->addDays(10)->toDateString(),
                'end_date' => now()->addDays(10)->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '17:00:00',
                'venue' => $dhakaVenue->name,
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 700,
                'status' => Event::STATUS_PUBLISHED,
                'approved_at' => now()->subDays(5),
                'is_featured' => true,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        $publishedWorkshop = Event::updateOrCreate(
            ['slug' => 'public-discovery-free-workshop'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $workshop->id,
                'venue_id' => $chattogramVenue->id,
                'name' => 'Public Discovery Free Workshop',
                'tagline' => 'A free published event for price filter testing.',
                'description' => 'This event helps verify free-ticket discovery and public event cards.',
                'start_date' => now()->addDays(3)->toDateString(),
                'end_date' => now()->addDays(3)->toDateString(),
                'start_time' => '15:00:00',
                'end_time' => '18:00:00',
                'venue' => $chattogramVenue->name,
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 300,
                'status' => Event::STATUS_PUBLISHED,
                'approved_at' => now()->subDays(4),
                'is_featured' => false,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        Event::updateOrCreate(
            ['slug' => 'public-discovery-hidden-draft-event'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $conference->id,
                'venue_id' => $dhakaVenue->id,
                'name' => 'Public Discovery Hidden Draft Event',
                'description' => 'This event must stay hidden from public browse and detail pages.',
                'start_date' => now()->addDays(20)->toDateString(),
                'end_date' => now()->addDays(20)->toDateString(),
                'start_time' => '11:00:00',
                'end_time' => '13:00:00',
                'venue' => $dhakaVenue->name,
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 200,
                'status' => Event::STATUS_DRAFT,
                'is_featured' => false,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        EventTicket::updateOrCreate(
            ['event_id' => $publishedConference->id, 'name' => 'General Admission'],
            [
                'description' => 'Standard access to the public discovery summit.',
                'ticket_type' => EventTicket::TYPE_PAID,
                'price' => 1200,
                'currency' => 'BDT',
                'quantity' => 300,
                'sold_quantity' => 25,
                'min_per_order' => 1,
                'max_per_order' => 5,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addDays(9),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'platform_fee_type' => EventTicket::FEE_PERCENT,
                'platform_fee_value' => 5,
                'organizer_absorbs_fee' => false,
            ]
        );

        EventTicket::updateOrCreate(
            ['event_id' => $publishedConference->id, 'name' => 'Hidden Partner Ticket'],
            [
                'description' => 'Hidden ticket for visibility testing.',
                'ticket_type' => EventTicket::TYPE_INVITE_ONLY,
                'price' => 0,
                'currency' => 'BDT',
                'quantity' => 50,
                'sold_quantity' => 0,
                'min_per_order' => 1,
                'max_per_order' => 2,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addDays(9),
                'visibility' => EventTicket::VISIBILITY_HIDDEN,
                'status' => EventTicket::STATUS_ACTIVE,
                'platform_fee_type' => EventTicket::FEE_NONE,
                'platform_fee_value' => 0,
                'organizer_absorbs_fee' => true,
            ]
        );

        EventTicket::updateOrCreate(
            ['event_id' => $publishedWorkshop->id, 'name' => 'Free Workshop Pass'],
            [
                'description' => 'Free pass for the public workshop.',
                'ticket_type' => EventTicket::TYPE_FREE,
                'price' => 0,
                'currency' => 'BDT',
                'quantity' => 150,
                'sold_quantity' => 5,
                'min_per_order' => 1,
                'max_per_order' => 2,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addDays(2),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'platform_fee_type' => EventTicket::FEE_NONE,
                'platform_fee_value' => 0,
                'organizer_absorbs_fee' => true,
            ]
        );
    }
}
