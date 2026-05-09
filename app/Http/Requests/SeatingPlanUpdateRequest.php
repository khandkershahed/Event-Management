<?php

namespace App\Http\Requests;

use App\Models\SeatingPlan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeatingPlanUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $profile = auth('web')->user()?->organizerProfile;
        $plan = $this->route('seating_plan');

        return $profile
            && $profile->isApproved()
            && $plan instanceof SeatingPlan
            && (int) $plan->organizer_profile_id === (int) $profile->id
            && $plan->canBeEdited();
    }

    public function rules(): array
    {
        $profile = auth('web')->user()?->organizerProfile;

        return [
            'venue_id' => [
                'required',
                Rule::exists('venues', 'id')->where(fn ($query) => $query->where('organizer_profile_id', $profile?->id)),
            ],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'string', Rule::in([SeatingPlan::STATUS_DRAFT, SeatingPlan::STATUS_ACTIVE, SeatingPlan::STATUS_ARCHIVED])],
        ];
    }

    public function messages(): array
    {
        return ['venue_id.exists' => 'Please select one of your own venues.'];
    }
}
