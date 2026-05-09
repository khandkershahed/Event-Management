<x-frontend-app-layout :title="'Write Review'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4"><h3><i class="fa-solid fa-star me-3"></i>Review: {{ $event->name }}</h3></div>
                <div class="main-card p-4">
                    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                    @if($orders->isEmpty())
                        <div class="alert alert-warning mb-0">You can review this event only after a completed order or valid attendance.</div>
                    @else
                        <form method="POST" action="{{ route('user.event-reviews.store', $event) }}">
                            @csrf
                            <div class="mb-3"><label class="form-label">Related Order</label><select name="order_id" class="form-select">@foreach($orders as $order)<option value="{{ $order->id }}">{{ $order->order_number }} — {{ $order->currency }} {{ number_format((float) $order->total, 2) }}</option>@endforeach</select></div>
                            <div class="mb-3"><label class="form-label">Rating</label><select name="rating" class="form-select" required>@foreach([5,4,3,2,1] as $rating)<option value="{{ $rating }}">{{ $rating }} / 5</option>@endforeach</select></div>
                            <div class="mb-3"><label class="form-label">Title</label><input name="title" class="form-control" value="{{ old('title') }}"></div>
                            <div class="mb-3"><label class="form-label">Review</label><textarea name="body" rows="5" class="form-control">{{ old('body') }}</textarea></div>
                            <button class="main-btn btn-hover" type="submit">Submit Review</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
