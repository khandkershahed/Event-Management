<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, HasSlug;
    protected $slugSourceColumn = 'title';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $casts = [
        'is_featured' => 'boolean',
        'views' => 'integer',
        'price' => 'decimal:2',
        'available_from' => 'date',
        'available_until' => 'date',
    ];
}
