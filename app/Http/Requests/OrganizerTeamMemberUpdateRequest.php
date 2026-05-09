<?php

namespace App\Http\Requests;

use App\Models\OrganizerTeamMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizerTeamMemberUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => ['required', Rule::in(OrganizerTeamMember::staffRoles())],
            'status' => ['required', Rule::in([OrganizerTeamMember::STATUS_ACTIVE, OrganizerTeamMember::STATUS_INACTIVE])],
        ];
    }
}
