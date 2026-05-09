<?php

namespace App\Services\Seating;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\SeatingSeat;
use Illuminate\Support\Collection;

class PublicSeatMapService
{
    public function build(Event $event, array $seatStatuses): array
    {
        $event->loadMissing(['seatingPlan.sections.seats', 'publicTickets']);

        $plan = $event->seatingPlan;
        $tickets = $event->publicTickets;
        $sections = $plan?->sections ?? collect();
        $ticketRules = $this->ticketRules($tickets);

        if (! $plan) {
            return [
                'has_visual_map' => false,
                'blocks' => [],
                'fallback_sections' => [],
                'selected_seats' => [],
            ];
        }

        $blocks = $this->visualBlocks($plan->design_json ?: [], $sections, $seatStatuses, $ticketRules);
        $hasVisualMap = count($blocks) > 0;

        return [
            'has_visual_map' => $hasVisualMap,
            'blocks' => $blocks,
            'fallback_sections' => $hasVisualMap ? [] : $this->fallbackSections($sections, $seatStatuses, $ticketRules),
            'selected_seats' => $this->selectedSeats($sections, $seatStatuses),
        ];
    }

    private function visualBlocks(mixed $designJson, Collection $sections, array $seatStatuses, array $ticketRules): array
    {
        if (is_string($designJson)) {
            $decoded = json_decode($designJson, true);
            $designJson = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($designJson)) {
            return [];
        }

        if (array_key_exists('blocks', $designJson) && is_array($designJson['blocks'])) {
            $designJson = $designJson['blocks'];
        }

        if ($designJson === [] || $this->isAssoc($designJson)) {
            return [];
        }

        $sectionsByName = $sections->keyBy(fn ($section) => strtolower((string) $section->name));
        $blocks = [];

        foreach ($designJson as $index => $block) {
            if (! is_array($block)) {
                continue;
            }

            $type = (string) ($block['type'] ?? 'seat');
            $name = trim((string) ($block['name'] ?? $block['label'] ?? 'Section '.($index + 1)));
            $section = $sectionsByName->get(strtolower($name));

            $blocks[] = [
                'id' => (string) ($block['id'] ?? 'block-'.$index),
                'type' => $type,
                'name' => $name !== '' ? $name : 'Section '.($index + 1),
                'x' => (int) ($block['x'] ?? 0),
                'y' => (int) ($block['y'] ?? 0),
                'width' => max(20, (int) ($block['width'] ?? 160)),
                'height' => max(20, (int) ($block['height'] ?? 100)),
                'rotation' => (int) ($block['rotation'] ?? 0),
                'capacity' => (int) ($block['capacity'] ?? 0),
                'section_id' => $section?->id,
                'seats' => $type === 'stage' ? [] : $this->blockSeats($block['seats'] ?? [], $section, $seatStatuses, $ticketRules),
            ];
        }

        return $blocks;
    }

    private function fallbackSections(Collection $sections, array $seatStatuses, array $ticketRules): array
    {
        return $sections->map(function ($section) use ($seatStatuses, $ticketRules): array {
            return [
                'id' => $section->id,
                'name' => $section->name,
                'type' => $section->type,
                'seats' => $section->seats->map(fn (SeatingSeat $seat): array => $this->seatPayload($seat, $seatStatuses, $ticketRules))->values()->all(),
            ];
        })->values()->all();
    }

    private function blockSeats(mixed $seatBlocks, $section, array $seatStatuses, array $ticketRules): array
    {
        if (! $section || ! is_array($seatBlocks)) {
            return [];
        }

        $seatsByLabel = $section->seats->keyBy(fn ($seat) => strtolower((string) $seat->label));
        $seats = [];

        foreach ($seatBlocks as $index => $seatBlock) {
            if (! is_array($seatBlock)) {
                continue;
            }

            $label = trim((string) ($seatBlock['label'] ?? ''));
            $seat = $label !== '' ? $seatsByLabel->get(strtolower($label)) : null;

            if (! $seat) {
                $seats[] = [
                    'id' => null,
                    'label' => $label !== '' ? $label : 'Seat '.($index + 1),
                    'x' => (int) ($seatBlock['x'] ?? 0),
                    'y' => (int) ($seatBlock['y'] ?? 0),
                    'status' => 'unavailable',
                    'base_status' => 'unavailable',
                    'allowed_ticket_ids' => [],
                    'clickable' => false,
                ];
                continue;
            }

            $payload = $this->seatPayload($seat, $seatStatuses, $ticketRules);
            $payload['x'] = (int) ($seatBlock['x'] ?? $seat->x ?? 0);
            $payload['y'] = (int) ($seatBlock['y'] ?? $seat->y ?? 0);
            $seats[] = $payload;
        }

        return $seats;
    }

    private function seatPayload(SeatingSeat $seat, array $seatStatuses, array $ticketRules): array
    {
        $status = $seatStatuses[$seat->id] ?? 'available';

        if ((bool) ($seat->is_disabled ?? false) || (bool) ($seat->is_researved ?? false)) {
            $status = 'unavailable';
        }

        return [
            'id' => $seat->id,
            'label' => $seat->label,
            'row_label' => $seat->row_label,
            'seat_number' => $seat->seat_number,
            'x' => (int) ($seat->x ?? 0),
            'y' => (int) ($seat->y ?? 0),
            'status' => $status,
            'base_status' => $status,
            'allowed_ticket_ids' => $this->allowedTicketIdsForSection((int) $seat->section_id, $ticketRules),
            'clickable' => in_array($status, ['available', 'selected'], true),
        ];
    }

    private function selectedSeats(Collection $sections, array $seatStatuses): array
    {
        return $sections->flatMap(function ($section) use ($seatStatuses) {
            return $section->seats->filter(fn ($seat): bool => ($seatStatuses[$seat->id] ?? 'available') === 'selected')
                ->map(fn ($seat): array => [
                    'id' => $seat->id,
                    'label' => $seat->label,
                    'section_name' => $section->name,
                ]);
        })->values()->all();
    }

    private function ticketRules(Collection $tickets): array
    {
        return $tickets->mapWithKeys(function (EventTicket $ticket): array {
            return [(int) $ticket->id => collect($ticket->validSectionIdsArray())->map(fn ($id) => (int) $id)->values()->all()];
        })->all();
    }

    private function allowedTicketIdsForSection(int $sectionId, array $ticketRules): array
    {
        $allowed = [];

        foreach ($ticketRules as $ticketId => $sectionIds) {
            if ($sectionIds === [] || in_array($sectionId, $sectionIds, true)) {
                $allowed[] = (int) $ticketId;
            }
        }

        return $allowed;
    }

    private function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
