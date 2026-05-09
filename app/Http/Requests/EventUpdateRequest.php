<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $event = $this->route('event');
        $profile = auth('web')->user()?->organizerProfile;

        return auth('web')->check()
            && $profile?->isApproved()
            && $event
            && (int) $event->organizer_profile_id === (int) $profile->id
            && $event->canBeEditedByOrganizer();
    }

    public function rules(): array
    {
        $event = $this->route('event');
        $profile = auth('web')->user()?->organizerProfile;

        return [
            'event_type_id' => ['nullable', 'exists:event_types,id'],
            'venue_id' => ['required', Rule::exists('venues', 'id')->where(fn ($query) => $query->where('organizer_profile_id', $profile?->id))],
            'seating_plan_id' => ['nullable', Rule::exists('seating_plans', 'id')->where(fn ($query) => $query->where('organizer_profile_id', $profile?->id))],
            'name' => ['required', 'string', 'max:255', Rule::unique('events', 'name')->ignore($event?->id)],
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
