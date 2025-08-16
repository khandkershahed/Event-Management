<div id="kt_header" class="header align-items-stretch" style="box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px !important;">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">

        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
            <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px"
                id="kt_aside_mobile_toggle">

                <span class="svg-icon svg-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                            fill="currentColor" />
                        <path opacity="0.3"
                            d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                            fill="currentColor" />
                    </svg>
                </span>

            </div>
        </div>

        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            @if ($title ?? null)
            <h1 class="my-1 d-flex text-dark fw-bolder fs-3 align-items-center">{{ $title }}</h1>
            <span class="mx-4 border-gray-300 h-20px border-start"></span>
            @endif
            @if (isset($breadcrumbs))
            <ul class="my-1 breadcrumb breadcrumb-separatorless fw-bold fs-5">
                @foreach ($breadcrumbs as $breadcrumb)
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}" class="text-hover-primary">{{ $breadcrumb['name'] }}</a>
                </li>
                @unless ($loop->last)
                <li class="breadcrumb-item">
                    <span class="bg-gray-300 bullet w-5px h-2px"></span>
                </li>
                @endunless
                @endforeach
            </ul>
            @endif
        </div>

        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{ route('admin.dashboard') }}" class="d-lg-none">
                {{-- <img alt="Logo" src="{{ asset('admin/assets/media/logos/logo-2.svg') }}" class="h-30px" /> --}}
            </a>
        </div>

        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-stretch" id="kt_header_nav"> </div>

            <div class="flex-shrink-0 d-flex align-items-stretch">

                {{-- Notification Start  --}}
                @php
                $alladminNots = Auth::guard('admin')->user();
                $ncount = Auth::guard('admin')->user()->unreadNotifications->count();
                $latestNotifications = $alladminNots->notifications->take(7);
                @endphp

                <div class="d-flex align-items-center ms-1 ms-lg-3">

                    <div class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                        data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">

                        <div class="position-relative">
                            <i class="fa-solid fa-bell fs-3"></i>
                            <span
                                class="top-0 position-absolute start-100 translate-middle badge badge-sm rounded-pill bg-danger">
                                {{ $ncount }}
                            </span>
                        </div>

                    </div>

                    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">

                                <div class="px-8 my-5 scroll-y mh-325px">

                                    {{-- @foreach ($latestNotifications as $notification)
                                        <div class="py-4 d-flex flex-stack">
                                            <div class="d-flex align-items-center">

                                                <div class="symbol symbol-35px me-4">
                                                    <span class="symbol-label bg-light-primary">

                                                        <span class="svg-icon svg-icon-2 svg-icon-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3"
                                                                    d="M11 6.5C11 9 9 11 6.5 11C4 11 2 9 2 6.5C2 4 4 2 6.5 2C9 2 11 4 11 6.5ZM17.5 2C15 2 13 4 13 6.5C13 9 15 11 17.5 11C20 11 22 9 22 6.5C22 4 20 2 17.5 2ZM6.5 13C4 13 2 15 2 17.5C2 20 4 22 6.5 22C9 22 11 20 11 17.5C11 15 9 13 6.5 13ZM17.5 13C15 13 13 15 13 17.5C13 20 15 22 17.5 22C20 22 22 20 22 17.5C22 15 20 13 17.5 13Z"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M17.5 16C17.5 16 17.4 16 17.5 16L16.7 15.3C16.1 14.7 15.7 13.9 15.6 13.1C15.5 12.4 15.5 11.6 15.6 10.8C15.7 9.99999 16.1 9.19998 16.7 8.59998L17.4 7.90002H17.5C18.3 7.90002 19 7.20002 19 6.40002C19 5.60002 18.3 4.90002 17.5 4.90002C16.7 4.90002 16 5.60002 16 6.40002V6.5L15.3 7.20001C14.7 7.80001 13.9 8.19999 13.1 8.29999C12.4 8.39999 11.6 8.39999 10.8 8.29999C9.99999 8.19999 9.20001 7.80001 8.60001 7.20001L7.89999 6.5V6.40002C7.89999 5.60002 7.19999 4.90002 6.39999 4.90002C5.59999 4.90002 4.89999 5.60002 4.89999 6.40002C4.89999 7.20002 5.59999 7.90002 6.39999 7.90002H6.5L7.20001 8.59998C7.80001 9.19998 8.19999 9.99999 8.29999 10.8C8.39999 11.5 8.39999 12.3 8.29999 13.1C8.19999 13.9 7.80001 14.7 7.20001 15.3L6.5 16H6.39999C5.59999 16 4.89999 16.7 4.89999 17.5C4.89999 18.3 5.59999 19 6.39999 19C7.19999 19 7.89999 18.3 7.89999 17.5V17.4L8.60001 16.7C9.20001 16.1 9.99999 15.7 10.8 15.6C11.5 15.5 12.3 15.5 13.1 15.6C13.9 15.7 14.7 16.1 15.3 16.7L16 17.4V17.5C16 18.3 16.7 19 17.5 19C18.3 19 19 18.3 19 17.5C19 16.7 18.3 16 17.5 16Z"
                                                                    fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                </div>

                                                <div class="mb-0 me-2">
                                                    <a href="{{ route('admin.notifications.read', $notification->id) }}"
                                    class="fs-6 text-hover-primary fw-bolder
                                    {{ is_null($notification->read_at) ? 'text-danger' : 'text-gray-800' }}">
                                    {{ $notification->data['message'] }}
                                    </a>
                                </div>


                            </div>

                            <span
                                class="badge badge-light fs-8">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                        </div>
                        @endforeach --}}

                        @forelse ($latestNotifications as $notification)
                        <div class="py-4 d-flex flex-stack">
                            <div class="d-flex align-items-center">

                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label">

                                        <span class="svg-icon svg-icon-2 svg-icon-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512">
                                                <path
                                                    d="M64 0C28.7 0 0 28.7 0 64L0 352c0 35.3 28.7 64 64 64l96 0 0 80c0 6.1 3.4 11.6 8.8 14.3s11.9 2.1 16.8-1.5L309.3 416 448 416c35.3 0 64-28.7 64-64l0-288c0-35.3-28.7-64-64-64L64 0z" />
                                            </svg>
                                        </span>

                                    </span>
                                </div>

                                <div class="mb-0 me-2">
                                    <a href="{{ route('admin.notifications.read', $notification->id) }}"
                                        class="fs-6 text-hover-primary fw-bolder
                                                    {{ is_null($notification->read_at) ? 'text-danger' : 'text-gray-800' }}">
                                        {{ $notification->data['message'] }}
                                    </a>
                                </div>


                            </div>

                            <span
                                class="badge badge-light fs-8">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                        </div>
                        @empty
                        <p>No Notification Avaiable</p>
                        @endforelse


                    </div>

                </div>

            </div>

        </div>


    </div>
    {{-- Notification End  --}}

    @php
    $id = Auth::guard('admin')->user()->id;
    $profileData = App\Models\Admin::find($id);

    $roles = Spatie\Permission\Models\Role::latest()->get();

    $routes = Route::current()->getName();
    @endphp

    <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
        <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click"
            data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <img src="{{ !empty($profileData->photo) ? asset('storage/admin_images/' . $profileData->photo) : asset('upload/no_image.jpg') }}"
                alt="Admin Image">
        </div>
        <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold fs-6 w-275px"
            data-kt-menu="true">
            <div class="px-3 menu-item">
                <div class="px-3 menu-content d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">

                        <img src="{{ !empty($profileData->photo) ? asset('storage/admin_images/' . $profileData->photo) : asset('upload/no_image.jpg') }}"
                            alt="Admin Image">

                    </div>
                    <div class="d-flex flex-column">
                        <div class="fw-bolder d-flex align-items-center fs-5">
                            {{ Auth::guard('admin')->user()->name }}
                        </div>
                        <a href="mailto:{{ Auth::guard('admin')->user()->email }}"
                            class="fw-bold text-muted text-hover-primary fs-7"
                            style="overflow-wrap: anywhere;">
                            {{ Auth::guard('admin')->user()->email }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="my-2 separator"></div>

            <div class="px-5 menu-item">
                <a href="{{ route('admin.profile.edit') }}" class="px-5 menu-link">My
                    Profile</a>
            </div>

            <div class="px-5 menu-item" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">

                {{-- <a href="#" class="px-5 menu-link">
                                <span class="menu-title position-relative">Language
                                    <span
                                        class="px-3 py-2 rounded fs-8 bg-light position-absolute translate-middle-y top-50 end-0">English
                                        <img class="w-15px h-15px rounded-1 ms-2"
                                            src="{{ asset('admin/assets/media/flags/united-states.svg') }}"
                alt="" />
                </span>
                </span>
                </a> --}}

                <div class="py-4 menu-sub menu-sub-dropdown w-175px">

                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <div class="px-3 menu-item">
                        <a rel="alternate" hreflang="{{ $localeCode }}"
                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                            class="px-5 menu-link d-flex">
                            <span class="symbol symbol-20px me-4">
                                <img class="rounded-1"
                                    src="{{ asset('admin/assets/media/flags/' . $localeCode . '.svg') }}"
                                    alt="" />
                            </span>{{ $properties['native'] }}
                        </a>
                    </div>
                    @endforeach

                </div>

            </div>
            <div class="px-5 menu-item">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <a href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();"
                        class="px-5 menu-link"> {{ __('Sign Out') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>