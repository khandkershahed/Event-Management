<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatLock extends Model
{
    use HasFactory, HasSlug;


    protected $fillable = [
        'event_id',
        'seat_id',
        'user_id',
        'session_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
