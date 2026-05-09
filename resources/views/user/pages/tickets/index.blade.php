<x-frontend-app-layout :title="'My Tickets'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4">
                    <h3><i class="fa-solid fa-ticket me-3"></i>My Tickets</h3>
                </div>
                <div class="row">
                    @forelse ($tickets as $ticket)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="main-card p-4 h-100">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">{{ $ticket->event?->name ?? 'Event removed' }}</h5>
                                        <div class="text-muted small">{{ $ticket->eventTicket?->name ?? $ticket->orderItem?->ticket_name ?? 'Ticket' }}</div>
                                    </div>
                                    <span class="badge bg-success">{{ ucfirst($ticket->status) }}</span>
                                </div>
                                <hr>
                                @include('user.partials.ticket-qr', ['ticket' => $ticket, 'size' => 112])
                                <p class="mb-1 mt-3"><strong>Ticket Code:</strong> {{ $ticket->ticket_code }}</p>
                                <p class="mb-1"><strong>Seat:</strong> {{ $ticket->seat?->label ?? 'General admission' }}</p>
                                <p class="mb-3"><strong>Order:</strong> {{ $ticket->order?->order_number }}</p>
                                <div class="small bg-light p-2 rounded mb-3" style="word-break: break-word;">{{ $ticket->qr_payload }}</div>
                                <a href="{{ route('user.tickets.show', $ticket) }}" class="btn btn-sm btn-primary">View</a>
                                <a href="{{ route('user.tickets.print', $ticket) }}" class="btn btn-sm btn-outline-secondary">Print</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="main-card p-5 text-center text-muted">No issued tickets found.</div>
                        </div>
                    @endforelse
                </div>
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-frontend-app-layout>
