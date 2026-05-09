<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\RefundRequest;
use App\Models\RefundTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RefundService
{
    public function __construct(protected LedgerReversalService $ledgerReversalService) {}

    public function createCustomerRequest(Order $order, User $user, ?string $reason = null): RefundRequest
    {
        $order->loadMissing('event.organizerProfile');
        if ((int) $order->user_id !== (int) $user->id) { throw new RuntimeException('This order does not belong to the current customer.'); }
        if (! $order->isPaidForRefund()) { throw new RuntimeException('Only paid and completed orders can be refunded.'); }
        if ($order->isRefundedOrCancelled()) { throw new RuntimeException('This order is already refunded or cancelled.'); }
        if ($this->hasOpenRequest($order)) { throw new RuntimeException('A pending refund request already exists for this order.'); }

        return RefundRequest::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'organizer_profile_id' => $order->event?->organizer_profile_id,
            'event_id' => $order->event_id,
            'requested_by_type' => User::class,
            'requested_by_id' => $user->id,
            'reason' => $reason,
            'amount' => $order->total,
            'currency' => $order->currency ?: 'BDT',
            'status' => RefundRequest::STATUS_PENDING,
        ]);
    }

    public function hasOpenRequest(Order $order): bool
    {
        return RefundRequest::query()->where('order_id', $order->id)->where('status', RefundRequest::STATUS_PENDING)->exists();
    }

    public function approve(RefundRequest $refundRequest, int $adminId, ?string $adminNote = null): RefundRequest
    {
        if ($refundRequest->status !== RefundRequest::STATUS_PENDING) { throw new RuntimeException('Only pending refund requests can be approved.'); }

        return DB::transaction(function () use ($refundRequest, $adminId, $adminNote) {
            $order = $refundRequest->order()->with(['tickets', 'latestPaymentTransaction'])->lockForUpdate()->firstOrFail();
            if (! $order->isPaidForRefund()) { throw new RuntimeException('Only paid and completed orders can be approved for refund.'); }

            $refundRequest->forceFill(['status' => RefundRequest::STATUS_APPROVED, 'admin_note' => $adminNote, 'reviewed_by' => $adminId, 'reviewed_at' => now()])->save();

            RefundTransaction::query()->firstOrCreate(
                ['refund_request_id' => $refundRequest->id, 'order_id' => $order->id],
                [
                    'payment_transaction_id' => $order->latestPaymentTransaction?->id,
                    'provider' => $order->latestPaymentTransaction?->provider ?: 'manual',
                    'amount' => $refundRequest->amount,
                    'currency' => $refundRequest->currency ?: ($order->currency ?: 'BDT'),
                    'status' => RefundTransaction::STATUS_MANUAL,
                    'raw_payload' => ['note' => 'Internal manual refund record. Real provider refund API is deferred.'],
                    'processed_at' => now(),
                ]
            );

            $order->tickets()->update(['status' => OrderTicket::STATUS_REFUNDED, 'is_checked_in' => false, 'checked_in_at' => null]);
            $order->markRefunded();
            $this->ledgerReversalService->reverseForOrder($order, 'refund');

            return $refundRequest->fresh(['order', 'transactions']);
        });
    }

    public function reject(RefundRequest $refundRequest, int $adminId, ?string $adminNote = null): RefundRequest
    {
        if ($refundRequest->status !== RefundRequest::STATUS_PENDING) { throw new RuntimeException('Only pending refund requests can be rejected.'); }
        $refundRequest->forceFill(['status' => RefundRequest::STATUS_REJECTED, 'admin_note' => $adminNote, 'reviewed_by' => $adminId, 'reviewed_at' => now()])->save();
        return $refundRequest->fresh();
    }
}
