<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasSlug;
    protected $slugSourceColumn = 'name';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'start_date'         => 'date',
        'end_date'           => 'date',
        'start_time'         => 'datetime:H:i', // Handles TIME columns
        'end_time'           => 'datetime:H:i', // Handles TIME columns
        'purchase_deadline'  => 'datetime',
        'is_featured'        => 'boolean',
    ];

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }
    // images
    public function images()
    {
        return $this->hasMany(EventImage::class);
    }
    // eventSeats
    public function eventSeats()
    {
        return $this->hasMany(EventSeat::class);
    }
    // In app/Models/Event.php

    // Example for getting a price
    public function getDisplayPriceAttribute()
    {
        // Add your logic here. Maybe get the minimum ticket price?
        // This is just a placeholder.
        if ($this->price > 0) {
            return 'AUD $' . $this->price;
        }
        return 'Free';
    }

    // protected function duration(): Attribute
    // {
    //     return Attribute::make(
    //         get: function ($value) {
    //             if (!$this->start_time || !$this->end_time) {
    //                 return null;
    //             }
    //             $hours = $this->start_time->diffInHours($this->end_time);
    //             return $hours > 0 ? $hours . 'h' : null;
    //         }
    //     );
    // }
    // Example for getting duration (if you calculate it)
    public function getDurationAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time->diffInHours($this->end_time) . 'h';
        }
        return '1h'; // Default
    }
}
