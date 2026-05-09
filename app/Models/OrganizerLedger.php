<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerLedger extends Model
{
    use HasFactory;

    public const TYPE_ORDER_EARNING = 'order_earning';
    public const TYPE_PAYOUT_PAID = 'payout_paid';

    public const DIRECTION_CREDIT = 'credit';
    public const DIRECTION_DEBIT = 'debit';

    public const STATUS_POSTED = 'posted';
    public const STATUS_VOID = 'void';

    protected $fillable = [
        'organizer_profile_id',
        'order_id',
        'organizer_payout_id',
        'type',
        'direction',
        'amount',
        'currency',
        'status',
        'description',
        'meta',
        'posted_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
        'posted_at' => 'datetime',
    ];

    public function organizerProfile()
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payout()
    {
        return $this->belongsTo(OrganizerPayout::class, 'organizer_payout_id');
    }
}
