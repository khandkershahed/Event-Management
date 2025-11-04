<!-- Modal -->
<style>
    /* Use Inter font family */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
        /* bg-gray-100 */
    }

    /* Custom orange color for Bootstrap primary button (matches image) */
    .btn-primary {
        --bs-btn-bg: #F97316;
        --bs-btn-border-color: #F97316;
        --bs-btn-hover-bg: #EA580C;
        --bs-btn-hover-border-color: #EA580C;
        --bs-btn-active-bg: #EA580C;
        --bs-btn-active-border-color: #EA580C;
        --bs-btn-disabled-bg: #F97316;
        --bs-btn-disabled-border-color: #F97316;
        --bs-btn-focus-shadow-rgb: 249, 115, 22, 0.5;
    }

    .btn-check:focus+.btn-primary,
    .btn-primary:focus {
        --bs-btn-bg: #EA580C;
        --bs-btn-border-color: #EA580C;
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.5);
    }

    /* Custom seat button sizing */
    .seat {
        width: 2.25rem;
        /* 36px */
        height: 2.25rem;
        /* 36px */
        font-size: 0.75rem;
        /* 12px */
        padding: 0.25rem;
        line-height: 1.5;
        /* Adjust for Bootstrap button vertical alignment */
    }

    /* Custom styles for occupied seats */
    .seat.occupied {
        background-color: #E5E7EB;
        border-color: #D1D5DB;
        color: #9CA3AF;
    }

    /* Ensure selected seat stays orange and text is white */
    .seat.btn-primary {
        color: #ffffff;
    }

    /* Match the bg-slate-50 color */
    .bg-slate-50 {
        background-color: #f8fafc;
    }

    /* Ensure modal content can scroll */
    .modal-content {
        max-height: 90vh;
    }

    .modal-body {
        overflow-y: auto;
    }
