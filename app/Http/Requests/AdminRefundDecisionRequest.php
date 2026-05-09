<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRefundDecisionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['admin_note' => ['nullable', 'string', 'max:2000']]; }
}
