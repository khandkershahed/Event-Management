<x-frontend-app-layout :title="'Ticket Detail'">
    @unless($isPrint)
        @include('user.layout.sidebar')
    @endunless

    <style>
        .ticket-print-card { border: 2px dashed #d9dce3; }
        .ticket-code-block { letter-spacing: .08em; word-break: break-word; }
        .qr-payload-box { word-break: break-word; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        .ticket-qr-svg { max-width: 100%; height: auto; }
        @media print {
            header, footer, .vertical_nav, .no-print { display: none !important; }
            body { background: #fff !important; }
            .wrapper { margin: 0 !important; }
            .dashboard-body { padding: 0 !important; }
            .main-card { box-shadow: none !important; }
            .ticket-print-card { border: 2px solid #111 !important; page-break-inside: avoid; }
            a[href]::after { content: "" !important; }
        }
    </style>

    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                @unless($isPrint)
                    <div class="d-main-title mb-4">
                        <h1 class="h3"><i class="fa-solid fa-ticket me-3" aria-hidden="true"></i>Ticket Detail</h1>
                        <p class="text-muted mb-0">Print or save this ticket and bring the QR payload/code to the event entrance.</p>
                    </div>
                @endunless

                <section class="main-card p-4 mx-auto ticket-print-card" style="max-width: 860px;" aria-labelledby="ticket-title">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <p class="text-uppercase text-muted small mb-1">Admission Ticket</p>
                            <h2 class="mb-1 h3" id="ticket-title">{{ $ticket->event?->name ?? 'Event removed' }}</h2>
                            <p class="text-muted mb-0">{{ $ticket->eventTicket?->name ?? $ticket->orderItem?->ticket_name ?? 'Ticket' }}</p>
                        </div>
                        <span class="badge bg-success fs-6" aria-label="Ticket status {{ ucfirst($ticket->status) }}">{{ ucfirst($ticket->status) }}</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100 d-flex align-items-center justify-content-center">
                                @include('user.partials.ticket-qr', ['ticket' => $ticket, 'size' => 156])
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100 bg-light">
                                <strong class="text-muted d-block mb-1">Ticket Code</strong>
                                <div class="fs-4 fw-bold ticket-code-block">{{ $ticket->ticket_code }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <strong class="text-muted d-block mb-1">Order Number</strong>
                                <div class="fw-semibold">{{ $ticket->order?->order_number ?? 'Order unavailable' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <strong class="text-muted d-block mb-1">Seat</strong>
                                <div>{{ $ticket->seat?->label ?? 'General admission' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <strong class="text-muted d-block mb-1">Event Date & Time</strong>
                                <div>
                                    {{ $ticket->event?->start_date?->format('d M Y') ?? $ticket->event?->start_date ?? 'Date TBA' }}
                                    @if($ticket->event?->start_time)
                                        {{ $ticket->event->start_time?->format('g:i A') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="border rounded p-3 h-100">
                                <strong class="text-muted d-block mb-1">Venue</strong>
                                <div>{{ $ticket->event?->venueRecord?->name ?? $ticket->event?->venue ?? 'Venue TBA' }}</div>
                                @if($ticket->event?->venueRecord?->city)
                                    <small class="text-muted">{{ $ticket->event->venueRecord->city }}{{ $ticket->event->venueRecord->country ? ', ' . $ticket->event->venueRecord->country : '' }}</small>
                                @endif
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-4">
                        <strong class="d-block mb-2">QR Payload Text</strong>
                        <div class="qr-payload-box small bg-light rounded p-3">{{ $ticket->qr_payload }}</div>
                        <small class="text-muted d-block mt-2">This text is the secure payload used for QR/ticket validation.</small>
                    </div>

                    <div class="alert alert-info" role="note">
                        Keep this ticket private. Entry staff may validate the ticket code or QR payload once at the event entrance.
                    </div>

                    <div class="no-print d-flex flex-wrap gap-2">
                        <button type="button" onclick="window.print()" class="main-btn btn-hover" aria-label="Print ticket {{ $ticket->ticket_code }}">Print Ticket</button>
                        @if($ticket->order)
                            <a href="{{ route('user.orders.show', $ticket->order) }}" class="btn btn-outline-secondary">Back to Order</a>
                        @endif
                        <a href="{{ route('user.tickets.index') }}" class="btn btn-outline-secondary">My Tickets</a>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @if($isPrint)
        <script>window.addEventListener('load', function () { window.print(); });</script>
    @endif
</x-frontend-app-layout>
