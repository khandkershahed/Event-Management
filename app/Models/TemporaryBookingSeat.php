<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryBookingSeat extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    public function temporaryBooking()
    {
        return $this->belongsTo(TemporaryBooking::class, 'temporary_booking_id');
    }

    public function seat()
    {
        return $this->belongsTo(EventSeat::class);
    }
}
