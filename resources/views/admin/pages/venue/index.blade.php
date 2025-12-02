<x-admin-app-layout :title="'Venues'">
    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between">
            <h3 class="card-title">Venue List</h3>
            <div class="card-toolbar">
                <a href="{{ route('admin.venues.create') }}" class="btn btn-light-primary">
                    <i class="fas fa-plus-circle me-2"></i>Add Venue
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <table class="table table-striped table-row-bordered gy-5 gs-7 border rounded" id="venueTable">
                <thead class="bg-dark text-light">
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Capacity</th>
                        <th>Organizer</th>
                        <th>Seating Plans</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-bold text-gray-600">
                    @foreach($venues as $key => $venue)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $venue->name }}</td>
                            <td>{{ $venue->slug }}</td>
                            <td>{{ $venue->capacity ?? '—' }}</td>
                            <td>{{ optional($venue->organizer)->name ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin.seating-plans.index', $venue->id) }}" class="text-primary">
                                    {{ $venue->seating_plans_count }} Plans
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.venues.edit', $venue->id) }}" class="me-2">
                                    <i class="fas fa-edit fs-4 text-primary"></i>
                                </a>
                                <a href="javascript:void(0)" data-id="{{ $venue->id }}" class="deleteVenue">
                                    <i class="fas fa-trash fs-4 text-danger"></i>
                                </a>
                                <form id="deleteForm-{{ $venue->id }}" action="{{ route('admin.venues.destroy', $venue->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        $("#venueTable").DataTable({
            language: { lengthMenu: "Show _MENU_" },
        });

        $(document).on('click', '.deleteVenue', function() {
            if (confirm("Are you sure you want to delete this venue?")) {
                const id = $(this).data('id');
                $("#deleteForm-" + id).submit();
            }
        });
    </script>
    @endpush
</x-admin-app-layout>
