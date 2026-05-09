<x-admin-app-layout :title="'Support Tickets'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title"><h2>Support Tickets</h2></div>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 mb-5">
                <div class="col-md-2"><select name="status" class="form-select form-select-sm"><option value="">All Statuses</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="priority" class="form-select form-select-sm"><option value="">All Priorities</option>@foreach($priorities as $priority)<option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="type" class="form-select form-select-sm"><option value="">All Types</option>@foreach($types as $type)<option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>@endforeach</select></div>
                <div class="col-md-1"><input name="user_id" value="{{ request('user_id') }}" class="form-control form-control-sm" placeholder="User ID"></div>
                <div class="col-md-1"><input name="organizer_id" value="{{ request('organizer_id') }}" class="form-control form-control-sm" placeholder="Org ID"></div>
                <div class="col-md-1"><input name="event_id" value="{{ request('event_id') }}" class="form-control form-control-sm" placeholder="Event ID"></div>
                <div class="col-md-1"><input name="order_id" value="{{ request('order_id') }}" class="form-control form-control-sm" placeholder="Order ID"></div>
                <div class="col-md-2"><button class="btn btn-sm btn-primary">Filter</button><a href="{{ route('admin.support-tickets.index') }}" class="btn btn-sm btn-light">Clear</a></div>
            </form>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>No.</th><th>Subject</th><th>Requester</th><th>Type</th><th>Status</th><th>Priority</th><th>Updated</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>{{ $ticket->user?->name ?: $ticket->organizerProfile?->organization_name }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $ticket->type)) }}</td>
                                <td><span class="badge badge-light-primary">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span></td>
                                <td>{{ ucfirst($ticket->priority) }}</td>
                                <td>{{ optional($ticket->last_replied_at ?? $ticket->updated_at)->format('d M Y H:i') }}</td>
                                <td><a href="{{ route('admin.support-tickets.show', $ticket) }}" class="btn btn-sm btn-light-primary">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-5">No support tickets found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $tickets->links() }}
        </div>
    </div>
</x-admin-app-layout>
