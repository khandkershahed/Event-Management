<x-admin-app-layout :title="'Review Event'">
    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between align-items-center">
            <div class="card-title"><h3 class="fw-bold mb-0">Review: {{ $event->name }}</h3></div>
            <a href="{{ route('admin.event-approvals.index') }}" class="btn btn-light-info">Back</a>
        </div>
        <div class="card-body">
            <div class="row gy-4">
                <div class="col-md-6"><strong>Organizer:</strong> {{ optional($event->organizerProfile)->organization_name ?? '-' }}</div>
                <div class="col-md-6"><strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $event->status)) }}</div>
                <div class="col-md-6"><strong>Venue:</strong> {{ optional($event->venueModel)->name ?? $event->venue ?? '-' }}</div>
                <div class="col-md-6"><strong>Seating Plan:</strong> {{ optional($event->seatingPlan)->name ?? 'General admission' }}</div>
                <div class="col-md-6"><strong>Start Date:</strong> {{ $event->start_date ? $event->start_date->format('Y-m-d') : '-' }}</div>
                <div class="col-md-6"><strong>End Date:</strong> {{ $event->end_date ? $event->end_date->format('Y-m-d') : '-' }}</div>
                <div class="col-12"><strong>Description:</strong><br>{{ $event->description ?: '-' }}</div>
            </div>

            @if(in_array($event->status, [\App\Models\Event::STATUS_SUBMITTED, \App\Models\Event::STATUS_UNDER_REVIEW], true))
                <div class="mt-8 d-flex gap-3 align-items-start flex-wrap">
                    <form method="POST" action="{{ route('admin.event-approvals.approve', $event) }}">
                        @csrf
                        <button class="btn btn-success" type="submit">Approve Event</button>
                    </form>
                    <form method="POST" action="{{ route('admin.event-approvals.reject', $event) }}" style="min-width:360px">
                        @csrf
                        <textarea class="form-control mb-2" name="rejection_reason" rows="3" required placeholder="Write rejection reason"></textarea>
                        <button class="btn btn-danger" type="submit">Reject Event</button>
                    </form>
                </div>
            @else
                <div class="alert alert-info mt-8">This event is not currently pending review.</div>
            @endif

            <div class="mt-8 border rounded p-4">
                <h5>Moderation Flag</h5>
                <form method="POST" action="{{ route('admin.moderation-flags.events.flag', $event) }}">
                    @csrf
                    <textarea class="form-control mb-2" name="reason" rows="3" required placeholder="Reason for flagging this event"></textarea>
                    <textarea class="form-control mb-2" name="admin_note" rows="2" placeholder="Internal admin note (optional)"></textarea>
                    <button class="btn btn-light-danger" type="submit">Flag Event</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-app-layout>
