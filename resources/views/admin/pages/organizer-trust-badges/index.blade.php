<x-admin-app-layout>
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Organizer Trust Badges</h1>
                    </div>
                </div>
            </div>
            <div class="app-content flex-column-fluid">
                <div class="app-container container-xxl">
                    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                    <div class="card mb-5">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-4"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search organizer, slug, email"></div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">All statuses</option>
                                        @foreach(['approved','pending','suspended','rejected','draft'] as $status)
                                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2"><button class="btn btn-primary">Filter</button></div>
                                <div class="col-md-2"><a href="{{ route('admin.organizer-trust-badges.index') }}" class="btn btn-light">Clear</a></div>
                            </form>
                        </div>
                    </div>

                    @foreach($organizers as $organizer)
                        <div class="card mb-5">
                            <div class="card-header">
                                <div class="card-title">
                                    <div>
                                        <h3 class="mb-1">{{ $organizer->organization_name }}</h3>
                                        <small class="text-muted">{{ $organizer->email }} · Followers: {{ $organizer->followers_count }} · Rating: {{ number_format((float) ($organizer->ratingSummary?->average_rating ?? 0), 2) }}/5</small>
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('public.organizers.show', $organizer->slug) }}" target="_blank" class="btn btn-sm btn-light-primary">Public Profile</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5>Current Badges</h5>
                                <div class="table-responsive mb-5">
                                    <table class="table table-row-bordered align-middle">
                                        <thead><tr><th>Badge</th><th>Status</th><th>Public</th><th>Note</th><th>Action</th></tr></thead>
                                        <tbody>
                                            @forelse($organizer->trustBadges as $badge)
                                                <tr>
                                                    <td><strong>{{ $badge->label }}</strong><br><small>{{ $badge->badge_key }}</small></td>
                                                    <td><span class="badge bg-secondary">{{ ucfirst($badge->status) }}</span></td>
                                                    <td>{{ $badge->is_public ? 'Yes' : 'No' }}</td>
                                                    <td>{{ $badge->admin_note }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.organizer-trust-badges.destroy', $badge) }}" onsubmit="return confirm('Remove this trust badge?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-danger">Remove</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-muted">No trust badges yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <form method="POST" action="{{ route('admin.organizer-trust-badges.store', $organizer) }}" class="row g-3">
                                    @csrf
                                    <div class="col-md-3">
                                        <label class="form-label">Badge</label>
                                        <select name="badge_key" class="form-select" required>
                                            @foreach($availableBadges as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Label</label>
                                        <input type="text" name="label" class="form-control" placeholder="Optional custom label">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Public</label>
                                        <select name="is_public" class="form-select">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Description</label>
                                        <input type="text" name="description" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Admin Note</label>
                                        <textarea name="admin_note" class="form-control" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12"><button class="btn btn-primary">Save Badge</button></div>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    {{ $organizers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-app-layout>
