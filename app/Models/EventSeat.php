<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeat extends Model
{
    use HasFactory, HasSlug;
    protected $slugSourceColumn = 'name';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    // eventType
    public function eventType()
    {
        return $this->belongsTo(EventSeatType::class);
    }
    // event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    // eventSeatType
    public function eventSeatType()
    {
        return $this->belongsTo(EventSeatType::class, 'seat_type_id');
    }
}
