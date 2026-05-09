@extends('organizer.layouts.app', ['title' => 'Organizer Reviews'])

@section('content')
    <div class="row mb-4">
        <div class="col-md-4"><div class="card stat-card"><div class="card-body"><h6>Average Rating</h6><h2>{{ number_format((float) $ratingSummary->average_rating, 2) }}/5</h2></div></div></div>
        <div class="col-md-4"><div class="card stat-card"><div class="card-body"><h6>Approved Reviews</h6><h2>{{ $ratingSummary->approved_reviews_count }}</h2></div></div></div>
    </div>
    <div class="card stat-card"><div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">Event Reviews</h2></div>
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><select name="status" class="form-select"><option value="">All Status</option>@foreach(\App\Models\MarketplaceEventReview::statuses() as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
            <div class="col-md-3"><select name="rating" class="form-select"><option value="">All Ratings</option>@foreach([5,4,3,2,1] as $rating)<option value="{{ $rating }}" @selected(request('rating')==$rating)>{{ $rating }}/5</option>@endforeach</select></div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div>
        </form>
        <div class="table-responsive"><table class="table table-sm"><thead><tr><th>Event</th><th>User</th><th>Rating</th><th>Status</th><th>Review</th><th>Date</th></tr></thead><tbody>
            @forelse($reviews as $review)
                <tr><td>{{ $review->event?->name }}</td><td>{{ $review->user?->name }}</td><td>{{ $review->rating }}/5</td><td>{{ ucfirst($review->status) }}</td><td><strong>{{ $review->title }}</strong><br>{{ $review->body }}</td><td>{{ $review->created_at?->format('M d, Y') }}</td></tr>
            @empty<tr><td colspan="6" class="text-center text-muted">No reviews found.</td></tr>@endforelse
        </tbody></table></div>
        {{ $reviews->links() }}
    </div></div>
@endsection
