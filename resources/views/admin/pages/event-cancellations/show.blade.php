<x-admin-app-layout :title="'Event Cancellation Review'">
    <div class="card card-flush">
        <div class="card-header"><h2>Event Cancellation Request #{{ $cancellationRequest->id }}</h2></div>
        <div class="card-body">
            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

            <p><strong>Event:</strong> {{ $cancellationRequest->event?->name }}</p>
            <p><strong>Organizer:</strong> {{ $cancellationRequest->organizerProfile?->organization_name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($cancellationRequest->status) }}</p>
            <p><strong>Reason:</strong> {{ $cancellationRequest->reason ?: '-' }}</p>
            <p><strong>Admin Note:</strong> {{ $cancellationRequest->admin_note ?: '-' }}</p>

            <h5>Affected Orders</h5>
            <ul>
                @foreach($cancellationRequest->event?->orders ?? [] as $order)
                    <li>{{ $order->order_number }} — {{ $order->payment_status }} — {{ $order->currency }} {{ number_format((float) $order->total, 2) }}</li>
                @endforeach
            </ul>

            @if($cancellationRequest->status === \App\Models\EventCancellationRequest::STATUS_PENDING)
                <form method="POST" action="{{ route('admin.event-cancellations.approve', $cancellationRequest) }}" class="mb-3">
                    @csrf
                    <textarea name="admin_note" class="form-control mb-2" rows="3" placeholder="Approval note">{{ old('admin_note') }}</textarea>
                    <button class="btn btn-success">Approve Cancellation</button>
                </form>
                <form method="POST" action="{{ route('admin.event-cancellations.reject', $cancellationRequest) }}">
                    @csrf
                    <textarea name="admin_note" class="form-control mb-2" rows="3" placeholder="Rejection note">{{ old('admin_note') }}</textarea>
                    <button class="btn btn-danger">Reject Cancellation</button>
                </form>
            @endif

            <a href="{{ route('admin.event-cancellations.index') }}" class="btn btn-light mt-3">Back</a>
        </div>
    </div>
</x-admin-app-layout>
