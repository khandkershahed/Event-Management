<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeatingPlan extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_LOCKED = 'locked';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'organizer_profile_id',
        'venue_id',
        'name',
        'status',
        'design_json',
    ];

    protected $casts = [
        'design_json' => 'array',
    ];

    public function organizerProfile()
    {
        return $this->belongsTo(OrganizerProfile::class);
    }

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

    public function isLocked(): bool
    {
        return $this->status === self::STATUS_LOCKED;
    }

    public function canBeEdited(): bool
    {
        return ! $this->isLocked();
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_ACTIVE,
            self::STATUS_LOCKED,
            self::STATUS_ARCHIVED,
        ];
    }
}
