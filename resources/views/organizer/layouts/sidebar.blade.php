@php
    $teamAccessService = app(\App\Services\OrganizerTeamAccessService::class);
    $dashboardService = app(\App\Services\Dashboard\OrganizerDashboardService::class);
    $organizerProfile = auth()->check() ? $teamAccessService->resolveProfileFor(auth()->user()) : null;

    $canOperate =
        auth()->check() &&
        $organizerProfile &&
        $teamAccessService->userCan(auth()->user(), 'operations.manage', $organizerProfile);
    $canManageTeam =
        auth()->check() &&
        $organizerProfile &&
        $teamAccessService->userCan(auth()->user(), 'team.manage', $organizerProfile);
    $canCheckIn =
        auth()->check() &&
        $organizerProfile &&
        $teamAccessService->userCan(auth()->user(), 'checkin.manage', $organizerProfile);
    $canViewFinance =
        auth()->check() &&
        $organizerProfile &&
        $teamAccessService->userCan(auth()->user(), 'finance.view', $organizerProfile);

    $counters = $organizerProfile ? $dashboardService->sidebarCounters($organizerProfile) : [];

    $badge = function ($key) use ($counters) {
        $value = (int) ($counters[$key] ?? 0);
        return $value > 0 ? '<span class="organizer-sidebar-counter">' . e($value) . '</span>' : '';
    };
@endphp

<aside class="organizer-sidebar">
    <div class="organizer-sidebar-header">
        <h3 class="mb-1 text-dark fw-bold sidebar-brand-text" style="font-size: 1.25rem;">Organizer</h3>
        <small class="text-muted sidebar-brand-text">Control Panel</small>
    </div>

    <div class="organizer-sidebar-inner">

        @if ($canOperate || $canViewFinance || $canCheckIn)
            <div class="sidebar-group-label">Communication</div>

            <a href="{{ route('organizer.notifications.index') }}"
                class="{{ request()->routeIs('organizer.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> <span>Notifications</span>{!! $badge('notifications') !!}
            </a>
            <a href="{{ route('organizer.support-tickets.index') }}"
                class="{{ request()->routeIs('organizer.support-tickets.*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-dots"></i> <span>Support Tickets</span>{!! $badge('support') !!}
            </a>
        @endif

        @if ($canOperate)
            <div class="sidebar-group-label">Operations</div>

            <a href="{{ route('organizer.dashboard') }}"
                class="{{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('organizer.approved-profile') }}"
                class="{{ request()->routeIs('organizer.approved-profile') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> <span>Profile</span>
            </a>
            <a href="{{ route('organizer.events.index') }}"
                class="{{ request()->routeIs('organizer.events.index') || request()->routeIs('organizer.events.show') || request()->routeIs('organizer.events.create') || request()->routeIs('organizer.events.edit') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> <span>Events</span>{!! $badge('pending_events') !!}
            </a>
            <a href="{{ route('organizer.events.index') }}"
                class="{{ request()->routeIs('organizer.events.ticket-types.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-detailed"></i> <span>Tickets Setup</span>
            </a>
            <a href="{{ route('organizer.venues.index') }}"
                class="{{ request()->routeIs('organizer.venues.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> <span>Venues</span>
            </a>
            <a href="{{ route('organizer.seating-plans.index') }}"
                class="{{ request()->routeIs('organizer.seating-plans.*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3"></i> <span>Seating Plans</span>
            </a>
            <a href="{{ route('organizer.orders.index') }}"
                class="{{ request()->routeIs('organizer.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> <span>Orders</span>{!! $badge('orders') !!}
            </a>
        @endif

        @if ($canManageTeam)
            <div class="sidebar-group-label">Team</div>
            <a href="{{ route('organizer.team-members.index') }}"
                class="{{ request()->routeIs('organizer.team-members.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span>Team Members</span>
            </a>
        @endif

        @if ($canCheckIn)
            <div class="sidebar-group-label">Access Control</div>
            <a href="{{ route('organizer.check-in.index') }}"
                class="{{ request()->routeIs('organizer.check-in.*') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> <span>Check-In</span>{!! $badge('check_ins') !!}
            </a>
        @endif

        @if ($canViewFinance)
            <div class="sidebar-group-label">Finance & Data</div>
            <a href="{{ route('organizer.reports.sales') }}"
                class="{{ request()->routeIs('organizer.reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> <span>Reports</span>
            </a>
            <a href="{{ route('organizer.reviews.index') }}"
                class="{{ request()->routeIs('organizer.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i> <span>Reviews</span>{!! $badge('reviews') !!}
            </a>
            <a href="{{ route('organizer.payouts.index') }}"
                class="{{ request()->routeIs('organizer.payouts.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> <span>Payouts</span>{!! $badge('payouts') !!}
            </a>
            <a href="{{ route('organizer.finance-profile.show') }}"
                class="{{ request()->routeIs('organizer.finance-profile.*') ? 'active' : '' }}">
                <i class="bi bi-bank"></i> <span>Finance Profile</span>
            </a>
        @endif

        <div style="margin-top: 2rem; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
            <a href="{{ route('homepage') }}">
                <i class="bi bi-arrow-left-circle"></i> <span>Back to Website</span>
            </a>
        </div>
    </div>
</aside>
