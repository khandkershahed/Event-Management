<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTicket extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'seat_id',
        'ticket_code',
        'attendee_name',
        'is_checked_in',
        'checked_in_at',
    ];

    protected $casts = [
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function seat()
    {
        return $this->belongsTo(SeatingSeat::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
