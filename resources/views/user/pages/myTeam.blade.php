<x-frontend-app-layout :title="'Followed Organizers'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body"><div class="dashboard-body"><div class="container-fluid">
        <div class="d-main-title mb-4"><h3><i class="fa-solid fa-user-group me-3"></i>Followed Organizers</h3><p class="text-muted mb-0">Customer networking is shown through real followed organizer records.</p></div>
        <div class="main-card p-4">
            @forelse ($followedOrganizers as $followed)
                @php($organizer = $followed->organizerProfile)
                <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                    <div><strong>{{ $organizer?->organization_name ?? 'Organizer removed' }}</strong><div class="small text-muted">Followed {{ $followed->created_at?->diffForHumans() }}</div></div>
                    @if ($organizer?->slug)<a href="{{ route('public.organizers.show', $organizer->slug) }}" class="btn btn-sm btn-outline-primary">View Organizer</a>@endif
                </div>
            @empty
                @include('user.pages.partials.panel-empty', ['message' => 'You are not following any organizers yet.', 'url' => route('all.events'), 'label' => 'Discover Events'])
            @endforelse
        </div>
    </div></div></div>
</x-frontend-app-layout>
