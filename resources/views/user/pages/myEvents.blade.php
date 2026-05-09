<x-frontend-app-layout :title="'My Marketplace Activity'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4">
                    <h3><i class="fa-solid fa-calendar-days me-3"></i>My Marketplace Activity</h3>
                    <p class="text-muted mb-0">Your purchased tickets, saved events, and followed organizers are shown from real database records.</p>
                </div>

                <div class="row">
                    @include('user.pages.partials.panel-stat', ['label' => 'Orders', 'value' => $summary['orders'] ?? 0, 'icon' => 'fa-receipt'])
                    @include('user.pages.partials.panel-stat', ['label' => 'Tickets', 'value' => $summary['tickets'] ?? 0, 'icon' => 'fa-ticket'])
                    @include('user.pages.partials.panel-stat', ['label' => 'Saved Events', 'value' => $summary['saved_events'] ?? 0, 'icon' => 'fa-bookmark'])
                    @include('user.pages.partials.panel-stat', ['label' => 'Followed Organizers', 'value' => $summary['followed_organizers'] ?? 0, 'icon' => 'fa-user-check'])
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Upcoming / Recent Tickets</h5>
                                <a href="{{ route('user.tickets.index') }}" class="btn btn-sm btn-outline-primary">All Tickets</a>
                            </div>
                            @forelse ($tickets as $ticket)
                                <div class="border rounded p-3 mb-2">
                                    <strong>{{ $ticket->event?->name ?? 'Event removed' }}</strong>
                                    <div class="small text-muted">{{ $ticket->eventTicket?->name ?? 'Ticket' }} • {{ $ticket->ticket_code }}</div>
                                    <a href="{{ route('user.tickets.show', $ticket) }}" class="small">View ticket</a>
                                </div>
                            @empty
                                @include('user.pages.partials.panel-empty', ['message' => 'You do not have tickets yet.', 'url' => route('all.events'), 'label' => 'Browse Events'])
                            @endforelse
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Saved Events</h5>
                                <a href="{{ route('user.saved-events.index') }}" class="btn btn-sm btn-outline-primary">Saved Events</a>
                            </div>
                            @forelse ($savedEvents as $saved)
                                <div class="border rounded p-3 mb-2">
                                    <strong>{{ $saved->event?->name ?? 'Event removed' }}</strong>
                                    <div class="small text-muted">{{ $saved->event?->start_date?->format('d M Y') ?? 'Date TBA' }}</div>
                                    @if ($saved->event)
                                        <a href="{{ route('event.details', $saved->event->slug) }}" class="small">View event</a>
                                    @endif
                                </div>
                            @empty
                                @include('user.pages.partials.panel-empty', ['message' => 'No saved events yet.', 'url' => route('all.events'), 'label' => 'Discover Events'])
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
