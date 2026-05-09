<x-frontend-app-layout :title="'Customer Dashboard'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body"><div class="dashboard-body"><div class="container-fluid"><div class="main-card p-5 text-center"><h3>Customer Dashboard</h3><p class="text-muted">Your live dashboard is available from the main customer dashboard.</p><a href="{{ route('user.dashboard') }}" class="btn btn-primary">Open Dashboard</a></div></div></div></div>
</x-frontend-app-layout>
