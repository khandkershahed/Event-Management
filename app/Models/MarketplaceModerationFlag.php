<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceModerationFlag extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_DISMISSED = 'dismissed';

    protected $guarded = [];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [self::STATUS_ACTIVE, self::STATUS_RESOLVED, self::STATUS_DISMISSED];
    }

    public function flaggable()
    {
        return $this->morphTo();
    }

    public function flaggedBy()
    {
        return $this->belongsTo(Admin::class, 'flagged_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }
}
