<?php

namespace App\Services;

use App\Models\Event;
use App\Models\SeatingSection;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class TicketSectionAssignmentService
{
    public function sectionsForEvent(Event $event): Collection
    {
        if (! $event->seating_plan_id) {
            return collect();
        }

        return SeatingSection::query()
            ->where('seating_plan_id', $event->seating_plan_id)
            ->orderBy('name')
            ->get();
    }

    public function normalizeForEvent(Event $event, mixed $sectionIds, string $field = 'valid_section_ids'): array
    {
        $ids = collect(is_array($sectionIds) ? $sectionIds : [])
            ->filter(fn ($id) => $id !== null && $id !== '')
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return [];
        }

        if (! $event->seating_plan_id) {
            throw ValidationException::withMessages([
                $field => 'Section restrictions can only be used for an event with a seating plan.',
            ]);
        }

        $allowedIds = $this->sectionsForEvent($event)->pluck('id')->map(fn ($id) => (int) $id);
        $invalidIds = $ids->diff($allowedIds);

        if ($invalidIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                $field => 'All selected sections must belong to this event seating plan.',
            ]);
        }

        return $ids->all();
    }
}
