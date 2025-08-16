<x-admin-app-layout :title="'Admin Dashboard'">

    <div class="py-4 container-fluid">

        {{-- Greeting --}}
        <div class="mb-4 row">
            <div class="col-12">
                <div class="p-4 py-10 text-black rounded-4">
                    <h2 class="mb-1 display-5 fw-bold">Good Morning, Admin!</h2>
                    <p class="mb-0 lead">Welcome back! Here’s your dashboard overview.</p>
                </div>
            </div>
        </div>

        {{-- Top Metrics --}}
        <div class="mb-10 row gx-5 gx-xl-10">
            <div class="col-md-3">
                <div class="p-4 py-10 shadow-sm card rounded-4 hover-scale" style="border-left: 5px solid #4e73df;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="mb-2 fas fa-calendar-alt fa-3x text-primary"></i>
                            <h5 class="mt-2 mb-1 fw-bold">Total Events</h5>
                        </div>
                        <h2 class="display-5 text-dark">25</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 py-10 shadow-sm card rounded-4 hover-scale" style="border-left: 5px solid #1cc88a;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="mb-2 fas fa-calendar-check fa-3x text-success"></i>
                            <h5 class="mt-2 mb-1 fw-bold">Upcoming Events</h5>
                        </div>
                        <h2 class="display-5 text-dark">10</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 py-10 shadow-sm card rounded-4 hover-scale" style="border-left: 5px solid #f6c23e;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="mb-2 fas fa-users fa-3x text-warning"></i>
                            <h5 class="mt-2 mb-1 fw-bold">Total Users</h5>
                        </div>
                        <h2 class="display-5 text-dark">890</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 py-10 shadow-sm card rounded-4 hover-scale" style="border-left: 5px solid #e74a3b;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="mb-2 fas fa-shopping-cart fa-3x text-danger"></i>
                            <h5 class="mt-2 mb-1 fw-bold">Total Purchases</h5>
                        </div>
                        <h2 class="display-5 text-dark">530</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Secondary Metrics --}}
        <div class="row gx-5 gx-xl-10">
            <div class="col-md-3">
                <div class="p-4 py-10 shadow-sm card rounded-4 hover-scale" style="border-left: 5px solid #36b9cc;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="mb-2 fas fa-ticket-alt fa-3x text-info"></i>
                            <h5 class="mt-2 mb-1 fw-bold">Tickets Sold</h5>
                        </div>
                        <h2 class="display-5 text-dark">1,250</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 py-10 shadow-sm card rounded-4 hover-scale" style="border-left: 5px solid #f6c23e;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="mb-2 fas fa-star fa-3x text-warning"></i>
                            <h5 class="mt-2 mb-1 fw-bold">Client Reviews</h5>
                        </div>
                        <h2 class="display-5 text-dark">54</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 py-10 shadow-sm card rounded-4 hover-scale" style="border-left: 5px solid #1cc88a;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="mb-2 fas fa-box-open fa-3x text-success"></i>
                            <h5 class="mt-2 mb-1 fw-bold">Total Products</h5>
                        </div>
                        <h2 class="display-5 text-dark">350</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-4 py-10 shadow-sm card rounded-4 hover-scale" style="border-left: 5px solid #4e73df;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="mb-2 fas fa-pen-fancy fa-3x text-primary"></i>
                            <h5 class="mt-2 mb-1 fw-bold">Total Blogs</h5>
                        </div>
                        <h2 class="display-5 text-dark">78</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Event Management --}}
        <div class="mt-10 row g-4">
            <div class="col-12">
                <div class="shadow-sm card rounded-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light-warning">
                        <h3 class="mb-0 fw-bold">Event Management</h3>
                        <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="More events coming soon">View All Or Create</a>
                    </div>
                    <div class="p-0 px-3 card-body">
                        <div class="mb-4 row g-4">
                            <div class="col-12">
                                <table class="table border rounded datatable table-striped table-row-bordered gy-5 gs-7">
                                    <thead>
                                        <tr class="text-gray-800 fw-bold fs-6">
                                            <th>SL</th>
                                            <th>Event Name</th>
                                            <th>Total Seats</th>
                                            <th>Available Seats</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Venue</th>
                                            <th>Category</th>
                                            <th>Organizer</th>
                                            <th>Price</th>
                                            <th>Tickets Sold</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="text-start d-flex flex-column justify-content-start align-items-start">
                                                    <img class="rounded-2" src="https://cdn.pixabay.com/photo/2016/11/23/15/48/audience-1853662_1280.jpg" alt="Event 1" width="80">
                                                    <div class="mt-2">
                                                        Annual Tech Conference
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td>500</td>
                                            <td>150</td>
                                            <td>2025-09-20</td>
                                            <td>10:00 AM</td>
                                            <td>City Convention Center</td>
                                            <td>Technology</td>
                                            <td>Tech Corp</td>
                                            <td>$200</td>
                                            <td class="gap-1 d-flex align-items-center justify-content-center">
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-expand"></i>
                                                </a>
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-trash-arrow-up"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="text-start d-flex flex-column justify-content-start align-items-start">
                                                    <img class="rounded-2" src="https://cdn.pixabay.com/photo/2016/11/23/15/48/audience-1853662_1280.jpg" alt="Event 1" width="80">
                                                    <div class="mt-2">
                                                        Annual Tech Conference
                                                    </div>
                                                </div>
                                            </td>
                                            <td>300</td>
                                            <td>80</td>
                                            <td>2025-08-30</td>
                                            <td>2:00 PM</td>
                                            <td>Downtown Hall</td>
                                            <td>Business</td>
                                            <td>Marketing Group</td>
                                            <td>$150</td>
                                            <td>220</td>
                                            <td class="gap-1 d-flex align-items-center justify-content-center">
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-expand"></i>
                                                </a>
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-trash-arrow-up"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="text-start d-flex flex-column justify-content-start align-items-start">
                                                    <img class="rounded-2" src="https://cdn.pixabay.com/photo/2016/11/23/15/48/audience-1853662_1280.jpg" alt="Event 1" width="80">
                                                    <div class="mt-2">
                                                        Annual Tech Conference
                                                    </div>
                                                </div>
                                            </td>
                                            <td>200</td>
                                            <td>0</td>
                                            <td>2025-07-15</td>
                                            <td>11:00 AM</td>
                                            <td>Innovation Hub</td>
                                            <td>Startup</td>
                                            <td>Startup Inc.</td>
                                            <td>$100</td>
                                            <td>200</td>
                                            <td class="gap-1 d-flex align-items-center justify-content-center">
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-expand"></i>
                                                </a>
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="p-2">
                                                    <i class="fas fa-trash-arrow-up"></i>
                                                </a>
                                            </td>

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

    {{-- Styles --}}
    <style>
        .hover-scale {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .hover-scale:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
    </style>

</x-admin-app-layout>