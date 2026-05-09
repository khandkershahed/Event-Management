<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceSupportMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(MarketplaceSupportTicket::class, 'marketplace_support_ticket_id');
    }

    public function sender()
    {
        return $this->morphTo();
    }
}
