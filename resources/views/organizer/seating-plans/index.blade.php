@extends('organizer.layouts.app', ['title' => 'My Seating Plans'])
@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h3>My Seating Plans</h3>
        <a class="btn btn-primary" href="{{ route('organizer.seating-plans.create') }}">Create Seating Plan</a>
    </div>
    <table class="table">
        <thead><tr><th>Name</th><th>Venue</th><th>Status</th><th>Sections</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($plans as $plan)
            <tr>
                <td>{{ $plan->name }}</td>
                <td>{{ $plan->venue?->name ?? '-' }}</td>
                <td>{{ ucfirst($plan->status ?? 'draft') }}</td>
                <td>{{ $plan->sections()->count() }}</td>
                <td>
                    <a class="btn btn-light" href="{{ route('organizer.seating-plans.show', $plan) }}">View</a>
                    @if(! $plan->isLocked())
                        <a class="btn btn-primary" href="{{ route('organizer.seating-plans.edit', $plan) }}">Edit</a>
                    @endif
                    <form action="{{ route('organizer.seating-plans.duplicate', $plan) }}" method="POST" style="display:inline-block">@csrf<button class="btn btn-light" type="submit">Duplicate</button></form>
                    <form action="{{ route('organizer.seating-plans.destroy', $plan) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this seating plan?')">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No seating plans found.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $plans->links() }}
</div>
@endsection
