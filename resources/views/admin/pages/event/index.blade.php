<x-admin-app-layout :title="'Events List'">

    <div class="card card-flash">

        <!-- ================= HEADER ================= -->
        <div class="card-header mt-6 d-flex justify-content-between align-items-center">
            <div class="card-title">
                <h3 class="fw-bold mb-0">Events</h3>
            </div>

            <div class="card-toolbar">
                <a href="{{ route('admin.event.create') }}" class="btn btn-light-primary">
                    <span class="svg-icon svg-icon-3">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    Add Event
                </a>
            </div>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="card-body pt-0">

            <table id="kt_datatable_events" class="table table-striped table-row-bordered gy-5 gs-7 border rounded">

                <thead class="bg-dark text-light">
                    <tr>
                        <th width="5%">Sl.</th>
                        <th width="15%">Event Type</th>
                        <th width="25%">Event Name</th>
                        <th width="15%">Start Date</th>
                        <th width="15%">End Date</th>
                        <th width="10%">Status</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-bold text-gray-700">

                    @foreach ($events as $key => $event)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td class="text-start">
                                {{ optional($event->eventType)->name }}
                            </td>

                            <td class="text-start">
                                {{ $event->name }}
                            </td>

                            <td class="text-start">
                                {{ $event->start_date ? $event->start_date->format('Y-m-d') : '-' }}
                            </td>

                            <td class="text-start">
                                {{ $event->end_date ? $event->end_date->format('Y-m-d') : '-' }}
                            </td>

                            <td>
                                <span class="badge {{ $event->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>

                            <td class="text-center">

                                <!-- Assign Seating Plan -->
                                <a href="{{ route('admin.seating-plans.index') }}" class="me-2"
                                    title="Assign Seating Plan">
                                    <i class="fa-solid fa-chair text-warning fs-4"></i>
                                </a>

                                <!-- Ticket Types Management -->
                                <a href="{{ route('admin.events.ticket-types.manage', $event->id) }}" class="me-2"
                                    title="Manage Ticket Types">
                                    <i class="fa-solid fa-ticket-simple text-info fs-4"></i>
                                </a>


                                <!-- Edit -->
                                <a href="{{ route('admin.event.edit', $event->id) }}" class="me-2"
                                    title="Edit Event">
                                    <i class="fa-solid fa-edit text-primary fs-4"></i>
                                </a>

                                <!-- Delete -->
                                <a href="{{ route('admin.event.destroy', $event->id) }}" class="delete"
                                    title="Delete Event">
                                    <i class="fa-solid fa-trash text-danger fs-4"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    @push('scripts')
        <script>
            $("#kt_datatable_events").DataTable({
                "language": {
                    "lengthMenu": "Show _MENU_"
                },
                "dom": "<'row'" +
                    "<'col-sm-6 d-flex align-items-center justify-content-start'l>" +
                    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                    ">" +

                    "<'table-responsive'tr>" +

                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">"
            });
        </script>
    @endpush

</x-admin-app-layout>
