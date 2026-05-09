<x-frontend-app-layout>
    <div class="breadcrumb-block">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('frontend.cart') }}">Cart</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </div>
    </div>

    <div class="event-dt-block p-80">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="main-card p-4">
                        <h3 class="mb-4">Checkout Review</h3>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Ticket</th>
                                        <th>Seat</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $item->event?->name }}</td>
                                            <td>{{ $item->ticketType?->name }}</td>
                                            <td>{{ $item->seat?->label ?? 'General admission' }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ $currency }} {{ number_format((float) $item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form action="{{ route('frontend.order.process') }}" method="POST" class="mt-4">
                            @csrf
                            <h5 class="mb-3">Customer Information</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="main-btn btn-hover">Place Order</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="main-card p-4 sticky-top">
                        <h4>Order Summary</h4>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong>{{ $currency }} {{ number_format((float) $subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <strong>{{ $currency }} {{ number_format((float) $discount_total, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Fees</span>
                            <strong>{{ $currency }} {{ number_format((float) $fee_total, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total</span>
                            <strong>{{ $currency }} {{ number_format((float) $total, 2) }}</strong>
                        </div>
                        <div class="alert alert-warning mb-0">
                            Paid orders will redirect to Stripe payment after order placement. Free orders are completed immediately.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
