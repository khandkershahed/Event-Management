<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SeatingSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'seating_section_id',
        'label',
        'row_label',
        'seat_number',
        'x',
        'y',
        'status',
        'is_disabled',
        'is_researved',
    ];

    protected static function booted(): void
    {
        static::creating(function (SeatingSeat $seat): void {
            $seat->normalizeSeatFields();
        });

        static::updating(function (SeatingSeat $seat): void {
            $seat->normalizeSeatFields();
        });
    }

    protected function normalizeSeatFields(): void
    {
        if ($this->x === null) {
            $this->x = 0;
        }

        if ($this->y === null) {
            $this->y = 0;
        }

        $hasAliasColumn = Schema::hasColumn('seating_seats', 'seating_section_id');

        if (! $this->section_id && $hasAliasColumn && $this->seating_section_id) {
            $this->section_id = $this->seating_section_id;
        }

        if ($hasAliasColumn && ! $this->seating_section_id && $this->section_id) {
            $this->seating_section_id = $this->section_id;
        }

        if (! $hasAliasColumn) {
            unset($this->attributes['seating_section_id']);
        }
    }

    public function section()
    {
        return $this->belongsTo(SeatingSection::class, 'section_id');
    }

    public function seatingSection()
    {
        return $this->belongsTo(SeatingSection::class, 'seating_section_id');
    }

    public function locks()
    {
        return $this->hasMany(SeatLock::class, 'seat_id');
    }

    public function orderTickets()
    {
        return $this->hasMany(OrderTicket::class, 'seat_id');
    }
}
