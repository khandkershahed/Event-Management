<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventCancellationRequest;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\OrganizerProfile;
use App\Models\RefundRequest;
use App\Models\RefundTransaction;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EventCancellationService
{
    public function __construct(protected LedgerReversalService $ledgerReversalService) {}

    public function requestCancellation(Event $event, OrganizerProfile $organizer, int $userId, ?string $reason = null): EventCancellationRequest
    {
        if ((int) $event->organizer_profile_id !== (int) $organizer->id) { throw new RuntimeException('You can request cancellation only for your own event.'); }
        if ($event->status === Event::STATUS_CANCELLED) { throw new RuntimeException('This event is already cancelled.'); }
        if ($this->hasOpenRequest($event)) { throw new RuntimeException('A pending cancellation request already exists for this event.'); }

        return EventCancellationRequest::create([
            'event_id' => $event->id,
            'organizer_profile_id' => $organizer->id,
            'requested_by' => $userId,
            'reason' => $reason,
            'status' => EventCancellationRequest::STATUS_PENDING,
        ]);
    }

    public function hasOpenRequest(Event $event): bool
    {
        return EventCancellationRequest::query()->where('event_id', $event->id)->where('status', EventCancellationRequest::STATUS_PENDING)->exists();
    }

    public function approve(EventCancellationRequest $request, int $adminId, ?string $adminNote = null): EventCancellationRequest
    {
        if ($request->status !== EventCancellationRequest::STATUS_PENDING) { throw new RuntimeException('Only pending cancellation requests can be approved.'); }

        return DB::transaction(function () use ($request, $adminId, $adminNote) {
            $event = $request->event()->lockForUpdate()->firstOrFail();
            $event->forceFill(['status' => Event::STATUS_CANCELLED])->save();
            $event->tickets()->update(['status' => 'paused']);
            $event->orderTickets()->whereNotIn('status', [OrderTicket::STATUS_REFUNDED, OrderTicket::STATUS_CANCELLED])->update(['status' => OrderTicket::STATUS_CANCELLED, 'is_checked_in' => false, 'checked_in_at' => null]);

            $request->forceFill(['status' => EventCancellationRequest::STATUS_APPROVED, 'admin_note' => $adminNote, 'reviewed_by' => $adminId, 'reviewed_at' => now()])->save();

            $event->orders()->with('latestPaymentTransaction')->chunkById(50, function ($orders) use ($event, $adminId) {
                foreach ($orders as $order) {
                    if ($order->payment_status === Order::PAYMENT_PAID && ! $order->isRefundedOrCancelled()) {
                        $refund = RefundRequest::query()->firstOrCreate(
                            ['order_id' => $order->id, 'status' => RefundRequest::STATUS_APPROVED],
                            [
                                'user_id' => $order->user_id,
                                'organizer_profile_id' => $event->organizer_profile_id,
                                'event_id' => $event->id,
                                'requested_by_type' => 'system_event_cancellation',
                                'requested_by_id' => $adminId,
                                'reason' => 'Event cancellation approved by admin.',
                                'amount' => $order->total,
                                'currency' => $order->currency ?: 'BDT',
                                'admin_note' => 'Automatic refund record from event cancellation.',
                                'reviewed_by' => $adminId,
                                'reviewed_at' => now(),
                            ]
                        );
                        RefundTransaction::query()->firstOrCreate(
                            ['refund_request_id' => $refund->id, 'order_id' => $order->id],
                            [
                                'payment_transaction_id' => $order->latestPaymentTransaction?->id,
                                'provider' => $order->latestPaymentTransaction?->provider ?: 'manual',
                                'amount' => $order->total,
                                'currency' => $order->currency ?: 'BDT',
                                'status' => RefundTransaction::STATUS_MANUAL,
                                'raw_payload' => ['note' => 'Internal manual refund record from event cancellation.'],
                                'processed_at' => now(),
                            ]
                        );
                        $order->markRefunded();
                        $this->ledgerReversalService->reverseForOrder($order, 'event_cancellation');
                    } elseif ($order->payment_status !== Order::PAYMENT_PAID) {
                        $order->markCancelled();
                    }
                }
            });

            return $request->fresh(['event', 'organizerProfile']);
        });
    }

    public function reject(EventCancellationRequest $request, int $adminId, ?string $adminNote = null): EventCancellationRequest
    {
        if ($request->status !== EventCancellationRequest::STATUS_PENDING) { throw new RuntimeException('Only pending cancellation requests can be rejected.'); }
        $request->forceFill(['status' => EventCancellationRequest::STATUS_REJECTED, 'admin_note' => $adminNote, 'reviewed_by' => $adminId, 'reviewed_at' => now()])->save();
        return $request->fresh();
    }
}
