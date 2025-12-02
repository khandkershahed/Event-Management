<x-frontend-app-layout>

    <div class="breadcrumb-block">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('event.details', $event->slug) }}">
                        {{ $event->name }}
                    </a></li>
                <li class="breadcrumb-item active">Select Seats</li>
            </ol>
        </div>
    </div>


    <div class="event-dt-block p-80">
        <div class="container">

            <div class="row justify-content-center">

                <div class="col-xl-9 col-lg-9 col-md-12">

                    <h2 class="fw-bold mb-4">Choose Your Seats</h2>

                    <!-- Section Names -->
                    <div class="mb-3">
                        <h5 class="fw-bold">Sections</h5>
                        <div class="d-flex flex-wrap gap-2">

                            @foreach ($sections as $section)
                                <span class="badge bg-dark">{{ $section->name }}</span>
                            @endforeach

                        </div>
                    </div>

                    <!-- Seat Map -->
                    <div class="seat-map-wrapper p-4 border rounded bg-light text-center">

                        <div id="seat-map" style="min-height: 400px;">
                            <!-- JS will render seats here -->
                        </div>

                        <div class="mt-4">
                            <span class="badge bg-success">Available</span>
                            <span class="badge bg-secondary">Locked</span>
                            <span class="badge bg-danger">Sold</span>
                            <span class="badge bg-primary">Selected</span>
                        </div>
                    </div>
                </div>


                <!-- Selected Seats Sidebar -->
                <div class="col-xl-3 col-lg-3 col-md-12">

                    <div class="main-card p-4 sticky-top">

                        <h4 class="fw-bold mb-3">Your Selection</h4>

                        <ul id="selected-seats-list" class="list-group mb-3"></ul>

                        <h5>Total: BDT <span id="total-amount">0</span></h5>

                        <button id="add-to-cart-btn" class="main-btn btn-hover w-100 mt-3" disabled>
                            Continue to Cart
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </div>


    @push('scripts')
        <script>
            let eventId = {{ $event->id }};
            let ticketId = {{ $ticketId }};
            let seatDesign = @json($designJson);
            let soldSeats = @json($soldSeats);
            let lockedSeats = @json($lockedSeats);
            let selectedSeats = [];
            let price = {{ $ticketPrice }};

            function renderSeatMap() {
                const map = document.getElementById('seat-map');
                map.innerHTML = "";

                seatDesign.forEach(section => {

                    let sectionDiv = document.createElement('div');
                    sectionDiv.classList.add('my-4', 'p-3', 'border', 'bg-white', 'rounded');

                    let title = document.createElement('h5');
                    title.textContent = section.name;
                    sectionDiv.appendChild(title);

                    let grid = document.createElement('div');
                    grid.classList.add('d-flex', 'flex-wrap', 'gap-2');

                    section.seats.forEach(seat => {

                        let seatBtn = document.createElement('button');
                        seatBtn.textContent = seat.label;
                        seatBtn.classList.add('seat-btn');

                        seatBtn.style.minWidth = '45px';
                        seatBtn.style.padding = '6px 8px';
                        seatBtn.style.borderRadius = '4px';
                        seatBtn.style.border = '1px solid #ccc';

                        let seatId = seat.id;

                        // SOLD
                        if (soldSeats.includes(seatId)) {
                            seatBtn.classList.add('bg-danger', 'text-white');
                            seatBtn.disabled = true;
                        }
                        // LOCKED
                        else if (lockedSeats.includes(seatId)) {
                            seatBtn.classList.add('bg-secondary', 'text-white');
                            seatBtn.disabled = true;
                        }
                        // AVAILABLE
                        else {
                            seatBtn.classList.add('bg-success', 'text-white');

                            seatBtn.addEventListener('click', () => toggleSeat(seatId, seat.label, seatBtn));
                        }

                        grid.appendChild(seatBtn);
                    });

                    sectionDiv.appendChild(grid);
                    map.appendChild(sectionDiv);
                });
            }

            function toggleSeat(id, label, btn) {
                if (selectedSeats.find(s => s.id === id)) {
                    // remove
                    selectedSeats = selectedSeats.filter(s => s.id !== id);
                    btn.classList.remove('bg-primary');
                    btn.classList.add('bg-success');
                } else {
                    // add
                    selectedSeats.push({
                        id,
                        label
                    });
                    btn.classList.remove('bg-success');
                    btn.classList.add('bg-primary');
                }

                updateSidebar();
            }

            function updateSidebar() {
                let list = document.getElementById('selected-seats-list');
                list.innerHTML = "";

                selectedSeats.forEach(seat => {
                    let li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = seat.label;
                    list.appendChild(li);
                });

                document.getElementById('total-amount').textContent = (selectedSeats.length * price).toFixed(2);

                document.getElementById('add-to-cart-btn').disabled = selectedSeats.length === 0;
            }

            document.getElementById('add-to-cart-btn').addEventListener('click', function() {
                fetch(`/seats/add-to-cart`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            event_id: eventId,
                            ticket_id: ticketId,
                            seats: selectedSeats
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            window.location.href = `/cart`;
                        } else {
                            alert(data.message);
                        }
                    });
            });

            renderSeatMap();
        </script>
    @endpush

</x-frontend-app-layout>
