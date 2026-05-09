<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventType;
use App\Models\OrganizerProfile;
use App\Models\SeatingPlan;
use App\Models\SeatingSection;
use App\Models\Venue;
use App\Services\Seating\SeatingPlanDesignService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdvancedSeatMapDesignSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = OrganizerProfile::where('status', OrganizerProfile::STATUS_APPROVED)->first();

        if (! $organizer) {
            $this->call(OrganizerSeeder::class);
            $organizer = OrganizerProfile::where('status', OrganizerProfile::STATUS_APPROVED)->first();
        }

        if (! $organizer) {
            return;
        }

        $service = app(SeatingPlanDesignService::class);

        $adminVenue = Venue::query()->updateOrCreate(
            ['slug' => 'advanced-admin-seatmap-arena'],
            [
                'name' => 'Advanced Admin Seatmap Arena',
                'address' => 'Admin Demo Avenue',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 1200,
                'description' => 'Platform-admin demo venue for validating the visual seat map designer.',
            ]
        );

        $adminPlan = SeatingPlan::query()->updateOrCreate(
            ['venue_id' => $adminVenue->id, 'name' => 'Advanced Admin Visual Layout'],
            [
                'organizer_profile_id' => null,
                'status' => SeatingPlan::STATUS_DRAFT,
            ]
        );

        $service->saveDesign($adminPlan, $this->adminDesign());

        $organizerVenue = Venue::query()->updateOrCreate(
            ['slug' => 'advanced-organizer-seatmap-hall'],
            [
                'organizer_profile_id' => $organizer->id,
                'organizer_id' => $organizer->user_id,
                'name' => 'Advanced Organizer Seatmap Hall',
                'address' => 'Organizer Demo Road',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'capacity' => 900,
                'description' => 'Organizer-owned demo venue for validating visual seat map design and public selection.',
            ]
        );

        $organizerPlan = SeatingPlan::query()->updateOrCreate(
            ['venue_id' => $organizerVenue->id, 'name' => 'Advanced Organizer Visual Layout'],
            [
                'organizer_profile_id' => $organizer->id,
                'status' => SeatingPlan::STATUS_ACTIVE,
            ]
        );

        $service->saveDesign($organizerPlan, $this->organizerDesign());

        $eventType = EventType::query()->updateOrCreate(
            ['slug' => 'advanced-seatmap-demo'],
            [
                'name' => 'Advanced Seatmap Demo',
                'code' => 'ASMDEMO',
                'serial' => 95,
                'status' => 'active',
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        $event = Event::query()->updateOrCreate(
            ['slug' => 'advanced-visual-seatmap-demo'],
            [
                'organizer_profile_id' => $organizer->id,
                'event_type_id' => $eventType->id,
                'venue_id' => $organizerVenue->id,
                'seating_plan_id' => $organizerPlan->id,
                'name' => 'Advanced Visual Seatmap Demo',
                'tagline' => 'A seeded event for testing visual seat map design and public seat selection.',
                'description' => '<p>This event validates the Advanced Step A2 visual designer rebuild flow from organizer/admin panels to the public frontend.</p>',
                'start_date' => now()->addWeeks(6)->toDateString(),
                'end_date' => now()->addWeeks(6)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'venue' => $organizerVenue->name,
                'event_type' => 'physical',
                'organizer_name' => $organizer->organization_name,
                'organizer_brand' => $organizer->organization_name,
                'total_capacity' => 500,
                'status' => Event::STATUS_PUBLISHED,
                'submitted_at' => now()->subDays(2),
                'approved_at' => now()->subDay(),
                'rejection_reason' => null,
                'is_featured' => true,
                'added_by' => 'system',
                'updated_by' => 'system',
            ]
        );

        $vipSection = SeatingSection::query()
            ->where('seating_plan_id', $organizerPlan->id)
            ->where('name', 'VIP Front')
            ->first();

        $standardSection = SeatingSection::query()
            ->where('seating_plan_id', $organizerPlan->id)
            ->where('name', 'Standard Middle')
            ->first();

        EventTicket::query()->updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Advanced VIP Reserved'],
            [
                'description' => 'Seeded VIP reserved-seat ticket for the visual seat map demo.',
                'ticket_type' => EventTicket::TYPE_PAID,
                'price' => 2500,
                'currency' => 'BDT',
                'quantity' => 60,
                'sold_quantity' => 0,
                'min_per_order' => 1,
                'max_per_order' => 4,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addWeeks(4),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'is_active' => true,
                'valid_section_ids' => $vipSection ? [$vipSection->id] : [],
                'platform_fee_type' => EventTicket::FEE_PERCENT,
                'platform_fee_value' => 5,
                'organizer_absorbs_fee' => false,
            ]
        );

        EventTicket::query()->updateOrCreate(
            ['event_id' => $event->id, 'name' => 'Advanced Standard Reserved'],
            [
                'description' => 'Seeded standard reserved-seat ticket for the visual seat map demo.',
                'ticket_type' => EventTicket::TYPE_PAID,
                'price' => 1000,
                'currency' => 'BDT',
                'quantity' => 120,
                'sold_quantity' => 0,
                'min_per_order' => 1,
                'max_per_order' => 6,
                'sales_start_at' => now()->subDay(),
                'sales_end_at' => now()->addWeeks(4),
                'visibility' => EventTicket::VISIBILITY_PUBLIC,
                'status' => EventTicket::STATUS_ACTIVE,
                'is_active' => true,
                'valid_section_ids' => $standardSection ? [$standardSection->id] : [],
                'platform_fee_type' => EventTicket::FEE_PERCENT,
                'platform_fee_value' => 5,
                'organizer_absorbs_fee' => false,
            ]
        );
    }

    private function adminDesign(): array
    {
        return [
            [
                'id' => 'admin-stage-main',
                'type' => 'stage',
                'name' => 'Main Stage',
                'x' => 260,
                'y' => 30,
                'width' => 320,
                'height' => 80,
                'rotation' => 0,
                'capacity' => 0,
                'seats' => [],
            ],
            [
                'id' => 'admin-vip-front',
                'type' => 'seat',
                'name' => 'Admin VIP Front',
                'x' => 150,
                'y' => 150,
                'width' => 280,
                'height' => 160,
                'rotation' => 0,
                'capacity' => 6,
                'seats' => $this->seatGrid('AV', 2, 3, 34, 46),
            ],
            [
                'id' => 'admin-standard-middle',
                'type' => 'seat',
                'name' => 'Admin Standard Middle',
                'x' => 470,
                'y' => 150,
                'width' => 280,
                'height' => 160,
                'rotation' => 0,
                'capacity' => 6,
                'seats' => $this->seatGrid('AS', 2, 3, 34, 46),
            ],
        ];
    }

    private function organizerDesign(): array
    {
        return [
            [
                'id' => 'org-stage-main',
                'type' => 'stage',
                'name' => 'Main Stage',
                'x' => 260,
                'y' => 30,
                'width' => 320,
                'height' => 80,
                'rotation' => 0,
                'capacity' => 0,
                'seats' => [],
            ],
            [
                'id' => 'org-vip-front',
                'type' => 'seat',
                'name' => 'VIP Front',
                'x' => 120,
                'y' => 150,
                'width' => 300,
                'height' => 180,
                'rotation' => 0,
                'capacity' => 8,
                'seats' => $this->seatGrid('V', 2, 4, 34, 46),
            ],
            [
                'id' => 'org-standard-middle',
                'type' => 'seat',
                'name' => 'Standard Middle',
                'x' => 460,
                'y' => 150,
                'width' => 320,
                'height' => 180,
                'rotation' => 0,
                'capacity' => 8,
                'seats' => $this->seatGrid('S', 2, 4, 34, 46),
            ],
            [
                'id' => 'org-general-standing',
                'type' => 'general_admission',
                'name' => 'Standing Zone',
                'x' => 210,
                'y' => 380,
                'width' => 420,
                'height' => 160,
                'rotation' => 0,
                'capacity' => 200,
                'seats' => [],
            ],
        ];
    }

    private function seatGrid(string $prefix, int $rows, int $columns, int $size, int $gap): array
    {
        $seats = [];

        for ($row = 1; $row <= $rows; $row++) {
            $rowLabel = Str::upper(Str::substr($prefix, 0, 1)).$row;

            for ($column = 1; $column <= $columns; $column++) {
                $seats[] = [
                    'id' => Str::slug($prefix.'-'.$row.'-'.$column),
                    'label' => $rowLabel.'-'.$column,
                    'row_label' => $rowLabel,
                    'seat_number' => $column,
                    'disabled' => false,
                    'x' => 30 + (($column - 1) * ($size + $gap)),
                    'y' => 32 + (($row - 1) * ($size + $gap)),
                ];
            }
        }

        return $seats;
    }
}
