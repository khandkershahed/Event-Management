@extends('organizer.layouts.app', ['title' => 'Create Support Ticket'])

@section('content')
<div class="card">
    <h2>Create Support Ticket</h2>
    <form method="POST" action="{{ route('organizer.support-tickets.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Type</label>
                <select name="type" class="form-control" required>
                    @foreach($types as $type)<option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    @foreach($priorities as $priority)<option value="{{ $priority }}" @selected(old('priority', 'normal') === $priority)>{{ ucfirst($priority) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Related Payout</label>
                <select name="organizer_payout_id" class="form-control">
                    <option value="">None</option>
                    @foreach($payouts as $payout)<option value="{{ $payout->id }}" @selected((int) old('organizer_payout_id') === (int) $payout->id)>{{ $payout->payout_number }} - {{ ucfirst($payout->status) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label>Related Event</label>
                <select name="event_id" class="form-control">
                    <option value="">None</option>
                    @foreach($events as $event)<option value="{{ $event->id }}" @selected((int) old('event_id') === (int) $event->id)>{{ $event->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3"><label>Subject</label><input name="subject" value="{{ old('subject') }}" class="form-control" required></div>
            <div class="col-md-12 mb-3"><label>Description</label><textarea name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea></div>
        </div>
        <button class="btn btn-primary">Submit Ticket</button>
        <a href="{{ route('organizer.support-tickets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
