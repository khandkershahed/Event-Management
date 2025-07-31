<x-admin-app-layout :title="$user->name . ' Profile Update'">
    <div class="d-flex flex-column flex-lg-row">

        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">

            <div class="card mb-5 mb-xl-8">

                <div class="card-body">


                    <div class="d-flex flex-center flex-column py-5">

                        <div class="symbol symbol-100px symbol-circle mb-7">
                            {{-- <img src="{{ asset('admin/assets/media/svg/avatars/blank-dark.svg') }}" alt="image" /> --}}
                            <img src="{{ !empty(Auth::guard('admin')->user()->photo) && file_exists(public_path('storage/' . Auth::guard('admin')->user()->photo)) ? asset('storage/' . Auth::guard('admin')->user()->photo) : asset('https://ui-avatars.com/api/?name=' . urlencode(Auth::guard('admin')->user()->name)) }}"
                                alt="{{ Auth::guard('admin')->user()->name }}" />
                        </div>


                        <a href="#"
                            class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3">{{ $user->name }}</a>


                        <div class="mb-9">
                            @foreach ($user->getRoleNames() as $role)
                                <div class="badge badge-lg badge-light-primary d-inline">{{ $role }}</div>
                            @endforeach

                        </div>



                        {{-- <div class="fw-bolder mb-3">Assigned Tickets
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover"
                                data-bs-trigger="hover" data-bs-html="true"
                                data-bs-content="Number of support tickets assigned, closed and pending this week."></i>
                        </div>

                        <div class="d-flex flex-wrap flex-center">

                            <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                <div class="fs-4 fw-bolder text-gray-700">
                                    <span class="w-75px">243</span>

                                    <span class="svg-icon svg-icon-3 svg-icon-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="13" y="6" width="13" height="2"
                                                rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                                            <path
                                                d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>

                                </div>
                                <div class="fw-bold text-muted">Total</div>
                            </div>


                            <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                <div class="fs-4 fw-bolder text-gray-700">
                                    <span class="w-50px">56</span>

                                    <span class="svg-icon svg-icon-3 svg-icon-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="11" y="18" width="13" height="2"
                                                rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                            <path
                                                d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>

                                </div>
                                <div class="fw-bold text-muted">Solved</div>
                            </div>


                            <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                <div class="fs-4 fw-bolder text-gray-700">
                                    <span class="w-50px">188</span>

                                    <span class="svg-icon svg-icon-3 svg-icon-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="13" y="6" width="13" height="2"
                                                rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                                            <path
                                                d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>

                                </div>
                                <div class="fw-bold text-muted">Open</div>
                            </div>

                        </div> --}}

                    </div>



                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse"
                            data-bs-target="#user_collapse_details_{{ $user->id }}" role="button"
                            aria-expanded="false" aria-controls="user_collapse_details_{{ $user->id }}">
                            Details
                            <span class="ms-2 rotate-180">

                                <span class="svg-icon svg-icon-3">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>

                            </span>
                        </div>
                    </div>

                    <div class="separator"></div>

                    <div class="collapse show" id="user_collapse_details_{{ $user->id }}">
                        <div class="pb-5 fs-6">

                            @if (!empty($user->username))
                                <div class="fw-bolder mt-5">User Name</div>
                                <div class="text-gray-600">{{ $user->username }}</div>
                            @endif


                            @if (!empty($user->email))
                                <div class="fw-bolder mt-5">Email</div>
                                <div class="text-gray-600">
                                    <a href="#" class="text-gray-600 text-hover-primary">{{ $user->email }}</a>
                                </div>
                            @endif


                            @if (!empty($user->address) || !empty($user->country))
                                <div class="fw-bolder mt-5">Address</div>
                                <div class="text-gray-600">{{ $user->address }}</div>
                                <div class="text-gray-600">{{ $user->city }},{{ $user->country }}</div>
                            @endif


                            <div class="fw-bolder mt-5">Language</div>
                            <div class="text-gray-600">English</div>


                            @if (!empty($user->created_at))
                                <div class="fw-bolder mt-5">Account Created At</div>
                                <div class="text-gray-600">{{ $user->created_at->format('D, d M Y') }}</div>
                            @endif

                        </div>
                    </div>


                </div>

            </div>


        </div>


        <div class="flex-lg-row-fluid ms-lg-15">

            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">

                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                        href="#kt_user_view_overview_tab">Overview</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                        href="#kt_user_view_overview_security">Security</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                        href="#kt_user_view_overview_events_and_logs_tab">Account Deactivate/Delete</a>
                </li>




            </ul>


            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                    @include('admin.profile.partials.update-profile-information-form')
                </div>


                <div class="tab-pane fade" id="kt_user_view_overview_security" role="tabpanel">

                    @include('admin.profile.partials.update-password-form')

                </div>


                <div class="tab-pane fade" id="kt_user_view_overview_events_and_logs_tab" role="tabpanel">

                    @include('admin.profile.partials.delete-user-form')

                </div>

            </div>

        </div>

    </div>
</x-admin-app-layout>

{{--
    @include('admin.profile.partials.update-profile-information-form')
    @include('admin.profile.partials.update-password-form')
    @include('admin.profile.partials.delete-user-form')
 --}}
