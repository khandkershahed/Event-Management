<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceSupportTicket extends Model
{
    use HasFactory;

    public const TYPE_GENERAL = 'general';
    public const TYPE_ORDER = 'order';
    public const TYPE_EVENT = 'event';
    public const TYPE_REFUND = 'refund';
    public const TYPE_PAYOUT = 'payout';
    public const TYPE_APPROVAL = 'approval';
    public const TYPE_DISPUTE = 'dispute';

    public const STATUS_OPEN = 'open';
    public const STATUS_PENDING = 'pending';
    public const STATUS_WAITING_CUSTOMER = 'waiting_customer';
    public const STATUS_WAITING_ORGANIZER = 'waiting_organizer';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    protected $guarded = [];

    protected $casts = [
        'last_replied_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (MarketplaceSupportTicket $ticket): void {
            if (blank($ticket->ticket_number)) {
                $ticket->ticket_number = static::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        do {
            $number = 'ST-' . now()->format('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (static::query()->where('ticket_number', $number)->exists());

        return $number;
    }

    public static function types(): array
    {
        return [self::TYPE_GENERAL, self::TYPE_ORDER, self::TYPE_EVENT, self::TYPE_REFUND, self::TYPE_PAYOUT, self::TYPE_APPROVAL, self::TYPE_DISPUTE];
    }

    public static function statuses(): array
    {
        return [self::STATUS_OPEN, self::STATUS_PENDING, self::STATUS_WAITING_CUSTOMER, self::STATUS_WAITING_ORGANIZER, self::STATUS_RESOLVED, self::STATUS_CLOSED];
    }

    public static function priorities(): array
    {
        return [self::PRIORITY_LOW, self::PRIORITY_NORMAL, self::PRIORITY_HIGH, self::PRIORITY_URGENT];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function organizerProfile() { return $this->belongsTo(OrganizerProfile::class); }
    public function event() { return $this->belongsTo(Event::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function refundRequest() { return $this->belongsTo(RefundRequest::class); }
    public function organizerPayout() { return $this->belongsTo(OrganizerPayout::class); }
    public function assignedAdmin() { return $this->belongsTo(Admin::class, 'assigned_admin_id'); }
    public function createdBy() { return $this->morphTo(); }
    public function messages() { return $this->hasMany(MarketplaceSupportMessage::class)->oldest('id'); }
    public function latestMessage() { return $this->hasOne(MarketplaceSupportMessage::class)->latestOfMany(); }

    public function isOpenForReplies(): bool
    {
        return ! in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_CLOSED], true);
    }
}
