<x-admin-app-layout :title="'Manage Ticket Types — ' . $event->name">

    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between align-items-center">

            <h3 class="mb-0 fw-bold text-primary">
                <i class="fa fa-tags me-2"></i> Ticket Types for Event:
                <span class="text-dark">{{ $event->name }}</span>
            </h3>

            <div>
                <a href="{{ route('admin.event.edit', $event->id) }}" class="btn btn-light-secondary me-2">
                    <i class="fa fa-arrow-left me-1"></i> Back to Event
                </a>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTicketTypeModal">
                    <i class="fa fa-plus-circle me-1"></i> Add Ticket Type
                </button>
            </div>
        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                <thead class="bg-dark text-light">
                    <tr>
                        <th width="8%">#</th>
                        <th width="20%">Name</th>
                        <th width="18%">Price</th>
                        <th width="18%">Capacity</th>
                        <th width="26%">Linked Sections</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-700">
                    @forelse($ticketTypes as $key => $type)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $type->name }}</td>

                            <td>{{ number_format($type->price, 2) }}</td>

                            <td>{{ $type->capacity }}</td>

                            <td>
                                @if($type->valid_section_ids && count($type->sections) > 0)
                                    @foreach($type->sections as $section)
                                        <span class="badge bg-info m-1">{{ $section->name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">No Sections Linked</span>
                                @endif
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-warning me-1 editTicketBtn"
                                    data-id="{{ $type->id }}"
                                    data-name="{{ $type->name }}"
                                    data-price="{{ $type->price }}"
                                    data-capacity="{{ $type->capacity }}"
                                    data-sections="{{ json_encode($type->valid_section_ids) }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-light-danger deleteTicketBtn"
                                    data-id="{{ $type->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No ticket types added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

        <!-- ADD TICKET TYPE MODAL -->
    <div class="modal fade" id="addTicketTypeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ route('admin.ticket-types.store') }}">
                @csrf

                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Ticket Type</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Ticket Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. VIP, Early Bird">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Capacity</label>
                                <input type="number" name="capacity" class="form-control" required placeholder="100">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Linked Sections</label>
                            <select name="valid_section_ids[]" multiple class="form-select">
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Users can select seats only from selected sections.</small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save Ticket Type
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

        <!-- EDIT TICKET TYPE MODAL -->
    <div class="modal fade" id="editTicketTypeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ route('admin.ticket-types.update') }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="edit_ticket_id">
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="modal-content">

                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Edit Ticket Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Ticket Name</label>
                            <input type="text" id="edit_ticket_name" name="name" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" step="0.01" id="edit_ticket_price" name="price" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Capacity</label>
                                <input type="number" id="edit_ticket_capacity" name="capacity" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Linked Sections</label>
                            <select name="valid_section_ids[]" id="edit_ticket_sections" multiple class="form-select">
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-warning">
                            <i class="fa fa-save me-1"></i> Update Ticket Type
                        </button>
                    </div>

                </div>

            </form>
        </div>
    </div>


    @push('scripts')
    <script>
        // ---------------------------
        // OPEN EDIT MODAL
        // ---------------------------
        $(".editTicketBtn").click(function () {

            let id       = $(this).data('id');
            let name     = $(this).data('name');
            let price    = $(this).data('price');
            let capacity = $(this).data('capacity');
            let sections = $(this).data('sections');

            $("#edit_ticket_id").val(id);
            $("#edit_ticket_name").val(name);
            $("#edit_ticket_price").val(price);
            $("#edit_ticket_capacity").val(capacity);

            $("#edit_ticket_sections option").prop("selected", false);

            if (sections) {
                sections.forEach(id => {
                    $("#edit_ticket_sections option[value='" + id + "']").prop("selected", true);
                });
            }

            $("#editTicketTypeModal").modal('show');
        });

        // ---------------------------
        // DELETE TICKET
        // ---------------------------
        $(".deleteTicketBtn").click(function () {
            let id = $(this).data('id');

            if (!confirm("Are you sure you want to delete this ticket type?")) {
                return;
            }

            $.post("{{ route('admin.ticket-types.delete') }}", {
                _token: "{{ csrf_token() }}",
                id: id
            }, function () {
                location.reload();
            });
        });
    </script>
    @endpush

</x-admin-app-layout>

