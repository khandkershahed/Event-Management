<?php

namespace App\Services;

use App\Models\OrganizerPayoutMethod;
use App\Models\OrganizerProfile;
use Illuminate\Support\Arr;
use RuntimeException;

class PayoutMethodService
{
    public function upsertForOrganizer(OrganizerProfile $organizer, array $data): OrganizerPayoutMethod
    {
        $methodType = $data['method_type'] ?? OrganizerPayoutMethod::METHOD_BANK;

        $payload = [
            'method_type' => $methodType,
            'status' => OrganizerPayoutMethod::STATUS_PENDING_REVIEW,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'requires_verification' => (bool) ($data['requires_verification'] ?? true),
            'currency' => $data['currency'] ?? 'BDT',
            'account_holder_name' => $data['account_holder_name'] ?? null,
            'bank_name' => $methodType === OrganizerPayoutMethod::METHOD_BANK ? ($data['bank_name'] ?? null) : null,
            'branch_name' => $methodType === OrganizerPayoutMethod::METHOD_BANK ? ($data['branch_name'] ?? null) : null,
            'account_number' => $methodType === OrganizerPayoutMethod::METHOD_BANK ? ($data['account_number'] ?? null) : null,
            'routing_number' => $methodType === OrganizerPayoutMethod::METHOD_BANK ? ($data['routing_number'] ?? null) : null,
            'mobile_wallet_provider' => $methodType === OrganizerPayoutMethod::METHOD_MOBILE_WALLET ? ($data['mobile_wallet_provider'] ?? null) : null,
            'mobile_wallet_number' => $methodType === OrganizerPayoutMethod::METHOD_MOBILE_WALLET ? ($data['mobile_wallet_number'] ?? null) : null,
            'organizer_note' => $data['organizer_note'] ?? null,
            'admin_note' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'submitted_at' => now(),
        ];

        $method = OrganizerPayoutMethod::query()->updateOrCreate(
            ['organizer_profile_id' => $organizer->id],
            $payload
        );

        if (! $method->isComplete()) {
            $method->forceFill(['status' => OrganizerPayoutMethod::STATUS_DRAFT])->save();
        }

        return $method->fresh();
    }

    public function verify(OrganizerPayoutMethod $method, int $adminId, ?string $note = null): OrganizerPayoutMethod
    {
        if (! $method->is_active) {
            throw new RuntimeException('Inactive payout method cannot be verified.');
        }

        if (! $method->isComplete()) {
            throw new RuntimeException('Incomplete payout method cannot be verified.');
        }

        $method->forceFill([
            'status' => OrganizerPayoutMethod::STATUS_VERIFIED,
            'admin_note' => $note,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ])->save();

        return $method->fresh();
    }

    public function reject(OrganizerPayoutMethod $method, int $adminId, ?string $note = null): OrganizerPayoutMethod
    {
        $method->forceFill([
            'status' => OrganizerPayoutMethod::STATUS_REJECTED,
            'admin_note' => $note,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ])->save();

        return $method->fresh();
    }

    public function assertCanRequestPayout(OrganizerProfile $organizer): void
    {
        $method = $organizer->payoutMethod;

        if (! $method) {
            throw new RuntimeException('Complete and verify your payout method before requesting a payout.');
        }

        if (! $method->is_active) {
            throw new RuntimeException('Your payout method is inactive. Activate and verify payout details before requesting a payout.');
        }

        if (! $method->isComplete()) {
            throw new RuntimeException('Your payout method is incomplete. Complete payout details before requesting a payout.');
        }

        if ($method->status === OrganizerPayoutMethod::STATUS_REJECTED) {
            throw new RuntimeException('Your payout method was rejected. Update it and wait for admin verification before requesting a payout.');
        }

        if ($method->requires_verification && $method->status !== OrganizerPayoutMethod::STATUS_VERIFIED) {
            throw new RuntimeException('Your payout method must be verified by admin before requesting a payout.');
        }
    }
}
