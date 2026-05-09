<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTicket extends Model
{
    use HasFactory;

    public const STATUS_ISSUED = 'issued';
    public const STATUS_USED = 'used';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    protected $guarded = [];

    protected $casts = [
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    // orderItem
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function eventTicket()
    {
        return $this->belongsTo(EventTicket::class, 'ticket_type_id');
    }
    public function ticketType()
    {
        return $this->belongsTo(EventTicket::class, 'ticket_type_id');
    }
    public function seat()
    {
        return $this->belongsTo(SeatingSeat::class, 'seat_id');
    }
    public function checkIns()
    {
        return $this->hasMany(TicketCheckIn::class);
    }

    public function isCheckInBlocked(): bool
    {
        return in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_REFUNDED, self::STATUS_USED], true);
    }
}
