<x-frontend-app-layout :title="'My Coupons'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-main-title">
                            <h3><i class="fa-solid fa-chart-pie me-3"></i>Reports</h3>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-5 main-card">
                            <div class="p-4 dashboard-wrap-content">
                                <div class="nav custom2-tabs btn-group" role="tablist">
                                    <button class="tab-link ms-0" data-bs-toggle="tab" data-bs-target="#orders-tab" type="button" role="tab" aria-controls="orders-tab" aria-selected="false" tabindex="-1">Orders (<span class="total_event_counter">1</span>)</button>
                                    <button class="tab-link" data-bs-toggle="tab" data-bs-target="#customers-tab" type="button" role="tab" aria-controls="customers-tab" aria-selected="false" tabindex="-1">Customers (<span class="total_event_counter">0</span>)</button>
                                    <button class="tab-link" data-bs-toggle="tab" data-bs-target="#tickets-tab" type="button" role="tab" aria-controls="tickets-tab" aria-selected="false" tabindex="-1">Tickets (<span class="total_event_counter">1</span>)</button>
                                    <button class="tab-link active" data-bs-toggle="tab" data-bs-target="#payouts-tab" type="button" role="tab" aria-controls="payouts-tab" aria-selected="true">Payouts (<span class="total_event_counter">1</span>)</button>
                                </div>
                                <div class="flex-wrap d-md-flex align-items-center">
                                    <div class="mt-4 dashboard-date-wrap">
                                        <div class="form-group">
                                            <div class="relative-input position-relative">
                                                <input class="form-control h_40" type="text" placeholder="Search by name" value="">
                                                <i class="pt-1 fa fa-search"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 rs ms-auto mt_r4">
                                        <a href="javascript:void(0);" class="text-center pe-4 w-100 ps-4 co-main-btn h_40 d-inline-block"><i class="fa-solid fa-arrow-rotate-right me-3"></i>Refresh</a>
                                    </div>
                                </div>
                                <div class="mt-4 main-form">
                                    <div class="row g-3">
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <div class="dropdown bootstrap-select"><select class="selectpicker" data-size="5" data-live-search="true" tabindex="null">
                                                        <option value="all events" selected="">All Events</option>
                                                    </select>
                                                    <div class="dropdown-menu" style="overflow: hidden;">
                                                        <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-1" aria-autocomplete="list" aria-activedescendant="bs-select-1-0"></div>
                                                        <div class="inner show" role="listbox" id="bs-select-1" tabindex="-1" style="overflow-y: auto;">
                                                            <ul class="dropdown-menu inner show" role="presentation" style="margin-top: 0px; margin-bottom: 0px;">
                                                                <li class="selected active"><a role="option" class="dropdown-item active selected" id="bs-select-1-0" tabindex="0" aria-setsize="1" aria-posinset="1" aria-selected="true"><span class="text">All Events</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <div class="dropdown bootstrap-select dropup"><select class="selectpicker" tabindex="null">
                                                        <option value="Today">Today</option>
                                                        <option value="Yesterday">Yesterday</option>
                                                        <option value="Last 7 Days">Last 7 Days</option>
                                                        <option value="Last 30 Days" selected="">Last 30 Days</option>
                                                        <option value="This Month">This Month</option>
                                                        <option value="Last Month">Last Month</option>
                                                        <option value="Custom Range">Custom Range</option>
                                                    </select>
                                                    <div class="dropdown-menu" style="max-height: 291.797px; overflow: hidden; min-height: 139px;">
                                                        <div class="inner show" role="listbox" id="bs-select-2" tabindex="-1" aria-activedescendant="bs-select-2-3" style="max-height: 275.797px; overflow-y: auto; min-height: 123px;">
                                                            <ul class="dropdown-menu inner show" role="presentation" style="margin-top: 0px; margin-bottom: 0px;">
                                                                <li><a role="option" class="dropdown-item" id="bs-select-2-0" tabindex="0"><span class="text">Today</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-2-1" tabindex="0"><span class="text">Yesterday</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-2-2" tabindex="0"><span class="text">Last 7 Days</span></a></li>
                                                                <li class="selected active"><a role="option" class="dropdown-item active selected" id="bs-select-2-3" tabindex="0" aria-setsize="7" aria-posinset="4" aria-selected="true"><span class="text">Last 30 Days</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-2-4" tabindex="0"><span class="text">This Month</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-2-5" tabindex="0"><span class="text">Last Month</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-2-6" tabindex="0"><span class="text">Custom Range</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <div class="dropdown bootstrap-select dropup"><select class="selectpicker" tabindex="null">
                                                        <option value="All Orders" selected="">All Orders</option>
                                                        <option value="Refunded">Refunded</option>
                                                        <option value="Refund Rejected">Refund Rejected</option>
                                                        <option value="Refund Requested">Refund Requested</option>
                                                        <option value="Partially Refunded">Partially Refunded</option>
                                                    </select>
                                                    <div class="dropdown-menu" style="max-height: 291.797px; overflow: hidden; min-height: 139px;">
                                                        <div class="inner show" role="listbox" id="bs-select-3" tabindex="-1" aria-activedescendant="bs-select-3-0" style="max-height: 275.797px; overflow-y: auto; min-height: 123px;">
                                                            <ul class="dropdown-menu inner show" role="presentation" style="margin-top: 0px; margin-bottom: 0px;">
                                                                <li class="selected active"><a role="option" class="dropdown-item active selected" id="bs-select-3-0" tabindex="0" aria-setsize="5" aria-posinset="1" aria-selected="true"><span class="text">All Orders</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-3-1" tabindex="0"><span class="text">Refunded</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-3-2" tabindex="0"><span class="text">Refund Rejected</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-3-3" tabindex="0"><span class="text">Refund Requested</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-3-4" tabindex="0"><span class="text">Partially Refunded</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <div class="dropdown bootstrap-select"><select class="selectpicker" tabindex="null">
                                                        <option value="all">All Orders</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Canceled</option>
                                                    </select>
                                                    <div class="dropdown-menu" style="max-height: 238.203px; overflow: hidden; min-height: 0px;">
                                                        <div class="inner show" role="listbox" id="bs-select-4" tabindex="-1" aria-activedescendant="bs-select-4-0" style="max-height: 222.203px; overflow-y: auto; min-height: 0px;">
                                                            <ul class="dropdown-menu inner show" role="presentation" style="margin-top: 0px; margin-bottom: 0px;">
                                                                <li class="selected active"><a role="option" class="dropdown-item active selected" id="bs-select-4-0" tabindex="0" aria-setsize="3" aria-posinset="1" aria-selected="true"><span class="text">All Orders</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-4-1" tabindex="0"><span class="text">Active</span></a></li>
                                                                <li><a role="option" class="dropdown-item" id="bs-select-4-2" tabindex="0"><span class="text">Canceled</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="event-list">
                            <div class="tab-content">
                                <div class="tab-pane fade" id="orders-tab" role="tabpanel">
                                    <div class="mt-4 table-card">
                                        <div class="main-table">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">ID</th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Event Name</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Reference</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>5291</td>
                                                            <td>Larry Paige</td>
                                                            <td><a href="#" target="_blank">Astrology on sunday event</a></td>
                                                            <td>04/22/2022</td>
                                                            <td>E1021100NA8711001</td>
                                                            <td><span class="status-circle red-circle"></span>Canceled</td>
                                                            <td>AUD $0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5290</td>
                                                            <td>John Cena</td>
                                                            <td><a href="#" target="_blank">Melbourne plant sale</a></td>
                                                            <td>04/22/2022</td>
                                                            <td>E1021100NA8711002</td>
                                                            <td><span class="status-circle green-circle"></span>Paid</td>
                                                            <td>AUD $0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5289</td>
                                                            <td>Gleen Smith</td>
                                                            <td><a href="#" target="_blank">Testing Events</a></td>
                                                            <td>04/21/2022</td>
                                                            <td>E1021100NA8711003</td>
                                                            <td><span class="status-circle blue-circle"></span>Refunded</td>
                                                            <td>AUD $0.00</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="customers-tab" role="tabpanel">
                                    <div class="mt-4 table-card">
                                        <div class="main-table">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">ID</th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Email address</th>
                                                            <th scope="col">Address</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>2356</td>
                                                            <td>Larry Paige</td>
                                                            <td>larry@example.com</td>
                                                            <td>140 St Kilda Rd, St Kilda, Victoria, Melbourne, Victoria, 3000, Australia</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2355</td>
                                                            <td>John Cena</td>
                                                            <td>johncena@example.com</td>
                                                            <td>140 St Kilda Rd, St Kilda, Victoria, Melbourne, Victoria, 3000, Australia</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2354</td>
                                                            <td>Jassica William</td>
                                                            <td>jassica@example.com</td>
                                                            <td>140 St Kilda Rd, St Kilda, Victoria, Melbourne, Victoria, 3000, Australia</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2353</td>
                                                            <td>Rock William</td>
                                                            <td>rockwilliam@example.com</td>
                                                            <td>140 St Kilda Rd, St Kilda, Victoria, Melbourne, Victoria, 3000, Australia</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2352</td>
                                                            <td>Gleen Smith</td>
                                                            <td>gleensmith@example.com</td>
                                                            <td>140 St Kilda Rd, St Kilda, Victoria, Melbourne, Victoria, 3000, Australia</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2351</td>
                                                            <td>John Doe</td>
                                                            <td>johndoe@example.com</td>
                                                            <td>140 St Kilda Rd, St Kilda, Victoria, Melbourne, Victoria, 3000, Australia</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tickets-tab" role="tabpanel">
                                    <div class="mt-4 table-card">
                                        <div class="main-table">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">Order ID</th>
                                                            <th scope="col">Reference ID</th>
                                                            <th scope="col">Customer Name</th>
                                                            <th scope="col">Email Address</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>2356</td>
                                                            <td>F6ACCM-R76MTK-1434658508</td>
                                                            <td>Larry Paige</td>
                                                            <td>larry@example.com</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2355</td>
                                                            <td>F6ACCM-R76MTK-1434658508</td>
                                                            <td>Gleen William</td>
                                                            <td>gleenwilliam@example.com</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2354</td>
                                                            <td>F6ACCM-R76MTK-1434658508</td>
                                                            <td>Rock Smith</td>
                                                            <td>rocksmith@example.com</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2353</td>
                                                            <td>F6ACCM-R76MTK-1434658508</td>
                                                            <td>John Cena</td>
                                                            <td>johncena@example.com</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade active show" id="payouts-tab" role="tabpanel">
                                    <div class="mt-4 table-card">
                                        <div class="main-table">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">Remittance ID</th>
                                                            <th scope="col">Remittance Date</th>
                                                            <th scope="col">Date Paid</th>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Transaction ID</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>12475</td>
                                                            <td>28/1.04/2022</td>
                                                            <td>26/04/2022</td>
                                                            <td>22/04/2022</td>
                                                            <td>TXR21234123UX</td>
                                                            <td><a href="#" class="a-link">Download</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.menu-toggle-btn').on('click', function() {
                $('.vertical_nav').toggleClass('active');
            });
        });
    </script>
    @endpush
</x-frontend-app-layout>