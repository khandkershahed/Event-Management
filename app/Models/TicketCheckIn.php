<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCheckIn extends Model
{
    use HasFactory;

    public const RESULT_VALID = 'valid';
    public const RESULT_ALREADY_CHECKED_IN = 'already_checked_in';
    public const RESULT_CANCELLED = 'cancelled';
    public const RESULT_REFUNDED = 'refunded';
    public const RESULT_WRONG_EVENT = 'wrong_event';
    public const RESULT_NOT_FOUND = 'not_found';

    protected $fillable = ['order_ticket_id','event_id','checked_in_by','checked_in_at','result','scanned_code','notes','ip_address','session_id','user_agent'];

    protected $casts = ['checked_in_at' => 'datetime'];

    public static function results(): array
    {
        return [self::RESULT_VALID,self::RESULT_ALREADY_CHECKED_IN,self::RESULT_CANCELLED,self::RESULT_REFUNDED,self::RESULT_WRONG_EVENT,self::RESULT_NOT_FOUND];
    }

    public function orderTicket(){return $this->belongsTo(OrderTicket::class);}
    public function event(){return $this->belongsTo(Event::class);}
    public function checkedInBy(){return $this->belongsTo(User::class, 'checked_in_by');}
}
