<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenueStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('web')->check() && auth('web')->user()->organizerProfile?->isApproved();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
