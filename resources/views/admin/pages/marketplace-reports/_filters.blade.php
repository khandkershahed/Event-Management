<form method="GET" class="row g-3 align-items-end mb-5">
    <div class="col-md-2">
        <label class="form-label fw-semibold">Date From</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
    </div>
    <div class="col-md-2">
        <label class="form-label fw-semibold">Date To</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
    </div>
    @isset($organizers)
        <div class="col-md-3">
            <label class="form-label fw-semibold">Organizer</label>
            <select name="organizer_id" class="form-select form-select-sm">
                <option value="">All Organizers</option>
                @foreach($organizers as $organizer)
                    <option value="{{ $organizer->id }}" @selected((string) request('organizer_id') === (string) $organizer->id)>{{ $organizer->organization_name }}</option>
                @endforeach
            </select>
        </div>
    @endisset
    @isset($events)
        <div class="col-md-3">
            <label class="form-label fw-semibold">Event</label>
            <select name="event_id" class="form-select form-select-sm">
                <option value="">All Events</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" @selected((string) request('event_id') === (string) $event->id)>{{ $event->name }}</option>
                @endforeach
            </select>
        </div>
    @endisset
    @isset($statuses)
        <div class="col-md-2">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
    @endisset
    <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
        <a href="{{ url()->current() }}" class="btn btn-sm btn-light">Clear</a>
    </div>
</form>
