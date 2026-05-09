<x-frontend-app-layout :title="'My Reviews'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4 d-flex justify-content-between align-items-center">
                    <h3><i class="fa-solid fa-star me-3"></i>My Reviews</h3>
                    <a href="{{ route('all.events') }}" class="btn btn-primary">Find Events</a>
                </div>
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                <div class="main-card p-4">
                    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Event</th><th>Rating</th><th>Status</th><th>Submitted</th></tr></thead><tbody>
                        @forelse($reviews as $review)
                            <tr><td>{{ $review->event?->name ?? 'Event' }}<br><small>{{ $review->title }}</small></td><td>{{ $review->rating }}/5</td><td><span class="badge bg-secondary">{{ ucfirst($review->status) }}</span></td><td>{{ $review->created_at?->format('M d, Y') }}</td></tr>
                        @empty<tr><td colspan="4" class="text-center text-muted">No reviews yet. Open an event page after attending or completing an order to leave a review.</td></tr>@endforelse
                    </tbody></table></div>
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
