<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SeatLock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderPlacementService
{
    public function __construct(
        protected TicketAvailabilityService $availabilityService,
        protected OrderNumberService $orderNumberService,
        protected TicketIssuanceService $ticketIssuanceService,
        protected MarketplaceNotificationService $notificationService
    ) {
    }

    public function preview(string $sessionId, ?int $userId = null): array
    {
        $items = $this->cartItems($sessionId, $userId);

        return [
            'items' => $items,
            'subtotal' => (float) $items->sum('subtotal'),
            'discount_total' => 0.00,
            'fee_total' => 0.00,
            'total' => (float) $items->sum('subtotal'),
            'currency' => $items->first()?->ticketType?->currency ?: 'BDT',
        ];
    }

    public function placeFromCart(string $sessionId, array $customerData = [], ?int $userId = null): Order
    {
        $userId = $userId ?: Auth::id();

        return DB::transaction(function () use ($sessionId, $customerData, $userId) {
            $items = $this->cartItems($sessionId, $userId, lock: true);

            if ($items->isEmpty()) {
                throw new RuntimeException('Your cart is empty.');
            }

            $eventIds = $items->pluck('event_id')->unique()->values();
            if ($eventIds->count() !== 1) {
                throw new RuntimeException('Please place separate orders for different events.');
            }

            $event = Event::publiclyVisible()->find($eventIds->first());
            if (! $event) {
                throw new RuntimeException('This event is no longer available for checkout.');
            }

            $this->revalidateCartItems($items, $sessionId, $userId);

            $subtotal = (float) $items->sum('subtotal');
            $discountTotal = 0.00;
            $feeTotal = 0.00;
            $total = max(0, $subtotal - $discountTotal + $feeTotal);
            $isFreeOrder = $total <= 0;

            $user = Auth::user();
            $order = Order::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'event_id' => $event->id,
                'order_number' => $this->orderNumberService->generate(),
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'fee_total' => $feeTotal,
                'total' => $total,
                'currency' => $items->first()?->ticketType?->currency ?: 'BDT',
                'status' => $isFreeOrder ? Order::STATUS_COMPLETED : Order::STATUS_PENDING_PAYMENT,
                'payment_status' => $isFreeOrder ? Order::PAYMENT_PAID : Order::PAYMENT_UNPAID,
                'customer_name' => $customerData['customer_name'] ?? $user?->name,
                'customer_email' => $customerData['customer_email'] ?? $user?->email,
                'customer_phone' => $customerData['customer_phone'] ?? null,
                'expires_at' => $isFreeOrder ? null : now()->addMinutes(30),
            ]);

            foreach ($items as $cartItem) {
                $ticket = EventTicket::whereKey($cartItem->ticket_type_id)->lockForUpdate()->firstOrFail();

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'event_ticket_id' => $ticket->id,
                    'ticket_type_id' => $ticket->id,
                    'ticket_name' => $ticket->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'subtotal' => $cartItem->subtotal,
                ]);

                if ($cartItem->seat_id) {
                    $this->ticketIssuanceService->issue($order, $orderItem, $ticket, $cartItem->seat, $order->customer_name, $order->customer_email);
                } else {
                    for ($i = 0; $i < (int) $cartItem->quantity; $i++) {
                        $this->ticketIssuanceService->issue($order, $orderItem, $ticket, null, $order->customer_name, $order->customer_email);
                    }
                }

                $ticket->increment('sold_quantity', (int) $cartItem->quantity);
            }

            SeatLock::query()
                ->where('event_id', $event->id)
                ->where(function ($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->whereNull('user_id')->where('session_id', $sessionId);
                    }
                })
                ->delete();

            CartItem::query()
                ->forOwner($userId, $sessionId)
                ->delete();

            $freshOrder = $order->fresh(['user', 'event', 'items.tickets.seat.section', 'tickets.seat.section', 'tickets.ticketType']);

            $this->notificationService->notifyOrderPlaced($freshOrder);
            $this->notificationService->notifyTicketsIssuedForOrder($freshOrder);

            return $freshOrder;
        });
    }

    protected function cartItems(string $sessionId, ?int $userId = null, bool $lock = false): Collection
    {
        $userId = $userId ?: Auth::id();

        $query = CartItem::query()
            ->with(['event', 'ticketType', 'seat.section'])
            ->forOwner($userId, $sessionId)
            ->orderBy('id');

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->get();
    }

    protected function revalidateCartItems(Collection $items, string $sessionId, ?int $userId = null): void
    {
        $userId = $userId ?: Auth::id();

        foreach ($items->groupBy('ticket_type_id') as $ticketId => $ticketItems) {
            $ticket = EventTicket::whereKey($ticketId)->lockForUpdate()->firstOrFail();
            $requestedQuantity = (int) $ticketItems->sum('quantity');
            $validation = $this->availabilityService->validateRequestedQuantity($ticket, $requestedQuantity);

            if (! $validation['ok']) {
                throw new RuntimeException($validation['message']);
            }
        }

        foreach ($items as $item) {
            if (! $item->ticketType) {
                throw new RuntimeException('A ticket in your cart is no longer available.');
            }

            if ($item->seat_id) {
                $lock = SeatLock::where('event_id', $item->event_id)
                    ->where('seat_id', $item->seat_id)
                    ->where('ticket_type_id', $item->ticket_type_id)
                    ->where('expires_at', '>', now())
                    ->lockForUpdate()
                    ->first();

                if (! $lock || ! $lock->isOwnedBy($userId, $sessionId)) {
                    throw new RuntimeException('One of your selected seats has expired or is no longer locked by you.');
                }
            }
        }
    }
}
