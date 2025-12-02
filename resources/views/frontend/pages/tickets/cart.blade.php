<x-frontend-app-layout>
    <div class="breadcrumb-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-10">
                    <div class="barren-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('homepage') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('all.events') }}">Explore Events</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Cart
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-80 event-dt-block">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-10 col-md-12">

                    <div class="main-card p-4">

                        <div class="bp-title mb-4">
                            <h4>Your Cart</h4>
                        </div>

                        @if (count($items) == 0)
                            <div class="text-center p-5">
                                <h4>Your cart is empty</h4>
                                <a href="{{ route('all.events') }}" class="main-btn btn-hover mt-3">Browse Events</a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Event</th>
                                            <th>Ticket Type</th>
                                            <th>Seat</th>
                                            <th>Unit Price</th>
                                            <th width="10%">Qty</th>
                                            <th>Subtotal</th>
                                            <th width="5%">Remove</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr data-id="{{ $item->id }}">

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

                                                <td>{{ number_format($item->unit_price, 2) }}</td>

                                                <td>
                                                    <input type="number" class="form-control cart-qty-input"
                                                        min="1" value="{{ $item->quantity }}">
                                                </td>

                                                <td class="cart-item-subtotal">
                                                    {{ number_format($item->unit_price * $item->quantity, 2) }}
                                                </td>

                                                <td>
                                                    <button class="btn btn-sm btn-danger remove-item-btn">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                            <div class="text-end mt-4">
                                <h4>Total: <span id="cartTotalDisplay">{{ number_format($total, 2) }}</span></h4>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('all.events') }}" class="btn btn-secondary">
                                    Continue Shopping
                                </a>

                                <a href="{{ route('tickets.checkout') }}" class="main-btn btn-hover">
                                    Proceed to Checkout
                                </a>
                            </div>

                        @endif

                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            /**
             * Update quantity and cart price
             */
            document.querySelectorAll('.cart-qty-input').forEach(input => {
                input.addEventListener('change', function() {

                    const qty = parseInt(this.value);
                    if (qty <= 0) return;

                    const row = this.closest('tr');
                    const id = row.dataset.id;

                    fetch(`/tickets/cart/update/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                quantity: qty
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.status === "success") {
                                row.querySelector('.cart-item-subtotal').innerHTML = res.item_subtotal;
                                document.getElementById('cartTotalDisplay').innerHTML = res.total;
                            }
                        });
                });
            });


            /**
             * Remove item
             */
            document.querySelectorAll('.remove-item-btn').forEach(btn => {
                btn.addEventListener('click', function() {

                    const row = this.closest('tr');
                    const id = row.dataset.id;

                    fetch(`/tickets/cart/remove/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.status === "success") {
                                row.remove();
                                document.getElementById('cartTotalDisplay').innerHTML = res.total;

                                if (res.total == 0) {
                                    location.reload();
                                }
                            }
                        });

                });
            });
        </script>
    @endpush

</x-frontend-app-layout>
