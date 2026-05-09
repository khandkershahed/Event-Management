<x-admin-app-layout :title="'Audit Logs'">
    <div class="mb-5">
        <h1 class="fs-2 fw-bold mb-1">Audit Logs</h1>
        <p class="text-muted mb-0">Review sensitive marketplace actions performed by admins, organizers, and customers.</p>
    </div>

    <div class="card card-flush mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Action</label>
                    <input type="text" name="action" value="{{ request('action') }}" class="form-control form-control-sm" placeholder="event.approved">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Actor Guard</label>
                    <select name="actor_guard" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach($guards as $guard)
                            <option value="{{ $guard }}" @selected(request('actor_guard') === $guard)>{{ ucfirst($guard) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Auditable Type</label>
                    <input type="text" name="auditable_type" value="{{ request('auditable_type') }}" class="form-control form-control-sm" placeholder="Event, RefundRequest">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-12 d-flex gap-2">
                    <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                    <a class="btn btn-sm btn-light" href="{{ route('admin.audit-logs.index') }}">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-flush">
        <div class="card-body table-responsive">
            <table class="table align-middle table-row-dashed">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>Date</th>
                        <th>Action</th>
                        <th>Actor</th>
                        <th>Auditable</th>
                        <th>Description</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-nowrap">{{ $log->created_at?->format('d M Y, h:i A') }}</td>
                            <td><span class="badge badge-light-primary">{{ $log->action }}</span></td>
                            <td>
                                <div>{{ class_basename($log->actor_type) ?: 'System' }} #{{ $log->actor_id ?: '-' }}</div>
                                <small class="text-muted">{{ $log->actor_guard ?: 'n/a' }}</small>
                            </td>
                            <td>
                                <div>{{ class_basename($log->auditable_type) ?: '-' }}</div>
                                <small class="text-muted">#{{ $log->auditable_id ?: '-' }}</small>
                            </td>
                            <td>{{ $log->description ?: '-' }}</td>
                            <td>{{ $log->ip_address ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-8">No audit logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $logs->links() }}
        </div>
    </div>
</x-admin-app-layout>
