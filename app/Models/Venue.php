<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    use HasFactory, SoftDeletes, HasSlug;
    protected $slugSourceColumn = 'name';
    
    protected $fillable = [
        'name',
        'slug',
        'address',
        'city',
        'country',
        'capacity',
        'organizer_id',
        'description',
        'image',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function seatingPlans()
    {
        return $this->hasMany(SeatingPlan::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
