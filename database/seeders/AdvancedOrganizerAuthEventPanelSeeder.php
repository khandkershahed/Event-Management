<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\OrganizerTeamMember;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use App\Models\SeatingSeat;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdvancedOrganizerAuthEventPanelSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('organizer_profiles') || ! Schema::hasTable('events')) {
            return;
        }

        $admin = Admin::updateOrCreate(
            ['email' => 'a9.admin@example.com'],
            ['name' => 'A9 Admin', 'password' => Hash::make('password'), 'status' => 'active']
        );

        $customer = $this->user('a9.customer@example.com', 'A9 Customer');
        $owner = $this->user('a9.organizer@example.com', 'A9 Approved Organizer');
        $pendingOwner = $this->user('a9.pending-organizer@example.com', 'A9 Pending Organizer');
        $checkIn = $this->user('a9.checkin@example.com', 'A9 Check-in Staff');
        $finance = $this->user('a9.finance@example.com', 'A9 Finance Viewer');

        $profile = OrganizerProfile::updateOrCreate(
            ['user_id' => $owner->id],
            [
                'organization_name' => 'A9 Marketplace Events',
                'slug' => 'a9-marketplace-events',
                'contact_person' => $owner->name,
                'phone' => '01700000001',
                'email' => $owner->email,
                'status' => OrganizerProfile::STATUS_APPROVED,
                'submitted_at' => now()->subDays(10),
                'approved_at' => now()->subDays(9),
                'approved_by' => $admin->id,
            ]
        );

        OrganizerProfile::updateOrCreate(
            ['user_id' => $pendingOwner->id],
            [
                'organization_name' => 'A9 Pending Events',
                'slug' => 'a9-pending-events',
                'contact_person' => $pendingOwner->name,
                'email' => $pendingOwner->email,
                'status' => OrganizerProfile::STATUS_PENDING,
                'submitted_at' => now()->subDay(),
            ]
        );

        OrganizerTeamMember::updateOrCreate(
            ['organizer_profile_id' => $profile->id, 'user_id' => $checkIn->id],
            ['email' => $checkIn->email, 'name' => $checkIn->name, 'role' => OrganizerTeamMember::ROLE_CHECK_IN_STAFF, 'status' => OrganizerTeamMember::STATUS_ACTIVE, 'accepted_at' => now()]
        );

        OrganizerTeamMember::updateOrCreate(
            ['organizer_profile_id' => $profile->id, 'user_id' => $finance->id],
            ['email' => $finance->email, 'name' => $finance->name, 'role' => OrganizerTeamMember::ROLE_FINANCE_VIEWER, 'status' => OrganizerTeamMember::STATUS_ACTIVE, 'accepted_at' => now()]
        );

        $eventType = EventType::firstOrCreate(['name' => 'A9 Conference'], ['status' => 'active']);

        $venue = Venue::updateOrCreate(
            ['slug' => 'a9-grand-hall'],
            [
                'organizer_profile_id' => $profile->id,
                'organizer_id' => $owner->id,
                'name' => 'A9 Grand Hall',
                'address' => 'A9 Demo Road',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 500,
                'description' => 'Demo venue for A9 organizer authentication and event panel testing.',
            ]
        );

        $plan = SeatingPlan::updateOrCreate(
            ['organizer_profile_id' => $profile->id, 'venue_id' => $venue->id, 'name' => 'A9 Main Layout'],
            ['status' => SeatingPlan::STATUS_ACTIVE, 'design_json' => ['version' => 'a9-demo', 'items' => []]]
        );

        $section = SeatingSection::updateOrCreate(
            ['seating_plan_id' => $plan->id, 'name' => 'VIP'],
            ['type' => 'seat', 'capacity' => 20, 'x' => 40, 'y' => 40, 'rotation' => 0]
        );

        for ($i = 1; $i <= 5; $i++) {
            SeatingSeat::updateOrCreate(
                ['section_id' => $section->id, 'label' => 'A-' . $i],
                ['row_label' => 'A', 'seat_number' => $i, 'x' => 40 + ($i * 35), 'y' => 90, 'status' => 'available']
            );
        }

        foreach ([Event::STATUS_DRAFT, Event::STATUS_SUBMITTED, Event::STATUS_PUBLISHED] as $status) {
            $event = Event::updateOrCreate(
                ['slug' => 'a9-' . str_replace('_', '-', $status) . '-event'],
                [
                    'organizer_profile_id' => $profile->id,
                    'event_type_id' => $eventType->id,
                    'venue_id' => $venue->id,
                    'seating_plan_id' => $plan->id,
                    'name' => 'A9 ' . ucwords(str_replace('_', ' ', $status)) . ' Event',
                    'tagline' => 'Simple event management demo',
                    'description' => 'This event is used to test the unified event control panel.',
                    'venue' => $venue->name,
                    'start_date' => now()->addDays(15)->toDateString(),
                    'end_date' => now()->addDays(15)->toDateString(),
                    'start_time' => '10:00:00',
                    'end_time' => '16:00:00',
                    'purchase_deadline' => now()->addDays(14),
                    'total_capacity' => 500,
                    'age_restriction' => 'All Ages',
                    'organizer_name' => $profile->organization_name,
                    'organizer_brand' => $profile->organization_name,
                    'terms_and_conditions' => 'Bring your ticket QR code for entry.',
                    'status' => $status,
                    'submitted_at' => in_array($status, [Event::STATUS_SUBMITTED, Event::STATUS_PUBLISHED], true) ? now()->subDays(2) : null,
                    'approved_at' => $status === Event::STATUS_PUBLISHED ? now()->subDay() : null,
                    'approved_by' => $status === Event::STATUS_PUBLISHED ? $admin->id : null,
                ]
            );

            EventTicket::updateOrCreate(
                ['event_id' => $event->id, 'name' => 'A9 Standard Ticket'],
                [
                    'description' => 'Standard admission ticket.',
                    'ticket_type' => EventTicket::TYPE_PAID,
                    'price' => 500,
                    'currency' => 'BDT',
                    'quantity' => 100,
                    'sold_quantity' => 0,
                    'min_per_order' => 1,
                    'max_per_order' => 5,
                    'visibility' => EventTicket::VISIBILITY_PUBLIC,
                    'status' => EventTicket::STATUS_ACTIVE,
                    'is_active' => true,
                    'valid_section_ids' => [$section->id],
                    'platform_fee_type' => EventTicket::FEE_NONE,
                    'platform_fee_value' => 0,
                    'organizer_absorbs_fee' => false,
                ]
            );
        }
    }

    private function user(string $email, string $name): User
    {
        return User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make('password'), 'role' => 'user', 'status' => 'active']
        );
    }
}
