<x-frontend-app-layout :title="'Order Detail'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4">
                    <h3><i class="fa-solid fa-receipt me-3"></i>Order {{ $order->order_number }}</h3>
                </div>

                <div class="main-card p-4 mb-4">
                    <div class="row">
                        <div class="col-md-4 mb-3"><strong>Event:</strong><br>{{ $order->event?->name ?? 'Event removed' }}</div>
                        <div class="col-md-2 mb-3"><strong>Total:</strong><br>{{ $order->currency }} {{ number_format((float) $order->total, 2) }}</div>
                        <div class="col-md-2 mb-3"><strong>Status:</strong><br>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</div>
                        <div class="col-md-2 mb-3"><strong>Payment:</strong><br>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</div>
                        <div class="col-md-2 mb-3"><strong>Date:</strong><br>{{ $order->created_at?->format('d M Y') }}</div>
                    </div>

                    @if ($order->requiresPayment())
                        <div class="alert alert-warning mt-3">
                            This order is waiting for payment. Complete payment to finalize your purchase.
                        </div>
                        <a href="{{ route('frontend.payment.stripe', $order) }}" class="main-btn btn-hover">Pay Now with Stripe</a>
                    @endif
                </div>

                <div class="main-card p-4 mb-4">
                    <h5 class="mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ $item->ticket_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $order->currency }} {{ number_format((float) $item->unit_price, 2) }}</td>
                                        <td>{{ $order->currency }} {{ number_format((float) $item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="main-card p-4">
                    <h5 class="mb-3">Issued Tickets</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>QR</th>
                                    <th>Ticket Code</th>
                                    <th>Ticket Type</th>
                                    <th>Seat</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->tickets as $ticket)
                                    <tr>
                                        <td>@include('user.partials.ticket-qr', ['ticket' => $ticket, 'size' => 88])</td>
                                        <td><strong>{{ $ticket->ticket_code }}</strong><br><small class="text-muted" style="word-break:break-word">{{ $ticket->qr_payload }}</small></td>
                                        <td>{{ $ticket->eventTicket?->name ?? $ticket->orderItem?->ticket_name ?? 'Ticket' }}</td>
                                        <td>{{ $ticket->seat?->label ?? 'General admission' }}</td>
                                        <td>{{ ucfirst($ticket->status) }}</td>
                                        <td><a href="{{ route('user.tickets.show', $ticket) }}" class="btn btn-sm btn-primary">View Ticket</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No tickets issued for this order.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
