<x-admin-app-layout :title="'Create Event Seats'">
    <div class="card card-flash">
        <div class="mt-6 card-header">
            <div class="card-toolbar">
                <a href="{{ route('admin.event-seat.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>

        <div class="pt-0 card-body">
            <form method="POST" action="{{ route('admin.event-seat.store') }}">
                @csrf
                <div class="row">
                    {{-- Event --}}
                    <div class="col-lg-8 mb-7">
                        <x-metronic.label for="event_id" class="col-form-label fw-bold fs-6">
                            {{ __('Select Event') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="event_id" name="event_id" data-hide-search="true"
                            data-placeholder="Select an Event" required>
                            <option></option>
                            @foreach ($events as $event)
                            <option value="{{ $event->id }}"
                                {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    {{-- Seat Type --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="seat_type_id" class="col-form-label fw-bold fs-6">
                            {{ __('Seat Type') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="seat_type_id" name="seat_type_id" data-hide-search="true"
                            data-placeholder="Select a Seat Type" required>
                            <option></option>
                            @foreach ($seat_types as $seat_type)
                            <option value="{{ $seat_type->id }}"
                                {{ old('seat_type_id') == $seat_type->id ? 'selected' : '' }}>
                                {{ $seat_type->name }}
                            </option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>
                </div>

                {{-- Price --}}
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <x-metronic.label for="price" class="col-form-label fw-bold fs-6">
                            {{ __('Price') }}
                        </x-metronic.label>
                        <x-metronic.input id="price" name="price" type="number" step="0.01"
                            value="{{ old('price') }}" required />
                    </div>
                    <div class="col-lg-6">
                        <div class="mt-10 form-check form-check-custom form-check-solid">
                            <!--begin::Input-->
                            <input style="border: 1px solid black;" class="form-check-input me-3" name="checkbox_input" type="checkbox" value="1" id="kt_docs_formvalidation_checkbox_option_1" />
                            <!--end::Input-->

                            <!--begin::Label-->
                            <label class="border form-check-label" for="kt_docs_formvalidation_checkbox_option_1">
                                <div class="text-gray-800 fw-semibold">Bulk Seat Entry Or Repeater</div>
                            </label>
                            <!--end::Label-->
                        </div>
                    </div>
                </div>

                {{-- Bulk Seat Entry --}}
                <div class="row">
                    <div class="col-lg-12 mb-7 bulk-seats-section">
                        <x-metronic.label for="bulk_seats" class="col-form-label fw-bold fs-6">
                            {{ __('Bulk Seat Entry') }}
                        </x-metronic.label>
                        <textarea name="bulk_seats" id="bulk_seats" rows="8" class="form-control"
                            placeholder="Example: A1,A,1,VIP-A1 A2,A,2,VIP-A2 B1,B,1,VIP-B1">{{ old('bulk_seats') }}</textarea>
                        <small class="mt-2 text-muted d-block">
                            One seat per line in the format: <code>name,row,column,code</code><br>
                            <strong>Example:</strong><br>
                            <code>A1,A,1,VIP-A1</code><br>
                            <code>A2,A,2,VIP-A2</code><br>
                            <code>B1,B,1,VIP-B1</code>
                        </small>
                    </div>
                    <div class="col-lg-12 ">
                        {{-- repeater --}}
                        <div class="repeater-seats-section">
                            <div class="row">
                                <div class="col-12">
                                    <x-metronic.label class="fw-bold fs-6">{{ __('Seats') }}</x-metronic.label>

                                    <div id="seat-repeater">
                                        <div class="p-3 mb-3 border rounded repeater-item position-relative">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <x-metronic.input name="seats[0][name]" placeholder="Seat Name (e.g. A1)"
                                                        required />
                                                </div>
                                                <div class="col-md-3">
                                                    <x-metronic.input name="seats[0][code]" placeholder="Seat Code (optional)" />
                                                </div>
                                                <div class="col-md-2">
                                                    <x-metronic.input name="seats[0][row]" placeholder="Row" />
                                                </div>
                                                <div class="col-md-2">
                                                    <x-metronic.input name="seats[0][column]" placeholder="Column" />
                                                </div>
                                                <div class="col-md-2 d-flex align-items-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-repeater">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-light-primary btn-sm" id="add-seat">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-10 text-end">
                    <x-metronic.button type="submit" class="px-5 btn btn-primary dark rounded-1">
                        {{ __('Create Seats') }}
                    </x-metronic.button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let seatIndex = 1;

        document.getElementById('add-seat').addEventListener('click', function() {
            const seatRepeater = document.getElementById('seat-repeater');

            const html = `
            <div class="p-3 mb-3 border rounded repeater-item position-relative">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="seats[${seatIndex}][name]" class="form-control" placeholder="Seat Name (e.g. A1)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="seats[${seatIndex}][code]" class="form-control" placeholder="Seat Code (optional)">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="seats[${seatIndex}][row]" class="form-control" placeholder="Row">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="seats[${seatIndex}][column]" class="form-control" placeholder="Column">
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-repeater">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

            seatRepeater.insertAdjacentHTML('beforeend', html);
            seatIndex++;
        });

        // Remove seat row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-repeater')) {
                e.target.closest('.repeater-item').remove();
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.querySelector('input[name="checkbox_input"]');
            const bulkSection = document.querySelector('.bulk-seats-section');
            const repeaterSection = document.querySelector('.repeater-seats-section');
            const label = document.querySelector('label[for="kt_docs_formvalidation_checkbox_option_1"] .text-gray-800');

            function toggleSeats() {
                if (checkbox.checked) {
                    repeaterSection.style.display = 'block';
                    bulkSection.style.display = 'none';
                    label.textContent = 'Using Repeater Seats';
                } else {
                    repeaterSection.style.display = 'none';
                    bulkSection.style.display = 'block';
                    label.textContent = 'Using Bulk Seat Entry';
                }
            }

            toggleSeats();
            checkbox.addEventListener('change', toggleSeats);
        });
    </script>
    @endpush

</x-admin-app-layout>