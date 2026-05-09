@php
    $teamAccessService = app(\App\Services\OrganizerTeamAccessService::class);
    $dashboardService = app(\App\Services\Dashboard\OrganizerDashboardService::class);
    $organizerProfile = auth()->check() ? $teamAccessService->resolveProfileFor(auth()->user()) : null;
    $canOperate = auth()->check() && $organizerProfile && $teamAccessService->userCan(auth()->user(), 'operations.manage', $organizerProfile);
    $canManageTeam = auth()->check() && $organizerProfile && $teamAccessService->userCan(auth()->user(), 'team.manage', $organizerProfile);
    $canCheckIn = auth()->check() && $organizerProfile && $teamAccessService->userCan(auth()->user(), 'checkin.manage', $organizerProfile);
    $canViewFinance = auth()->check() && $organizerProfile && $teamAccessService->userCan(auth()->user(), 'finance.view', $organizerProfile);
    $counters = $organizerProfile ? $dashboardService->sidebarCounters($organizerProfile) : [];
    $badge = function ($key) use ($counters) {
        $value = (int) ($counters[$key] ?? 0);
        return $value > 0 ? '<span class="organizer-sidebar-counter">' . e($value) . '</span>' : '';
    };
@endphp
<aside class="organizer-sidebar">
    <h3 style="color:#fff;margin-bottom:24px">Organizer</h3>

    @if($canOperate || $canViewFinance || $canCheckIn)
        <a href="{{ route('organizer.notifications.index') }}" class="{{ request()->routeIs('organizer.notifications.*') ? 'active' : '' }}">
            <span>Notifications</span>{!! $badge('notifications') !!}
        </a>
        <a href="{{ route('organizer.support-tickets.index') }}" class="{{ request()->routeIs('organizer.support-tickets.*') ? 'active' : '' }}">
            <span>Support Tickets</span>{!! $badge('support') !!}
        </a>
    @endif

    @if($canOperate)
        <a href="{{ route('organizer.dashboard') }}" class="{{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}"><span>Dashboard</span></a>
        <a href="{{ route('organizer.approved-profile') }}" class="{{ request()->routeIs('organizer.approved-profile') ? 'active' : '' }}"><span>Profile</span></a>
        <a href="{{ route('organizer.events.index') }}" class="{{ request()->routeIs('organizer.events.index') || request()->routeIs('organizer.events.show') || request()->routeIs('organizer.events.create') || request()->routeIs('organizer.events.edit') ? 'active' : '' }}">
            <span>Events</span>{!! $badge('pending_events') !!}
        </a>
        <a href="{{ route('organizer.events.index') }}" class="{{ request()->routeIs('organizer.events.ticket-types.*') ? 'active' : '' }}"><span>Tickets Setup</span></a>
        <a href="{{ route('organizer.venues.index') }}" class="{{ request()->routeIs('organizer.venues.*') ? 'active' : '' }}"><span>Venues</span></a>
        <a href="{{ route('organizer.seating-plans.index') }}" class="{{ request()->routeIs('organizer.seating-plans.*') ? 'active' : '' }}"><span>Seating Plans</span></a>
        <a href="{{ route('organizer.orders.index') }}" class="{{ request()->routeIs('organizer.orders.*') ? 'active' : '' }}">
            <span>Orders</span>{!! $badge('orders') !!}
        </a>
    @endif

    @if($canManageTeam)
        <a href="{{ route('organizer.team-members.index') }}" class="{{ request()->routeIs('organizer.team-members.*') ? 'active' : '' }}"><span>Team Members</span></a>
    @endif

    @if($canCheckIn)
        <a href="{{ route('organizer.check-in.index') }}" class="{{ request()->routeIs('organizer.check-in.*') ? 'active' : '' }}">
            <span>Check-In</span>{!! $badge('check_ins') !!}
        </a>
    @endif

    @if($canViewFinance)
        <a href="{{ route('organizer.reports.sales') }}" class="{{ request()->routeIs('organizer.reports.*') ? 'active' : '' }}"><span>Reports</span></a>
        <a href="{{ route('organizer.reviews.index') }}" class="{{ request()->routeIs('organizer.reviews.*') ? 'active' : '' }}">
            <span>Reviews</span>{!! $badge('reviews') !!}
        </a>
        <a href="{{ route('organizer.payouts.index') }}" class="{{ request()->routeIs('organizer.payouts.*') ? 'active' : '' }}">
            <span>Payouts</span>{!! $badge('payouts') !!}
        </a>
        <a href="{{ route('organizer.finance-profile.show') }}" class="{{ request()->routeIs('organizer.finance-profile.*') ? 'active' : '' }}"><span>Finance Profile</span></a>
    @endif

    <a href="{{ route('homepage') }}"><span>Back to Website</span></a>
</aside>
