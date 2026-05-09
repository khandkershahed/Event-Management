@extends('organizer.layouts.app', ['title' => $supportTicket->ticket_number])

@section('content')
<div class="card">
    <h2>{{ $supportTicket->ticket_number }} - {{ $supportTicket->subject }}</h2>
    <p>Status: <strong>{{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}</strong> | Priority: <strong>{{ ucfirst($supportTicket->priority) }}</strong></p>
    <p>{{ $supportTicket->description }}</p>
</div>

<div class="card">
    <h3>Messages</h3>
    @foreach($supportTicket->messages as $message)
        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:12px">
            <strong>{{ class_basename($message->sender_type) }} #{{ $message->sender_id }}</strong>
            <small style="color:#6b7280">{{ $message->created_at?->format('d M Y, h:i A') }}</small>
            <p style="margin-top:8px;margin-bottom:0">{{ $message->body }}</p>
        </div>
    @endforeach
</div>

@if($supportTicket->isOpenForReplies())
<div class="card">
    <form method="POST" action="{{ route('organizer.support-tickets.reply', $supportTicket) }}">
        @csrf
        <label>Reply</label>
        <textarea name="body" rows="4" class="form-control" required>{{ old('body') }}</textarea>
        <button class="btn btn-primary mt-3">Send Reply</button>
    </form>
</div>
@endif
@endsection
