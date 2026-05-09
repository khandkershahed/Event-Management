<x-frontend-app-layout>
    <div class="breadcrumb-block">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home > </a></li>
                <li class="breadcrumb-item active">Cart</li>
            </ol>
        </div>
    </div>

    <div class="event-dt-block p-80">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="main-card p-4">
                        <h3 class="mb-4">Your Reservation Cart</h3>

                        @if ($items->isEmpty())
                            <div class="alert alert-warning mb-0">
                                Your cart is empty. Browse public events and add tickets to begin a reservation.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>Ticket</th>
                                            <th>Seat</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ $item->event?->name }}</strong><br>
                                                    <small>{{ $item->event?->start_date?->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    {{ $item->ticketType?->name }}<br>
                                                    <small>{{ $item->ticketType?->formattedPrice() }}</small>
                                                </td>
                                                <td>{{ $item->seat?->label ?? 'General admission' }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ $item->ticketType?->currency ?? 'BDT' }} {{ number_format((float) $item->subtotal, 2) }}</td>
                                                <td class="text-end">
                                                    <form action="{{ route('frontend.cart.remove') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <form action="{{ route('frontend.cart.clear') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Clear Cart</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="main-card p-4 sticky-top">
                        <h4>Reservation Summary</h4>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total</span>
                            <strong>BDT {{ number_format((float) $total, 2) }}</strong>
                        </div>
                        <div class="alert alert-info">
                            Step 10 creates the order and issues ticket codes. Online payment will be added in Step 11.
                        </div>
                        <a href="{{ route('all.events') }}" class="main-btn btn-hover w-100 mb-2">Continue Browsing</a>
                        <a href="{{ route('frontend.checkout') }}" class="btn btn-primary w-100 {{ $items->isEmpty() ? 'disabled' : '' }}">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
