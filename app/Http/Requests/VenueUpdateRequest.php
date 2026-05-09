<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenueUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $profile = auth('web')->user()?->organizerProfile;
        $venue = $this->route('venue');

        return $profile && $profile->isApproved() && $venue && (int) $venue->organizer_profile_id === (int) $profile->id;
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
            'image' => ['nullable', 'string', 'max:255'],
        ];
    }
}
