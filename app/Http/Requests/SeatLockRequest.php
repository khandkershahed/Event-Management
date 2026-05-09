<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeatLockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_ticket_id' => ['required', 'integer', 'exists:event_tickets,id'],
            'seat_id' => ['required', 'integer', 'exists:seating_seats,id'],
        ];
    }
}
