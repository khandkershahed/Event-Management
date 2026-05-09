@extends('organizer.layouts.app', ['title' => 'Ticket Type Details'])
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <div>
            <h3>{{ $ticket->name }}</h3>
            <p style="color:#6b7280;margin:0">Event: {{ $event->name }}</p>
        </div>
        <div>
            <a class="btn btn-light" href="{{ route('organizer.events.ticket-types.index', $event) }}">Back</a>
            <a class="btn btn-primary" href="{{ route('organizer.events.ticket-types.edit', [$event, $ticket]) }}">Edit</a>
        </div>
    </div>

    <div class="grid">
        <div class="card"><strong>Type</strong><br>{{ ucwords(str_replace('_', ' ', $ticket->ticket_type ?? 'paid')) }}</div>
        <div class="card"><strong>Price</strong><br>{{ $ticket->currency ?? 'BDT' }} {{ number_format((float) $ticket->price, 2) }}</div>
        <div class="card"><strong>Total Quantity</strong><br>{{ $availabilitySummary['total_quantity'] }}</div>
        <div class="card"><strong>Sold</strong><br>{{ $availabilitySummary['sold_quantity'] }}</div>
        <div class="card"><strong>Remaining</strong><br>{{ $availabilitySummary['remaining_quantity'] }}</div>
        <div class="card"><strong>On Sale</strong><br>{{ $availabilitySummary['is_on_sale'] ? 'Yes' : 'No' }}</div>
        <div class="card"><strong>Status</strong><br>{{ ucwords(str_replace('_', ' ', $ticket->status ?? 'active')) }}</div>
        <div class="card"><strong>Visibility</strong><br>{{ ucfirst($ticket->visibility ?? 'public') }}</div>
    </div>

    <div class="card" style="box-shadow:none;border:1px solid #e5e7eb">
        <strong>Description</strong>
        <p>{{ $ticket->description ?: 'No description provided.' }}</p>
    </div>

    <div class="card" style="box-shadow:none;border:1px solid #e5e7eb">
        <strong>Valid Sections</strong><br>
        @php($selected = $ticket->validSectionIdsArray())
        @if(count($selected) && $sections->count())
            @foreach($sections->whereIn('id', $selected) as $section)
                <span style="display:inline-block;background:#e0f2fe;color:#075985;padding:5px 9px;border-radius:999px;margin:6px 6px 0 0">{{ $section->name }}</span>
            @endforeach
        @else
            <span>All sections</span>
        @endif
    </div>

    <form method="POST" action="{{ route('organizer.events.ticket-types.destroy', [$event, $ticket]) }}" onsubmit="return confirm('Are you sure? If this ticket has sales, it will be archived instead of deleted.');">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">Delete / Archive</button>
    </form>
</div>
@endsection
