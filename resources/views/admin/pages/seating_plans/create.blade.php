<x-admin-app-layout :title="'Create Seating Plan'">

    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between">
            <h3 class="card-title">Create Seating Plan</h3>

            <a href="{{ route('admin.seating-plans.index') }}" class="btn btn-light-info">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body pt-0">

            <form action="{{ route('admin.seating-plans.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border p-5 card-flush">
                            <h5 class="text-primary mb-4">Seating Plan Information</h5>

                            <div class="mb-5">
                                <x-metronic.label for="name" class="required">Plan Name</x-metronic.label>
                                <x-metronic.input type="text" name="name" placeholder="e.g., Concert Layout" required />
                            </div>

                            <div class="mb-5">
                                <x-metronic.label for="venue_id" class="required">Venue</x-metronic.label>
                                <select class="form-select" data-control="select2" data-placeholder="Select Venue" name="venue_id" required>
                                    <option></option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="text-end mt-5">
                    <button type="submit" class="btn btn-primary px-5">Create Plan</button>
                </div>

            </form>

        </div>
    </div>

</x-admin-app-layout>
