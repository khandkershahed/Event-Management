<x-admin-app-layout :title="'New Site'">

    <style>
        .bxs-star {
            color: #f7941d;
        }
    </style>

    @if (Auth::guard('admin')->user()->status == 'active')

        <div class="row gy-5 g-xl-8">

            {{-- Total Job  --}}
            <div class="col-xl-4">
                <div class="card h-xl-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span style="font-size: 20px" class="card-label fw-bold text-gray-900">Total</span>
                        </h3>
                    </div>

                    <div class="card-body pt-6">


                        <div class="d-flex flex-stack">

                            <div class="symbol me-5">
                                <div class="text-inverse-danger">
                                    <img src="https://ui-avatars.com/api/?name=Job+Offer&size=40" alt="Avatar">
                                </div>
                            </div>

                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">

                                <div class="flex-grow-1 me-2">
                                    <a href="javascript:;"
                                        class="text-gray-800 text-hover-primary fs-6 fw-bold">AAAAA</a>

                                    {{-- <span class="text-muted fw-semibold d-block fs-7">
                                        Deadline:

                                        <span class="text-danger">Error: Job time deadline has passed.</span>

                                    </span> --}}

                                </div>

                                {{-- <a title="Needed Person" href="javascript:;"
                                    class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                    hgnhgng
                                </a> --}}

                            </div>
                        </div>

                        <div class="separator separator-dashed my-4"></div>


                    </div>
                </div>
            </div>
            {{-- Total Job  --}}

            {{-- Middle Section  --}}

            @php
                // Set the timezone to Bangladesh
                $hour = \Carbon\Carbon::now('Asia/Dhaka')->format('H');
                $greeting = '';

                if ($hour < 12) {
                    $greeting = 'Good Morning';
                } elseif ($hour < 18) {
                    $greeting = 'Good Afternoon';
                } else {
                    $greeting = 'Good Evening';
                }
            @endphp

            <div class="col-xl-4">
                <div class="card card-flush h-xl-100">

                    <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-200px"
                        data-bs-theme="light" style="background-color: #023154">
                        <h2 style="font-size: 22px"
                            class="align-items-center justify-content-center flex-column text-white pt-15">
                            <span class="fw-bold mb-3">{{ $greeting }} :
                                {{ Auth::guard('admin')->user()->name }}</span>
                        </h2>
                    </div>


                    <div class="card-body mt-n20">
                        <div class="mt-n20 position-relative">
                            <div class="row g-3 g-lg-6">
                                <div class="col-6">
                                    <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                        <div class="symbol symbol-30px me-5 mb-8">
                                            <span class="symbol-label">
                                                <i class="fas fa-check" style="font-size: 30px; color: green;"></i>
                                            </span>
                                        </div>

                                        <div class="m-0">
                                            <h4>Total Staff</h4>
                                            <span class="text-gray-500 fw-semibold fs-6"></span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                        <div class="symbol symbol-30px me-5 mb-8">
                                            <span class="symbol-label">
                                                <i class="fas fa-user" style="font-size: 30px; color: green;"></i>
                                            </span>
                                        </div>

                                        <div class="m-0">
                                            <h4>Total User</h4>

                                            <span class="text-gray-500 fw-semibold fs-6"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">

                                        <div class="symbol symbol-30px me-5 mb-8">
                                            <span class="symbol-label">
                                                <i class="fas fa-user-check" style="font-size: 30px; color: green;"></i>
                                            </span>
                                        </div>

                                        <div class="m-0">
                                            <h4>Total Product</h4>
                                            <span class="text-gray-500 fw-semibold fs-6"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">

                                        <div class="symbol symbol-30px me-5 mb-8">
                                            <span class="symbol-label">
                                                <i class="fas fa-briefcase" style="font-size: 30px; color: green;"></i>
                                            </span>
                                        </div>

                                        <div class="m-0">
                                            <h4>Total Blog</h4>

                                            <span class="text-gray-500 fw-semibold fs-6"></span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-xl-4">
                <div class="card h-xl-100">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span style="font-size: 20px" class="card-label fw-bold text-gray-900">Total Client
                                Review</span>
                        </h3>
                    </div>

                    <div class="card-body pt-6">


                        <div class="d-flex flex-stack">

                            <div class="symbol me-5">
                                <div class="text-inverse-danger">
                                    <img src="https://ui-avatars.com/api/?name=R&size=40" alt="Avatar">
                                </div>
                            </div>


                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">

                                <div class="flex-grow-1 me-2">
                                    <a href="javascript:;"
                                        class="text-gray-800 text-hover-primary fs-6 fw-bold">AAAAAA</a>

                                    <span class="text-muted fw-semibold d-block fs-7">
                                        Position: AAAAA
                                    </span>

                                </div>

                                <a title="Needed Person" href="javascript:;" class="">
                                    AAAAAA
                                </a>

                            </div>
                        </div>

                        <div class="separator separator-dashed my-4"></div>

                    </div>
                </div>
            </div>

        </div>

        <div class="row g-5 g-xl-8 mt-5">

            <div class="col-12 mb-5">

                <div class="card">
                    <div class="card-body">
                        <!--begin::Row-->
                        <div class="row">

                            {{-- @foreach ($alladmins as $admin)
                                @php

                                    $productCountByAdmins = App\Models\Product::where('added_by', $admin->id)->count();
                                    $brandCountByAdmins = App\Models\Brand::where('added_by', $admin->id)->count();
                                    $categoryCountByAdmins = App\Models\Category::where(
                                        'added_by',
                                        $admin->id,
                                    )->count();

                                @endphp

                                <!--begin::Col-->
                                <div class="col-lg-3">
                                    <div class="bg-light-dark p-8 rounded-2">

                                        <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                                        <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">

                                            <img src="{{ !empty($admin->photo) ? url('storage/admin_images/' . $admin->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($admin->name) }}"
                                                height="70" width="70" alt="{{ $admin->name }}"
                                                style="border-radius: 50%; object-fit: cover;">

                                            <p class="text-dark fs-6 float-end">{{ $admin->name }}
                                                <br><span>{{ $admin->email }}</span>
                                            </p>

                                        </span>

                                        <!--end::Svg Icon-->

                                        <div class="mb-4">
                                            <span class="float-end fw-bolder badge bg-dark">Brand
                                                {{ $brandCountByAdmins }}</span>
                                            <span class="float-end fw-bolder badge bg-info mx-1">Category
                                                {{ $categoryCountByAdmins }}</span>
                                            <span class="float-end fw-bolder badge bg-primary mx-1">Product
                                                {{ $productCountByAdmins }}</span>
                                        </div>

                                    </div>

                                </div>
                                <!--end::Col-->
                            @endforeach --}}

                        </div>
                        <!--end::Row-->
                    </div>
                </div>


            </div>

        </div>
    @else
        <p class="text-danger">Admin Is not approved your account</p>
        <span class="text-danger">Please wait super admin approve in your account as soon as possible</span>
    @endif



</x-admin-app-layout>
