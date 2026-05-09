<x-frontend-app-layout :title="'Request Refund'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4">
                    <h3><i class="fa-solid fa-rotate-left me-3"></i>Request Refund</h3>
                </div>
                <div class="main-card p-4">
                    <p><strong>Order:</strong> {{ $order->order_number }}</p>
                    <p><strong>Event:</strong> {{ $order->event?->name ?? '-' }}</p>
                    <p><strong>Amount:</strong> {{ $order->currency }} {{ number_format((float) $order->total, 2) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }} / {{ ucfirst($order->payment_status) }}</p>

                    @if($hasOpenRefund)
                        <div class="alert alert-warning">A pending refund request already exists for this order.</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('user.orders.refund.store', $order) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="4">{{ old('reason') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" @disabled($hasOpenRefund)>Submit Refund Request</button>
                        <a href="{{ route('user.orders.show', $order) }}" class="btn btn-light">Back to Order</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
