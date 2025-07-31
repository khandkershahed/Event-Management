<div id="kt_header" class="header align-items-stretch">

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
                            <a href="{{ $breadcrumb['url'] }}" class= "text-hover-primary">{{ $breadcrumb['name'] }}</a>
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
            <a href="" class="d-lg-none">
                {{-- <img alt="Logo" src="{{ asset('admin/assets/media/logos/logo-2.svg') }}" class="h-30px" /> --}}
            </a>
        </div>


        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-stretch" id="kt_header_nav"> </div>

            <div class="flex-shrink-0 d-flex align-items-stretch">

                {{-- Notification Start  --}}
                @php
                    // $alladminNots = Auth::guard('admin')->user();
                    // $ncount = Auth::guard('admin')->user()->unreadNotifications->count();
                    // $latestNotifications = $alladminNots->notifications->take(7);
                @endphp

                {{-- <div class="d-flex align-items-center ms-1 ms-lg-3">

                    <div class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                        data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">

                        <div class="position-relative">
                            <i class="fa-solid fa-bell fs-3"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge badge-sm rounded-pill bg-danger">
                                {{ $ncount }}
                            </span>
                        </div>

                    </div>

                    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">

                                <div class="px-8 my-5 scroll-y mh-325px">



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


                </div> --}}
                {{-- Notification End  --}}

                @php
                    $id = Auth::user()->id;
                    $userData = App\Models\User::find($id);
                @endphp

                <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click"
                        data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <img src="{{ !empty($userData->image) ? asset('storage/user_images/' . $userData->image) : asset('upload/no_image.jpg') }}" alt="Image">
                    </div>
                    <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold fs-6 w-275px"
                        data-kt-menu="true">
                        <div class="px-3 menu-item">
                            <div class="px-3 menu-content d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">

                                    <img src="{{ !empty($userData->image) ? asset('storage/user_images/' . $userData->image) : asset('upload/no_image.jpg') }}" alt="Admin Image">

                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-5">
                                        {{ $userData->name }}
                                    </div>
                                    <a href="mailto:" class="fw-bold text-muted text-hover-primary fs-7"
                                        style="overflow-wrap: anywhere;">
                                        {{ $userData->email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="my-2 separator"></div>

                        <div class="px-5 menu-item">
                            <a href="{{ route('user.profile') }}" class="px-5 menu-link">My
                                Profile</a>
                        </div>

                        <div class="px-5 menu-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
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
