<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Event Tailor" src="{{ !empty(optional($setting)->site_logo_black) && file_exists(public_path('storage/' . optional($setting)->site_logo_black)) ? asset('storage/' . optional($setting)->site_logo_black) : asset('images/logo.webp') }}" class="w-100" />
        </a>
        <div id="kt_aside_toggle" class="w-auto px-0 btn btn-icon btn-active-color-primary aside-toggle active" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <span class="rotate-180 svg-icon svg-icon-1"><i class="fa-solid fa-angles-left text-white"></i></span>
        </div>
    </div>

    <div class="aside-menu flex-column-fluid">
        <div class="my-5 hover-scroll-overlay-y my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <a class="menu-link d-flex align-items-center {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon"><i class="fa-solid fa-gauge text-white"></i></span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                @php
                    $adminDashboardCounters = app(\App\Services\Dashboard\AdminDashboardService::class)->sidebarCounters();
                    $counterBadge = function ($value) {
                        return $value > 0 ? '<span class="badge badge-light-danger ms-auto">' . e($value) . '</span>' : '';
                    };

                    $menuItems = [
                        [
                            'title' => 'Marketplace',
                            'routes' => ['admin.organizers.*', 'admin.organizer-trust-badges.*', 'admin.payouts.*', 'admin.payout-methods.*', 'admin.platform-commission.*', 'admin.marketplace-reports.*', 'admin.notifications.*', 'admin.audit-logs.*', 'admin.support-tickets.*', 'admin.moderation-flags.*', 'admin.reviews.*'],
                            'subMenu' => [
                                ['title' => 'Marketplace Reports', 'routes' => ['admin.marketplace-reports.dashboard'], 'route' => 'admin.marketplace-reports.dashboard'],
                                ['title' => 'Notifications', 'routes' => ['admin.notifications.*'], 'route' => 'admin.notifications.index', 'counter' => $adminDashboardCounters['unread_notifications']],
                                ['title' => 'Audit Logs', 'routes' => ['admin.audit-logs.*'], 'route' => 'admin.audit-logs.index'],
                                ['title' => 'Support Desk', 'routes' => ['admin.support-tickets.*'], 'route' => 'admin.support-tickets.index', 'counter' => $adminDashboardCounters['open_support']],
                                ['title' => 'Reviews', 'routes' => ['admin.reviews.*'], 'route' => 'admin.reviews.index'],
                                ['title' => 'Trust Badges', 'routes' => ['admin.organizer-trust-badges.*'], 'route' => 'admin.organizer-trust-badges.index'],
                                ['title' => 'Moderation Flags', 'routes' => ['admin.moderation-flags.*'], 'route' => 'admin.moderation-flags.index', 'counter' => $adminDashboardCounters['moderation_flags']],
                                ['title' => 'Sales by Date', 'routes' => ['admin.marketplace-reports.sales-by-date'], 'route' => 'admin.marketplace-reports.sales-by-date'],
                                ['title' => 'Sales by Organizer', 'routes' => ['admin.marketplace-reports.sales-by-organizer'], 'route' => 'admin.marketplace-reports.sales-by-organizer'],
                                ['title' => 'Sales by Event', 'routes' => ['admin.marketplace-reports.sales-by-event'], 'route' => 'admin.marketplace-reports.sales-by-event'],
                                ['title' => 'Commissions', 'routes' => ['admin.marketplace-reports.commissions'], 'route' => 'admin.marketplace-reports.commissions'],
                                ['title' => 'Payout Reports', 'routes' => ['admin.marketplace-reports.payouts'], 'route' => 'admin.marketplace-reports.payouts'],
                                ['title' => 'Refund Reports', 'routes' => ['admin.marketplace-reports.refunds'], 'route' => 'admin.marketplace-reports.refunds'],
                                ['title' => 'All Organizers', 'routes' => ['admin.organizers.index', 'admin.organizers.show'], 'route' => 'admin.organizers.index'],
                                ['title' => 'Pending Organizers', 'routes' => ['admin.organizers.pending'], 'route' => 'admin.organizers.pending', 'counter' => $adminDashboardCounters['pending_organizers']],
                                ['title' => 'Payout Requests', 'routes' => ['admin.payouts.*'], 'route' => 'admin.payouts.index', 'counter' => $adminDashboardCounters['pending_payouts']],
                                ['title' => 'Payout Methods', 'routes' => ['admin.payout-methods.*'], 'route' => 'admin.payout-methods.index'],
                                ['title' => 'Platform Commission', 'routes' => ['admin.platform-commission.*'], 'route' => 'admin.platform-commission.edit'],
                            ],
                        ],
                        [
                            'title' => 'Event & Ticketing',
                            'routes' => ['admin.event-type.*', 'admin.event.*', 'admin.event-approvals.*', 'admin.venue.*', 'admin.seating-plans.*', 'admin.events.ticket-types.*'],
                            'subMenu' => [
                                ['title' => 'Event Types', 'routes' => ['admin.event-type.*'], 'route' => 'admin.event-type.index'],
                                ['title' => 'Events', 'routes' => ['admin.event.*'], 'route' => 'admin.event.index'],
                                ['title' => 'Pending Event Approvals', 'routes' => ['admin.event-approvals.*'], 'route' => 'admin.event-approvals.index', 'counter' => $adminDashboardCounters['pending_events']],
                                ['title' => 'Venues', 'routes' => ['admin.venue.*'], 'route' => 'admin.venue.index'],
                                ['title' => 'Seating Plans', 'routes' => ['admin.seating-plans.*'], 'route' => 'admin.seating-plans.index'],
                            ],
                        ],
                        [
                            'title' => 'User Management',
                            'routes' => ['admin.user.*'],
                            'subMenu' => [
                                ['title' => 'Users', 'routes' => ['admin.user.*'], 'route' => 'admin.user.index'],
                            ],
                        ],
                        [
                            'title' => 'Web Settings',
                            'routes' => ['admin.categories.*', 'admin.settings.*'],
                            'subMenu' => [
                                ['title' => 'Categories', 'routes' => ['admin.categories.*'], 'route' => 'admin.categories.index'],
                                ['title' => 'Settings', 'routes' => ['admin.settings.*'], 'route' => 'admin.settings.index'],
                            ],
                        ],
                    ];
                @endphp

                @foreach ($menuItems as $item)
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Route::is(...$item['routes']) ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="fa-solid fa-circle-dot text-white"></i></span>
                            <span class="menu-title">{{ $item['title'] }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion {{ Route::is(...$item['routes']) ? 'menu-active-bg' : '' }}">
                            @foreach ($item['subMenu'] as $subItem)
                                <div class="menu-item">
                                    <a class="menu-link {{ Route::is(...$subItem['routes']) ? 'active' : '' }}" href="{{ route($subItem['route']) }}">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title">{{ $subItem['title'] }}</span>
                                        @if(! empty($subItem['counter']))
                                            {!! $counterBadge((int) $subItem['counter']) !!}
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="px-5 pt-5 aside-footer flex-column-auto pb-7" id="kt_aside_footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <a href="{{ route('admin.logout') }}" class="btn btn-custom btn-primary w-100" onclick="event.preventDefault();this.closest('form').submit();">
                <span class="btn-label">{{ __('Log Out') }}</span>
            </a>
        </form>
    </div>
</div>
