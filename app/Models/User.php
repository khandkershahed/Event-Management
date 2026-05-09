<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organizerProfile()
    {
        return $this->hasOne(OrganizerProfile::class);
    }

    public function organizerTeamMemberships()
    {
        return $this->hasMany(OrganizerTeamMember::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(MarketplaceSupportTicket::class);
    }

    public function marketplaceEventReviews()
    {
        return $this->hasMany(MarketplaceEventReview::class);
    }

    public function organizerFollows()
    {
        return $this->hasMany(OrganizerFollower::class);
    }

    public function followedOrganizers()
    {
        return $this->belongsToMany(OrganizerProfile::class, 'organizer_followers')->withTimestamps();
    }

    public function savedEvents()
    {
        return $this->hasMany(CustomerSavedEvent::class);
    }

    public function savedEventRecords()
    {
        return $this->belongsToMany(Event::class, 'customer_saved_events')->withTimestamps();
    }

    public function eventInterests()
    {
        return $this->hasMany(CustomerEventInterest::class);
    }
}

