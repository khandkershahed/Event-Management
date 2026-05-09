<div class="d-flex justify-content-between align-items-center border-bottom bg-white rounded-bottom px-3 py-3 mb-3">
    <div>
        <h1 class="h5 mb-0">{{ $title ?? 'Organizer Area' }}</h1>
        <small class="text-muted">{{ optional(auth()->user()->organizerProfile)->organization_name }}</small>
    </div>
    <span class="badge bg-success">Approved Organizer</span>
</div>
