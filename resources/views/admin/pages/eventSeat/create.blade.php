<x-admin-app-layout :title="'Create Event Seats'">
    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-toolbar">
                <a href="{{ route('admin.event-seat.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form method="POST" action="{{ route('admin.event-seat.store') }}">
                @csrf
                <div class="row">
                    {{-- Event --}}
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="event_id" class="col-form-label fw-bold fs-6">
                            {{ __('Select Event') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="event_id" name="event_id" required>
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
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="seat_type_id" class="col-form-label fw-bold fs-6">
                            {{ __('Seat Type') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="seat_type_id" name="seat_type_id" required>
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
                <div class="row">
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="price" class="col-form-label fw-bold fs-6">
                            {{ __('Price') }}
                        </x-metronic.label>
                        <x-metronic.input id="price" name="price" type="number" step="0.01"
                            value="{{ old('price') }}" required />
                    </div>
                </div>

                {{-- Bulk Seat Entry --}}
                <div class="row">
                    <div class="col-lg-12 mb-7">
                        <x-metronic.label for="bulk_seats" class="col-form-label fw-bold fs-6">
                            {{ __('Bulk Seat Entry') }}
                        </x-metronic.label>
                        <textarea name="bulk_seats" id="bulk_seats" rows="8" class="form-control"
                            placeholder="Example: A1,A,1,VIP-A1 A2,A,2,VIP-A2 B1,B,1,VIP-B1">{{ old('bulk_seats') }}</textarea>
                        <small class="text-muted d-block mt-2">
                            One seat per line in the format: <code>name,row,column,code</code><br>
                            <strong>Example:</strong><br>
                            <code>A1,A,1,VIP-A1</code><br>
                            <code>A2,A,2,VIP-A2</code><br>
                            <code>B1,B,1,VIP-B1</code>
                        </small>
                    </div>
                </div>

                <div class="text-end pt-10">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Create Seats') }}
                    </x-metronic.button>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
