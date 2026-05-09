<?php

namespace App\Services\Seating;

use App\Models\EventTicket;
use App\Models\OrderTicket;
use App\Models\SeatLock;
use App\Models\SeatingPlan;
use App\Models\SeatingSeat;
use App\Models\SeatingSection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class SeatingPlanDesignService
{
    private const MAX_BLOCKS = 150;
    private const MAX_SEATS_PER_BLOCK = 600;
    private const MAX_TOTAL_SEATS = 5000;

    /**
     * Save the visual designer payload and rebuild the real seating inventory.
     *
     * @param  mixed  $designPayload  Raw designer JSON from the visual editor.
     * @param  mixed  $sectionPayload  Optional normalized sections from the visual editor.
     */
    public function saveDesign(SeatingPlan $plan, mixed $designPayload, mixed $sectionPayload = null): SeatingPlan
    {
        $this->assertCanRebuildInventory($plan);

        $designBlocks = $this->normalizeDesignBlocks($designPayload);
        $sections = $this->normalizeSections($sectionPayload, $designBlocks);

        return DB::transaction(function () use ($plan, $designBlocks, $sections): SeatingPlan {
            $ticketSectionNames = $this->ticketSectionNamesForPlan($plan);
            $createdSectionIdsByName = [];

            $plan->forceFill([
                'design_json' => $designBlocks,
            ])->save();

            $plan->sections()->delete();

            foreach ($sections as $sectionData) {
                $section = SeatingSection::create([
                    'seating_plan_id' => $plan->id,
                    'name' => $sectionData['name'],
                    'type' => $sectionData['type'],
                    'capacity' => $sectionData['capacity'],
                    'x' => $sectionData['x'],
                    'y' => $sectionData['y'],
                    'rotation' => $sectionData['rotation'],
                ]);

                $createdSectionIdsByName[$this->sectionNameKey($section->name)] = $section->id;

                $this->insertSeats($section, $sectionData['seats']);
            }

            $this->restoreTicketSectionAssignments($ticketSectionNames, $createdSectionIdsByName);

            return $plan->refresh()->load('sections.seats');
        });
    }

    public function assertCanRebuildInventory(SeatingPlan $plan): void
    {
        if ($plan->isLocked()) {
            throw ValidationException::withMessages([
                'design_json' => 'Locked seating plans cannot be changed in the visual designer.',
            ]);
        }

        $seatIds = $plan->seats()->pluck('seating_seats.id');

        if ($seatIds->isEmpty()) {
            return;
        }

        $hasActiveLocks = SeatLock::query()
            ->whereIn('seat_id', $seatIds)
            ->active()
            ->exists();

        if ($hasActiveLocks) {
            throw ValidationException::withMessages([
                'design_json' => 'This seating plan has active seat locks. Wait until locks expire before rebuilding the layout.',
            ]);
        }

        $hasIssuedTickets = OrderTicket::query()
            ->whereIn('seat_id', $seatIds)
            ->whereNotIn('status', [OrderTicket::STATUS_CANCELLED, OrderTicket::STATUS_REFUNDED])
            ->exists();

        if ($hasIssuedTickets) {
            throw ValidationException::withMessages([
                'design_json' => 'This seating plan already has issued tickets. Rebuilding the layout is blocked to protect sold seats.',
            ]);
        }
    }


    /**
     * Capture current ticket-to-section assignments by stable section name before
     * sections are rebuilt. This keeps Advanced Step A4 matrix rules intact after
     * a safe designer re-save that recreates section IDs.
     */
    private function ticketSectionNamesForPlan(SeatingPlan $plan): array
    {
        $oldSectionsById = $plan->sections()
            ->get(['id', 'name'])
            ->mapWithKeys(fn (SeatingSection $section): array => [(int) $section->id => $this->sectionNameKey($section->name)])
            ->all();

        if ($oldSectionsById === []) {
            return [];
        }

        $tickets = EventTicket::query()
            ->whereHas('event', fn ($query) => $query->where('seating_plan_id', $plan->id))
            ->get(['id', 'valid_section_ids']);

        $assignments = [];

        foreach ($tickets as $ticket) {
            $sectionNames = collect($ticket->validSectionIdsArray())
                ->map(fn (int $sectionId): ?string => $oldSectionsById[$sectionId] ?? null)
                ->filter()
                ->unique()
                ->values()
                ->all();

            if ($sectionNames !== []) {
                $assignments[(int) $ticket->id] = $sectionNames;
            }
        }

        return $assignments;
    }

    private function restoreTicketSectionAssignments(array $ticketSectionNames, array $createdSectionIdsByName): void
    {
        foreach ($ticketSectionNames as $ticketId => $sectionNames) {
            $newSectionIds = collect($sectionNames)
                ->map(fn (string $sectionName): ?int => $createdSectionIdsByName[$sectionName] ?? null)
                ->filter()
                ->values()
                ->all();

            EventTicket::query()
                ->whereKey($ticketId)
                ->update(['valid_section_ids' => $newSectionIds]);
        }
    }

    private function sectionNameKey(string $name): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/', ' ', $name)));
    }

    private function normalizeDesignBlocks(mixed $payload): array
    {
        $decoded = $this->decodePayload($payload, 'design_json');

        if (Arr::isAssoc($decoded) && isset($decoded['blocks']) && is_array($decoded['blocks'])) {
            $decoded = $decoded['blocks'];
        }

        if (! is_array($decoded) || Arr::isAssoc($decoded)) {
            throw ValidationException::withMessages([
                'design_json' => 'The designer JSON must be a list of visual blocks.',
            ]);
        }

        if (count($decoded) > self::MAX_BLOCKS) {
            throw ValidationException::withMessages([
                'design_json' => 'The designer contains too many visual blocks.',
            ]);
        }

        $blocks = [];

        foreach ($decoded as $index => $block) {
            if (! is_array($block)) {
                throw ValidationException::withMessages([
                    'design_json' => "Designer block #".($index + 1)." is invalid.",
                ]);
            }

            $type = $this->normalizeBlockType($block['type'] ?? 'seat');
            $name = $this->cleanName($block['name'] ?? $block['label'] ?? ucfirst(str_replace('_', ' ', $type)));
            $seats = $this->normalizeSeats($block['seats'] ?? [], false);

            $blocks[] = [
                'id' => $this->cleanOptionalString($block['id'] ?? null),
                'type' => $type,
                'name' => $name,
                'x' => $this->safeInt($block['x'] ?? 0),
                'y' => $this->safeInt($block['y'] ?? 0),
                'width' => max(20, $this->safeInt($block['width'] ?? 160)),
                'height' => max(20, $this->safeInt($block['height'] ?? 100)),
                'rotation' => $this->safeInt($block['rotation'] ?? 0),
                'capacity' => max(0, $this->safeInt($block['capacity'] ?? count($seats))),
                'ticket_type_id' => $this->safeNullableInt($block['ticket_type_id'] ?? null),
                'color' => $this->cleanOptionalString($block['color'] ?? $block['background'] ?? null),
                'seats' => $seats,
            ];
        }

        return $blocks;
    }

    private function normalizeSections(mixed $payload, array $designBlocks): array
    {
        $decoded = $payload === null ? null : $this->decodePayload($payload, 'sections');

        if ($decoded === null || $decoded === []) {
            $decoded = collect($designBlocks)
                ->reject(fn (array $block): bool => $block['type'] === 'stage')
                ->values()
                ->all();
        }

        if (! is_array($decoded) || Arr::isAssoc($decoded)) {
            throw ValidationException::withMessages([
                'sections' => 'The section inventory payload must be a list of sections.',
            ]);
        }

        if (count($decoded) > self::MAX_BLOCKS) {
            throw ValidationException::withMessages([
                'sections' => 'The designer contains too many sections.',
            ]);
        }

        $sections = [];
        $totalSeats = 0;

        foreach ($decoded as $index => $section) {
            if (! is_array($section)) {
                throw ValidationException::withMessages([
                    'sections' => "Section #".($index + 1)." is invalid.",
                ]);
            }

            $type = $this->normalizeSectionType($section['type'] ?? 'seat');

            if ($type === 'stage') {
                continue;
            }

            $seats = $this->normalizeSeats($section['seats'] ?? [], true);
            $totalSeats += count($seats);

            if ($totalSeats > self::MAX_TOTAL_SEATS) {
                throw ValidationException::withMessages([
                    'sections' => 'The designer contains too many seats for one seating plan.',
                ]);
            }

            $sections[] = [
                'name' => $this->cleanName($section['name'] ?? $section['label'] ?? 'Section '.($index + 1)),
                'type' => $type,
                'capacity' => max(0, $this->safeInt($section['capacity'] ?? count($seats))),
                'x' => $this->safeInt($section['x'] ?? 0),
                'y' => $this->safeInt($section['y'] ?? 0),
                'rotation' => $this->safeInt($section['rotation'] ?? 0),
                'seats' => $seats,
            ];
        }

        return $sections;
    }

    private function normalizeSeats(mixed $payload, bool $forInventory): array
    {
        if ($payload === null || $payload === '') {
            return [];
        }

        if (! is_array($payload)) {
            throw ValidationException::withMessages([
                'sections' => 'Seat rows must be an array.',
            ]);
        }

        if (count($payload) > self::MAX_SEATS_PER_BLOCK) {
            throw ValidationException::withMessages([
                'sections' => 'One section contains too many seats.',
            ]);
        }

        $seats = [];
        $usedLabels = [];

        foreach ($payload as $index => $seat) {
            if (! is_array($seat)) {
                throw ValidationException::withMessages([
                    'sections' => "Seat #".($index + 1)." is invalid.",
                ]);
            }

            $label = $this->cleanName($seat['label'] ?? 'Seat '.($index + 1));
            $stableLabel = $label;
            $suffix = 2;

            while (isset($usedLabels[strtolower($stableLabel)])) {
                $stableLabel = $label.'-'.$suffix;
                $suffix++;
            }

            $usedLabels[strtolower($stableLabel)] = true;

            $rowLabel = $seat['row_label'] ?? null;
            $seatNumber = $seat['seat_number'] ?? null;

            if ($forInventory && ($rowLabel === null || $seatNumber === null)) {
                [$rowLabel, $seatNumber] = $this->splitSeatLabel($stableLabel);
            }

            $seats[] = [
                'id' => $this->cleanOptionalString($seat['id'] ?? null),
                'label' => $stableLabel,
                'row_label' => $this->cleanOptionalString($rowLabel),
                'seat_number' => $this->safeNullableInt($seatNumber),
                'disabled' => filter_var($seat['disabled'] ?? $seat['is_disabled'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'x' => $this->safeInt($seat['x'] ?? 0),
                'y' => $this->safeInt($seat['y'] ?? 0),
            ];
        }

        return $seats;
    }

    private function insertSeats(SeatingSection $section, array $seats): void
    {
        if ($seats === []) {
            return;
        }

        $now = now();
        $hasAliasColumn = Schema::hasColumn('seating_seats', 'seating_section_id');
        $rows = [];

        foreach ($seats as $seat) {
            $row = [
                'section_id' => $section->id,
                'label' => $seat['label'],
                'row_label' => $seat['row_label'],
                'seat_number' => $seat['seat_number'],
                'is_disabled' => $seat['disabled'],
                'is_researved' => false,
                'status' => $seat['disabled'] ? 'unavailable' : 'available',
                'x' => $seat['x'],
                'y' => $seat['y'],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($hasAliasColumn) {
                $row['seating_section_id'] = $section->id;
            }

            $rows[] = $row;
        }

        SeatingSeat::insert($rows);
    }

    private function decodePayload(mixed $payload, string $field): mixed
    {
        if (is_string($payload)) {
            $decoded = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw ValidationException::withMessages([
                    $field => 'Invalid JSON: '.json_last_error_msg(),
                ]);
            }

            return $decoded;
        }

        return $payload;
    }

    private function normalizeBlockType(string $type): string
    {
        return match ($type) {
            'section' => 'seat',
            'ga' => 'general_admission',
            'seat', 'stage', 'general_admission', 'table' => $type,
            default => 'seat',
        };
    }

    private function normalizeSectionType(string $type): string
    {
        return match ($this->normalizeBlockType($type)) {
            'table' => 'table',
            'general_admission' => 'general_admission',
            'stage' => 'stage',
            default => 'seat',
        };
    }

    private function cleanName(mixed $value): string
    {
        $name = trim(strip_tags((string) $value));
        $name = preg_replace('/\s+/', ' ', $name) ?: 'Section';

        return mb_substr($name, 0, 120);
    }

    private function cleanOptionalString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return mb_substr(trim(strip_tags((string) $value)), 0, 120);
    }

    private function safeInt(mixed $value): int
    {
        if (is_numeric($value)) {
            return (int) round((float) $value);
        }

        return 0;
    }

    private function safeNullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }

    private function splitSeatLabel(string $label): array
    {
        preg_match('/^([A-Za-z]+)[\s\-]*(\d+)$/', $label, $matches);

        if ($matches) {
            return [$matches[1], (int) $matches[2]];
        }

        preg_match('/([A-Za-z]+)/', $label, $rowMatches);
        preg_match('/(\d+)/', $label, $numberMatches);

        return [$rowMatches[1] ?? null, isset($numberMatches[1]) ? (int) $numberMatches[1] : null];
    }
}
