@php
    $selectedSections = old('valid_section_ids', method_exists($ticket, 'validSectionIdsArray') ? $ticket->validSectionIdsArray() : []);
@endphp

@if ($errors->any())
    <div class="card" style="border-left:4px solid #dc2626">
        <strong>Please fix the following errors:</strong>
        <ul style="margin-bottom:0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid">
    <div class="mb-3">
        <label>Name</label>
        <input class="form-control" type="text" name="name" value="{{ old('name', $ticket->name) }}" required>
    </div>

    <div class="mb-3">
        <label>Ticket Type</label>
        <select class="form-control" name="ticket_type" required>
            @foreach (\App\Models\EventTicket::ticketTypes() as $type)
                <option value="{{ $type }}" @selected(old('ticket_type', $ticket->ticket_type) === $type)>{{ ucwords(str_replace('_', ' ', $type)) }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input class="form-control" type="number" step="0.01" min="0" name="price" value="{{ old('price', $ticket->price ?? 0) }}" required>
    </div>

    <div class="mb-3">
        <label>Currency</label>
        <input class="form-control" type="text" maxlength="10" name="currency" value="{{ old('currency', $ticket->currency ?? 'BDT') }}" required>
    </div>

    <div class="mb-3">
        <label>Quantity</label>
        <input class="form-control" type="number" min="1" name="quantity" value="{{ old('quantity', $ticket->quantity ?? 100) }}" required>
    </div>

    <div class="mb-3">
        <label>Minimum Per Order</label>
        <input class="form-control" type="number" min="1" name="min_per_order" value="{{ old('min_per_order', $ticket->min_per_order ?? 1) }}" required>
    </div>

    <div class="mb-3">
        <label>Maximum Per Order</label>
        <input class="form-control" type="number" min="1" name="max_per_order" value="{{ old('max_per_order', $ticket->max_per_order) }}">
    </div>

    <div class="mb-3">
        <label>Visibility</label>
        <select class="form-control" name="visibility" required>
            @foreach (\App\Models\EventTicket::visibilities() as $visibility)
                <option value="{{ $visibility }}" @selected(old('visibility', $ticket->visibility ?? 'public') === $visibility)>{{ ucfirst($visibility) }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select class="form-control" name="status" required>
            @foreach (\App\Models\EventTicket::statuses() as $status)
                <option value="{{ $status }}" @selected(old('status', $ticket->status ?? 'active') === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Sales Start At</label>
        <input class="form-control" type="datetime-local" name="sales_start_at" value="{{ old('sales_start_at', optional($ticket->sales_start_at)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="mb-3">
        <label>Sales End At</label>
        <input class="form-control" type="datetime-local" name="sales_end_at" value="{{ old('sales_end_at', optional($ticket->sales_end_at)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="mb-3">
        <label>Platform Fee Type</label>
        <select class="form-control" name="platform_fee_type" required>
            @foreach (\App\Models\EventTicket::platformFeeTypes() as $feeType)
                <option value="{{ $feeType }}" @selected(old('platform_fee_type', $ticket->platform_fee_type ?? 'none') === $feeType)>{{ ucfirst($feeType) }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Platform Fee Value</label>
        <input class="form-control" type="number" step="0.01" min="0" name="platform_fee_value" value="{{ old('platform_fee_value', $ticket->platform_fee_value ?? 0) }}" required>
    </div>
</div>

<div class="mb-3">
    <label>Description</label>
    <textarea class="form-control" name="description" rows="4">{{ old('description', $ticket->description) }}</textarea>
</div>

@if($sections->count())
    <div class="mb-3">
        <label>Ticket / Section Assignment Matrix</label>
        <div class="card" style="box-shadow:none;border:1px solid #e5e7eb">
            <label style="display:flex;gap:8px;align-items:flex-start;margin-bottom:12px">
                <input type="checkbox" id="all_sections_toggle" @checked(count($selectedSections) === 0)>
                <span>
                    <strong>Valid for all sections / no restriction</strong><br>
                    <small style="color:#6b7280">Use this when the ticket should work in every section of this event seating plan.</small>
                </span>
            </label>

            <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:10px">
                @foreach($sections as $section)
                    <label style="display:flex;gap:8px;align-items:flex-start;margin:0;padding:10px;border:1px solid #e5e7eb;border-radius:8px;background:#fff">
                        <input class="section-checkbox" type="checkbox" name="valid_section_ids[]" value="{{ $section->id }}" @checked(in_array($section->id, $selectedSections)) @disabled(count($selectedSections) === 0)>
                        <span>
                            <strong>{{ $section->name }}</strong><br>
                            <small style="color:#6b7280">Type: {{ ucwords(str_replace('_', ' ', $section->type ?? 'section')) }} · Capacity: {{ $section->capacity ?? $section->seats_count ?? $section->seats()->count() }}</small>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
@endif

<div class="mb-3">
    <label style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="organizer_absorbs_fee" value="1" @checked(old('organizer_absorbs_fee', $ticket->organizer_absorbs_fee))>
        Organizer absorbs platform fee
    </label>
</div>

<div style="display:flex;gap:10px">
    <button class="btn btn-primary" type="submit">Save Ticket Type</button>
    <a class="btn btn-light" href="{{ route('organizer.events.ticket-types.index', $event) }}">Cancel</a>
</div>

@if($sections->count())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const allToggle = document.getElementById('all_sections_toggle');
                const sectionCheckboxes = document.querySelectorAll('.section-checkbox');

                if (! allToggle) {
                    return;
                }

                allToggle.addEventListener('change', function () {
                    sectionCheckboxes.forEach(function (checkbox) {
                        checkbox.disabled = allToggle.checked;
                        if (allToggle.checked) {
                            checkbox.checked = false;
                        }
                    });
                });
            });
        </script>
    @endpush
@endif
