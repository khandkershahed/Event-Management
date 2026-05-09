<x-admin-app-layout :title="'Refund Review'">
    <div class="card card-flush">
        <div class="card-header"><h2>Refund Request #{{ $refund->id }}</h2></div>
        <div class="card-body">
            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Order:</strong> {{ $refund->order?->order_number }}</p>
                    <p><strong>Customer:</strong> {{ $refund->user?->name ?? $refund->order?->customer_name }}</p>
                    <p><strong>Event:</strong> {{ $refund->event?->name ?? '-' }}</p>
                    <p><strong>Amount:</strong> {{ $refund->currency }} {{ number_format((float) $refund->amount, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> {{ ucfirst($refund->status) }}</p>
                    <p><strong>Reason:</strong> {{ $refund->reason ?: '-' }}</p>
                    <p><strong>Admin Note:</strong> {{ $refund->admin_note ?: '-' }}</p>
                </div>
            </div>

            <h5>Tickets</h5>
            <ul>
                @foreach($refund->order?->tickets ?? [] as $ticket)
                    <li>{{ $ticket->ticket_code }} — {{ ucfirst($ticket->status) }}</li>
                @endforeach
            </ul>

            @if($refund->status === \App\Models\RefundRequest::STATUS_PENDING)
                <form method="POST" action="{{ route('admin.refunds.approve', $refund) }}" class="mb-3">
                    @csrf
                    <textarea name="admin_note" class="form-control mb-2" rows="3" placeholder="Approval note">{{ old('admin_note') }}</textarea>
                    <button class="btn btn-success">Approve Refund</button>
                </form>
                <form method="POST" action="{{ route('admin.refunds.reject', $refund) }}">
                    @csrf
                    <textarea name="admin_note" class="form-control mb-2" rows="3" placeholder="Rejection note">{{ old('admin_note') }}</textarea>
                    <button class="btn btn-danger">Reject Refund</button>
                </form>
            @endif

            <a href="{{ route('admin.refunds.index') }}" class="btn btn-light mt-3">Back</a>
        </div>
    </div>
</x-admin-app-layout>
