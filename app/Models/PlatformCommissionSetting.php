<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformCommissionSetting extends Model
{
    use HasFactory;

    public const TYPE_NONE = 'none';
    public const TYPE_FIXED = 'fixed';
    public const TYPE_PERCENT = 'percent';

    protected $fillable = [
        'name',
        'commission_type',
        'commission_value',
        'is_active',
        'description',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public static function types(): array
    {
        return [self::TYPE_NONE, self::TYPE_FIXED, self::TYPE_PERCENT];
    }

    public static function activeSetting(): self
    {
        return static::query()->where('is_active', true)->latest('id')->first()
            ?: static::query()->create([
                'name' => 'Default Commission',
                'commission_type' => self::TYPE_PERCENT,
                'commission_value' => 5,
                'is_active' => true,
                'description' => 'Default marketplace commission used for organizer earnings.',
            ]);
    }
}
