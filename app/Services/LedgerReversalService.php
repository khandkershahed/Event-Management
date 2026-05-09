<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrganizerLedger;
use App\Models\PlatformCommissionLedger;
use Illuminate\Support\Facades\DB;

class LedgerReversalService
{
    public function reverseForOrder(Order $order, string $reason = 'refund'): array
    {
        return DB::transaction(function () use ($order, $reason) {
            $order->loadMissing('event.organizerProfile');
            $organizer = $order->event?->organizerProfile;
            if (! $organizer) {
                return ['reversed' => false, 'reason' => 'Order has no organizer.'];
            }

            $originalOrganizerLedger = OrganizerLedger::query()
                ->where('order_id', $order->id)
                ->where('type', OrganizerLedger::TYPE_ORDER_EARNING)
                ->where('direction', OrganizerLedger::DIRECTION_CREDIT)
                ->first();

            $organizerReversal = null;
            if ($originalOrganizerLedger) {
                $organizerReversal = OrganizerLedger::query()->firstOrCreate(
                    [
                        'organizer_profile_id' => $organizer->id,
                        'order_id' => $order->id,
                        'type' => 'refund_reversal',
                        'direction' => OrganizerLedger::DIRECTION_DEBIT,
                    ],
                    [
                        'amount' => $originalOrganizerLedger->amount,
                        'currency' => $originalOrganizerLedger->currency,
                        'status' => OrganizerLedger::STATUS_POSTED,
                        'description' => 'Ledger reversal for order ' . $order->order_number . ' due to ' . $reason,
                        'meta' => ['reason' => $reason, 'source_ledger_id' => $originalOrganizerLedger->id],
                        'posted_at' => now(),
                    ]
                );
            }

            $originalPlatformLedger = PlatformCommissionLedger::query()
                ->where('order_id', $order->id)
                ->where('status', PlatformCommissionLedger::STATUS_POSTED)
                ->first();

            $platformReversal = null;
            if ($originalPlatformLedger) {
                $platformReversal = PlatformCommissionLedger::query()->firstOrCreate(
                    [
                        'organizer_profile_id' => $organizer->id,
                        'order_id' => $order->id,
                        'status' => 'reversed',
                    ],
                    [
                        'gross_amount' => -1 * abs((float) $originalPlatformLedger->gross_amount),
                        'commission_amount' => -1 * abs((float) $originalPlatformLedger->commission_amount),
                        'currency' => $originalPlatformLedger->currency,
                        'commission_type' => $originalPlatformLedger->commission_type,
                        'commission_value' => $originalPlatformLedger->commission_value,
                        'meta' => ['reason' => $reason, 'source_ledger_id' => $originalPlatformLedger->id],
                        'posted_at' => now(),
                    ]
                );
            }

            return ['reversed' => (bool) ($organizerReversal || $platformReversal), 'organizer_ledger' => $organizerReversal, 'platform_ledger' => $platformReversal];
        });
    }
}
