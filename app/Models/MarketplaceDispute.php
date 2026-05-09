<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceDispute extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function ticket()
    {
        return $this->belongsTo(MarketplaceSupportTicket::class, 'marketplace_support_ticket_id');
    }

    public function user() { return $this->belongsTo(User::class); }
    public function organizerProfile() { return $this->belongsTo(OrganizerProfile::class); }
    public function event() { return $this->belongsTo(Event::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function refundRequest() { return $this->belongsTo(RefundRequest::class); }
}
