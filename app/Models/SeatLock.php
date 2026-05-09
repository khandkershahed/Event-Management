<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'seat_id',
        'ticket_type_id',
        'user_id',
        'session_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function seat()
    {
        return $this->belongsTo(SeatingSeat::class, 'seat_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(EventTicket::class, 'ticket_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function isOwnedBy(?int $userId, string $sessionId): bool
    {
        if ($userId && (int) $this->user_id === $userId) {
            return true;
        }

        return (string) $this->session_id === $sessionId;
    }
}
