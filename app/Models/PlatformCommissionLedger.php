<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformCommissionLedger extends Model
{
    use HasFactory;

    public const STATUS_POSTED = 'posted';
    public const STATUS_VOID = 'void';

    protected $fillable = [
        'organizer_profile_id',
        'order_id',
        'gross_amount',
        'commission_amount',
        'currency',
        'commission_type',
        'commission_value',
        'status',
        'meta',
        'posted_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_value' => 'decimal:2',
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
}
