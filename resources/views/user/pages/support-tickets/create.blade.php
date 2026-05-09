<x-frontend-app-layout :title="'Create Support Ticket'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4"><h3><i class="fa-solid fa-headset me-3"></i>Create Support Ticket</h3></div>
                <div class="main-card p-4">
                    <form method="POST" action="{{ route('user.support-tickets.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    @foreach($types as $type)<option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    @foreach($priorities as $priority)<option value="{{ $priority }}" @selected(old('priority', 'normal') === $priority)>{{ ucfirst($priority) }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Related Order</label>
                                <select name="order_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach($orders as $order)<option value="{{ $order->id }}" @selected((int) old('order_id') === (int) $order->id)>{{ $order->order_number }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Related Event</label>
                                <select name="event_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach($events as $event)<option value="{{ $event->id }}" @selected((int) old('event_id') === (int) $event->id)>{{ $event->name }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Related Refund</label>
                                <select name="refund_request_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach($refunds as $refund)<option value="{{ $refund->id }}" @selected((int) old('refund_request_id') === (int) $refund->id)>Refund #{{ $refund->id }} - {{ ucfirst($refund->status) }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-12"><label class="form-label">Subject</label><input name="subject" value="{{ old('subject') }}" class="form-control" required></div>
                            <div class="col-md-12"><label class="form-label">Description</label><textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea></div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Submit Ticket</button>
                            <a href="{{ route('user.support-tickets.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>
