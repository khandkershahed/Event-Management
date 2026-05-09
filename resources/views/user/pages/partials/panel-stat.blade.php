<div class="col-md-3 col-sm-6 mb-4">
    <div class="main-card p-4 h-100">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <p class="text-muted mb-1">{{ $label }}</p>
                <h3 class="mb-0">{{ number_format((int) $value) }}</h3>
            </div>
            <i class="fa-solid {{ $icon ?? 'fa-chart-simple' }} fa-2x text-primary"></i>
        </div>
    </div>
</div>
