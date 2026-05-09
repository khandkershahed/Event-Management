<x-frontend-app-layout>
    <div class="breadcrumb-block">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                <li class="breadcrumb-item active">Order Success</li>
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

            <div class="main-card p-4">
                <h3 class="mb-2">Order Created</h3>
                <p class="text-muted mb-4">Order Number: <strong>{{ $order->order_number }}</strong></p>

                <div class="row mb-4">
                    <div class="col-md-3"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</div>
                    <div class="col-md-3"><strong>Payment:</strong> {{ ucfirst($order->payment_status) }}</div>
                    <div class="col-md-3"><strong>Total:</strong> {{ $order->currency }} {{ number_format((float) $order->total, 2) }}</div>
                    <div class="col-md-3"><strong>Event:</strong> {{ $order->event?->name }}</div>
                </div>

                @if ($order->requiresPayment())
                    <div class="alert alert-warning">
                        This is a paid order and is waiting for payment. You can pay securely with Stripe now.
                    </div>
                    <a href="{{ route('frontend.payment.stripe', $order) }}" class="main-btn btn-hover mb-4">
                        Pay Now with Stripe
                    </a>
                @elseif ($order->payment_status === \App\Models\Order::PAYMENT_FAILED)
                    <div class="alert alert-danger">
                        The last payment attempt failed or was cancelled. You can try again.
                    </div>
                    <a href="{{ route('frontend.payment.stripe', $order) }}" class="main-btn btn-hover mb-4">
                        Try Payment Again
                    </a>
                @else
                    <div class="alert alert-success">
                        This order is paid/completed and tickets have been issued.
                    </div>
                @endif

                <h5 class="mt-4">Issued Tickets</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>QR</th>
                                <th>Ticket Code</th>
                                <th>Seat</th>
                                <th>Status</th>
                                <th>QR Payload</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->tickets as $ticket)
                                <tr>
                                    <td>@include('user.partials.ticket-qr', ['ticket' => $ticket, 'size' => 88])</td>
                                    <td><strong>{{ $ticket->ticket_code }}</strong></td>
                                    <td>{{ $ticket->seat?->label ?? 'General admission' }}</td>
                                    <td>{{ ucfirst($ticket->status) }}</td>
                                    <td><small>{{ $ticket->qr_payload }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted">No tickets found for this order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('all.events') }}" class="main-btn btn-hover mt-3">Browse More Events</a>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
