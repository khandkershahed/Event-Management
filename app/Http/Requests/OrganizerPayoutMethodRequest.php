<?php

namespace App\Http\Requests;

use App\Models\OrganizerPayoutMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizerPayoutMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'method_type' => ['required', Rule::in(OrganizerPayoutMethod::methodTypes())],
            'currency' => ['required', 'string', 'max:10'],
            'account_holder_name' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'required_if:method_type,bank', 'string', 'max:255'],
            'branch_name' => ['nullable', 'required_if:method_type,bank', 'string', 'max:255'],
            'account_number' => ['nullable', 'required_if:method_type,bank', 'string', 'max:100'],
            'routing_number' => ['nullable', 'string', 'max:100'],
            'mobile_wallet_provider' => ['nullable', 'required_if:method_type,mobile_wallet', 'string', 'max:100'],
            'mobile_wallet_number' => ['nullable', 'required_if:method_type,mobile_wallet', 'string', 'max:100'],
            'organizer_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
