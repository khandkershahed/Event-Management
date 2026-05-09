@extends('organizer.layouts.app', ['title' => 'Seating Plan Details'])
@section('content')
<div class="card">
    <h3>{{ $plan->name }}</h3>
    <p><strong>Venue:</strong> {{ $plan->venue?->name ?? '-' }}</p>
    <p><strong>Status:</strong> {{ ucfirst($plan->status ?? 'draft') }}</p>
    <p><strong>Sections:</strong> {{ $plan->sections->count() }}</p>
    <p><strong>Seats:</strong> {{ $plan->sections->sum(fn($section) => $section->seats->count()) }}</p>
    @if($plan->isLocked())
        <p style="color:#dc2626"><strong>This seating plan is locked and cannot be edited.</strong></p>
    @endif
    @if(! $plan->isLocked())
        <a class="btn btn-primary" href="{{ route('organizer.seating-plans.edit', $plan) }}">Edit Details</a>
        <a class="btn btn-light" href="{{ route('organizer.seating-plans.designer', $plan) }}">Open Visual Designer</a>
    @endif
    <form action="{{ route('organizer.seating-plans.duplicate', $plan) }}" method="POST" style="display:inline-block">@csrf<button class="btn btn-light" type="submit">Duplicate</button></form>
    <a class="btn btn-light" href="{{ route('organizer.seating-plans.index') }}">Back</a>
</div>
@endsection
