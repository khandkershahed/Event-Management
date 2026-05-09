<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $slugSourceColumn = 'name';

    protected $fillable = [
        'organizer_profile_id',
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

    public function organizerProfile()
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

    public function seatingPlans()
    {
        return $this->hasMany(SeatingPlan::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
