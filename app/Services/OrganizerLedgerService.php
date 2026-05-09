<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrganizerLedger;
use App\Models\OrganizerPayout;
use App\Models\OrganizerProfile;
use App\Models\PlatformCommissionLedger;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrganizerLedgerService
{
    public function __construct(protected CommissionCalculationService $commissionCalculationService)
    {
    }

    public function postPaidOrder(Order $order): array
    {
        $order->loadMissing('event.organizerProfile');

        if ($order->payment_status !== Order::PAYMENT_PAID) {
            return ['posted' => false, 'reason' => 'Order is not paid.'];
        }

        $organizer = $order->event?->organizerProfile;
        if (! $organizer) {
            return ['posted' => false, 'reason' => 'Order event has no organizer.'];
        }

        return DB::transaction(function () use ($order, $organizer) {
            $existingOrganizerLedger = OrganizerLedger::query()->where('order_id', $order->id)->first();
            $existingPlatformLedger = PlatformCommissionLedger::query()->where('order_id', $order->id)->first();

            if ($existingOrganizerLedger && $existingPlatformLedger) {
                return [
                    'posted' => false,
                    'reason' => 'Order has already been posted.',
                    'organizer_ledger' => $existingOrganizerLedger,
                    'platform_ledger' => $existingPlatformLedger,
                ];
            }

            $calculation = $this->commissionCalculationService->calculateForOrder($order);

            $organizerLedger = $existingOrganizerLedger ?: OrganizerLedger::create([
                'organizer_profile_id' => $organizer->id,
                'order_id' => $order->id,
                'type' => OrganizerLedger::TYPE_ORDER_EARNING,
                'direction' => OrganizerLedger::DIRECTION_CREDIT,
                'amount' => $calculation['organizer_net_amount'],
                'currency' => $calculation['currency'],
                'status' => OrganizerLedger::STATUS_POSTED,
                'description' => 'Organizer earning for order ' . $order->order_number,
                'meta' => $calculation,
                'posted_at' => now(),
            ]);

            $platformLedger = $existingPlatformLedger ?: PlatformCommissionLedger::create([
                'organizer_profile_id' => $organizer->id,
                'order_id' => $order->id,
                'gross_amount' => $calculation['gross_amount'],
                'commission_amount' => $calculation['commission_amount'],
                'currency' => $calculation['currency'],
                'commission_type' => $calculation['commission_type'],
                'commission_value' => $calculation['commission_value'],
                'status' => PlatformCommissionLedger::STATUS_POSTED,
                'meta' => $calculation,
                'posted_at' => now(),
            ]);

            return [
                'posted' => true,
                'organizer_ledger' => $organizerLedger,
                'platform_ledger' => $platformLedger,
                'calculation' => $calculation,
            ];
        });
    }

    public function availableBalance(OrganizerProfile $organizer): float
    {
        $credits = (float) OrganizerLedger::query()
            ->where('organizer_profile_id', $organizer->id)
            ->where('status', OrganizerLedger::STATUS_POSTED)
            ->where('direction', OrganizerLedger::DIRECTION_CREDIT)
            ->sum('amount');

        $debits = (float) OrganizerLedger::query()
            ->where('organizer_profile_id', $organizer->id)
            ->where('status', OrganizerLedger::STATUS_POSTED)
            ->where('direction', OrganizerLedger::DIRECTION_DEBIT)
            ->sum('amount');

        $reserved = (float) OrganizerPayout::query()
            ->where('organizer_profile_id', $organizer->id)
            ->whereIn('status', [OrganizerPayout::STATUS_PENDING, OrganizerPayout::STATUS_APPROVED])
            ->sum('amount');

        return round(max(0, $credits - $debits - $reserved), 2);
    }

    public function requestPayout(OrganizerProfile $organizer, float $amount, ?int $requestedBy = null, ?string $notes = null): OrganizerPayout
    {
        $amount = round($amount, 2);
        if ($amount <= 0) {
            throw new RuntimeException('Payout amount must be greater than zero.');
        }

        $available = $this->availableBalance($organizer);
        if ($amount > $available) {
            throw new RuntimeException('Payout amount cannot exceed available balance.');
        }

        return OrganizerPayout::create([
            'organizer_profile_id' => $organizer->id,
            'payout_number' => OrganizerPayout::generateNumber(),
            'amount' => $amount,
            'currency' => 'BDT',
            'status' => OrganizerPayout::STATUS_PENDING,
            'requested_by' => $requestedBy,
            'notes' => $notes,
            'requested_at' => now(),
        ]);
    }

    public function approvePayout(OrganizerPayout $payout, int $adminId): OrganizerPayout
    {
        if ($payout->status !== OrganizerPayout::STATUS_PENDING) {
            throw new RuntimeException('Only pending payouts can be approved.');
        }

        $payout->forceFill([
            'status' => OrganizerPayout::STATUS_APPROVED,
            'reviewed_by' => $adminId,
            'approved_at' => now(),
            'rejection_reason' => null,
        ])->save();

        return $payout->fresh();
    }

    public function rejectPayout(OrganizerPayout $payout, int $adminId, ?string $reason = null): OrganizerPayout
    {
        if (! in_array($payout->status, [OrganizerPayout::STATUS_PENDING, OrganizerPayout::STATUS_APPROVED], true)) {
            throw new RuntimeException('Only pending or approved payouts can be rejected.');
        }

        $payout->forceFill([
            'status' => OrganizerPayout::STATUS_REJECTED,
            'reviewed_by' => $adminId,
            'rejection_reason' => $reason,
        ])->save();

        return $payout->fresh();
    }

    public function markPayoutPaid(OrganizerPayout $payout, int $adminId): OrganizerPayout
    {
        if (! in_array($payout->status, [OrganizerPayout::STATUS_PENDING, OrganizerPayout::STATUS_APPROVED], true)) {
            throw new RuntimeException('Only pending or approved payouts can be marked as paid.');
        }

        return DB::transaction(function () use ($payout, $adminId) {
            $payout->forceFill([
                'status' => OrganizerPayout::STATUS_PAID,
                'paid_by' => $adminId,
                'paid_at' => now(),
                'approved_at' => $payout->approved_at ?: now(),
                'reviewed_by' => $payout->reviewed_by ?: $adminId,
            ])->save();

            OrganizerLedger::query()->firstOrCreate(
                [
                    'organizer_payout_id' => $payout->id,
                    'type' => OrganizerLedger::TYPE_PAYOUT_PAID,
                ],
                [
                    'organizer_profile_id' => $payout->organizer_profile_id,
                    'direction' => OrganizerLedger::DIRECTION_DEBIT,
                    'amount' => $payout->amount,
                    'currency' => $payout->currency,
                    'status' => OrganizerLedger::STATUS_POSTED,
                    'description' => 'Payout paid: ' . $payout->payout_number,
                    'posted_at' => now(),
                ]
            );

            return $payout->fresh();
        });
    }
}
