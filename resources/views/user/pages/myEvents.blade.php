<x-frontend-app-layout :title="'My Events'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
		<div class="dashboard-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="d-main-title">
							<h3><i class="fa-solid fa-calendar-days me-3"></i>Events</h3>
						</div>
					</div>
					<div class="col-md-12">
						<div class="mt-5 main-card">
							<div class="p-4 dashboard-wrap-content">
								<h5 class="mb-4">Events (1)</h5>
								<div class="flex-wrap d-md-flex align-items-center">
									<div class="dashboard-date-wrap">
										<div class="form-group">
											<div class="relative-input position-relative">
												<input class="form-control h_40" type="text" placeholder="Search by event name, status" value="">
												<i class="uil uil-search"></i>
											</div>
										</div>
									</div>
									<div class="rs ms-auto mt_r4">
										<div class="nav custom2-tabs btn-group" role="tablist">
											<button class="tab-link active" data-bs-toggle="tab" data-bs-target="#all-tab" type="button" role="tab" aria-controls="all-tab" aria-selected="true">All Event (<span class="total_event_counter">1</span>)</button>
											<button class="tab-link" data-bs-toggle="tab" data-bs-target="#online-tab" type="button" role="tab" aria-controls="online-tab" aria-selected="false" tabindex="-1">Online Event (<span class="total_event_counter">0</span>)</button>
											<button class="tab-link" data-bs-toggle="tab" data-bs-target="#venue-tab" type="button" role="tab" aria-controls="venue-tab" aria-selected="false" tabindex="-1">Venue Event (<span class="total_event_counter">1</span>)</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="event-list">
							<div class="tab-content">
								<div class="tab-pane fade active show" id="all-tab" role="tabpanel">
									<div class="mt-4 main-card">
										<div class="contact-list">
											<div class="flex-wrap p-4 card-top event-top align-items-center top d-md-flex justify-content-between">
												<div class="d-md-flex align-items-center event-top-info">
													<div class="card-event-img">
														<img src="https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/event-imgs/img-7.jpg" alt="">
													</div>
													<div class="card-event-dt">
														<h5>Tutorial on Canvas Painting for Beginners</h5>
													</div>
												</div>
												<div class="dropdown">
													<button class="option-btn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
													<div class="dropdown-menu dropdown-menu-right" style="">
														<a href="#" class="dropdown-item"><i class="fa-solid fa-gear me-3"></i>Manage</a>
														<a href="#" class="dropdown-item"><i class="fa-solid fa-eye me-3"></i>Preview Event</a>
														<a href="#" class="dropdown-item"><i class="fa-solid fa-clone me-3"></i>Duplicate</a>
														<a href="#" class="dropdown-item delete-event"><i class="fa-solid fa-trash-can me-3"></i>Delete</a>
													</div>
												</div>
											</div>
											<div class="flex-wrap p-4 bottom d-flex justify-content-between align-items-center">
												<div class="icon-box ">
													<span class="icon">
														<i class="fa-solid fa-location-dot"></i>
													</span>
													<p>Status</p>
													<h6 class="coupon-status">Publish</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-calendar-days"></i>
													</span>
													<p>Starts on</p>
													<h6 class="coupon-status">30 Jun, 2022 10:00 AM</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-ticket"></i>
													</span>
													<p>Ticket</p>
													<h6 class="coupon-status">250</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-tag"></i>
													</span>
													<p>Tickets sold</p>
													<h6 class="coupon-status">20</h6>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="online-tab" role="tabpanel">
									<div class="mt-4 main-card d-none">
										<div class="d-flex align-items-center justify-content-center flex-column min-height-430">
											<div class="event-list-icon">
												<img src="images/calendar.png" alt="">
											</div>
											<p class="mt-4 font-16 text-light3">No Event found</p>
										</div>
									</div>
									<div class="mt-4 main-card">
										<div class="contact-list">
											<div class="flex-wrap p-4 card-top event-top align-items-center top d-md-flex justify-content-between">
												<div class="d-md-flex align-items-center event-top-info">
													<div class="card-event-img">
														<img src="images/event-imgs/img-2.jpg" alt="">
													</div>
													<div class="card-event-dt">
														<h5>Earrings Workshop with Bronwyn David</h5>
													</div>
												</div>
												<div class="dropdown">
													<button class="option-btn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="#" class="dropdown-item"><i class="fa-solid fa-gear me-3"></i>Manage</a>
														<a href="#" class="dropdown-item"><i class="fa-solid fa-eye me-3"></i>Preview Event</a>
														<a href="#" class="dropdown-item"><i class="fa-solid fa-clone me-3"></i>Duplicate</a>
														<a href="#" class="dropdown-item delete-event"><i class="fa-solid fa-trash-can me-3"></i>Delete</a>
													</div>
												</div>
											</div>
											<div class="flex-wrap p-4 bottom d-flex justify-content-between align-items-center">
												<div class="icon-box ">
													<span class="icon">
														<i class="fa-solid fa-location-dot"></i>
													</span>
													<p>Status</p>
													<h6 class="coupon-status">Publish</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-calendar-days"></i>
													</span>
													<p>Starts on</p>
													<h6 class="coupon-status">30 Jun, 2022 10:00 AM</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-ticket"></i>
													</span>
													<p>Ticket</p>
													<h6 class="coupon-status">250</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-tag"></i>
													</span>
													<p>Tickets sold</p>
													<h6 class="coupon-status">20</h6>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="venue-tab" role="tabpanel">
									<div class="mt-4 main-card">
										<div class="contact-list">
											<div class="flex-wrap p-4 card-top event-top align-items-center top d-md-flex justify-content-between">
												<div class="d-md-flex align-items-center event-top-info">
													<div class="card-event-img">
														<img src="images/event-imgs/img-7.jpg" alt="">
													</div>
													<div class="card-event-dt">
														<h5>Tutorial on Canvas Painting for Beginners</h5>
													</div>
												</div>
												<div class="dropdown">
													<button class="option-btn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
													<div class="dropdown-menu dropdown-menu-right">
														<a href="#" class="dropdown-item"><i class="fa-solid fa-gear me-3"></i>Manage</a>
														<a href="#" class="dropdown-item"><i class="fa-solid fa-eye me-3"></i>Preview Event</a>
														<a href="#" class="dropdown-item"><i class="fa-solid fa-clone me-3"></i>Duplicate</a>
														<a href="#" class="dropdown-item delete-event"><i class="fa-solid fa-trash-can me-3"></i>Delete</a>
													</div>
												</div>
											</div>
											<div class="flex-wrap p-4 bottom d-flex justify-content-between align-items-center">
												<div class="icon-box ">
													<span class="icon">
														<i class="fa-solid fa-location-dot"></i>
													</span>
													<p>Status</p>
													<h6 class="coupon-status">Publish</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-calendar-days"></i>
													</span>
													<p>Starts on</p>
													<h6 class="coupon-status">30 Jun, 2022 10:00 AM</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-ticket"></i>
													</span>
													<p>Ticket</p>
													<h6 class="coupon-status">250</h6>
												</div>
												<div class="icon-box">
													<span class="icon">
														<i class="fa-solid fa-tag"></i>
													</span>
													<p>Tickets sold</p>
													<h6 class="coupon-status">20</h6>
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