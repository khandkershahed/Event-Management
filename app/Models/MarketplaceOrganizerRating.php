<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceOrganizerRating extends Model
{
    use HasFactory;

    protected $fillable = ['organizer_profile_id','approved_reviews_count','average_rating','last_reviewed_at'];
    protected $casts = ['average_rating' => 'decimal:2', 'last_reviewed_at' => 'datetime'];
    public function organizerProfile() { return $this->belongsTo(OrganizerProfile::class); }
}
