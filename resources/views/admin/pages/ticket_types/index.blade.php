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

                <button class="btn btn-primary" id="openAddTicketModal">
                    <i class="fa fa-plus-circle me-1"></i> Add Ticket Type
                </button>
            </div>
        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-bordered gy-5 gs-7 border rounded">
                <thead class="bg-dark text-light">
                    <tr>
                        <th width="6%">#</th>
                        <th width="20%">Name</th>
                        <th width="14%">Price</th>
                        <th width="12%">Qty</th>
                        <th width="26%">Sections</th>
                        <th width="12%">Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-700">
                    @foreach ($ticketTypes as $i => $type)
                        <tr>
                            <td>{{ $i + 1 }}</td>

                            <td>{{ $type->name }}</td>

                            <td>${{ number_format($type->price, 2) }}</td>

                            <td>{{ $type->quantity }}</td>

                            <td>
                                @if ($type->valid_section_ids)
                                    @foreach (json_decode($type->valid_section_ids) as $id)
                                        <?php $s = $sections->firstWhere('id', $id); ?>
                                        @if ($s)
                                            <span class="badge bg-info m-1">{{ $s->name }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">None</span>
                                @endif
                            </td>

                            <td>
                                @if ($type->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <button class="btn btn-sm btn-light-warning editTicketBtn"
                                    data-id="{{ $type->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-light-danger deleteTicketBtn"
                                    data-id="{{ $type->id }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>


    <!-- ------------------------------------------------------------- -->
    <!-- ADD / EDIT MODAL — T4 ADVANCED TICKET BUILDER                 -->
    <!-- ------------------------------------------------------------- -->

    <div class="modal fade" id="ticketTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="ticketTypeForm">
                    @csrf

                    <input type="hidden" name="ticket_id" id="ticket_id">
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    <div class="modal-content">

                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="ticketModalTitle">Add Ticket Type</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <!-- NAV TABS -->
                            <ul class="nav nav-tabs mb-4" id="ticketTab" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#tab-basic">
                                        Basic
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#tab-pricing">
                                        Pricing
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#tab-rules">
                                        Rules
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#tab-fees">
                                        Fees
                                    </button>
                                </li>
                            </ul>



                            <div class="tab-content">

                                <!-- BASIC TAB -->
                                <div class="tab-pane fade show active" id="tab-basic">

                                    <div class="row">

                                        <div class="col-md-6 mb-4">
                                            <label class="fw-bold">Ticket Name</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="fw-bold">Status</label>
                                            <select name="is_active" id="is_active" class="form-select">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="mb-4">
                                        <label class="fw-bold">Description</label>
                                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="fw-bold">Linked Section(s)</label>
                                        <select multiple class="form-select" data-control="select2"
                                            data-placeholder="Select Seat Type" name="section_ids[]"
                                            id="section_ids">
                                            <option></option>
                                            @foreach ($sections as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>


                                <!-- PRICING TAB -->
                                <div class="tab-pane fade" id="tab-pricing">

                                    <div class="row">

                                        <div class="col-md-4 mb-4">
                                            <label class="fw-bold">Base Price ($)</label>
                                            <input type="number" step="0.01" name="price" id="price"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <label class="fw-bold">Quantity</label>
                                            <input type="number" name="quantity" id="quantity"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <label class="fw-bold">Early Bird Ends At</label>
                                            <input type="datetime-local" name="early_bird_ends_at"
                                                id="early_bird_ends_at" class="form-control">
                                        </div>

                                    </div>

                                </div>


                                <!-- RULES TAB -->
                                <div class="tab-pane fade" id="tab-rules">

                                    <div class="row">

                                        <div class="col-md-6 mb-4">
                                            <label class="fw-bold">Min Tickets Per Order</label>
                                            <input type="number" name="min_per_order" id="min_per_order"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="fw-bold">Max Tickets Per Order</label>
                                            <input type="number" name="max_per_order" id="max_per_order"
                                                class="form-control">
                                        </div>

                                    </div>

                                </div>


                                <!-- FEES TAB -->
                                <div class="tab-pane fade" id="tab-fees">

                                    <h5 class="fw-bold text-primary">Platform Fee</h5>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label>Fixed ($)</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="platform_fee_fixed" id="platform_fee_fixed">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Percent (%)</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="platform_fee_percent" id="platform_fee_percent">
                                        </div>
                                    </div>

                                    <h5 class="fw-bold text-primary">Processing Fee</h5>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label>Fixed ($)</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="processing_fee_fixed" id="processing_fee_fixed">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Percent (%)</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="processing_fee_percent" id="processing_fee_percent">
                                        </div>
                                    </div>

                                    <h5 class="fw-bold text-primary">Payment Gateway (Stripe) Fee</h5>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label>Fixed ($)</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="payment_gateway_fee_fixed" id="payment_gateway_fee_fixed">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Percent (%)</label>
                                            <input type="number" step="0.01" class="form-control"
                                                name="payment_gateway_fee_percent" id="payment_gateway_fee_percent">
                                        </div>
                                    </div>

                                    <h5 class="fw-bold text-primary">Fee Split (Customer vs Organizer)</h5>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label>Customer Pays (%)</label>
                                            <input type="number" class="form-control" name="fee_customer_percent"
                                                id="fee_customer_percent">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Organizer Pays (%)</label>
                                            <input type="number" class="form-control" name="fee_organizer_percent"
                                                id="fee_organizer_percent">
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary"
                                data-bs-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-primary" id="ticketSubmitBtn">
                                <i class="fa fa-save me-1"></i> Save Ticket Type
                            </button>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            let TICKET_MODAL = new bootstrap.Modal(document.getElementById("ticketTypeModal"));

            // -----------------------------------------------
            // OPEN ADD TICKET MODAL
            // -----------------------------------------------
            $("#openAddTicketModal").click(function() {
                $("#ticketModalTitle").text("Add Ticket Type");
                $("#ticketTypeForm")[0].reset();
                $("#ticket_id").val("");

                TICKET_MODAL.show();
            });


            // -----------------------------------------------
            // OPEN EDIT TICKET MODAL (AJAX)
            // -----------------------------------------------
            $(".editTicketBtn").click(function() {

                let id = $(this).data("id");

                $.get("{{ url('admin/events/' . $event->id . '/ticket-types') }}/" + id, function(res) {

                    if (res.status === "success") {

                        let t = res.ticket;

                        $("#ticketModalTitle").text("Edit Ticket Type");
                        $("#ticket_id").val(t.id);

                        $("#name").val(t.name);
                        $("#description").val(t.description);
                        $("#price").val(t.price);
                        $("#quantity").val(t.quantity);
                        $("#is_active").val(t.is_active);
                        $("#min_per_order").val(t.min_per_order);
                        $("#max_per_order").val(t.max_per_order);
                        $("#early_bird_ends_at").val(t.early_bird_ends_at ? t.early_bird_ends_at.replace(" ",
                            "T") : "");

                        // Select sections
                        $("#section_ids option").prop("selected", false);

                        let sections = JSON.parse(t.valid_section_ids ?? "[]");

                        sections.forEach(id => {
                            $("#section_ids option[value='" + id + "']").prop("selected", true);
                        });

                        $("#section_ids").trigger("change"); // IMPORTANT FOR SELECT2


                        // Fees
                        $("#platform_fee_fixed").val(t.platform_fee_fixed);
                        $("#platform_fee_percent").val(t.platform_fee_percent);

                        $("#processing_fee_fixed").val(t.processing_fee_fixed);
                        $("#processing_fee_percent").val(t.processing_fee_percent);

                        $("#payment_gateway_fee_fixed").val(t.payment_gateway_fee_fixed);
                        $("#payment_gateway_fee_percent").val(t.payment_gateway_fee_percent);

                        $("#fee_customer_percent").val(t.fee_customer_percent);
                        $("#fee_organizer_percent").val(t.fee_organizer_percent);

                        TICKET_MODAL.show();
                    }
                });
            });


            // -----------------------------------------------
            // SUBMIT FORM (Create OR Update)
            // -----------------------------------------------
            $("#ticketTypeForm").submit(function(e) {
                e.preventDefault();

                let form = $(this);
                let ticketId = $("#ticket_id").val();


                let url = ticketId === "" ?
                    "{{ route('admin.events.ticket-types.store', $event->id) }}" :
                    "{{ url('admin/events/' . $event->id . '/ticket-types') }}/" + ticketId;


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let method = ticketId === "" ? "POST" : "PUT";

                $.ajax({
                    url: url,
                    type: method,
                    data: form.serialize(),
                    success: function(res) {
                        if (res.status === "success") {
                            alert("Saved!");
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        alert("Error saving ticket.");
                    }
                });
            });


            // -----------------------------------------------
            // DELETE TICKET
            // -----------------------------------------------
            $(".deleteTicketBtn").click(function() {

                if (!confirm("Delete this ticket type?")) return;

                let id = $(this).data("id");

                $.ajax({
                    url: "{{ url('admin/events/' . $event->id . '/ticket-types') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            alert("Deleted.");
                            location.reload();
                        }
                    }
                });
            });
        </script>
    @endpush

</x-admin-app-layout>
