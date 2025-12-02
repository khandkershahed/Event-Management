<x-admin-app-layout :title="'Edit Venue'">

    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between">
            <h3 class="card-title">Edit Venue</h3>
            <div>
                <a href="{{ route('admin.venues.index') }}" class="btn btn-light-info">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form action="{{ route('admin.venues.update', $venue->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border p-5 card-flush">

                            <h5 class="text-primary mb-4">Venue Information</h5>

                            <div class="mb-5">
                                <x-metronic.label for="name" class="required">Venue Name</x-metronic.label>
                                <x-metronic.input type="text" name="name" value="{{ $venue->name }}" required />
                            </div>

                            <div class="mb-5">
                                <x-metronic.label for="slug">Slug</x-metronic.label>
                                <x-metronic.input type="text" name="slug" value="{{ $venue->slug }}" />
                            </div>

                            <div class="mb-5">
                                <x-metronic.label for="capacity">Capacity</x-metronic.label>
                                <x-metronic.input type="number" name="capacity" value="{{ $venue->capacity }}" />
                            </div>

                            <div class="mb-5">
                                <x-metronic.label for="address">Address</x-metronic.label>
                                <x-metronic.textarea name="address" rows="3">{{ $venue->address }}</x-metronic.textarea>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-4">

                        <div class="card card-flush border p-5 mb-5">
                            <h5 class="fw-bold mb-4">Status</h5>
                            <x-metronic.select-option name="status" data-hide-search="true">
                                <option value="active" {{ $venue->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $venue->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </x-metronic.select-option>
                        </div>

                        <div class="card card-flush border p-5">
                            <h5 class="fw-bold mb-4">Seating Plans</h5>

                            @if($venue->seatingPlans->count())
                                <ul class="list-group">
                                    @foreach($venue->seatingPlans as $plan)
                                        <li class="list-group-item d-flex justify-content-between">
                                            {{ $plan->name }}
                                            <a href="{{ route('admin.seating-plans.edit', $plan->id) }}" class="text-primary">
                                                Edit
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No seating plans yet.</p>
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('admin.seating-plans.create', $venue->id) }}" class="btn btn-light-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add Seating Plan
                                </a>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="mt-5 text-end">
                    <button type="submit" class="btn btn-primary px-5">Update Venue</button>
                </div>

            </form>
        </div>
    </div>

</x-admin-app-layout>
