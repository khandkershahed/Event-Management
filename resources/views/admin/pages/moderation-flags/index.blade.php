<x-admin-app-layout :title="'Moderation Flags'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title"><h2>Moderation Flags</h2></div>
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach
                </select>
                <button class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Flag events or organizers from their admin pages by posting to the moderation flag routes. This page tracks all moderation decisions.</div>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-3">
                    <thead><tr class="text-gray-500 fw-bold fs-7 text-uppercase"><th>ID</th><th>Target</th><th>Reason</th><th>Status</th><th>Flagged By</th><th>Reviewed</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($flags as $flag)
                            <tr>
                                <td>#{{ $flag->id }}</td>
                                <td>{{ class_basename($flag->flaggable_type) }} #{{ $flag->flaggable_id }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($flag->reason, 80) }}</td>
                                <td><span class="badge badge-light-primary">{{ ucfirst($flag->status) }}</span></td>
                                <td>{{ $flag->flaggedBy?->name }}</td>
                                <td>{{ optional($flag->reviewed_at)->format('d M Y H:i') ?: 'Pending' }}</td>
                                <td>
                                    @if($flag->status === \App\Models\MarketplaceModerationFlag::STATUS_ACTIVE)
                                        <form method="POST" action="{{ route('admin.moderation-flags.resolve', $flag) }}" class="d-inline">@csrf<button class="btn btn-sm btn-light-success">Resolve</button></form>
                                        <form method="POST" action="{{ route('admin.moderation-flags.unflag', $flag) }}" class="d-inline">@csrf<button class="btn btn-sm btn-light-danger">Unflag</button></form>
                                    @else
                                        <span class="text-muted">Reviewed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-5">No moderation flags found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $flags->links() }}
        </div>
    </div>
</x-admin-app-layout>
