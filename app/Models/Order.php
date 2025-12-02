<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasSlug;


    protected $fillable = [
        'user_id',
        'event_id',
        'order_number',
        'total',
        'status',
        'payment_status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets()
    {
        return $this->hasMany(OrderTicket::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
