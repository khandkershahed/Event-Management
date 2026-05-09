<x-frontend-app-layout :title="$supportTicket->ticket_number">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-main-title mb-4"><h3><i class="fa-solid fa-headset me-3"></i>{{ $supportTicket->ticket_number }}</h3></div>
                <div class="main-card p-4 mb-4">
                    <h4>{{ $supportTicket->subject }}</h4>
                    <p>Status: <strong>{{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}</strong> | Priority: <strong>{{ ucfirst($supportTicket->priority) }}</strong></p>
                    <p>{{ $supportTicket->description }}</p>
                </div>
                <div class="main-card p-4 mb-4">
                    <h5>Messages</h5>
                    @foreach($supportTicket->messages as $message)
                        <div class="border rounded p-3 mb-3">
                            <strong>{{ class_basename($message->sender_type) }} #{{ $message->sender_id }}</strong>
                            <small class="text-muted">{{ $message->created_at?->format('d M Y, h:i A') }}</small>
                            <p class="mb-0 mt-2">{{ $message->body }}</p>
                        </div>
                    @endforeach
                </div>
                @if($supportTicket->isOpenForReplies())
                    <div class="main-card p-4">
                        <form method="POST" action="{{ route('user.support-tickets.reply', $supportTicket) }}">
                            @csrf
                            <label class="form-label">Reply</label>
                            <textarea name="body" rows="4" class="form-control" required>{{ old('body') }}</textarea>
                            <button class="btn btn-primary mt-3">Send Reply</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-frontend-app-layout>
