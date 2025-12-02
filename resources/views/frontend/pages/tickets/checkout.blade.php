<x-frontend-app-layout>

    <div class="breadcrumb-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-10">
                    <div class="barren-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('all.events') }}">Explore Events</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('tickets.cart') }}">Cart</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="event-dt-block p-80">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-10 col-md-12">

                    <form id="checkoutForm" method="POST" action="{{ route('tickets.checkout.confirm') }}">
                        @csrf

                        <div class="main-card p-4 mb-4">
                            <div class="bp-title mb-4">
                                <h4>Billing Information</h4>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="main-card p-4 mb-4">
                            <div class="bp-title mb-4">
                                <h4>Your Tickets</h4>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Event</th>
                                            <th>Ticket</th>
                                            <th>Seat</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ $item->event->name }}</strong><br>
                                                    <small>{{ $item->event->start_date?->format('d M, Y') }}</small>
                                                </td>

                                                <td>{{ $item->ticketType->name }}</td>

                                                <td>
                                                    @if ($item->seat_id)
                                                        <span class="badge bg-primary">{{ $item->seat->label }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">General</span>
                                                    @endif
                                                </td>

                                                <td>{{ $item->quantity }}</td>

                                                <td>{{ number_format($item->unit_price, 2) }}</td>

                                                <td>{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-end mt-3">
                                <h4>Total: {{ number_format($total, 2) }}</h4>
                            </div>
                        </div>

                        <div class="main-card p-4 mb-4">
                            <div class="bp-title mb-4">
                                <h4>Payment Method</h4>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="paymentCOD"
                                    value="cod" checked>
                                <label class="form-check-label" for="paymentCOD">
                                    Cash on Delivery (COD)
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="paymentOnline"
                                    value="online">
                                <label class="form-check-label" for="paymentOnline">
                                    Online Payment (SSLCommerz / Stripe)
                                </label>
                            </div>

                        </div>

                        <div class="text-end">
                            <button type="submit" class="main-btn btn-hover px-5">
                                Confirm Order
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                e.preventDefault();

                let btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = "Processing...";

                this.submit();
            });
        </script>
    @endpush

</x-frontend-app-layout>
