<div class="main-card p-5 text-center text-muted">
    <div class="mb-3"><i class="fa-solid fa-circle-info fa-2x"></i></div>
    <p class="mb-3">{{ $message ?? 'No marketplace activity found yet.' }}</p>
    <a href="{{ $url ?? route('all.events') }}" class="btn btn-sm btn-primary">{{ $label ?? 'Discover Events' }}</a>
</div>
