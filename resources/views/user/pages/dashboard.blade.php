<x-frontend-app-layout :title="'Customer Dashboard'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-main-title">
                            <h3><i class="fa-solid fa-gauge me-3"></i>Customer Dashboard</h3>
                            <p class="text-muted mb-0">Your live marketplace activity, tickets, support, refunds, and notifications in one place.</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('all.events') }}" class="btn btn-primary btn-sm me-2">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Discover Events
                        </a>
                        <a href="{{ route('user.notifications.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa-solid fa-bell me-1"></i> Notifications
                            @if(($stats['unread_notifications'] ?? 0) > 0)
                                <span class="badge bg-danger ms-1">{{ $stats['unread_notifications'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                @if(! ($hasMarketplaceActivity ?? false))
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="main-card p-4 border border-primary-subtle bg-light">
                                <h5 class="mb-2">Welcome to your marketplace dashboard</h5>
                                <p class="text-muted mb-3">You do not have marketplace activity yet. Start by discovering events, saving events you like, or following an organizer.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('all.events') }}" class="btn btn-primary btn-sm">Discover Events</a>
                                    <a href="{{ route('user.saved-events.index') }}" class="btn btn-outline-primary btn-sm">Saved Events</a>
                                    <a href="{{ route('user.followed-organizers.index') }}" class="btn btn-outline-primary btn-sm">Followed Organizers</a>
                                    <a href="{{ route('user.support-tickets.create') }}" class="btn btn-outline-primary btn-sm">Create Support Ticket</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mt-4">
                    @php
                        $statCards = [
                            ['label' => 'Total Orders', 'value' => $stats['total_orders'] ?? 0, 'icon' => 'fa-receipt', 'route' => route('user.orders.index')],
                            ['label' => 'Paid / Completed', 'value' => $stats['paid_orders'] ?? 0, 'icon' => 'fa-circle-check', 'route' => route('user.orders.index')],
                            ['label' => 'Pending Payments', 'value' => $stats['pending_payment_orders'] ?? 0, 'icon' => 'fa-clock', 'route' => route('user.orders.index')],
                            ['label' => 'Issued Tickets', 'value' => $stats['issued_tickets'] ?? 0, 'icon' => 'fa-ticket', 'route' => route('user.tickets.index')],
                            ['label' => 'Upcoming Tickets', 'value' => $stats['upcoming_tickets'] ?? 0, 'icon' => 'fa-calendar-day', 'route' => route('user.tickets.index')],
                            ['label' => 'Upcoming Events', 'value' => $stats['upcoming_events'] ?? 0, 'icon' => 'fa-calendar-check', 'route' => route('user.tickets.index')],
                            ['label' => 'Saved Events', 'value' => $stats['saved_events'] ?? 0, 'icon' => 'fa-bookmark', 'route' => route('user.saved-events.index')],
                            ['label' => 'Followed Organizers', 'value' => $stats['followed_organizers'] ?? 0, 'icon' => 'fa-user-check', 'route' => route('user.followed-organizers.index')],
                            ['label' => 'Support Tickets', 'value' => $stats['support_tickets'] ?? 0, 'icon' => 'fa-headset', 'route' => route('user.support-tickets.index')],
                            ['label' => 'Open Support', 'value' => $stats['open_support_tickets'] ?? 0, 'icon' => 'fa-comments', 'route' => route('user.support-tickets.index')],
                            ['label' => 'Refund Requests', 'value' => $stats['refund_requests'] ?? 0, 'icon' => 'fa-rotate-left', 'route' => route('user.refunds.index')],
                            ['label' => 'Unread Notifications', 'value' => $stats['unread_notifications'] ?? 0, 'icon' => 'fa-bell', 'route' => route('user.notifications.index')],
                        ];
                    @endphp
                    @foreach($statCards as $card)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a href="{{ $card['route'] }}" class="text-decoration-none text-dark">
                                <div class="main-card p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-muted small">{{ $card['label'] }}</div>
                                            <h3 class="mb-0">{{ $card['value'] }}</h3>
                                        </div>
                                        <i class="fa-solid {{ $card['icon'] }} fa-2x text-primary opacity-75"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="row mt-3">
                    <div class="col-lg-7 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Recent Orders</h5>
                                <a href="{{ route('user.orders.index') }}" class="btn btn-sm btn-outline-primary">My Orders</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Event</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentOrders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->event?->name ?? 'Event removed' }}</td>
                                                <td>{{ $order->currency ?? 'BDT' }} {{ number_format((float) $order->total, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                                    <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                                                </td>
                                                <td><a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center text-muted py-4">No orders yet. <a href="{{ route('all.events') }}">Discover events</a>.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Upcoming Tickets</h5>
                                <a href="{{ route('user.tickets.index') }}" class="btn btn-sm btn-outline-primary">My Tickets</a>
                            </div>
                            @forelse (($upcomingTickets ?? collect()) as $ticket)
                                <div class="border rounded p-3 mb-2">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-bold">{{ $ticket->event?->name ?? 'Event removed' }}</div>
                                            <div class="text-muted small">{{ $ticket->event?->start_date?->format('M d, Y') ?? 'Date TBA' }} · {{ $ticket->ticket_code }}</div>
                                        </div>
                                        <a href="{{ route('user.tickets.show', $ticket) }}" class="small">Open</a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No upcoming tickets yet. <a href="{{ route('all.events') }}">Find your next event</a>.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Recent Tickets</h5>
                                <a href="{{ route('user.tickets.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            @forelse ($recentTickets as $ticket)
                                <div class="border rounded p-3 mb-2">
                                    <div class="fw-bold">{{ $ticket->ticket_code }}</div>
                                    <div class="text-muted small">{{ $ticket->event?->name ?? 'Event removed' }} · {{ $ticket->eventTicket?->name ?? 'Ticket type removed' }}</div>
                                    <a href="{{ route('user.tickets.show', $ticket) }}" class="small">Open ticket</a>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No issued tickets yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Recently Saved Events</h5>
                                <div>
                                    <a href="{{ route('user.discovery.index') }}" class="btn btn-sm btn-outline-primary">Recommended</a>
                                    <a href="{{ route('user.saved-events.index') }}" class="btn btn-sm btn-primary">View Saved</a>
                                </div>
                            </div>
                            <div class="row">
                                @forelse (($recentSavedEvents ?? collect()) as $savedEvent)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3 h-100">
                                            <div class="fw-bold">{{ $savedEvent->event?->name ?? 'Event removed' }}</div>
                                            <div class="text-muted small">{{ $savedEvent->event?->start_date?->format('M d, Y') ?? 'Date TBA' }}</div>
                                            @if($savedEvent->event)
                                                <a href="{{ route('event.details', $savedEvent->event->slug) }}" class="small">Open event</a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12"><p class="text-muted mb-0">No saved events yet. <a href="{{ route('all.events') }}">Browse events</a>.</p></div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Followed Organizers</h5>
                                <a href="{{ route('user.followed-organizers.index') }}" class="btn btn-sm btn-outline-primary">View Followed</a>
                            </div>
                            @forelse (($recentFollowedOrganizers ?? collect()) as $follow)
                                <div class="border rounded p-3 mb-2">
                                    <div class="fw-bold">{{ $follow->organizerProfile?->organization_name ?? 'Organizer removed' }}</div>
                                    <div class="text-muted small">Following since {{ $follow->created_at?->format('M d, Y') }}</div>
                                    @if($follow->organizerProfile?->slug)
                                        <a href="{{ route('public.organizers.show', $follow->organizerProfile->slug) }}" class="small">Open organizer</a>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted mb-0">No followed organizers yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Recent Notifications</h5>
                                <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-outline-primary">View Notifications</a>
                            </div>
                            @forelse (($recentNotifications ?? collect()) as $notification)
                                @php($notificationData = $notification->data ?? [])
                                <div class="border rounded p-3 mb-2 {{ $notification->read_at ? '' : 'bg-light' }}">
                                    <div class="fw-bold">{{ $notificationData['title'] ?? class_basename($notification->type) }}</div>
                                    <div class="text-muted small">{{ $notificationData['message'] ?? 'Notification update' }}</div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No notifications yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Recent Support Tickets</h5>
                                <a href="{{ route('user.support-tickets.index') }}" class="btn btn-sm btn-outline-primary">View Support</a>
                            </div>
                            @forelse (($recentSupportTickets ?? collect()) as $supportTicket)
                                <div class="border rounded p-3 mb-2">
                                    <div class="fw-bold">{{ $supportTicket->subject }}</div>
                                    <div class="text-muted small">{{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }} · {{ ucfirst($supportTicket->priority) }}</div>
                                    <a href="{{ route('user.support-tickets.show', $supportTicket) }}" class="small">Open ticket</a>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No support tickets yet. <a href="{{ route('user.support-tickets.create') }}">Create one</a>.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="main-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Recent Refund Requests</h5>
                                <a href="{{ route('user.refunds.index') }}" class="btn btn-sm btn-outline-primary">View Refunds</a>
                            </div>
                            @forelse (($recentRefunds ?? collect()) as $refund)
                                <div class="border rounded p-3 mb-2">
                                    <div class="fw-bold">Order {{ $refund->order?->order_number ?? '#' . $refund->order_id }}</div>
                                    <div class="text-muted small">{{ ucfirst(str_replace('_', ' ', $refund->status)) }} · {{ $refund->currency ?? 'BDT' }} {{ number_format((float) $refund->amount, 2) }}</div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No refund requests yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <div class="main-card p-4">
                            <h5 class="mb-3">Quick Links</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('user.orders.index') }}" class="btn btn-outline-primary btn-sm">My Orders</a>
                                <a href="{{ route('user.tickets.index') }}" class="btn btn-outline-primary btn-sm">My Tickets</a>
                                <a href="{{ route('user.saved-events.index') }}" class="btn btn-outline-primary btn-sm">Saved Events</a>
                                <a href="{{ route('user.followed-organizers.index') }}" class="btn btn-outline-primary btn-sm">Followed Organizers</a>
                                <a href="{{ route('user.support-tickets.index') }}" class="btn btn-outline-primary btn-sm">Support Tickets</a>
                                <a href="{{ route('user.refunds.index') }}" class="btn btn-outline-primary btn-sm">Refunds</a>
                                <a href="{{ route('user.notifications.index') }}" class="btn btn-outline-primary btn-sm">Notifications</a>
                                <a href="{{ route('all.events') }}" class="btn btn-primary btn-sm">Discover Events</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
