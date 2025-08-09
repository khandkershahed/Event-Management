<x-admin-app-layout :title="'Edit Seat Type'">
    <div class="card card-flash">
        <div class="card-header mt-6">
            <div class="card-toolbar">
                <a href="{{ route('admin.event-seat-type.index') }}" class="btn btn-light-info">
                    <span class="svg-icon svg-icon-3"><i class="fas fa-arrow-left"></i></span>
                    Back to the list
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form method="POST" action="{{ route('admin.event-seat-type.update', $eventSeatType->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Name --}}
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="name" class="col-form-label required fw-bold fs-6">
                            {{ __('Seat Type Name') }}
                        </x-metronic.label>
                        <x-metronic.input id="name" type="text" name="name"
                            placeholder="Enter seat type name"
                            :value="old('name', $eventSeatType->name)" required />
                    </div>

                    {{-- Code --}}
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="code" class="col-form-label fw-bold fs-6">
                            {{ __('Code') }}
                        </x-metronic.label>
                        <x-metronic.input id="code" type="text" name="code"
                            placeholder="Enter seat type code"
                            :value="old('code', $eventSeatType->code)" />
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="status" class="col-form-label fw-bold fs-6">
                            {{ __('Status') }}
                        </x-metronic.label>
                        <x-metronic.select-option id="status" name="status" data-hide-search="true" required>
                            <option value="active" {{ (old('status', $eventSeatType->status) === 'active') ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ (old('status', $eventSeatType->status) === 'inactive') ? 'selected' : '' }}>Inactive</option>
                        </x-metronic.select-option>
                    </div>

                    {{-- Image --}}
                    <div class="col-lg-6 mb-7">
                        <x-metronic.label for="image" class="col-form-label fw-bold fs-6">
                            {{ __('Seat Type Image') }}
                        </x-metronic.label>
                        <x-metronic.file-input id="image" name="image" :value="old('image')" :source="isset($eventSeatType->image) ? asset('storage/' . $eventSeatType->image) : null" />
                    </div>

                    {{-- Description --}}
                    <div class="col-lg-12 mb-7">
                        <x-metronic.label for="description" class="col-form-label fw-bold fs-6">
                            {{ __('Description') }}
                        </x-metronic.label>
                        <x-metronic.textarea id="description" name="description"
                            placeholder="Write short description...">{{ old('description', $eventSeatType->description) }}</x-metronic.textarea>
                    </div>
                </div>

                <div class="text-end pt-15">
                    <x-metronic.button type="submit" class="dark rounded-1 px-5">
                        {{ __('Update Seat Type') }}
                    </x-metronic.button>
                </div>
            </form>
        </div>
    </div>
</x-admin-app-layout>
