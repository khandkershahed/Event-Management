<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_id','user_id','organizer_profile_id','event_id','requested_by_type','requested_by_id',
        'reason','amount','currency','status','admin_note','reviewed_by','reviewed_at',
    ];

    protected $casts = ['amount' => 'decimal:2', 'reviewed_at' => 'datetime'];

    public static function statuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_CANCELLED];
    }

    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function event() { return $this->belongsTo(Event::class); }
    public function organizerProfile() { return $this->belongsTo(OrganizerProfile::class); }
    public function transactions() { return $this->hasMany(RefundTransaction::class); }
}
