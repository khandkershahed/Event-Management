<?php

namespace App\Http\Requests;

use App\Models\EventTicket;
use App\Models\SeatingSection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class EventTicketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('web')->check() && auth('web')->user()->organizerProfile?->isApproved();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'price' => $this->input('ticket_type') === EventTicket::TYPE_FREE ? 0 : $this->input('price', 0),
            'organizer_absorbs_fee' => $this->boolean('organizer_absorbs_fee'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ticket_type' => ['required', Rule::in(EventTicket::ticketTypes())],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'quantity' => ['required', 'integer', 'min:1'],
            'min_per_order' => ['required', 'integer', 'min:1'],
            'max_per_order' => ['nullable', 'integer', 'gte:min_per_order', 'lte:quantity'],
            'sales_start_at' => ['nullable', 'date'],
            'sales_end_at' => ['nullable', 'date', 'after:sales_start_at'],
            'visibility' => ['required', Rule::in(EventTicket::visibilities())],
            'status' => ['required', Rule::in(EventTicket::statuses())],
            'platform_fee_type' => ['required', Rule::in(EventTicket::platformFeeTypes())],
            'platform_fee_value' => ['required', 'numeric', 'min:0'],
            'organizer_absorbs_fee' => ['boolean'],
            'valid_section_ids' => ['nullable', 'array'],
            'valid_section_ids.*' => ['integer', 'exists:seating_sections,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $event = $this->route('event');
            $sectionIds = collect($this->input('valid_section_ids', []))->filter()->map(fn ($id) => (int) $id)->values();

            if ($sectionIds->isEmpty()) {
                return;
            }

            if (! $event || ! $event->seating_plan_id) {
                $validator->errors()->add('valid_section_ids', 'Section restrictions can only be used for an event with a seating plan.');
                return;
            }

            $validCount = SeatingSection::query()
                ->where('seating_plan_id', $event->seating_plan_id)
                ->whereIn('id', $sectionIds)
                ->count();

            if ($validCount !== $sectionIds->unique()->count()) {
                $validator->errors()->add('valid_section_ids', 'All selected sections must belong to this event seating plan.');
            }
        });
    }
}
