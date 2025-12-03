<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'design_json',
    ];

    protected $casts = [
        'design_json' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // public function venue()
    // {
    //     return $this->belongsTo(Venue::class);
    // }
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function sections()
    {
        return $this->hasMany(SeatingSection::class, 'seating_plan_id');
    }
    public function events()
    {
        return $this->hasMany(Event::class, 'seating_plan_id');
    }
    public function seats()
    {
        return $this->hasManyThrough(
            SeatingSeat::class,
            SeatingSection::class,
            'seating_plan_id',
            'section_id'
        );
    }
}
