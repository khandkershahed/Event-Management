<x-admin-app-layout :title="'Edit Seating Plan'">

    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between">
            <h3 class="card-title">Edit Seating Plan</h3>

            <a href="{{ route('admin.seating-plans.index') }}" class="btn btn-light-info">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body pt-0">

            <form action="{{ route('admin.seating-plans.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-lg-8">
                        <div class="card border p-5 card-flush">
                            <h5 class="text-primary mb-4">Plan Information</h5>

                            <div class="mb-5">
                                <x-metronic.label for="name" class="required">Plan Name</x-metronic.label>
                                <x-metronic.input type="text" name="name" value="{{ $plan->name }}" required />
                            </div>

                            <div class="mb-5">
                                <x-metronic.label for="venue_id" class="required">Venue</x-metronic.label>
                                <x-metronic.select-option name="venue_id" required>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ $plan->venue_id == $venue->id ? 'selected' : '' }}>
                                            {{ $venue->name }}
                                        </option>
                                    @endforeach
                                </x-metronic.select-option>
                            </div>

                            <hr>

                            <h5 class="text-primary mb-4">Layout</h5>

                            <a href="{{ route('admin.seating-plans.designer', $plan->id) }}" class="btn btn-light-info">
                                <i class="fas fa-pencil-ruler me-1"></i>
                                Open Visual Designer
                            </a>

                        </div>
                    </div>

                </div>

                <div class="mt-5 text-end">
                    <button type="submit" class="btn btn-primary px-5">Update Plan</button>
                </div>

            </form>

        </div>
    </div>

</x-admin-app-layout>
