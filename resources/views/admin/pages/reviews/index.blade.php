<x-admin-app-layout :title="'Marketplace Reviews'">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5"><div class="card-title"><h2>Marketplace Reviews</h2></div></div>
        <div class="card-body">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            <form method="GET" class="row g-3 mb-5">
                <div class="col-md-2"><select name="status" class="form-select form-select-sm"><option value="">All Status</option>@foreach(\App\Models\MarketplaceEventReview::statuses() as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="rating" class="form-select form-select-sm"><option value="">All Ratings</option>@foreach([5,4,3,2,1] as $rating)<option value="{{ $rating }}" @selected(request('rating')==$rating)>{{ $rating }}/5</option>@endforeach</select></div>
                <div class="col-md-2"><select name="event_id" class="form-select form-select-sm"><option value="">All Events</option>@foreach($events as $event)<option value="{{ $event->id }}" @selected(request('event_id')==$event->id)>{{ $event->name }}</option>@endforeach</select></div>
                <div class="col-md-2"><select name="organizer_id" class="form-select form-select-sm"><option value="">All Organizers</option>@foreach($organizers as $organizer)<option value="{{ $organizer->id }}" @selected(request('organizer_id')==$organizer->id)>{{ $organizer->organization_name }}</option>@endforeach</select></div>
                <div class="col-md-2"><input type="number" name="user_id" value="{{ request('user_id') }}" class="form-control form-control-sm" placeholder="User ID"></div>
                <div class="col-md-2"><button class="btn btn-sm btn-primary w-100">Filter</button></div>
            </form>
            <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Review</th><th>Event</th><th>Organizer</th><th>User</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td><strong>{{ $review->rating }}/5 — {{ $review->title }}</strong><br>{{ $review->body }}<br><small class="text-muted">{{ $review->created_at?->format('M d, Y') }}</small></td>
                        <td>{{ $review->event?->name }}</td><td>{{ $review->organizerProfile?->organization_name }}</td><td>{{ $review->user?->name }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($review->status) }}</span></td>
                        <td style="min-width:260px">
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="d-inline">@csrf<input type="hidden" name="admin_note" value="Rejected by admin"><button class="btn btn-sm btn-warning">Reject</button></form>
                            <form method="POST" action="{{ route('admin.reviews.hide', $review) }}" class="d-inline">@csrf<input type="hidden" name="admin_note" value="Hidden by admin"><button class="btn btn-sm btn-danger">Hide</button></form>
                        </td>
                    </tr>
                @empty<tr><td colspan="6" class="text-center text-muted">No reviews found.</td></tr>@endforelse
            </tbody></table></div>
            {{ $reviews->links() }}
        </div>
    </div>
</x-admin-app-layout>
