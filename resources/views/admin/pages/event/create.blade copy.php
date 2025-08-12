<x-admin-app-layout :title="'Create Event'">
    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-toolbar">
                <a href="{{ route('admin.event.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <form method="POST" action="{{ route('admin.event.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    {{-- Event Type --}}
                    <div class="col-lg-4 mb-7">
                        <x-metronic.label for="event_type_id" class="col-form-label required fw-bold fs-6">
                            {{ __('Select Event Type') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="event_type_id" name="event_type_id"
                            data-hide-search="true" data-placeholder="Select an option" required>
                            <option></option>
                            @foreach ($event_types as $event_type)
                                <option value="{{ $event_type->id }}"
                                    {{ old('event_type_id') == $event_type->id ? 'selected' : '' }}>
                                    {{ $event_type->name }}
                                </option>
                            @endforeach
                        </x-metronic.select-option>
                    </div>

                    {{-- Name --}}






                    {{-- Total Capacity, Age Restriction --}}
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="total_capacity" class="col-form-label fw-bold fs-6">
                            {{ __('Total Capacity') }}
                        </x-metronic.label>
                        <x-metronic.input id="total_capacity" type="number" name="total_capacity"
                            :value="old('total_capacity')" />
                    </div>
                    <div class="col-lg-3 mb-7">
                        <x-metronic.label for="age_restriction" class="col-form-label fw-bold fs-6">
                            {{ __('Age Restriction') }}
                        </x-metronic.label>
                        <x-metronic.input id="age_restriction" type="text" name="age_restriction"
                            placeholder="e.g., 18+, All Ages" :value="old('age_restriction')" />
                    </div>


                    {{-- Terms & Conditions --}}
                    <div class="col-lg-12 mb-7">
                        <x-metronic.label for="terms_and_conditions" class="col-form-label fw-bold fs-6">
                            {{ __('Terms & Conditions') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="terms_and_conditions" name="terms_and_conditions"
                            placeholder="Enter any terms or conditions">{{ old('terms_and_conditions') }}</x-metronic.textarea>
                    </div>


                </div>

                <div class="text‑end pt‑15">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Create Event') }}
                    </x-metronic.button>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
