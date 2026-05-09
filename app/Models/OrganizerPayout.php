<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerPayout extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PAID = 'paid';

    protected $fillable = [
        'organizer_profile_id',
        'payout_number',
        'amount',
        'currency',
        'status',
        'requested_by',
        'reviewed_by',
        'paid_by',
        'notes',
        'rejection_reason',
        'requested_at',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_PAID];
    }

    public static function generateNumber(): string
    {
        do {
            $number = 'PO-' . now()->format('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (static::query()->where('payout_number', $number)->exists());

        return $number;
    }

    public function organizerProfile()
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function paidBy()
    {
        return $this->belongsTo(Admin::class, 'paid_by');
    }

    public function ledgers()
    {
        return $this->hasMany(OrganizerLedger::class, 'organizer_payout_id');
    }
}
