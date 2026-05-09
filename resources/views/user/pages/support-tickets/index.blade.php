<x-frontend-app-layout :title="'My Support Tickets'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4 d-flex justify-content-between align-items-center">
                    <h3><i class="fa-solid fa-headset me-3"></i>My Support Tickets</h3>
                    <a href="{{ route('user.support-tickets.create') }}" class="btn btn-primary">Create Ticket</a>
                </div>
                <div class="main-card p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No.</th><th>Subject</th><th>Type</th><th>Status</th><th>Priority</th><th>Last Reply</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->ticket_number }}</td>
                                        <td>{{ $ticket->subject }}</td>
                                        <td>{{ ucfirst($ticket->type) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                                        <td>{{ ucfirst($ticket->priority) }}</td>
                                        <td>{{ optional($ticket->last_replied_at)->format('d M Y, h:i A') }}</td>
                                        <td><a href="{{ route('user.support-tickets.show', $ticket) }}" class="btn btn-sm btn-primary">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-5">No support tickets found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
