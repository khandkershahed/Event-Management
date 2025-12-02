<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'order_id',
        'ticket_name',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tickets()
    {
        return $this->hasMany(OrderTicket::class, 'order_item_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
