<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $casts = [
        'valid_section_ids' => 'array'
    ];

    // ----------------------------
    // FEE CALCULATIONS
    // ----------------------------
    public function totalFees()
    {
        $base = $this->price;

        $platform  = $this->platform_fee_fixed + ($base * $this->platform_fee_percent / 100);
        $processing = $this->processing_fee_fixed + ($base * $this->processing_fee_percent / 100);
        $gateway    = $this->payment_gateway_fee_fixed + ($base * $this->payment_gateway_fee_percent / 100);

        return round($platform + $processing + $gateway, 2);
    }

    public function customerFee()
    {
        return round($this->totalFees() * ($this->fee_customer_percent / 100), 2);
    }

    public function organizerFee()
    {
        return round($this->totalFees() * ($this->fee_organizer_percent / 100), 2);
    }

    public function customerFinalPrice()
    {
        return round($this->price + $this->customerFee(), 2);
    }

    public function organizerPayout()
    {
        return round($this->price - $this->organizerFee(), 2);
    }
}
