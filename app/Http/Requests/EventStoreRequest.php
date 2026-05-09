<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('web')->check() && auth('web')->user()->organizerProfile?->isApproved();
    }

    public function rules(): array
    {
        $profile = auth('web')->user()?->organizerProfile;

        return [
            'event_type_id' => ['nullable', 'exists:event_types,id'],
            'venue_id' => ['required', Rule::exists('venues', 'id')->where(fn ($query) => $query->where('organizer_profile_id', $profile?->id))],
            'seating_plan_id' => ['nullable', Rule::exists('seating_plans', 'id')->where(fn ($query) => $query->where('organizer_profile_id', $profile?->id))],
            'name' => ['required', 'string', 'max:255', 'unique:events,name'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'purchase_deadline' => ['nullable', 'date'],
            'total_capacity' => ['nullable', 'integer', 'min:0'],
            'age_restriction' => ['nullable', 'string', 'max:50'],
            'terms_and_conditions' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'venue_id.exists' => 'Please select one of your own venues.',
            'seating_plan_id.exists' => 'Please select one of your own seating plans.',
        ];
    }
}
