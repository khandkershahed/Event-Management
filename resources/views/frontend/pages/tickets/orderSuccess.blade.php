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
                                <li class="breadcrumb-item active" aria-current="page">Order Confirmation</li>
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

                    <div class="main-card p-4">

                        <div class="text-center mb-4">
                            <i class="fa-solid fa-circle-check text-success" style="font-size: 80px;"></i>
                            <h2 class="mt-3">Order Confirmed!</h2>
                            <p class="text-muted">
                                Thank you for your purchase. Your tickets have been reserved successfully.
                            </p>
                        </div>


                        <div class="border rounded p-4 mb-4">
                            <h4 class="mb-3">Order Details</h4>

                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <strong>Order Number:</strong> {{ $order->order_number }}
                                </div>

                                <div class="col-lg-6 mb-2">
                                    <strong>Status:</strong>
                                    <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                                </div>

                                <div class="col-lg-6 mb-2">
                                    <strong>Payment Status:</strong>
                                    {{ ucfirst($order->payment_status) }}
                                </div>

                                <div class="col-lg-6 mb-2">
                                    <strong>Order Date:</strong>
                                    {{ $order->created_at->format('d M, Y h:i A') }}
                                </div>
                            </div>
                        </div>


                        <div class="border rounded p-4 mb-4">
                            <h4 class="mb-3">Event</h4>

                            <div class="row align-items-center">
                                <div class="col-lg-3">
                                    <img src="{{ asset('storage/' . $order->event->image) }}"
                                         onerror="this.src='https://via.placeholder.com/300';"
                                         class="img-fluid rounded">
                                </div>

                                <div class="col-lg-9">
                                    <h4 class="fw-bold">{{ $order->event->name }}</h4>
                                    <p class="mb-1">
                                        <i class="fa-solid fa-calendar"></i>
                                        {{ $order->event->start_date?->format('D, d M Y') }}
                                        {{ $order->event->start_time?->format('h:i A') }}
                                    </p>

                                    <p class="mb-1">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ $order->event->venue ?? 'Venue TBA' }}
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="border rounded p-4 mb-4">
                            <h4 class="mb-3">Your Tickets</h4>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Ticket</th>
                                            <th>Seat</th>
                                            <th>Price</th>
                                            <th>QR Code</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($tickets as $ticket)
                                            <tr>
                                                <td>{{ $ticket->orderItem->ticket_name }}</td>

                                                <td>
                                                    @if($ticket->seat_id)
                                                       <span class="badge bg-primary">{{ $ticket->seat->label }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">General</span>
                                                    @endif
                                                </td>

                                                <td>{{ number_format($ticket->orderItem->unit_price, 2) }}</td>

                                                <td>
                                                    {{-- If you later add real QR codes, replace this --}}
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $ticket->ticket_code }}"
                                                         class="img-fluid">
                                                    <div class="mt-1"><small>{{ $ticket->ticket_code }}</small></div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                            <div class="text-end">
                                <h4>Total Paid: {{ number_format($order->total, 2) }}</h4>
                            </div>
                        </div>

                        <div class="text-center mt-4">

                            <a href="{{ route('homepage') }}" class="main-btn btn-hover me-3">
                                Back to Home
                            </a>

                            <a href="{{ route('tickets.download', $order->order_number) }}"
                                class="main-btn btn-hover">
                                Download Tickets (PDF)
                            </a>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

</x-frontend-app-layout>
