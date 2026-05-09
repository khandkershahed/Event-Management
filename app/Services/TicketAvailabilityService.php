<?php

namespace App\Services;

use App\Models\EventTicket;

class TicketAvailabilityService
{
    public function totalQuantity(EventTicket $ticket): int
    {
        return (int) ($ticket->quantity ?? 0);
    }

    public function soldQuantity(EventTicket $ticket): int
    {
        return (int) ($ticket->sold_quantity ?? 0);
    }

    public function remainingQuantity(EventTicket $ticket): int
    {
        if ($ticket->quantity === null) {
            return PHP_INT_MAX;
        }

        return max(0, $this->totalQuantity($ticket) - $this->soldQuantity($ticket));
    }

    public function isOnSale(EventTicket $ticket): bool
    {
        return $ticket->isOnSale()
            && ($ticket->status === EventTicket::STATUS_ACTIVE || ($ticket->status === null && (bool) $ticket->is_active))
            && ($ticket->visibility === EventTicket::VISIBILITY_PUBLIC || $ticket->visibility === null);
    }

    public function isSoldOut(EventTicket $ticket): bool
    {
        return $this->remainingQuantity($ticket) <= 0 || $ticket->status === EventTicket::STATUS_SOLD_OUT;
    }

    public function validateRequestedQuantity(EventTicket $ticket, int $quantity): array
    {
        $min = max(1, (int) ($ticket->min_per_order ?? 1));
        $max = $ticket->max_per_order ? (int) $ticket->max_per_order : null;
        $remaining = $this->remainingQuantity($ticket);

        if (! $this->isOnSale($ticket)) {
            return ['ok' => false, 'message' => 'This ticket is not currently on sale.'];
        }

        if ($this->isSoldOut($ticket)) {
            return ['ok' => false, 'message' => 'This ticket is sold out.'];
        }

        if ($quantity < $min) {
            return ['ok' => false, 'message' => "Minimum order quantity is {$min}."];
        }

        if ($max !== null && $quantity > $max) {
            return ['ok' => false, 'message' => "Maximum order quantity is {$max}."];
        }

        if ($quantity > $remaining) {
            return ['ok' => false, 'message' => 'Requested quantity is greater than the remaining ticket quantity.'];
        }

        return ['ok' => true, 'message' => 'Ticket quantity is available.'];
    }

    public function summary(EventTicket $ticket, int $requestedQuantity = 1): array
    {
        $requestedQuantity = max(1, $requestedQuantity);
        $validation = $this->validateRequestedQuantity($ticket, $requestedQuantity);
        $maxPerOrder = $ticket->max_per_order ? (int) $ticket->max_per_order : null;

        return [
            'total_quantity' => $this->totalQuantity($ticket),
            'sold_quantity' => $this->soldQuantity($ticket),
            'remaining_quantity' => $this->remainingQuantity($ticket),
            'requested_quantity' => $requestedQuantity,
            'min_per_order' => max(1, (int) ($ticket->min_per_order ?? 1)),
            'max_per_order' => $maxPerOrder,
            'is_on_sale' => $this->isOnSale($ticket),
            'is_sold_out' => $this->isSoldOut($ticket),
            'can_order' => (bool) $validation['ok'],
            'can_purchase' => (bool) $validation['ok'],
            'requested_quantity_allowed' => (bool) $validation['ok'],
            'message' => $validation['message'],
        ];
    }
}