</style>
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="shadow-lg modal-content rounded-3">

            <!-- Modal Header -->
            <div class="p-4 modal-header border-bottom-0">
                <h5 class="modal-title fs-5 fw-semibold text-dark" id="bookingModalLabel">Book Your Seat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="p-0 modal-body">
                <!-- Two-column layout -->
                <div class="row g-0">

                    <!-- Left Column: Seat Selection -->
                    <div class="p-4 col-lg-8 p-md-5">
                        <h6 class="mb-4 fs-6 fw-semibold text-dark">Select Your Seats</h6>

                        <!-- Screen visual -->
                        <div style="background-color: #E5E7EB; height: 0.5rem;" class="mx-auto mb-3 rounded w-75"></div>
                        <p class="mb-4 text-center text-muted small">SCREEN</p>

                        <!-- Seat Grid -->
                        <div class="flex-wrap gap-2 mx-auto d-flex justify-content-center" style="max-width: 320px;">
                            <!-- Row A -->
                            <button class="seat" data-seat="A1">A1</button>
                            <button class="seat" data-seat="A2">A2</button>
                            <button class="seat occupied" data-seat="A3" disabled>A3</button>
                            <button class="seat" data-seat="A4">A4</button>
                            <button class="seat" data-seat="A5">A5</button>
                            <button class="seat" data-seat="A6">A6</button>
                            <button class="seat occupied" data-seat="A7" disabled>A7</button>
                            <button class="seat" data-seat="A8">A8</button>

                            <!-- Row B -->
                            <button class="seat" data-seat="B1">B1</button>
                            <button class="seat" data-seat="B2">B2</button>
                            <button class="seat" data-seat="B3">B3</button>
                            <button class="seat" data-seat="B4">B4</button>
                            <button class="seat occupied" data-seat="B5" disabled>B5</button>
                            <button class="seat" data-seat="B6">B6</button>
                            <button class="seat" data-seat="B7">B7</button>
                            <button class="seat" data-seat="B8">B8</button>

                            <!-- Row C -->
                            <button class="seat" data-seat="C1">C1</button>
                            <button class="seat" data-seat="C2">C2</button>
                            <button class="seat" data-seat="C3">C3</button>
                            <button class="seat" data-seat="C4">C4</button>
                            <button class="seat" data-seat="C5">C5</button>
                            <button class="seat" data-seat="C6">C6</button>
                            <button class="seat" data-seat="C7">C7</button>
                            <button class="seat" data-seat="C8">C8</button>

                            <!-- Row D -->
                            <button class="seat" data-seat="D1">D1</button>
                            <button class="seat" data-seat="D2">D2</button>
                            <button class="seat" data-seat="D3">D3</button>
                            <button class="seat" data-seat="D4">D4</button>
                            <button class="seat" data-seat="D5">D5</button>
                            <button class="seat occupied" data-seat="D6" disabled>D6</button>
                            <button class="seat" data-seat="D7">D7</button>
                            <button class="seat" data-seat="D8">D8</button>

                            <!-- Row E -->
                            <button class="seat" data-seat="E1">E1</button>
                            <button class="seat" data-seat="E2">E2</button>
                            <button class="seat" data-seat="E3">E3</button>
                            <button class="seat" data-seat="E4">E4</button>
                            <button class="seat" data-seat="E5">E5</button>
                            <button class="seat" data-seat="E6">E6</button>
                            <button class="seat" data-seat="E7">E7</button>
                            <button class="seat" data-seat="E8">E8</button>
                        </div>

                        <!-- Legend -->
                        <div class="gap-4 mt-4 d-flex justify-content-center">
                            <div class="gap-2 d-flex align-items-center">
                                <div class="border rounded" style="width: 1rem; height: 1rem; border-color: #6c757d!important;"></div>
                                <span class="small">Available</span>
                            </div>
                            <div class="gap-2 d-flex align-items-center">
                                <div class="rounded" style="width: 1rem; height: 1rem; background-color: #F97316;"></div>
                                <span class="small">Selected</span>
                            </div>
                            <div class="gap-2 d-flex align-items-center">
                                <div class="border rounded" style="width: 1rem; height: 1rem; background-color: #E5E7EB; border-color: #D1D5DB;"></div>
                                <span class="small">Occupied</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Order Summary (Styled like the screenshot) -->
                    <div class="p-4 col-lg-4 bg-slate-50 border-start p-md-5">

                        <!-- Event Image -->
                        <img src="https://placehold.co/600x400/1E1B2E/FFFFFF?text=Getting+Paid+To+Talk"
                            alt="Event: Getting Paid to Talk"
                            class="mb-4 img-fluid rounded-3">

                        <h6 class="mb-4 fs-5 fw-semibold text-dark">Order Summary</h6>

                        <div class="gap-2 d-flex flex-column">
                            <p class="mb-1 fw-semibold text-dark">Selected Seats:</p>
                            <!-- List of selected seats -->
                            <ul id="selectedSeatsList" class="mb-2 text-muted small ps-4">
                                <!-- JS will populate this -->
                                <li id="noSeats" class="text-muted" style="list-style: none; margin-left: -1rem;">No seats selected</li>
                            </ul>

                            <hr class="my-2">

                            <!-- Totals -->
                            <div class="d-flex justify-content-between align-items-center text-dark">
                                <p class="mb-0">Total Tickets:</p>
                                <p class="mb-0 fw-semibold" id="totalTickets">0</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-dark">
                                <p class="mb-0">Price per Ticket:</p>
                                <p class="mb-0 fw-semibold" id="ticketPrice">$44.52</p>
                            </div>

                            <!-- Final Total -->
                            <div class="pt-2 d-flex justify-content-between align-items-center fs-5 fw-bold text-dark">
                                <p class="mb-0">Total</p>
                                <p class="mb-0" id="totalPrice">$0.00</p>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button type="button" id="checkoutBtn" class="py-2 mt-4 btn btn-primary w-100 fw-semibold">
                            Proceed to Checkout
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>