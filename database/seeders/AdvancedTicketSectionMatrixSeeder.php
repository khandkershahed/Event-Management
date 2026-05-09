<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use App\Models\User;
use App\Models\Venue;
use App\Services\Seating\SeatingPlanDesignService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdvancedTicketSectionMatrixSeeder extends Seeder
{
    public function run(): void
    {
        $organizerUser = User::query()->updateOrCreate(
            ['email' => 'advanced.matrix.organizer@example.com'],
            [
                'name' => 'Advanced Matrix Organizer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $organizer = OrganizerProfile::query()->updateOrCreate(
            ['slug' => 'advanced-ticket-section-matrix-organizer'],
            [
                'user_id' => $organizerUser->id,
                'organization_name' => 'Advanced Ticket Section Matrix Organizer',
                'email' => $organizerUser->email,
                'status' => OrganizerProfile::STATUS_APPROVED,
                'approved_at' => now(),
            ]
        );

        $venue = Venue::query()->updateOrCreate(
            ['slug' => 'advanced-ticket-section-matrix-venue'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizerUser->id,
                'name' => 'Advanced Ticket Section Matrix Venue',
                'address' => 'Matrix Demo Road',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 500,
            ]
        );

        $plan = SeatingPlan::query()->updateOrCreate(
            ['venue_id' => $venue->id, 'name' => 'Advanced Ticket Section Matrix Layout'],
            [
                'organizer_profile_id' => $organizer->id,
                'status' => SeatingPlan::STATUS_ACTIVE,
            ]
        );

        app(SeatingPlanDesignService::class)->saveDesign($plan->refresh(), $this->design());

        $eventType = EventType::query()->updateOrCreate(
            ['slug' => 'advanced-ticket-section-matrix'],
            [
                'name' => 'Advanced Ticket Section Matrix',
                'code' => 'ATSM',
                'serial' => 97,
                'status' => 'active',
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        $event = Event::query()->updateOrCreate(
            ['slug' => 'advanced-ticket-section-matrix-demo'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $eventType->id,
                'venue_id' => $venue->id,
                'seating_plan_id' => $plan->id,
                'name' => 'Advanced Ticket Section Matrix Demo',
                'tagline' => 'Ticket types mapped to visual seating sections.',
                'description' => '<p>This event validates the Advanced Step A4 ticket-to-section assignment matrix.</p>',
                'start_date' => now()->addWeeks(9)->toDateString(),
                'end_date' => now()->addWeeks(9)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'venue' => $venue->name,
                'event_type' => 'physical',
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 500,
                'status' => Event::STATUS_PUBLISHED,
                'submitted_at' => now()->subDays(3),
                'approved_at' => now()->subDays(2),
                'is_featured' => true,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        $vip = SeatingSection::query()->where('seating_plan_id', $plan->id)->where('name', 'Matrix VIP Section')->first();
        $standard = SeatingSection::query()->where('seating_plan_id', $plan->id)->where('name', 'Matrix Standard Section')->first();

        EventTicket::query()->updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Matrix VIP Ticket'],
            $this->ticketPayload('VIP-only ticket mapped to Matrix VIP Section.', 2500, $vip ? [$vip->id] : [])
        );

        EventTicket::query()->updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Matrix Standard Ticket'],
            $this->ticketPayload('Standard ticket mapped to Matrix Standard Section.', 1000, $standard ? [$standard->id] : [])
        );

        EventTicket::query()->updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Matrix All Sections Ticket'],
            $this->ticketPayload('All-sections ticket with no section restriction.', 1500, [])
        );
    }

    private function ticketPayload(string $description, int $price, array $sectionIds): array
    {
        return [
            'description' => $description,
            'ticket_type' => EventTicket::TYPE_PAID,
            'price' => $price,
            'currency' => 'BDT',
            'quantity' => 100,
            'sold_quantity' => 0,
            'min_per_order' => 1,
            'max_per_order' => 6,
            'sales_start_at' => now()->subDay(),
            'sales_end_at' => now()->addWeeks(7),
            'visibility' => EventTicket::VISIBILITY_PUBLIC,
            'status' => EventTicket::STATUS_ACTIVE,
            'is_active' => true,
            'valid_section_ids' => $sectionIds,
            'platform_fee_type' => EventTicket::FEE_PERCENT,
            'platform_fee_value' => 5,
            'organizer_absorbs_fee' => false,
        ];
    }

    private function design(): array
    {
        return [
            [
                'id' => 'matrix-stage',
                'type' => 'stage',
                'name' => 'Matrix Main Stage',
                'x' => 260,
                'y' => 30,
                'width' => 300,
                'height' => 70,
                'rotation' => 0,
                'capacity' => 0,
                'seats' => [],
            ],
            [
                'id' => 'matrix-vip',
                'type' => 'seat',
                'name' => 'Matrix VIP Section',
                'x' => 130,
                'y' => 145,
                'width' => 310,
                'height' => 180,
                'rotation' => 0,
                'capacity' => 8,
                'seats' => $this->seatGrid('MV', 2, 4),
            ],
            [
                'id' => 'matrix-standard',
                'type' => 'seat',
                'name' => 'Matrix Standard Section',
                'x' => 500,
                'y' => 145,
                'width' => 310,
                'height' => 180,
                'rotation' => 0,
                'capacity' => 8,
                'seats' => $this->seatGrid('MS', 2, 4),
            ],
        ];
    }

    private function seatGrid(string $prefix, int $rows, int $columns): array
    {
        $seats = [];

        for ($row = 1; $row <= $rows; $row++) {
            $rowLabel = $prefix.$row;

            for ($column = 1; $column <= $columns; $column++) {
                $seats[] = [
                    'id' => strtolower($prefix).'-'.$row.'-'.$column,
                    'label' => $rowLabel.'-'.$column,
                    'row_label' => $rowLabel,
                    'seat_number' => $column,
                    'x' => 24 + (($column - 1) * 48),
                    'y' => 46 + (($row - 1) * 48),
                    'width' => 34,
                    'height' => 34,
                    'disabled' => false,
                ];
            }
        }

        return $seats;
    }
}
