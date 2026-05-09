@extends('organizer.layouts.app', ['title' => 'Request Event Cancellation'])
@section('content')
<div class="card">
    <h3>Request Event Cancellation</h3>
    <p><strong>Event:</strong> {{ $event->name }}</p>
    <p><strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $event->status)) }}</p>

    @if($hasOpenRequest)
        <div class="alert alert-warning">A pending cancellation request already exists for this event.</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('organizer.events.cancellation.store', $event) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Cancellation Reason</label>
            <textarea name="reason" class="form-control" rows="5">{{ old('reason') }}</textarea>
        </div>
        <button class="btn btn-danger" @disabled($hasOpenRequest)>Submit for Admin Review</button>
        <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-light">Back</a>
    </form>

    @if($event->cancellationRequests->count())
        <hr>
        <h5>Previous Requests</h5>
        <ul>
            @foreach($event->cancellationRequests as $request)
                <li>#{{ $request->id }} — {{ ucfirst($request->status) }} — {{ $request->created_at?->format('d M Y H:i') }}</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
