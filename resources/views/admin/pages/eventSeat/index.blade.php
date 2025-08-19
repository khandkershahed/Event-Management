<x-admin-app-layout :title="'Event Seats'">
    <div class="card card-flash">
        <div class="mt-6 card-header">
            <div class="card-toolbar d-flex justify-content-between w-100">
                <div class="form-group">
                    <label for="eventSelector">Select Event</label>
                    <select id="eventSelector" class="form-control">
                        <option value="">-- Choose Event --</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
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
                    <p><strong>Name:</strong> <span id="modalSeatName"></span></p>
                    <p><strong>Code:</strong> <span id="modalSeatCode"></span></p>
                    <p><strong>Row:</strong> <span id="modalSeatRow"></span></p>
                    <p><strong>Column:</strong> <span id="modalSeatColumn"></span></p>
                    <p><strong>Price:</strong> $<span id="modalSeatPrice"></span></p>
                    <p><strong>Status:</strong> <span id="modalSeatStatus"></span></p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('eventSelector').addEventListener('change', function () {
            const eventId = this.value;
            const container = document.getElementById('seatContainer');
            container.innerHTML = '<p class="text-muted">Loading seats...</p>';

            if (!eventId) {
                container.innerHTML = '<p class="text-muted">Please select an event to view seats.</p>';
                return;
            }

            fetch("{{ route('admin.event-seat.fetch') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ event_id: eventId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-muted">No seats found for this event.</p>';
                    return;
                }

                container.innerHTML = '';

                // Sort by row then column
                data.sort((a, b) => {
                    if (a.row === b.row) {
                        return a.column - b.column;
                    }
                    return a.row.localeCompare(b.row);
                });

                data.forEach(seat => {
                    const seatDiv = document.createElement('div');
                    seatDiv.className = 'seat-box btn btn-light border text-center';
                    seatDiv.style.width = '60px';
                    seatDiv.style.height = '60px';
                    seatDiv.style.lineHeight = '60px';
                    seatDiv.style.cursor = 'pointer';
                    seatDiv.textContent = seat.name;
                    seatDiv.dataset.seat = JSON.stringify(seat);

                    seatDiv.addEventListener('click', () => {
                        const s = JSON.parse(seatDiv.dataset.seat);
                        document.getElementById('modalSeatName').textContent = s.name;
                        document.getElementById('modalSeatCode').textContent = s.code ?? '-';
                        document.getElementById('modalSeatRow').textContent = s.row;
                        document.getElementById('modalSeatColumn').textContent = s.column;
                        document.getElementById('modalSeatPrice').textContent = s.price;
                        document.getElementById('modalSeatStatus').textContent = s.status;

                        const modal = new bootstrap.Modal(document.getElementById('seatModal'));
                        modal.show();
                    });

                    container.appendChild(seatDiv);
                });
            })
            .catch(err => {
                container.innerHTML = '<p class="text-danger">Error fetching seats.</p>';
                console.error(err);
            });
        });
    </script>
    @endpush

</x-admin-app-layout>

