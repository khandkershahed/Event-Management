<div class="card">
    <div class="card-header align-items-center">
        <h3 class="fw-bold my-2">
            User(<span class="text-info">{{ $user->first_name }} {{ $user->last_name }}</span>) Overview
        </h3>
    </div>
    <div class="card-body">
        <div class="row g-6 g-xl-9">
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header flex-nowrap border-0 pt-9">
                        <div class="card-title m-0">
                            <div class="symbol symbol-45px w-45px me-5">
                                <div class="symbol symbol-45px w-45px me-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" x="0"
                                        y="0" viewBox="0 0 66 66" style="enable-background:new 0 0 512 512"
                                        xml:space="preserve" class="">
                                        <g>
                                            <path
                                                d="M48.1 61.9c-.6 0-1 .4-1 1V64H2V12.5h10.1c-.5 1.6-.4 2.7-.4 3.8 0 .6.4 1 1 1h23.8c.6 0 1-.4 1-1 0-1.1.1-2.2-.4-3.8h10.1v6.9c0 .6.4 1 1 1s1-.4 1-1v-7.9c0-.6-.4-1-1-1h-12c-1-1.8-2.6-3.2-4.6-4.2-.2-3.5-3.3-6.4-7-6.4s-6.8 2.8-7 6.4c-2 .9-3.5 2.4-4.6 4.2H1c-.6 0-1 .4-1 1V65c0 .6.4 1 1 1h47.1c.6 0 1-.4 1-1v-2.1c0-.5-.5-1-1-1zm-29.2-54c.4-.2.7-.6.6-1.2 0-2.6 2.2-4.7 5-4.7s5 2.1 5 4.7c-.1.6.2 1 .6 1.2 3.2 1.2 5.2 4.1 5.3 7.4H13.7c0-3.2 2.1-6.1 5.2-7.4z"
                                                fill="#000000" opacity="1" data-original="#000000" class="">
                                            </path>
                                            <path
                                                d="M24.5 11.7c2.1 0 3.9-1.7 3.9-3.9s-1.7-3.9-3.9-3.9c-2.1 0-3.9 1.7-3.9 3.9s1.8 3.9 3.9 3.9zm0-5.8c1 0 1.9.8 1.9 1.9s-.8 1.9-1.9 1.9c-1 0-1.9-.8-1.9-1.9s.9-1.9 1.9-1.9zM14.7 23.3c0-.6-.4-1-1-1H6.9c-.6 0-1 .4-1 1v7.3c0 .6.4 1 1 1h6.8c.6 0 1-.4 1-1zm-2 6.2H7.9v-5.3h4.8zM14.7 36.7c0-.6-.4-1-1-1H6.9c-.6 0-1 .4-1 1V44c0 .6.4 1 1 1h6.8c.6 0 1-.4 1-1zm-2 6.3H7.9v-5.3h4.8zM13.7 49.2H6.9c-.6 0-1 .4-1 1v7.3c0 .6.4 1 1 1h6.8c.6 0 1-.4 1-1v-7.3c0-.6-.5-1-1-1zm-1 7.3H7.9v-5.3h4.8zM65.4 32l-18.2-9.9c-.3-.2-.7-.2-.9 0-1.2.6-15.7 8.4-18.1 9.7-.3.2-.5.6-.5.9v16c0 .3.2.7.5.8l18.1 11.4c.3.2.7.2 1 0l18.2-9.7c.3-.2.5-.5.5-.9V32.9c0-.3-.2-.7-.6-.9zm-18.6-7.9L63 32.9l-4.2 2.3-16.2-8.9zm8 13.8L38 28.8l2.4-1.3L57.7 37v4.9l-2.4 1.3v-4.4c0-.4-.2-.8-.5-.9zm-18.9-8 16.3 8.9-5.4 3-16.1-9.1zM29.6 48V34.3l16.1 9.1v14.8zm18.2 10.3V43.5l5.5-3v4.4c0 .4.2.7.5.9s.7.2 1 0l4.4-2.4c.3-.2.5-.5.5-.9V37l4.3-2.3v15.1zM32.8 26.9c0-.6-.4-1-1-1H17.5c-.6 0-1 .4-1 1s.4 1 1 1h14.3c.5 0 1-.4 1-1zM25.7 39.4h-8.3c-.6 0-1 .4-1 1s.4 1 1 1h8.3c.6 0 1-.4 1-1s-.5-1-1-1zM15.8 53.8c0 .6.4 1 1 1h13.1c.6 0 1-.4 1-1s-.4-1-1-1H16.8c-.5 0-1 .5-1 1z"
                                                fill="#000000" opacity="1" data-original="#000000" class="">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                            </div>

                            <a href="#" class="fs-4 fw-semibold text-hover-primary text-gray-600 m-0">
                                Total Order </a>
                        </div>

                        {{-- <div class="card-toolbar m-0">
                            <button type="button"
                                class="btn btn-clean btn-sm btn-icon btn-icon-primary btn-active-light-primary me-n3"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">

                                <i class="fa-solid fa-element-plus fs-3 text-primary"></i> </button>


                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        Payments
                                    </div>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        Create Invoice
                                    </a>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link flex-stack px-3">
                                        Create Payment

                                        <span class="ms-2" data-bs-toggle="tooltip"
                                            aria-label="Specify a target name for future usage and reference"
                                            data-bs-original-title="Specify a target name for future usage and reference"
                                            data-kt-initialized="1">
                                            <i class="fa-solid fa-information fs-6"><span class="path1"></span><span
                                                    class="path2"></span><span class="path3"></span></i> </span>
                                    </a>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        Generate Bill
                                    </a>
                                </div>

                                <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                    data-kt-menu-placement="right-end">
                                    <a href="#" class="menu-link px-3">
                                        <span class="menu-title">Subscription</span>
                                        <span class="menu-arrow"></span>
                                    </a>

                                    <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Plans
                                            </a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Billing
                                            </a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Statements
                                            </a>
                                        </div>

                                        <div class="separator my-2"></div>

                                        <div class="menu-item px-3">
                                            <div class="menu-content px-3">
                                                <label
                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox"
                                                        value="1" checked="checked" name="notifications">

                                                    <span class="form-check-label text-muted fs-6">
                                                        Recuring
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="menu-item px-3 my-1">
                                    <a href="#" class="menu-link px-3">
                                        Settings
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="card-body d-flex flex-column px-9 pt-10 pb-10">
                        <div class="fs-2tx fw-bold mb-3">
                            <span class="text-info">{{ optional($user->order)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header flex-nowrap border-0 pt-9">
                        <div class="card-title m-0">
                            <div class="symbol symbol-45px w-45px me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" x="0" y="0"
                                    viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" xml:space="preserve"
                                    class="">
                                    <g>
                                        <g data-name="Layer 7">
                                            <path
                                                d="M12.6 31.751 1.385 27.078l-.77 1.845L8 32v9a1 1 0 0 0 1 1h5.055a13.279 13.279 0 0 0 1.823 2.293 2.408 2.408 0 0 0 3.742-.418l.193.243a2.342 2.342 0 0 0 4.082-.811 2.4 2.4 0 0 0 3.772-.518A2.346 2.346 0 0 0 30.561 42H45a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v8.339l-6.457-4.178L.457 14.84 16.63 25.305ZM10 40v-7.166l2 .833v1.262A13.224 13.224 0 0 0 13 40Zm18.36.87-4.591-5.51-1.538 1.281 2.487 2.983 1.131 1.508a.764.764 0 0 1 .151.454.43.43 0 0 1-.707.293l-4.586-4.586-1.414 1.415 1.885 1.885.637 1.273a1.75 1.75 0 0 1 .185.783.365.365 0 0 1-.624.22l-3.6-4.493-1.562 1.249 1.678 2.1a3.822 3.822 0 0 1 .1.864.43.43 0 0 1-.707.293A11.173 11.173 0 0 1 14 34.929v-1.643l4.449-7.119 3.21.357a78.207 78.207 0 0 0 8.6.476.743.743 0 0 1 .234 1.449A10.712 10.712 0 0 1 27.1 29H25a1 1 0 0 0-.895 1.448l4.715 9.428a1.711 1.711 0 0 1 .18.762.376.376 0 0 1-.64.232ZM24 10h6v8h-6Zm-14 8.633V10h12v9a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-9h12v30H30.925a3.66 3.66 0 0 0-.316-1.018L30.118 38H42v-2H29.118l-2.5-5h.482a12.752 12.752 0 0 0 4.029-.653A2.744 2.744 0 0 0 30.257 25a76.435 76.435 0 0 1-8.377-.463l-3.331-.37-.006-.006ZM43.294 44.091l-5.057.722A2.62 2.62 0 0 0 36 47.381l-6.13-1.226a7.035 7.035 0 0 0-4.491.538 2.494 2.494 0 0 0 .326 4.6l12.9 4.3a8.01 8.01 0 0 0 4.475.172l5.972-1.493a.99.99 0 0 0 .872.73l13 1L63.077 54 51 53.074V47h12v-2H50a.989.989 0 0 0-.732.331 9.035 9.035 0 0 0-5.974-1.24Zm-.7 9.73a6.022 6.022 0 0 1-3.357-.129l-12.9-4.3a.494.494 0 0 1-.064-.91 5.023 5.023 0 0 1 3.205-.366l7.95 1.59a2.575 2.575 0 0 0 1.178.294H42v-2h-3.394a.607.607 0 0 1-.085-1.207l5.056-.722a7.076 7.076 0 0 1 4.89 1.109l.533.356v4.684Z"
                                                fill="#000000" opacity="1" data-original="#000000"></path>
                                            <path d="M34 32h8v2h-8z" fill="#000000" opacity="1"
                                                data-original="#000000"></path>
                                        </g>
                                    </g>
                                </svg>
                            </div>

                            <a href="#" class="fs-4 fw-semibold text-hover-primary text-gray-600 m-0">
                                Product Recived </a>
                        </div>

                        {{-- <div class="card-toolbar m-0">
                            <button type="button"
                                class="btn btn-clean btn-sm btn-icon btn-icon-primary btn-active-light-primary me-n3"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">

                                <i class="fa-solid fa-element-plus fs-3 text-primary"></i> </button>


                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        Payments
                                    </div>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        Create Invoice
                                    </a>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link flex-stack px-3">
                                        Create Payment

                                        <span class="ms-2" data-bs-toggle="tooltip"
                                            aria-label="Specify a target name for future usage and reference"
                                            data-bs-original-title="Specify a target name for future usage and reference"
                                            data-kt-initialized="1">
                                            <i class="fa-solid fa-information fs-6"><span class="path1"></span><span
                                                    class="path2"></span><span class="path3"></span></i> </span>
                                    </a>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        Generate Bill
                                    </a>
                                </div>

                                <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                    data-kt-menu-placement="right-end">
                                    <a href="#" class="menu-link px-3">
                                        <span class="menu-title">Subscription</span>
                                        <span class="menu-arrow"></span>
                                    </a>

                                    <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Plans
                                            </a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Billing
                                            </a>
                                        </div>

                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Statements
                                            </a>
                                        </div>

                                        <div class="separator my-2"></div>

                                        <div class="menu-item px-3">
                                            <div class="menu-content px-3">
                                                <label
                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox"
                                                        value="1" checked="checked" name="notifications">

                                                    <span class="form-check-label text-muted fs-6">
                                                        Recuring
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="menu-item px-3 my-1">
                                    <a href="#" class="menu-link px-3">
                                        Settings
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="card-body d-flex flex-column px-9 pt-10 pb-10">
                        <div class="fs-2tx fw-bold mb-3">
                            <span class="text-info">
                                {{ $deliveredOrders->sum(function ($order) {
                                    return $order->orderItems->count();
                                }) }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header flex-nowrap border-0 pt-9">
                        <div class="card-title m-0">
                            <div class="symbol symbol-45px w-45px me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" x="0" y="0"
                                    viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve"
                                    class="">
                                    <g>
                                        <path
                                            d="m87.4 85.1-14.1 4.6c-.7-2.4-2.5-4.3-4.8-5.3L52 77.6c-6.8-2.8-14.7-.8-19.3 4.9h-4.6v-1.7c0-1-.8-1.8-1.8-1.8H12.8c-1 0-1.8.8-1.8 1.8v29.5c0 1 .8 1.7 1.7 1.8h13.5c1 0 1.7-.8 1.8-1.7v-1.7h5.2l20 6.5c3.8 1.2 7.9 1.1 11.6-.3l29.1-11.1c5.1-1.9 7.8-7.6 5.8-12.8-1.6-5-7.2-7.7-12.3-5.9zm-62.8 23.5h-10v-26h10zm7.2-3.4h-3.7V86h3.7zm61-4.7-29.1 11.1c-3 1.1-6.3 1.2-9.3.2l-19.1-6.2V84.8c3.7-4.6 9.9-6.2 15.4-4l16.5 6.8c2.5 1.1 3.7 3.9 2.6 6.4-1 2.5-3.9 3.7-6.3 2.7l-14.6-5.9c-.9-.4-1.9 0-2.3.9s0 2 1 2.4l14.6 5.9c4.3 1.7 9.2-.3 11-4.7.3-.7.5-1.4.5-2.1l14.9-4.9c3.4-1.1 7 .7 8.1 4.1 1 3.4-.7 6.9-3.9 8.1zM115 14.9h-13.5c-1 0-1.7.8-1.8 1.8v1.7h-5.2l-20-6.5c-3.8-1.2-7.9-1.1-11.6.3l-13.5 5.1-5.8-5.8c-.7-.7-1.8-.7-2.5 0L28.6 24.1l-6.1 6.1c-1.2 1.2 0 2.6 0 2.5l40.4 40.4c.7.7 1.8.7 2.5 0L84 54.5c.7-.7.7-1.8 0-2.5l-1.3-1.3c4.8-.2 9.3-2.4 12.4-6.1h4.6v1.7c0 1 .8 1.7 1.7 1.8H115c1 0 1.7-.8 1.8-1.8V16.7c-.1-1-.9-1.8-1.8-1.8zm-72.7.4 3.5 3.5c-2.2 1.3-4.8 1.3-7 0zM29.7 35l-3.5-3.5 3.5-3.5c1.2 2.1 1.2 4.8 0 7zm34.4 34.4-3.5-3.5c2.2-1.3 4.9-1.3 7 0zm6.1-6c-3.6-2.6-8.5-2.6-12.1 0L32.2 37.5c2.6-3.6 2.6-8.5 0-12.1l4.1-4.1c3.9 2.8 9.3 2.3 12.1 0l7.9 7.8c-.8.9-1.4 1.9-1.8 2.9s-.6 2.2-.5 3.3c-3.9-.5-7.5 2.4-7.9 6.3-.5 4.2 2.8 8 7.1 8 3.7 0 6.7-2.8 7.1-6.4l13.1 5.4c-1.8 3.5-1.5 7.6.8 10.8zM55.8 45c-1.4 1.4-3.7 1.4-5.2 0-1.4-1.4-1.4-3.7 0-5.2 1.3-1.3 3.5-1.4 4.9-.2 1.8 1.5 1.7 3.9.3 5.4zm21 11.8c-1.3-2.1-1.3-4.8 0-7h.1l3.4 3.4zm15.6-14.6c-3.4 4.2-9 6-14.1 4.4 0 0-11.6-4.7-17.8-7.3-2.3-.9-3.7-3.6-2.8-6.2s3.9-3.9 6.5-2.9l14.6 5.9c.9.4 1.9-.1 2.3-1s-.1-1.9-1-2.3L65.6 27c-2.1-.8-4.4-.8-6.5.1l-7.1-7 12-4.6c3-1.1 6.3-1.2 9.3-.3l19.1 6.2zm7.3-1.1H96V21.9h3.7zm13.5 3.4h-10v-26h10z"
                                            fill="#000000" opacity="1" data-original="#000000"></path>
                                    </g>
                                </svg>
                            </div>

                            <a href="#" class="fs-4 fw-semibold text-hover-primary text-gray-600 m-0">
                                Total Purchase </a>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column px-9 pt-10 pb-10">
                        <div class="fs-2tx fw-bold mb-3">
                            <span class="text-info">{{ $totalPurchaseAmount }}</span> (৳)
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header flex-nowrap border-0 pt-9">
                        <div class="card-title m-0">
                            <div class="symbol symbol-45px w-45px me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" x="0"
                                    y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                    xml:space="preserve" class="">
                                    <g>
                                        <path
                                            d="M385.39 177.89 346.21 280.2a6.007 6.007 0 0 1-5.6 3.86 6.01 6.01 0 0 1-5.6-8.15l39.18-102.31a6.006 6.006 0 0 1 7.75-3.46c3.09 1.19 4.64 4.66 3.45 7.75zm60 249.66c0 38.29-31.15 69.45-69.45 69.45-29.05 0-53.98-17.94-64.33-43.31h-210.7c-18.92 0-34.31-15.39-34.31-34.31V49.31C66.61 30.39 82 15 100.92 15h246.71c18.92 0 34.31 15.39 34.31 34.31v46.55c4.82-1 9.97-.69 14.9 1.2l22.82 8.74c6.62 2.54 11.87 7.52 14.77 14.02 2.9 6.51 3.11 13.74.57 20.36l-7.38 19.26c-.01.03-.03.05-.04.07h.01l-45.65 114.61v84.26c35.5 3.06 63.45 32.9 63.45 69.17zM414.2 160.72l-50.05-19.17-62.62 163.56 47.86 18.33zM301.33 317.9l7.1 44.58 32.96-29.24zm72.33-201.21-5.23 13.65 50.13 19.2 5.23-13.66c1.39-3.63 1.27-7.6-.32-11.18-1.6-3.58-4.48-6.32-8.1-7.71l-22.82-8.74c-7.53-2.87-16 .91-18.89 8.44zm-65.71 325c-.95-4.56-1.45-9.29-1.45-14.13 0-36.27 27.95-66.12 63.45-69.17v-54.13l-11.61 29.14h-.01c-.34.86-.87 1.64-1.58 2.27l-48.54 43.06a6.007 6.007 0 0 1-6 1.16 5.992 5.992 0 0 1-3.9-4.71l-10.45-65.66a5.99 5.99 0 0 1 .32-3.09l66.91-174.77 7.38-19.26c1.63-4.26 4.25-7.86 7.49-10.64V49.31c0-12.3-10.01-22.31-22.31-22.31H100.92c-12.3 0-22.31 10.01-22.31 22.31v370.07c0 12.3 10.01 22.31 22.31 22.31zm125.44-14.14c0-31.68-25.77-57.45-57.45-57.45s-57.45 25.77-57.45 57.45S344.26 485 375.94 485s57.45-25.77 57.45-57.45zm-32.87-22.59-32.46 32.46-16.69-16.69c-2.34-2.34-6.14-2.34-8.48 0s-2.34 6.14 0 8.49l20.93 20.93c1.12 1.12 2.65 1.76 4.24 1.76s3.12-.63 4.24-1.76l36.7-36.71c2.34-2.34 2.34-6.14 0-8.49a5.99 5.99 0 0 0-8.48.01zm-87.8-13.2c0-3.31-2.69-6-6-6h-93.5c-3.31 0-6 2.69-6 6s2.69 6 6 6h93.5c3.31 0 6-2.69 6-6zM127.89 94.63h201.92c3.31 0 6-2.69 6-6s-2.69-6-6-6H127.89c-3.31 0-6 2.69-6 6s2.69 6 6 6zm0 58.5h182.5c3.31 0 6-2.69 6-6s-2.69-6-6-6h-182.5c-3.31 0-6 2.69-6 6s2.69 6 6 6zm0 58.51h160.06c3.31 0 6-2.69 6-6s-2.69-6-6-6H127.89c-3.31 0-6 2.69-6 6s2.69 6 6 6zm0 58.5h135.6c3.31 0 6-2.69 6-6s-2.69-6-6-6h-135.6c-3.31 0-6 2.69-6 6s2.69 6 6 6zm0 58.51h140.7c3.31 0 6-2.69 6-6s-2.69-6-6-6h-140.7c-3.31 0-6 2.69-6 6s2.69 6 6 6zm71.53 29.79v65.53c0 3.31-2.69 6-6 6h-65.53c-3.31 0-6-2.69-6-6v-65.53c0-3.31 2.69-6 6-6h65.53c3.31 0 6 2.69 6 6zm-12 6h-53.53v53.53h53.53zm-35.87 42.33c1.12 1.12 2.65 1.76 4.24 1.76s3.12-.63 4.24-1.76l22.65-22.65c2.34-2.34 2.34-6.14 0-8.49a6 6 0 0 0-8.48 0l-18.41 18.41-8.68-8.68c-2.34-2.34-6.14-2.34-8.48 0s-2.34 6.14 0 8.49z"
                                            fill="#000000" opacity="1" data-original="#000000"></path>
                                    </g>
                                </svg>
                            </div>

                            <a href="#" class="fs-4 fw-semibold text-hover-primary text-gray-600 m-0">
                                Total Refund Request </a>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column px-9 pt-20 pb-20">
                        <div class="fs-2tx fw-bold mb-3">
                            <span class="text-info">{{ $cancelledOrders->count() }}</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header flex-nowrap border-0 pt-9">
                        <div class="card-title m-0">
                            <div class="symbol symbol-45px w-45px me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" x="0"
                                    y="0" viewBox="0 0 64 64" style="enable-background:new 0 0 512 512"
                                    xml:space="preserve" class="">
                                    <g>
                                        <path
                                            d="M4.123 43.338v6.567a1 1 0 0 0 1 1h25.944C32.672 57.379 38.501 62 45.25 62c8.065 0 14.627-6.562 14.627-14.626 0-7.809-5.987-14.11-13.683-14.579-2.67-4.251-6.919-7.404-11.76-8.669a19.497 19.497 0 0 0-1.686-.354c3.394-2.06 5.674-5.78 5.674-10.032C38.422 7.267 33.155 2 26.682 2S14.944 7.267 14.944 13.74c0 1.22.188 2.42.557 3.562a11.708 11.708 0 0 0 5.085 6.475c-.556.096-1.109.207-1.651.35-8.721 2.281-14.811 10.182-14.811 19.21zm53.754 4.036C57.877 54.336 52.213 60 45.25 60c-6.07 0-11.288-4.323-12.408-10.278a12.877 12.877 0 0 1-.218-2.348c0-6.963 5.664-12.627 12.626-12.627.073 0 .14.002.207.004.048 0 .087 0 .147.003a12.557 12.557 0 0 1 12.273 12.62zm-14.06-14.558-.03.005c-.142.014-.28.043-.42.06-.333.044-.665.087-.99.153-.18.035-.352.086-.528.128-.28.067-.563.132-.837.216-.185.056-.364.125-.546.189-.257.089-.514.177-.764.28-.184.075-.361.162-.541.245-.239.11-.477.22-.71.342-.177.093-.348.196-.521.296-.223.13-.445.26-.66.402-.17.11-.333.227-.497.345-.208.148-.413.299-.612.457-.159.127-.312.257-.465.39a14.76 14.76 0 0 0-.992.942 14.749 14.749 0 0 0-1.356 1.637c-.118.165-.234.33-.346.5-.14.215-.27.435-.398.656-.102.175-.204.349-.299.528-.12.23-.23.466-.338.702-.084.182-.171.362-.248.549-.1.246-.188.5-.276.753-.064.185-.134.368-.191.557-.081.27-.146.545-.212.82-.043.182-.094.36-.13.544-.063.31-.104.627-.145.944-.02.156-.052.309-.067.466-.048.478-.074.962-.074 1.452 0 .481.028.965.076 1.449.003.027.001.054.004.082H6.123v-5.567c0-7.85 5.125-14.736 12.55-17.041l7.117 14.11a.999.999 0 0 0 1.785 0l7.117-14.11a17.978 17.978 0 0 1 9.124 6.52zM17.403 16.691a9.59 9.59 0 0 1-.46-2.951c0-5.37 4.369-9.74 9.739-9.74s9.739 4.37 9.739 9.74-4.37 9.739-9.74 9.739a9.705 9.705 0 0 1-9.278-6.788zm11.972 8.788c1.127 0 2.243.107 3.333.311l-6.026 11.947-6.026-11.947a18.027 18.027 0 0 1 3.332-.311z"
                                            fill="#000000" opacity="1" data-original="#000000"></path>
                                        <path
                                            d="M44.256 39.305v1.543c-1.97.124-3.54 1.75-3.54 3.752a3.78 3.78 0 0 0 3.775 3.775h1.524c.978 0 1.775.796 1.775 1.775s-.797 1.776-1.775 1.776H44.49a1.778 1.778 0 0 1-1.775-1.776 1 1 0 1 0-2 0c0 2.001 1.57 3.628 3.54 3.752v1.546a1 1 0 1 0 2 0v-1.547c1.968-.127 3.534-1.752 3.534-3.75a3.78 3.78 0 0 0-3.775-3.776H44.49c-.978 0-1.775-.796-1.775-1.775s.797-1.776 1.775-1.776h.75l.015.003c.005 0 .01-.003.014-.003h.745c.978 0 1.775.797 1.775 1.776a1 1 0 1 0 2 0c0-2-1.566-3.624-3.534-3.751v-1.544a1 1 0 1 0-2 0z"
                                            fill="#000000" opacity="1" data-original="#000000"></path>
                                    </g>
                                </svg>
                            </div>

                            <a href="#" class="fs-4 fw-semibold text-hover-primary text-gray-600 m-0">
                                User Status </a>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column px-9 pt-6 pb-8">
                        <div class="text-center">
                            @if ($user->status == 'active')
                                <img class="img-fluid" width="150px" src="{{ asset('images/verified.jpeg') }}"
                                    alt="">
                            @else
                                <img class="img-fluid" width="150px" src="{{ asset('images/unverified.jpeg') }}"
                                    alt="">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header flex-nowrap border-0 pt-9">
                        <div class="card-title m-0">
                            <div class="symbol symbol-45px w-45px me-5">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" x="0"
                                    y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512"
                                    xml:space="preserve" class="">
                                    <g>
                                        <path
                                            d="M327.392 268.183a8.536 8.536 0 0 0 11.708-2.937c2.417-4.046 1.1-9.288-2.942-11.704-8.401-5.028-17.044-9.412-25.797-13.235 29.344-25.062 48.039-62.25 48.039-103.773C358.4 61.25 297.15 0 221.867 0S85.333 61.25 85.333 136.533c0 41.521 18.692 78.706 48.032 103.768C53.024 275.229 0 355.105 0 443.733v59.733C0 508.179 3.817 512 8.533 512s8.533-3.821 8.533-8.533v-59.733c0-85.231 53.141-161.657 132.55-191.564 20.986 13.16 45.705 20.897 72.25 20.897 26.571 0 51.313-7.75 72.312-20.935a207.681 207.681 0 0 1 33.214 16.051zM102.4 136.533c0-65.875 53.592-119.467 119.467-119.467s119.467 53.592 119.467 119.467S287.742 256 221.867 256 102.4 202.408 102.4 136.533zM392.533 273.067c-65.875 0-119.467 53.592-119.467 119.467S326.658 512 392.533 512 512 458.408 512 392.533s-53.592-119.466-119.467-119.466zm0 221.866c-56.467 0-102.4-45.938-102.4-102.4s45.933-102.4 102.4-102.4c56.467 0 102.4 45.938 102.4 102.4 0 56.463-45.933 102.4-102.4 102.4z"
                                            fill="#000000" opacity="1" data-original="#000000"></path>
                                        <path
                                            d="M452.267 384h-51.2v-51.2a8.53 8.53 0 0 0-8.533-8.533 8.53 8.53 0 0 0-8.533 8.533V384h-51.2a8.53 8.53 0 0 0-8.533 8.533 8.53 8.53 0 0 0 8.533 8.533H384v51.2a8.53 8.53 0 0 0 8.533 8.533 8.53 8.53 0 0 0 8.533-8.533v-51.2h51.2a8.53 8.53 0 0 0 8.533-8.533 8.53 8.53 0 0 0-8.532-8.533z"
                                            fill="#000000" opacity="1" data-original="#000000"></path>
                                    </g>
                                </svg>
                            </div>

                            <a href="#" class="fs-4 fw-semibold text-hover-primary text-gray-600 m-0">
                                Account Created </a>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column px-9 pt-20 pb-20">
                        <div class="fs-1 fw-bold mb-3">
                            <span class="text-info">{{ $user->created_at->format('d F Y') }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
