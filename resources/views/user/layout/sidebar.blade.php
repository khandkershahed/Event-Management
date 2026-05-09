@php
    $sidebarCounts = auth()->check() ? app(\App\Services\CustomerPanelService::class)->sidebar(auth()->user()) : [];
    $countBadge = function (string $key) use ($sidebarCounts) {
        $count = (int) ($sidebarCounts[$key] ?? 0);
        return $count > 0 ? '<span class="ms-auto badge bg-danger rounded-pill">' . e((string) $count) . '</span>' : '';
    };
    $menuItems = [
        ['route' => 'user.dashboard', 'patterns' => ['user.dashboard'], 'icon' => 'fa-gauge', 'label' => 'Dashboard', 'title' => 'Dashboard'],
        ['route' => 'user.orders.index', 'patterns' => ['user.orders.*'], 'icon' => 'fa-receipt', 'label' => 'My Orders', 'title' => 'My Orders', 'count' => 'orders'],
        ['route' => 'user.tickets.index', 'patterns' => ['user.tickets.*'], 'icon' => 'fa-ticket', 'label' => 'My Tickets', 'title' => 'My Tickets', 'count' => 'tickets'],
        ['route' => 'user.notifications.index', 'patterns' => ['user.notifications.*'], 'icon' => 'fa-bell', 'label' => 'Notifications', 'title' => 'Notifications', 'count' => 'unread_notifications'],
        ['route' => 'user.reviews.index', 'patterns' => ['user.reviews.*', 'user.event-reviews.*'], 'icon' => 'fa-star', 'label' => 'My Reviews', 'title' => 'My Reviews', 'count' => 'reviews'],
        ['route' => 'user.followed-organizers.index', 'patterns' => ['user.followed-organizers.*'], 'icon' => 'fa-user-check', 'label' => 'Followed', 'title' => 'Followed Organizers', 'count' => 'followed_organizers'],
        ['route' => 'user.saved-events.index', 'patterns' => ['user.saved-events.*'], 'icon' => 'fa-bookmark', 'label' => 'Saved', 'title' => 'Saved Events', 'count' => 'saved_events'],
        ['route' => 'user.discovery.index', 'patterns' => ['user.discovery.*'], 'icon' => 'fa-wand-magic-sparkles', 'label' => 'Discover', 'title' => 'Recommended Events'],
        ['route' => 'user.support-tickets.index', 'patterns' => ['user.support-tickets.*'], 'icon' => 'fa-headset', 'label' => 'Support', 'title' => 'Support Tickets', 'count' => 'open_support_tickets'],
        ['route' => 'user.refunds.index', 'patterns' => ['user.refunds.*', 'user.orders.refund.*'], 'icon' => 'fa-rotate-left', 'label' => 'Refunds', 'title' => 'Refund Requests', 'count' => 'pending_refunds'],
        ['route' => 'user.my.profile', 'patterns' => ['user.profile', 'user.my.profile', 'profile.*'], 'icon' => 'fa-user', 'label' => 'Profile', 'title' => 'Profile'],
    ];
@endphp
<style>
    footer { display: none; }
    .menu--link { display: flex; align-items: center; gap: 8px; }
    .menu--link.active { background: rgba(255,255,255,.12); color: #fff; }
    .menu--link .badge { font-size: 10px; min-width: 20px; }
</style>
<nav class="vertical_nav">
    <div class="left_section menu_left" id="js-menu">
        <div class="left_section">
            <ul>
                @foreach ($menuItems as $item)
                    @php
                        $isActive = collect($item['patterns'])->contains(fn ($pattern) => Route::is($pattern));
                    @endphp
                    <li class="menu--item">
                        <a href="{{ route($item['route']) }}" class="menu--link {{ $isActive ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="{{ $item['title'] }}" @if ($isActive) aria-current="page" @endif>
                            <i class="fa-solid {{ $item['icon'] }} menu--icon"></i>
                            <span class="menu--label">{{ $item['label'] }}</span>
                            @if (! empty($item['count']))
                                {!! $countBadge($item['count']) !!}
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
