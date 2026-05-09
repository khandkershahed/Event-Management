<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\OrderTicket;
use App\Models\SeatLock;
use App\Models\SeatingSeat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SeatLockService
{
    public function clearExpired(): int
    {
        return SeatLock::where('expires_at', '<=', now())->delete();
    }

    public function lockSeat(Event $event, EventTicket $ticket, SeatingSeat $seat, string $sessionId, ?int $userId = null): SeatLock
    {
        $userId = $userId ?: Auth::id();

        return DB::transaction(function () use ($event, $ticket, $seat, $sessionId, $userId) {
            $this->clearExpired();
            $ticket->loadMissing('event');
            $seat->loadMissing('section.seatingPlan');

            if ($event->status !== Event::STATUS_PUBLISHED) {
                throw new RuntimeException('This event is not available for reservations.');
            }

            if ((int) $ticket->event_id !== (int) $event->id) {
                throw new RuntimeException('This ticket does not belong to the selected event.');
            }

            if (! $event->seating_plan_id || ! $seat->section || (int) $seat->section->seating_plan_id !== (int) $event->seating_plan_id) {
                throw new RuntimeException('This seat does not belong to the selected event seating plan.');
            }

            if ((bool) ($seat->is_disabled ?? false) || (bool) ($seat->is_researved ?? false)) {
                throw new RuntimeException('This seat is not available.');
            }

            $validSectionIds = collect($ticket->valid_section_ids ?: [])->map(fn ($id) => (int) $id)->filter()->values();

            if ($validSectionIds->isNotEmpty() && ! $validSectionIds->contains((int) $seat->section_id)) {
                throw new RuntimeException('This seat is outside the sections allowed for this ticket type.');
            }

            $sold = OrderTicket::where('event_id', $event->id)->where('seat_id', $seat->id)->exists();

            if ($sold) {
                throw new RuntimeException('This seat has already been sold.');
            }

            $existing = SeatLock::where('event_id', $event->id)
                ->where('seat_id', $seat->id)
                ->lockForUpdate()
                ->first();

            if ($existing && $existing->expires_at && $existing->expires_at->gt(now()) && ! $existing->isOwnedBy($userId, $sessionId)) {
                throw new RuntimeException('This seat is currently locked by another customer.');
            }

            if ($existing) {
                $existing->update([
                    'ticket_type_id' => $ticket->id,
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'expires_at' => now()->addMinutes(10),
                ]);

                return $existing->fresh(['event', 'ticketType', 'seat.section']);
            }

            return SeatLock::create([
                'event_id' => $event->id,
                'seat_id' => $seat->id,
                'ticket_type_id' => $ticket->id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'expires_at' => now()->addMinutes(10),
            ])->fresh(['event', 'ticketType', 'seat.section']);
        });
    }

    public function unlockSeat(Event $event, SeatingSeat $seat, string $sessionId, ?int $userId = null): bool
    {
        $userId = $userId ?: Auth::id();

        return SeatLock::where('event_id', $event->id)
            ->where('seat_id', $seat->id)
            ->active()
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhere('session_id', $sessionId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->delete() > 0;
    }

    public function seatStatusMap(Event $event, string $sessionId, ?int $userId = null): array
    {
        $userId = $userId ?: Auth::id();
        $map = [];

        $event->loadMissing('seatingPlan.sections.seats');

        if (! $event->seatingPlan) {
            return $map;
        }

        foreach ($event->seatingPlan->sections as $section) {
            foreach ($section->seats as $seat) {
                $map[$seat->id] = ((bool) ($seat->is_disabled ?? false) || (bool) ($seat->is_researved ?? false)) ? 'unavailable' : 'available';
            }
        }

        $soldSeatIds = OrderTicket::where('event_id', $event->id)->whereNotNull('seat_id')->pluck('seat_id')->all();
        foreach ($soldSeatIds as $seatId) {
            $map[$seatId] = 'sold';
        }

        $locks = SeatLock::where('event_id', $event->id)->active()->get();
        foreach ($locks as $lock) {
            $map[$lock->seat_id] = $lock->isOwnedBy($userId, $sessionId) ? 'selected' : 'locked';
        }

        return $map;
    }
}
