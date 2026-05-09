<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCancellationRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'event_id','organizer_profile_id','requested_by','reason','status','admin_note','reviewed_by','reviewed_at',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public static function statuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED, self::STATUS_CANCELLED];
    }

    public function event() { return $this->belongsTo(Event::class); }
    public function organizerProfile() { return $this->belongsTo(OrganizerProfile::class); }
    public function requester() { return $this->belongsTo(User::class, 'requested_by'); }
}
