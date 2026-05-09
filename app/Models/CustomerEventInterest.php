<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEventInterest extends Model
{
    use HasFactory;

    public const TYPE_EVENT_TYPE = 'event_type';
    public const TYPE_ORGANIZER = 'organizer';
    public const TYPE_CITY = 'city';

    protected $fillable = [
        'user_id',
        'interest_type',
        'interest_value',
        'weight',
        'last_recorded_at',
    ];

    protected $casts = [
        'last_recorded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
