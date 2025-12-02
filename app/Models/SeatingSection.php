<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatingSection extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seating_plan_id',
        'name',
        'type',
        'capacity',
        'x',
        'y',
        'rotation',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function seatingPlan()
    {
        return $this->belongsTo(SeatingPlan::class);
    }

    public function seats()
    {
        return $this->hasMany(SeatingSeat::class, 'section_id');
    }
}
