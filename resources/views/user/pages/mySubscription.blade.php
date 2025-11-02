<x-frontend-app-layout :title="'My Coupons'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
		<div class="dashboard-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="d-main-title">
							<h3><i class="fa-solid fa-bahai me-3"></i>Subscription</h3>
						</div>
					</div>
					<div class="col-md-12">
						<div class="mt-5 main-card subscription-bg">
							<div class="d-md-flex d-sm-block justify-content-between align-items-center">
								<div class="s-info">
									<h3>Basic</h3>
									<p>All the tools you need to organise your free online and venue events, absolutely free!</p>
								</div>
								<div class="s-price">
									<h3>Free<span class="font-15 font-weight-semibold"></span></h3>
								</div>
							</div>
							<div class="since-text">Member since 026/04/2022</div>
						</div>
						<div class="p-4 mt-4 main-card">
							<div class="subscription-title">
								<h3 class="mt-3 mb-4">Premium plan includes all of these great features :</h3>							
							</div>
							<div class="subscription-feature-lists">
								<div class="row">
									<div class="col-xl-4 col-lg-6 col-md-6">
										<div class="mt-4 feature-item text-start p_30 subscription-item">
											<div class="feature-icon">
												<img src="	https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/icons/feature-icon-1.png" alt="">
											</div>
											<h4>Remove the Restrictions</h4>
											<p>No restriction on free events. Host large venue and online events.</p>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6">
										<div class="mt-4 feature-item text-start p_30 subscription-item">
											<div class="feature-icon">`
												<img src="	https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/icons/feature-icon-12.png" alt="">
											</div>
											<h4>Organiser App</h4>
											<p>Stay on top of things, manage and monitor your events using the organiser app.</p>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6">
										<div class="mt-4 feature-item text-start p_30 subscription-item">
											<div class="feature-icon">
												<img src="	https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/icons/feature-icon-9.png" alt="">
											</div>
											<h4>Advanced Tools</h4>
											<p>Turbo charge your sales, marketing and attendee management with the premium only tools.</p>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6">
										<div class="mt-4 feature-item text-start p_30 subscription-item">
											<div class="feature-icon">
												<img src="	https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/icons/feature-icon-8.png" alt="">
											</div>
											<h4>Live Streaming</h4>
											<p>Livestream your online events on Facebook, YouTube and other social networks.</p>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6">
										<div class="mt-4 feature-item text-start p_30 subscription-item">
											<div class="feature-icon">
												<img src="	https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/icons/online-class.png" alt="">
											</div>
											<h4>Messaging and Sharing</h4>
											<p>Exchange instant messages, securely share screens and files in your online events.</p>
										</div>
									</div>
									<div class="col-xl-4 col-lg-6 col-md-6">
										<div class="mt-4 feature-item text-start p_30 subscription-item">
											<div class="feature-icon">
												<img src="	https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/icons/feature-icon-7.png" alt="">
											</div>
											<h4>Recording</h4>
											<p>Securely record your online events and save on the cloud of your choice.</p>
										</div>
									</div>
									<div class="col-xl-12 col-lg-12 col-md-12">
										<div class="mt-5 text-center">
											<a href="checkout_premium.html" class="main-btn btn-hover">Upgrade to Premium<i class="fa-solid fa-arrow-right ms-2"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="p-4 mt-4 main-card d-flex justify-content-between align-items-center">
							<div class="bp-info">
								<h4>Billing Information</h4>
								<p class="mb-0">140 St Kilda Rd, Melbourne, Victoria, 3000, Australia</p>
							</div>
							<button class="text-center pe-4 ps-4 co-main-btn h_40 d-inline-block" data-bs-toggle="modal" data-bs-target="#billinginfoModal">Edit</button>
						</div>
						<div class="p-4 mt-4 main-card d-flex justify-content-between align-items-center">
							<div class="bp-info">
								<h4>Payment Information</h4>
								<p class="mb-0">No payment information added yet.</p>
							</div>
							<button class="text-center pe-4 ps-4 co-main-btn h_40 d-inline-block" data-bs-toggle="modal" data-bs-target="#paymentinfoModal">Edit</button>
						</div>
						<div class="p-4 mt-4 main-card">
							<div class="bp-info">
								<h4>Payment History</h4>
							</div>
							<div class="mt-4 table-card">
								<div class="main-table">
									<div class="table-responsive">
										<table class="table">
											<thead class="thead-dark">
												<tr>
													<th scope="col">ID</th>
													<th scope="col">Details</th>
													<th scope="col">Amount</th>
													<th scope="col">Status</th>
													<th scope="col">Invoice</th>
												</tr>
											</thead>
											<tbody>
												<tr>										
													<td>INV-0QAXMJ-0000</td>	
													<td>Basic</td>	
													<td>$0.00</td>	
													<td>N/A</td>	
													<td>
														<a href="#" class="ml-1 btn-gray no-bg">
															<svg width="28" height="24" viewBox="0 0 28 24" fill="none">
																<path d="M20.3023 18V21H6.76796V18H5.07617V21C5.07617 21.3978 5.25441 21.7794 5.57169 22.0607C5.88896 22.342 6.31927 22.5 6.76796 22.5H20.3023C20.751 22.5 21.1813 22.342 21.4986 22.0607C21.8158 21.7794 21.9941 21.3978 21.9941 21V18H20.3023Z" fill="#FF2116"></path>
																<path d="M17.7636 15.75L16.5675 14.6895L14.3801 16.629V10.5H12.6883V16.629L10.5008 14.6895L9.30469 15.75L13.5342 19.5L17.7636 15.75Z" fill="#FF2116"></path>
																<path d="M23.6847 3V1.5H18.6094V9H20.3012V6H22.8389V4.5H20.3012V3H23.6847Z" fill="#FF2116"></path>
																<path d="M14.3797 9H10.9961V1.5H14.3797C15.0525 1.5006 15.6976 1.73784 16.1733 2.15967C16.6491 2.5815 16.9167 3.15345 16.9174 3.75V6.75C16.9167 7.34655 16.6491 7.9185 16.1733 8.34033C15.6976 8.76216 15.0525 8.9994 14.3797 9ZM12.6879 7.5H14.3797C14.604 7.4998 14.819 7.42072 14.9776 7.28011C15.1362 7.1395 15.2253 6.94885 15.2256 6.75V3.75C15.2253 3.55115 15.1362 3.3605 14.9776 3.21989C14.819 3.07928 14.604 3.0002 14.3797 3H12.6879V7.5Z" fill="#FF2116"></path>
																<path d="M7.61229 1.5H3.38281V9H5.0746V6.75H7.61229C8.06077 6.7494 8.49069 6.59118 8.80782 6.31C9.12495 6.02883 9.30341 5.64764 9.30408 5.25V3C9.30363 2.6023 9.12524 2.221 8.80807 1.93978C8.49089 1.65856 8.06084 1.5004 7.61229 1.5ZM5.0746 5.25V3H7.61229L7.61313 5.25H5.0746Z" fill="#FF2116"></path>
															</svg>
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