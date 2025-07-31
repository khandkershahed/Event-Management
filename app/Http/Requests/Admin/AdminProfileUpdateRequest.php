<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name'         => ['nullable', 'string', 'max:255'],
            'username'     => ['nullable', 'string', 'max:255',
                Rule::unique('admins')->ignore($this->user()->id)
            ],
            'email'        => ['nullable', 'email', 'max:255',
                Rule::unique('admins')->ignore($this->user()->id)
            ],
            'designation'  => ['nullable', 'string', 'max:200'],
            'phone'        => ['nullable', 'string', 'max:20',
                Rule::unique('admins')->ignore($this->user()->id)
            ],
            'photo'        => ['nullable', 'image', 'max:2048'], // 2MB max
            'country'      => ['nullable', 'string', 'max:200'],
            'city'         => ['nullable', 'string', 'max:200'],
            'zipcode'      => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'youtube'      => ['nullable', 'url', 'max:200'],
            'facebook'     => ['nullable', 'url', 'max:200'],
            'twitter'      => ['nullable', 'url', 'max:200'],
            'linkedin'     => ['nullable', 'url', 'max:200'],
            'website'      => ['nullable', 'url', 'max:200'],
            'biometric_id' => ['nullable', 'string', 'max:100'],
            'mail_status'  => ['nullable', 'in:enabled,disabled'],
            'status'       => ['nullable', 'in:active,inactive'],
        ];
    }
}
