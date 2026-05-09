<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartAddRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_ticket_id' => ['required', 'integer', 'exists:event_tickets,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
