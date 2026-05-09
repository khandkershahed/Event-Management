<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Organizer Panel' }}</title>
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.bundle.css') }}">
    <style>.organizer-shell{display:flex;min-height:100vh}.organizer-sidebar{width:260px;background:#111827;color:#fff;padding:24px}.organizer-sidebar a{display:flex;align-items:center;justify-content:space-between;gap:8px;color:#d1d5db;text-decoration:none;padding:10px 12px;border-radius:8px;margin-bottom:6px}.organizer-sidebar-counter{background:#ef4444;color:#fff;border-radius:999px;font-size:12px;line-height:1;padding:4px 7px;min-width:22px;text-align:center}.organizer-sidebar a.active,.organizer-sidebar a:hover{background:#2563eb;color:#fff}.organizer-main{flex:1;background:#f3f4f6}.organizer-topbar{background:#fff;padding:18px 26px;border-bottom:1px solid #e5e7eb}.organizer-content{padding:26px}.card{background:#fff;border-radius:12px;padding:22px;margin-bottom:18px;box-shadow:0 1px 4px rgba(0,0,0,.06)}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px}.btn{display:inline-block;padding:9px 14px;border-radius:8px;text-decoration:none;border:0;cursor:pointer}.btn-primary{background:#2563eb;color:#fff}.btn-light{background:#e5e7eb;color:#111827}.btn-danger{background:#dc2626;color:#fff}.form-control{width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px}.mb-3{margin-bottom:1rem}.table{width:100%;border-collapse:collapse}.table th,.table td{border-bottom:1px solid #e5e7eb;padding:10px;text-align:left}</style>
    @stack('styles')
</head>
<body>
<div class="organizer-shell">
    @include('organizer.layouts.sidebar')
    <main class="organizer-main">
        <div class="organizer-topbar">
            <strong>{{ $title ?? 'Organizer Panel' }}</strong>
            <span style="float:right">{{ auth()->user()->name ?? 'Organizer' }}</span>
        </div>
        <div class="organizer-content">
            @if(session('success'))<div class="card" style="border-left:4px solid #16a34a">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="card" style="border-left:4px solid #dc2626">{{ session('error') }}</div>@endif
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>
