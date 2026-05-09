<x-admin-app-layout :title="$supportTicket->ticket_number">
    <div class="card card-flush mb-5">
        <div class="card-header"><div class="card-title"><h2>{{ $supportTicket->ticket_number }} - {{ $supportTicket->subject }}</h2></div></div>
        <div class="card-body">
            <p>Status: <strong>{{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}</strong> | Priority: <strong>{{ ucfirst($supportTicket->priority) }}</strong> | Type: <strong>{{ ucfirst(str_replace('_', ' ', $supportTicket->type)) }}</strong></p>
            <p>Customer: {{ $supportTicket->user?->name ?? 'N/A' }} | Organizer: {{ $supportTicket->organizerProfile?->organization_name ?? 'N/A' }}</p>
            <p>{{ $supportTicket->description }}</p>
        </div>
    </div>

    <div class="card card-flush mb-5">
        <div class="card-header"><div class="card-title"><h3>Messages</h3></div></div>
        <div class="card-body">
            @foreach($supportTicket->messages as $message)
                <div class="border rounded p-4 mb-3 {{ $message->is_internal ? 'bg-light-warning' : '' }}">
                    <strong>{{ class_basename($message->sender_type) }} #{{ $message->sender_id }}</strong>
                    @if($message->is_internal)<span class="badge badge-warning ms-2">Internal</span>@endif
                    <small class="text-muted ms-2">{{ $message->created_at?->format('d M Y, h:i A') }}</small>
                    <p class="mb-0 mt-2">{{ $message->body }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card card-flush mb-5">
        <div class="card-header"><div class="card-title"><h3>Reply and Update</h3></div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.support-tickets.reply', $supportTicket) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach($statuses as $status)<option value="{{ $status }}" @selected($supportTicket->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>@endforeach</select></div>
                    <div class="col-md-3"><label class="form-label">Priority</label><select name="priority" class="form-select">@foreach($priorities as $priority)<option value="{{ $priority }}" @selected($supportTicket->priority === $priority)>{{ ucfirst($priority) }}</option>@endforeach</select></div>
                    <div class="col-md-12"><label class="form-label">Reply</label><textarea name="body" rows="4" class="form-control" required>{{ old('body') }}</textarea></div>
                    <div class="col-md-12"><label class="form-check"><input type="checkbox" name="is_internal" value="1" class="form-check-input"> <span class="form-check-label">Internal note only</span></label></div>
                </div>
                <button class="btn btn-primary mt-4">Send Reply</button>
            </form>
        </div>
    </div>
</x-admin-app-layout>
