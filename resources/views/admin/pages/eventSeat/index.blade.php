<x-admin-app-layout :title="'Event Seats'">

    <style>
        .seat-box {
            margin: 4px;
            padding: 5px !important;
            font-size: 12px;
        }
    </style>

    <div class="card card-flash">
        <div class="mt-6 card-header">
            <div class="card-toolbar row justify-content-center w-100">

                {{-- Event Selector --}}
                <div class="col-lg-8 mb-7">
                    <label for="event_id" class="col-form-label fw-bold fs-6">
                        {{ __('Select Event') }}
                    </label>
                    <select class="form-select" id="event_id" name="event_id" required>
                        <option></option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Seat Type --}}
                <div class="col-lg-4 mb-7" id="seatTypeContainer" style="display: none;">
                    <label for="seat_type_id" class="col-form-label fw-bold fs-6">
                        {{ __('Seat Type') }}
                    </label>
                    <select class="form-select" id="seat_type_id" name="seat_type_id">
                        <option></option>
                        {{-- Populated by JS --}}
                    </select>
                </div>

            </div>
        </div>

        <div class="pt-0 card-body">
            <div id="seatContainer" class="d-flex flex-wrap gap-2" style="min-height: 300px;">
                <p class="text-muted">Please select an event to view seats.</p>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="seatModal" tabindex="-1" aria-labelledby="seatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="seatModalLabel" class="modal-title">Seat Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <p><strong>Name:</strong> <span id="modalSeatName"></span></p>
                    <p><strong>Code:</strong> <span id="modalSeatCode"></span></p>
                    <p><strong>Row:</strong> <span id="modalSeatRow"></span></p>
                    <p><strong>Column:</strong> <span id="modalSeatColumn"></span></p>
                    <p><strong>Price:</strong> $<span id="modalSeatPrice"></span></p>
                    <p><strong>Status:</strong> <span id="modalSeatStatus"></span></p> --}}
                    <form id="seatEditForm" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" id="modalSeatID" name="seat_id">

                        <div class="mb-3">
                            <label for="modalSeatName" class="form-label">Name</label>
                            <input type="text" name="name" id="modalSeatName" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="modalSeatCode" class="form-label">Code</label>
                            <input type="text" name="code" id="modalSeatCode" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="modalSeatRow" class="form-label">Row</label>
                            <input type="text" name="row" id="modalSeatRow" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="modalSeatColumn" class="form-label">Column</label>
                            <input type="text" name="column" id="modalSeatColumn" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="modalSeatPrice" class="form-label">Price</label>
                            <input type="number" name="price" id="modalSeatPrice" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="modalSeatStatus" class="form-label">Status</label>
                            <select name="status" id="modalSeatStatus" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="modalSeatDescription" class="form-label">Description</label>
                            <textarea name="description" id="modalSeatDescription" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const eventSelect = document.getElementById('event_id');
                const seatTypeSelect = document.getElementById('seat_type_id');
                const seatTypeContainer = document.getElementById('seatTypeContainer');
                const container = document.getElementById('seatContainer');

                const fetchSeats = (eventId, seatTypeId = null) => {
                    container.innerHTML = '<p class="text-muted">Loading seats...</p>';

                    fetch("{{ route('admin.event-seat.fetch') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                event_id: eventId,
                                seat_type_id: seatTypeId,
                            }),
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.length === 0) {
                                container.innerHTML = '<p class="text-muted">No seats found.</p>';
                                return;
                            }

                            container.innerHTML = '';
                            data.sort((a, b) => {
                                if (a.row === b.row) return a.column - b.column;
                                return a.row.localeCompare(b.row);
                            });

                            data.forEach(seat => {
                                const seatDiv = document.createElement('div');
                                seatDiv.className = 'seat-box btn btn-light border text-center';
                                seatDiv.style.width = '90px';
                                seatDiv.style.height = '60px';
                                seatDiv.style.lineHeight = '60px';
                                seatDiv.style.cursor = 'pointer';
                                seatDiv.textContent = seat.name;
                                seatDiv.dataset.seat = JSON.stringify(seat);

                                seatDiv.addEventListener('click', () => {
                                    const s = JSON.parse(seatDiv.dataset.seat);
                                    // Fill modal form fields
                                    document.getElementById('modalSeatID').value = s.id;
                                    document.getElementById('modalSeatName').value = s.name;
                                    document.getElementById('modalSeatCode').value = s.code ??
                                        '';
                                    document.getElementById('modalSeatRow').value = s.row ?? '';
                                    document.getElementById('modalSeatColumn').value = s
                                        .column ?? '';
                                    document.getElementById('modalSeatPrice').value = s.price ??
                                        '';
                                    document.getElementById('modalSeatStatus').value = s
                                        .status ?? 'active';
                                    document.getElementById('modalSeatDescription').value = s
                                        .description ?? '';

                                    // Set form action dynamically
                                    const form = document.getElementById('seatEditForm');
                                    form.action = `/admin/event-seat/${s.id}`;

                                    const modal = new bootstrap.Modal(document.getElementById(
                                        'seatModal'));
                                    modal.show();
                                });

                                container.appendChild(seatDiv);
                            });
                        })
                        .catch(err => {
                            container.innerHTML = '<p class="text-danger">Error fetching seats.</p>';
                            console.error(err);
                        });
                };

                eventSelect.addEventListener('change', function() {
                    const eventId = this.value;

                    // Reset
                    seatTypeSelect.innerHTML = '<option></option>';
                    seatTypeContainer.style.display = 'none';
                    container.innerHTML = '<p class="text-muted">Loading seats...</p>';

                    if (!eventId) {
                        container.innerHTML = '<p class="text-muted">Please select an event to view seats.</p>';
                        return;
                    }

                    // Fetch seats
                    fetchSeats(eventId);

                    // Fetch seat types
                    fetch("{{ route('admin.event-seat.fetch-seat-types') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                event_id: eventId
                            }),
                        })
                        .then(res => res.json())
                        .then(seatTypes => {
                            if (seatTypes.length === 0) {
                                seatTypeContainer.style.display = 'none';
                                return;
                            }

                            seatTypes.forEach(type => {
                                const opt = document.createElement('option');
                                opt.value = type.id;
                                opt.textContent = type.name;
                                seatTypeSelect.appendChild(opt);
                            });

                            seatTypeContainer.style.display = 'block';
                        })
                        .catch(err => {
                            console.error('Error fetching seat types:', err);
                            seatTypeContainer.style.display = 'none';
                        });
                });

                seatTypeSelect.addEventListener('change', function() {
                    const eventId = eventSelect.value;
                    const seatTypeId = this.value;

                    if (!eventId) return;
                    fetchSeats(eventId, seatTypeId);
                });
            });
        </script>
    @endpush

</x-admin-app-layout>
