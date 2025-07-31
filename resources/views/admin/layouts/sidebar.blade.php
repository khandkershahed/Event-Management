<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">

        <a href="{{ route('admin.dashboard') }}">
            <img alt="Event Tailor"
                src="{{ !empty(optional($setting)->site_logo_black) &&
                file_exists(public_path('storage/' . optional($setting)->site_logo_black))
                    ? asset('storage/' . optional($setting)->site_logo_black)
                    : asset('images/logo.jpg') }}"
                class="w-100">
        </a>
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle active"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <span class="svg-icon svg-icon-1 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="currentColor"></path>
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor"></path>
                </svg>
            </span>
        </div>
    </div>
    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
            data-kt-scroll-offset="0" style="height: 318px;">
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <a class="menu-link {{ Route::is('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">

                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor">
                                    </rect>
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                        fill="currentColor"></rect>
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                        fill="currentColor"></rect>
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                        </span>

                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                {{-- Site Content  --}}
                @php
                    $menuItems = [
                        //====================== Event Management Start ============
                        [
                            'title' => 'Event Management',
                            'icon' => 'fas fa-desktop',

                            'routes' => [
                                'admin.event-type.index',
                                'admin.event-type.create',
                                'admin.event-type.edit',

                                'admin.event.index',
                                'admin.event.create',
                                'admin.event.edit',
                            ],

                            'subMenu' => [
                                [
                                    'title' => 'Event Type',
                                    'routes' => ['admin.event-type.index', 'admin.event-type.create', 'admin.event-type.edit'],
                                    'route' => 'admin.event-type.index',
                                ],

                                [
                                    'title' => 'Events',
                                    'routes' => ['admin.event.index', 'admin.event.create', 'admin.event.edit'],
                                    'route' => 'admin.event.index',
                                ],
                            ],
                        ],
                        //====================== Event Management End ==============

                        //====================== Frontend Management Start ============
                        [
                            'title' => 'Frontend Management',
                            'icon' => 'fa fa-desktop',

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
                                    'title' => 'Banner',
                                    'routes' => ['admin.banner.index', 'admin.banner.create', 'admin.banner.edit'],
                                    'route' => 'admin.banner.index',
                                ],

                                [
                                    'title' => 'Services',
                                    'routes' => ['admin.service.index', 'admin.service.create', 'admin.service.edit'],
                                    'route' => 'admin.service.index',
                                ],

                                [
                                    'title' => 'Blog Category',
                                    'routes' => [
                                        'admin.blog-category.index',
                                        'admin.blog-category.create',
                                        'admin.blog-category.edit',
                                    ],
                                    'route' => 'admin.blog-category.index',
                                ],

                                [
                                    'title' => 'Blog',
                                    'routes' => ['admin.blog-post.index', 'admin.blog-post.create', 'admin.blog-post.edit'],
                                    'route' => 'admin.blog-post.index',
                                ],

                                [
                                    'title' => 'Contact',
                                    'routes' => ['admin.contact.index', 'admin.contact.create', 'admin.contact.edit'],
                                    'route' => 'admin.contact.index',
                                ],

                                [
                                    'title' => 'Subscription',

                                    'routes' => [
                                        'admin.subscription.index',
                                        'admin.subscription.create',
                                        'admin.subscription.edit',
                                    ],

                                    'route' => 'admin.subscription.index',
                                ],
                            ],
                        ],
                        //====================== Frontend Management End ==============

                        // ========================= Setting Start ====================
                        [
                            'title' => 'Web Settings',
                            'icon' => 'fas fa-cog',
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
                                    'title' => 'Setting',
                                    'routes' => ['admin.settings.index'],
                                    'route' => 'admin.settings.index',
                                ],

                                [
                                    'title' => 'FAQs',
                                    'routes' => ['admin.faq.index', 'admin.faq.create', 'admin.faq.edit'],
                                    'route' => 'admin.faq.index',
                                ],

                                [
                                    'title' => 'Term & Condition',
                                    'routes' => ['admin.terms.index', 'admin.terms.create', 'admin.terms.edit'],
                                    'route' => 'admin.terms.index',
                                ],

                                [
                                    'title' => 'Privacy Policy',
                                    'routes' => ['admin.privacy.index', 'admin.privacy.create', 'admin.privacy.edit'],
                                    'route' => 'admin.privacy.index',
                                ],
                            ],
                        ],
                        // ========================= Setting End ======================

                        // =================== Management Section Start ===============
                        [
                            'title' => 'User Management',
                            'icon' => 'fas fa-users',

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
                                    'title' => 'Staff',
                                    'routes' => ['admin.staff.index', 'admin.staff.create', 'admin.staff.edit'],
                                    'route' => 'admin.staff.index',
                                ],

                                [
                                    'title' => 'User',
                                    'routes' => ['admin.user.index', 'admin.user.create', 'admin.user.edit'],
                                    'route' => 'admin.user.index',
                                ],
                            ],
                        ],
                        // =================== Management Section End =================

                        // =================== Role & Permission Start ================
                        // [
                        //     'title' => 'Role & Permission',
                        //     'icon' => 'icons/duotune/ecommerce/ecm002.svg',

                        //     'routes' => [
                        //         'all.role',
                        //         'all.permission',
                        //         'all.admin.permission',
                        //         'add.roles.permission',
                        //         'all.roles.permission',
                        //     ],

                        //     'subMenu' => [
                        //         [
                        //             'title' => 'All Admin',
                        //             'routes' => ['all.admin.permission'],
                        //             'route' => 'all.admin.permission',
                        //         ],
                        //         [
                        //             'title' => 'Role',
                        //             'routes' => ['all.role'],
                        //             'route' => 'all.role',
                        //         ],
                        //         [
                        //             'title' => 'Permission',
                        //             'routes' => ['all.permission'],
                        //             'route' => 'all.permission',
                        //         ],
                        //     ],
                        // ],
                        // ================== Role & Permission End ===================
                    ];
                @endphp

                {{-- @if (Auth::guard('admin')->user()->can('brand.menu') || Auth::guard('admin')->user()->can('permission.menu') || Auth::guard('admin')->user()->can('role.menu') || Auth::guard('admin')->user()->can('admin.menu') || Auth::guard('admin')->user()->can('web_setting.menu')) --}}

                @foreach ($menuItems as $item)
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Route::is(...$item['routes'] ?? []) ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                            </span>
                            <span class="menu-title">{{ $item['title'] }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        @if (!empty($item['subMenu']))
                            <div
                                class="menu-sub menu-sub-accordion {{ Route::is(...$item['routes'] ?? []) ? 'menu-active-bg' : '' }}">
                                @foreach ($item['subMenu'] as $subItem)
                                    @if (isset($subItem['subMenu']))
                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
                                            <span class="menu-link">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">{{ $subItem['title'] }}</span>
                                                <span class="menu-arrow"></span>
                                            </span>
                                            <div
                                                class="menu-sub menu-sub-accordion {{ Route::is(...array_column($subItem['subMenu'], 'route') ?? []) ? 'here show' : '' }}">
                                                @foreach ($subItem['subMenu'] as $subSubItem)
                                                    <div class="menu-item">
                                                        @if (isset($subSubItem['route']))
                                                            <a class="menu-link {{ Route::is($subSubItem['route']) ? 'active' : '' }}"
                                                                href="{{ route($subSubItem['route']) }}">
                                                                <span class="menu-bullet">
                                                                    <span class="bullet bullet-dot"></span>
                                                                </span>
                                                                <span
                                                                    class="menu-title">{{ $subSubItem['title'] }}</span>
                                                            </a>
                                                        @else
                                                            <span class="menu-title">{{ $subSubItem['title'] }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="menu-item">
                                            @if (isset($subItem['route']))
                                                <a class="menu-link {{ Route::is($subItem['routes']) ? 'active' : '' }}"
                                                    href="{{ route($subItem['route']) }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">{{ $subItem['title'] }}</span>
                                                </a>
                                            @else
                                                <span class="menu-title">{{ $subItem['title'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                {{-- @endif --}}

            </div>
        </div>
    </div>

    <div class="aside-footer flex-column-auto pt-5 pb-7 px-5" id="kt_aside_footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            <a href="{{ route('admin.logout') }}" class="btn btn-custom btn-primary w-100"
                onclick="event.preventDefault();this.closest('form').submit();">
                <span class="btn-label">
                    @csrf
                    {{ __('Log Out') }}
                </span>
            </a>
        </form>
    </div>

</div>
