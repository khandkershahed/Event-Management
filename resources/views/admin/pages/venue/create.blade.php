<x-admin-app-layout :title="'Create Venue'">

    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between">
            <h3 class="card-title">Create Venue</h3>
            <div>
                <a href="{{ route('admin.venue.index') }}" class="btn btn-light-info">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form action="{{ route('admin.venue.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card card-flush border p-5">
                            <h5 class="text-primary mb-4">Venue Information</h5>

                            <div class="mb-5">
                                <x-metronic.label for="name" class="required">Venue Name</x-metronic.label>
                                <x-metronic.input type="text" name="name" placeholder="Enter venue name" required />
                            </div>

                            {{-- <div class="mb-5">
                                <x-metronic.label for="slug">Slug</x-metronic.label>
                                <x-metronic.input type="text" name="slug" placeholder="venue-slug" />
                                <small class="text-muted">Leave empty to auto-generate.</small>
                            </div> --}}

                            <div class="mb-5">
                                <x-metronic.label for="capacity">Capacity</x-metronic.label>
                                <x-metronic.input type="number" name="capacity" placeholder="3000" />
                            </div>

                            <div class="mb-5">
                                <x-metronic.label for="address">Address</x-metronic.label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Venue address..."></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border p-5 card-flush">
                            <h5 class="fw-bold mb-4">Status</h5>

                            <select class="form-select" data-control="select2" name="status" data-hide-search="true">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="mt-5 text-end">
                    <button type="submit" class="btn btn-primary px-5">Save Venue</button>
                </div>

            </form>
        </div>
    </div>

</x-admin-app-layout>
