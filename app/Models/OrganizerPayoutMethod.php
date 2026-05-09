<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerPayoutMethod extends Model
{
    use HasFactory;

    public const METHOD_BANK = 'bank';
    public const METHOD_MOBILE_WALLET = 'mobile_wallet';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'organizer_profile_id',
        'method_type',
        'status',
        'is_active',
        'requires_verification',
        'currency',
        'account_holder_name',
        'bank_name',
        'branch_name',
        'account_number',
        'routing_number',
        'mobile_wallet_provider',
        'mobile_wallet_number',
        'organizer_note',
        'admin_note',
        'reviewed_by',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_verification' => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public static function methodTypes(): array
    {
        return [self::METHOD_BANK, self::METHOD_MOBILE_WALLET];
    }

    public static function statuses(): array
    {
        return [self::STATUS_DRAFT, self::STATUS_PENDING_REVIEW, self::STATUS_VERIFIED, self::STATUS_REJECTED];
    }

    public function organizerProfile()
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function isBank(): bool
    {
        return $this->method_type === self::METHOD_BANK;
    }

    public function isMobileWallet(): bool
    {
        return $this->method_type === self::METHOD_MOBILE_WALLET;
    }

    public function isComplete(): bool
    {
        if ($this->isBank()) {
            return filled($this->account_holder_name)
                && filled($this->bank_name)
                && filled($this->branch_name)
                && filled($this->account_number);
        }

        if ($this->isMobileWallet()) {
            return filled($this->account_holder_name)
                && filled($this->mobile_wallet_provider)
                && filled($this->mobile_wallet_number);
        }

        return false;
    }

    public function isUsableForPayout(): bool
    {
        if (! $this->is_active || ! $this->isComplete()) {
            return false;
        }

        if ($this->status === self::STATUS_REJECTED) {
            return false;
        }

        if ($this->requires_verification && $this->status !== self::STATUS_VERIFIED) {
            return false;
        }

        if (! $this->requires_verification) {
            return in_array($this->status, [self::STATUS_VERIFIED, self::STATUS_PENDING_REVIEW], true);
        }

        return $this->status === self::STATUS_VERIFIED;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING_REVIEW => 'Pending Review',
            self::STATUS_VERIFIED => 'Verified',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function methodLabel(): string
    {
        return match ($this->method_type) {
            self::METHOD_BANK => 'Bank Account',
            self::METHOD_MOBILE_WALLET => 'Mobile Wallet',
            default => ucfirst(str_replace('_', ' ', $this->method_type)),
        };
    }
}
