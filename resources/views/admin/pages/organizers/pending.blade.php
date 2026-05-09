<x-admin-app-layout :title="'Pending Organizers'">
    <div class="card card-flash">
        <div class="card-header mt-6 d-flex justify-content-between align-items-center">
            <div class="card-title"><h3 class="fw-bold mb-0">Pending Organizers</h3></div>
            <div class="card-toolbar"><a href="{{ route('admin.organizers.index') }}" class="btn btn-light-primary">All Organizers</a></div>
        </div>
        <div class="card-body pt-0">
            @include('admin.pages.organizers._table', ['organizers' => $organizers])
        </div>
    </div>
</x-admin-app-layout>
