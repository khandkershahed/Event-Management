<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'session_id',
        'ticket_type_id',
        'seat_id',
        'quantity',
        'unit_price',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function ticketType()
    {
        return $this->belongsTo(EventTicket::class, 'ticket_type_id');
    }

    public function seat()
    {
        return $this->belongsTo(SeatingSeat::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
