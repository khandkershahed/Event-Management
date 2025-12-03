<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">

    {{-- LOGO + TOGGLE --}}
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Event Tailor"
                src="{{ !empty(optional($setting)->site_logo_black) &&
                file_exists(public_path('storage/' . optional($setting)->site_logo_black))
                    ? asset('storage/' . optional($setting)->site_logo_black)
                    : asset('images/logo.webp') }}"
                class="w-100" />
        </a>

        <div id="kt_aside_toggle"
            class="w-auto px-0 btn btn-icon btn-active-color-primary aside-toggle active"
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <span class="rotate-180 svg-icon svg-icon-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="currentColor" />
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor" />
                </svg>
            </span>
        </div>
    </div>

    {{-- ASIDE MENU --}}
    <div class="aside-menu flex-column-fluid">
        <div class="my-5 hover-scroll-overlay-y my-lg-5" id="kt_aside_menu_wrapper"
            data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
            data-kt-scroll-wrappers="#kt_aside_menu"
            data-kt-scroll-offset="0">

            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">

                {{-- DASHBOARD --}}
                <div class="menu-item">
                    <a class="menu-link d-flex align-items-center {{ Route::is('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">

                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"
                                    viewBox="0 0 66 66">
                                    <g>
                                        <path fill="#5d5b68"
                                            d="M24.86 51.13h16.27l3.78 7.52H21.09z" />
                                        <path fill="#848792"
                                            d="M16.4 58.65h33.19v4H16.4z" />
                                        <path fill="#e6e9ee"
                                            d="M60.52 3.35H5.48C3.56 3.35 2 4.91 2 6.83v36.23h62V6.83c0-1.92-1.56-3.48-3.48-3.48z" />
                                        <path fill="#c8ced6"
                                            d="M60.95 3.39c.02.15.05.29.05.44 0 20.01-16.22 36.23-36.23 36.23H2v3h62V6.83c0-1.77-1.33-3.22-3.05-3.44z" />
                                        <path fill="#848792"
                                            d="M2 43.06v4.6c0 1.92 1.56 3.48 3.48 3.48h55.04c1.92 0 3.48-1.56 3.48-3.48v-4.6z" />
                                        <path fill="#2c92bf"
                                            d="M46.11 15.05h4.81v23.01h-4.81zM34.83 38.057h-4.81v-13h4.81z" />
                                        <path fill="#4ec4a5"
                                            d="M54.15 25.06h4.81v13h-4.81z" />
                                        <path fill="#30aa87"
                                            d="M58.959 38.057h-2v-13h2z" />
                                        <path fill="#4ec4a5"
                                            d="M42.874 38.062h-4.81v-6.99h4.81z" />
                                        <path fill="#e1533b"
                                            d="M17.4 8.36c-.08 0-.15-.01-.23-.01C11.56 8.35 7 12.91 7 18.53c0 2.87 1.19 5.45 3.1 7.3l7.3-7.3z" />
                                        <path fill="#f8ce01"
                                            d="M10.1 25.83c1.83 1.78 4.32 2.87 7.08 2.87 5.62 0 10.18-4.56 10.18-10.18H17.4z" />
                                        <path fill="#5f6fe7"
                                            d="M17.4 8.36v10.17h9.95c0-5.55-4.43-10.05-9.95-10.17z" />
                                        <circle cx="33" cy="47.1" r="1" fill="#c8ced6" />
                                        <path fill="#6e7fed"
                                            d="M24.09 35.56H7c-.55 0-1-.45-1-1s.45-1 1-1h17.09c.55 0 1 .45 1 1s-.45 1-1 1zM17.4 39.06H7c-.55 0-1-.45-1-1s.45-1 1-1h10.4c.55 0 1 .45 1 1s-.44 1-1 1z" />
                                        <path fill="#30aa87"
                                            d="M42.87 38.062h-2v-6.99h2z" />
                                        <path fill="#1f81a3"
                                            d="M50.914 38.059h-2v-23.01h2zM34.825 38.057h-2v-13h2z" />
                                    </g>
                                </svg>
                            </span>
                        </span>

                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                {{-- ============= MAIN MENU CONFIG ============= --}}
                @php
                    $menuItems = [

                        // =================== EVENT & TICKETING SYSTEM ===================
                        [
                            'title' => 'Event & Ticketing',
                            'icon'  => '',

                            // All routes that should keep this group "open"
                            'routes' => [
                                // Event Types
                                'admin.event-type.index',
                                'admin.event-type.create',
                                'admin.event-type.edit',

                                // Events
                                'admin.event.index',
                                'admin.event.create',
                                'admin.event.edit',

                                // Venues
                                'admin.venue.index',
                                'admin.venue.create',
                                'admin.venue.edit',

                                // Seating Plans + Designer
                                'admin.seating-plans.index',
                                'admin.seating-plans.create',
                                'admin.seating-plans.edit',
                                'admin.seating-plans.designer',

                                // Seat Inventory
                                // 'admin.seating-seats.index',
                                // 'admin.seating-seats.show',

                                // Event Ticket Types manager (opened from Events)
                                'admin.events.ticket-types.manage',

                                // Check-in dashboard (per event)
                                'admin.events.checkin.dashboard',
                            ],

                            'subMenu' => [
                                [
                                    'title'  => 'Event Types',
                                    'routes' => [
                                        'admin.event-type.index',
                                        'admin.event-type.create',
                                        'admin.event-type.edit',
                                    ],
                                    'route'  => 'admin.event-type.index',
                                ],
                                [
                                    'title'  => 'Events',
                                    'routes' => [
                                        'admin.event.index',
                                        'admin.event.create',
                                        'admin.event.edit',
                                    ],
                                    'route'  => 'admin.event.index',
                                ],
                                [
                                    'title'  => 'Venues',
                                    'routes' => [
                                        'admin.venue.index',
                                        'admin.venue.create',
                                        'admin.venue.edit',
                                    ],
                                    'route'  => 'admin.venue.index',
                                ],
                                [
                                    'title'  => 'Seating Plans',
                                    'routes' => [
                                        'admin.seating-plans.index',
                                        'admin.seating-plans.create',
                                        'admin.seating-plans.edit',
                                        'admin.seating-plans.designer',
                                    ],
                                    'route'  => 'admin.seating-plans.index',
                                ],
                                // [
                                //     'title'  => 'Seat Inventory',
                                //     'routes' => [
                                //         'admin.seating-seats.index',
                                //         'admin.seating-seats.show',
                                //     ],
                                //     'route'  => 'admin.seating-seats.index',
                                // ],
                                // Ticket Types & Check-In are accessed from each Event row
                            ],
                        ],

                        // ======================= FRONTEND MANAGEMENT ====================
                        [
                            'title' => 'Frontend Management',
                            'icon'  => '',
                            'routes' => [
                                'admin.banner.index',
                                'admin.banner.create',
                                'admin.banner.edit',

                                'admin.service.index',
                                'admin.service.create',
                                'admin.service.edit',

                                'admin.blog-category.index',
                                'admin.blog-category.create',
                                'admin.blog-category.edit',

                                'admin.blog-post.index',
                                'admin.blog-post.create',
                                'admin.blog-post.edit',

                                'admin.contact.index',
                                'admin.contact.create',
                                'admin.contact.edit',

                                'admin.subscription.index',
                                'admin.subscription.create',
                                'admin.subscription.edit',
                            ],

                            'subMenu' => [
                                [
                                    'title'  => 'Banner',
                                    'routes' => ['admin.banner.index', 'admin.banner.create', 'admin.banner.edit'],
                                    'route'  => 'admin.banner.index',
                                ],
                                [
                                    'title'  => 'Services',
                                    'routes' => ['admin.service.index', 'admin.service.create', 'admin.service.edit'],
                                    'route'  => 'admin.service.index',
                                ],
                                [
                                    'title'  => 'Blog Category',
                                    'routes' => [
                                        'admin.blog-category.index',
                                        'admin.blog-category.create',
                                        'admin.blog-category.edit',
                                    ],
                                    'route'  => 'admin.blog-category.index',
                                ],
                                [
                                    'title'  => 'Blog',
                                    'routes' => [
                                        'admin.blog-post.index',
                                        'admin.blog-post.create',
                                        'admin.blog-post.edit',
                                    ],
                                    'route'  => 'admin.blog-post.index',
                                ],
                                [
                                    'title'  => 'Contact',
                                    'routes' => ['admin.contact.index', 'admin.contact.create', 'admin.contact.edit'],
                                    'route'  => 'admin.contact.index',
                                ],
                                [
                                    'title'  => 'Subscription',
                                    'routes' => [
                                        'admin.subscription.index',
                                        'admin.subscription.create',
                                        'admin.subscription.edit',
                                    ],
                                    'route'  => 'admin.subscription.index',
                                ],
                            ],
                        ],

                        // ========================= WEB SETTINGS =========================
                        [
                            'title' => 'Web Settings',
                            'icon'  => '',
                            'routes' => [
                                'admin.settings.index',

                                'admin.faq.index',
                                'admin.faq.create',
                                'admin.faq.edit',

                                'admin.terms.index',
                                'admin.terms.create',
                                'admin.terms.edit',

                                'admin.privacy.index',
                                'admin.privacy.create',
                                'admin.privacy.edit',
                            ],

                            'subMenu' => [
                                [
                                    'title'  => 'Setting',
                                    'routes' => ['admin.settings.index'],
                                    'route'  => 'admin.settings.index',
                                ],
                                [
                                    'title'  => 'FAQs',
                                    'routes' => ['admin.faq.index', 'admin.faq.create', 'admin.faq.edit'],
                                    'route'  => 'admin.faq.index',
                                ],
                                [
                                    'title'  => 'Term & Condition',
                                    'routes' => ['admin.terms.index', 'admin.terms.create', 'admin.terms.edit'],
                                    'route'  => 'admin.terms.index',
                                ],
                                [
                                    'title'  => 'Privacy Policy',
                                    'routes' => ['admin.privacy.index', 'admin.privacy.create', 'admin.privacy.edit'],
                                    'route'  => 'admin.privacy.index',
                                ],
                            ],
                        ],

                        // ======================= USER MANAGEMENT ========================
                        [
                            'title' => 'User Management',
                            'icon'  => '',
                            'routes' => [
                                'admin.user.index',
                                'admin.user.create',
                                'admin.user.edit',

                                'admin.staff.index',
                                'admin.staff.create',
                                'admin.staff.edit',
                            ],

                            'subMenu' => [
                                [
                                    'title'  => 'Staff',
                                    'routes' => ['admin.staff.index', 'admin.staff.create', 'admin.staff.edit'],
                                    'route'  => 'admin.staff.index',
                                ],
                                [
                                    'title'  => 'User',
                                    'routes' => ['admin.user.index', 'admin.user.create', 'admin.user.edit'],
                                    'route'  => 'admin.user.index',
                                ],
                            ],
                        ],

                    ];
                @endphp

                {{-- RENDER THE MENU ITEMS --}}
                @foreach ($menuItems as $item)
                    @if (empty($item['subMenu']))
                        {{-- Single-level item (we’re not using any right now, but kept for future) --}}
                        <div class="menu-item">
                            <a class="menu-link {{ Route::is(...$item['routes']) ? 'active' : '' }}"
                                href="{{ route($item['route']) }}">
                                <span class="menu-icon">
                                    {!! $item['icon'] !!}
                                </span>
                                <span class="menu-title">{{ $item['title'] }}</span>
                            </a>
                        </div>
                    @else
                        {{-- Accordion item with submenu --}}
                        <div data-kt-menu-trigger="click"
                            class="menu-item menu-accordion {{ Route::is(...$item['routes']) ? 'here show' : '' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    {!! $item['icon'] !!}
                                </span>
                                <span class="menu-title">{{ $item['title'] }}</span>
                                <span class="menu-arrow"></span>
                            </span>

                            <div
                                class="menu-sub menu-sub-accordion {{ Route::is(...$item['routes']) ? 'menu-active-bg' : '' }}">
                                @foreach ($item['subMenu'] as $subItem)
                                    @if (isset($subItem['subMenu']))
                                        {{-- 3rd level submenu (not used now) --}}
                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                            <span class="menu-link">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $subItem['title'] }}</span>
                                                <span class="menu-arrow"></span>
                                            </span>
                                            <div
                                                class="menu-sub menu-sub-accordion {{ Route::is(...array_column($subItem['subMenu'], 'route')) ? 'here show' : '' }}">
                                                @foreach ($subItem['subMenu'] as $subSubItem)
                                                    <div class="menu-item">
                                                        <a class="menu-link {{ Route::is($subSubItem['route']) ? 'active' : '' }}"
                                                            href="{{ route($subSubItem['route']) }}">
                                                            <span class="menu-bullet">
                                                                <span class="bullet bullet-dot"></span>
                                                            </span>
                                                            <span class="menu-title">{{ $subSubItem['title'] }}</span>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        {{-- Normal submenu item --}}
                                        <div class="menu-item">
                                            <a class="menu-link {{ Route::is(...$subItem['routes']) ? 'active' : '' }}"
                                                href="{{ route($subItem['route']) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $subItem['title'] }}</span>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>

    {{-- FOOTER / LOGOUT --}}
    <div class="px-5 pt-5 aside-footer flex-column-auto pb-7" id="kt_aside_footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <a href="{{ route('admin.logout') }}" class="btn btn-custom btn-primary w-100"
                onclick="event.preventDefault();this.closest('form').submit();">
                <span class="btn-label">
                    {{ __('Log Out') }}
                </span>
            </a>
        </form>
    </div>

</div>
