<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\SeatLock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartService
{
    public function __construct(
        protected TicketAvailabilityService $availabilityService
    ) {
    }

    public function addGeneralAdmission(EventTicket $ticket, int $quantity, string $sessionId, ?int $userId = null): CartItem
    {
        $userId = $userId ?: Auth::id();
        $quantity = max(1, $quantity);
        $ticket->loadMissing('event');

        if (! $ticket->event || $ticket->event->status !== \App\Models\Event::STATUS_PUBLISHED) {
            throw new RuntimeException('This event is not available for public reservation.');
        }

        if ($ticket->requiresSeatSelection() && $ticket->event->seating_plan_id) {
            throw new RuntimeException('This ticket requires seat selection before it can be added to the cart.');
        }

        $validation = $this->availabilityService->validateRequestedQuantity($ticket, $quantity);

        if (! $validation['ok']) {
            throw new RuntimeException($validation['message']);
        }

        return DB::transaction(function () use ($ticket, $quantity, $sessionId, $userId) {
            $existing = CartItem::forOwner($userId, $sessionId)
                ->where('event_id', $ticket->event_id)
                ->where('ticket_type_id', $ticket->id)
                ->whereNull('seat_id')
                ->lockForUpdate()
                ->first();

            $finalQuantity = $quantity + (int) ($existing?->quantity ?? 0);
            $validation = $this->availabilityService->validateRequestedQuantity($ticket->fresh(), $finalQuantity);

            if (! $validation['ok']) {
                throw new RuntimeException($validation['message']);
            }

            $unitPrice = (float) $ticket->price;
            $payload = [
                'event_id' => $ticket->event_id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ticket_type_id' => $ticket->id,
                'seat_id' => null,
                'quantity' => $finalQuantity,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $finalQuantity,
                'expires_at' => now()->addMinutes(15),
            ];

            if ($existing) {
                $existing->update($payload);
                return $existing->fresh(['event', 'ticketType', 'seat']);
            }

            return CartItem::create($payload)->fresh(['event', 'ticketType', 'seat']);
        });
    }

    public function addLockedSeat(SeatLock $lock, string $sessionId, ?int $userId = null): CartItem
    {
        $userId = $userId ?: Auth::id();
        $lock->loadMissing('event', 'ticketType', 'seat');

        if (! $lock->isOwnedBy($userId, $sessionId)) {
            throw new RuntimeException('This seat lock does not belong to the current cart owner.');
        }

        if (! $lock->ticketType) {
            throw new RuntimeException('This lock is missing its ticket type.');
        }

        return DB::transaction(function () use ($lock, $sessionId, $userId) {
            $unitPrice = (float) $lock->ticketType->price;

            $existing = CartItem::query()
                ->where('event_id', $lock->event_id)
                ->where('seat_id', $lock->seat_id)
                ->where(function ($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->whereNull('user_id')->where('session_id', $sessionId);
                    }
                })
                ->lockForUpdate()
                ->first();

            CartItem::query()
                ->where('event_id', $lock->event_id)
                ->where('seat_id', $lock->seat_id)
                ->where(function ($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', '!=', $userId)->orWhereNull('user_id');
                    } else {
                        $query->whereNotNull('user_id')->orWhere('session_id', '!=', $sessionId);
                    }
                })
                ->delete();

            $payload = [
                'event_id' => $lock->event_id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ticket_type_id' => $lock->ticket_type_id,
                'seat_id' => $lock->seat_id,
                'quantity' => 1,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice,
                'expires_at' => $lock->expires_at,
            ];

            if ($existing) {
                $existing->update($payload);

                return $existing->fresh(['event', 'ticketType', 'seat.section']);
            }

            return CartItem::create($payload)->fresh(['event', 'ticketType', 'seat.section']);
        });
    }


    public function removeLockedSeat(Event $event, \App\Models\SeatingSeat $seat, string $sessionId, ?int $userId = null): void
    {
        $userId = $userId ?: Auth::id();

        CartItem::query()
            ->where('event_id', $event->id)
            ->where('seat_id', $seat->id)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId)
                        ->orWhere('session_id', $sessionId);
                } else {
                    $query->whereNull('user_id')->where('session_id', $sessionId);
                }
            })
            ->delete();
    }

    public function removeItem(int $cartItemId, string $sessionId, ?int $userId = null): void
    {
        $userId = $userId ?: Auth::id();

        $item = CartItem::forOwner($userId, $sessionId)->findOrFail($cartItemId);

        if ($item->seat_id) {
            SeatLock::where('event_id', $item->event_id)
                ->where('seat_id', $item->seat_id)
                ->where(function ($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })
                ->delete();
        }

        $item->delete();
    }

    public function clear(string $sessionId, ?int $userId = null): void
    {
        $userId = $userId ?: Auth::id();
        $items = $this->items($sessionId, $userId);

        foreach ($items as $item) {
            $this->removeItem($item->id, $sessionId, $userId);
        }
    }

    public function items(string $sessionId, ?int $userId = null): Collection
    {
        $userId = $userId ?: Auth::id();

        return CartItem::query()
            ->with(['event', 'ticketType', 'seat.section'])
            ->forOwner($userId, $sessionId)
            ->latest()
            ->get();
    }

    public function total(string $sessionId, ?int $userId = null): float
    {
        return (float) $this->items($sessionId, $userId)->sum('subtotal');
    }
}
