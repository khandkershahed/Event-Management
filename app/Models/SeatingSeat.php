<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatingSeat extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_id',
        'label',
        'row_label',
        'seat_number',
        'x',
        'y',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function section()
    {
        return $this->belongsTo(SeatingSection::class);
    }

    public function locks()
    {
        return $this->hasMany(SeatLock::class, 'seat_id');
    }

    public function orderTickets()
    {
        return $this->hasMany(OrderTicket::class, 'seat_id');
    }
}
