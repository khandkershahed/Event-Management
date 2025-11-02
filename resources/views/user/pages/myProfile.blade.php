<x-frontend-app-layout :title="'My Profile'">
    <style>
        footer{
            display: none;
        }
    </style>
    <div class="wrapper">
		<div class="profile-banner">
			<div class="hero-cover-block">
				<div class="hero-cover">
					<div class="hero-cover-img"></div>	
				</div>
				<div class="upload-cover">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<div class="cover-img-btn">
									<input type="file" id="cover-img">
									<label for="cover-img"><i class="fa-solid fa-panorama me-3"></i>Change Image</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="user-dt-block">
				<div class="container">
					<div class="row">
						<div class="col-xl-4 col-lg-5 col-md-12">
							<div class="main-card user-left-dt">
								<div class="user-avatar-img">
									<img src="https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/profile-imgs/img-13.jpg" alt="">
									<div class="avatar-img-btn">
										<input type="file" id="avatar-img">
										<label for="avatar-img"><i class="fa-solid fa-camera"></i></label>
									</div>
								</div>
								<div class="user-dts">
									<h4 class="user-name">John Doe<span class="verify-badge"><i class="fa-solid fa-circle-check"></i></span></h4>
									<span class="user-email">johndoe@example.com</span>
								</div>
								<div class="ff-block">
									<a href="#" class="" role="button" data-bs-toggle="modal" data-bs-target="#FFModal"><span>0</span>Followers</a>
									<a href="#" class="" role="button" data-bs-toggle="modal" data-bs-target="#FFModal"><span>2</span>Following</a>
								</div>
								<div class="user-description">
									<p>Hey I am a John Doe</p>
								</div>
								<div class="user-btns">
									<a href="my_organisation_dashboard.html" class="co-main-btn co-btn-width min-width d-inline-block h_40">My Organisation<i class="fa-solid fa-right-left ms-3"></i></a>
								</div>
								<div class="profile-social-link">
									<h6>Find me on</h6>
									<div class="social-links">
										<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Facebook" data-bs-original-title="Facebook"><i class="fab fa-facebook-square"></i></a>
										<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Instagram" data-bs-original-title="Instagram"><i class="fab fa-instagram"></i></a>
										<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Twitter" data-bs-original-title="Twitter"><i class="fab fa-twitter"></i></a>
										<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="LinkedIn" data-bs-original-title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
										<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Youtube" data-bs-original-title="Youtube"><i class="fab fa-youtube"></i></a>
										<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Website" data-bs-original-title="Website"><i class="fa-solid fa-globe"></i></a>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-8 col-lg-7 col-md-12">
							<div class="right-profile">
								<div class="profile-tabs">
									<ul class="p-2 nav nav-pills nav-fill garren-line-tab" id="myTab" role="tablist">
										<li class="nav-item" role="presentation">
											<a class="nav-link active" id="feed-tab" data-bs-toggle="tab" href="#feed" role="tab" aria-controls="feed" aria-selected="true"><i class="fa-solid fa-house"></i>Home</a>
										</li>
										<li class="nav-item" role="presentation">
											<a class="nav-link" id="about-tab" data-bs-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="false" tabindex="-1"><i class="fa-solid fa-circle-info"></i>About</a>
										</li>
										<li class="nav-item" role="presentation">
											<a class="nav-link" id="setting-tab" data-bs-toggle="tab" href="#setting" role="tab" aria-controls="setting" aria-selected="false" tabindex="-1"><i class="fa-solid fa-gear"></i>Setting</a>
										</li>
										<li class="nav-item" role="presentation">
											<a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab" aria-controls="tickets" aria-selected="false" tabindex="-1"><i class="fa-solid fa-box"></i>My Tickets</a>
										</li>
									</ul>
									<div class="tab-content" id="myTabContent">
										<div class="tab-pane fade active show" id="feed" role="tabpanel" aria-labelledby="feed-tab">
											<div class="mt-4 nav my-event-tabs" role="tablist">
												<button class="event-link active" data-bs-toggle="tab" data-bs-target="#saved" type="button" role="tab" aria-controls="saved" aria-selected="true"><span class="event-count">1</span><span>Saved Events</span></button>
												<button class="event-link" data-bs-toggle="tab" data-bs-target="#organised" type="button" role="tab" aria-controls="organised" aria-selected="false" tabindex="-1"><span class="event-count">2</span><span>Organised Events</span></button>
												<button class="event-link" data-bs-toggle="tab" data-bs-target="#attending" type="button" role="tab" aria-controls="attending" aria-selected="false" tabindex="-1"><span class="event-count">1</span><span>Attending Events</span></button>
											</div>
											<div class="tab-content">
												<div class="tab-pane fade show active" id="saved" role="tabpanel">
													<div class="row">	
														<div class="col-md-12">														
															<div class="mt-4 main-card">
																<div class="p-4 card-top">
																	<div class="card-event-img">
																		<img src="https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/event-imgs/img-6.jpg" alt="">
																	</div>
																	<div class="card-event-dt">
																		<h5>Step Up Open Mic Show</h5>
																		<div class="evnt-time">Thu, Jun 30, 2022 4:30 AM</div>
																		<div class="event-btn-group">
																			<button class="esv-btn saved-btn me-2"><i class="fa-regular fa-bookmark me-2"></i>Save</button>
																			<button class="esv-btn me-2" onclick="window.location.href='online_event_detail_view.html'"><i class="fa-solid fa-arrow-up-from-bracket me-2"></i>View</button>
																		</div>
																	</div>
																</div>																
															</div>
														</div>
													</div>
												</div>
												<div class="tab-pane fade" id="organised" role="tabpanel">
													<div class="row">	
														<div class="col-md-12">														
															<div class="mt-4 main-card">
																<div class="p-4 card-top">
																	<div class="card-event-img">
																		<img src="https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/event-imgs/img-6.jpg" alt="">
																	</div>
																	<div class="card-event-dt">
																		<h5>Step Up Open Mic Show</h5>
																		<div class="evnt-time">Thu, Jun 30, 2022 4:30 AM</div>
																		<div class="event-btn-group">
																			<button class="esv-btn me-2" onclick="window.location.href='create_online_event.html'"><i class="fa-solid fa-gear me-2"></i>Manage Event</button>
																		</div>
																	</div>
																</div>																
															</div>
															<div class="mt-4 main-card">
																<div class="p-4 card-top">
																	<div class="card-event-img">
																		<img src="https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/event-imgs/img-7.jpg" alt="">
																	</div>
																	<div class="card-event-dt">
																		<h5>Tutorial on Canvas Painting for Beginners</h5>
																		<div class="evnt-time">Sun, Jul 17, 2022 5:30 AM</div>
																		<div class="event-btn-group">
																			<button class="esv-btn me-2" onclick="window.location.href='create_online_event.html'"><i class="fa-solid fa-gear me-2"></i>Manage Event</button>
																		</div>
																	</div>
																</div>																
															</div>
														</div>												
													</div>
												</div>
												<div class="tab-pane fade" id="attending" role="tabpanel">
													<div class="row">	
														<div class="col-md-12">														
															<div class="mt-4 main-card">
																<div class="p-4 card-top">
																	<div class="card-event-img">
																		<img src="https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/event-imgs/img-6.jpg" alt="">
																	</div>
																	<div class="card-event-dt">
																		<h5>Step Up Open Mic Show</h5>
																		<div class="evnt-time">Thu, Jun 30, 2022 4:30 AM</div>
																		<div class="event-btn-group">
																			<button class="esv-btn me-2" onclick="window.location.href='invoice.html'"><i class="fa-solid fa-arrow-up-from-bracket me-2"></i>View Ticket</button>
																		</div>
																	</div>
																</div>																
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="about-tab">
											<div class="mt-4 main-card">
												<div class="bp-title position-relative">
													<h4>About</h4>
													<button class="main-btn btn-hover ms-auto edit-btn me-3 pe-4 ps-4 h_40" data-bs-toggle="modal" data-bs-target="#aboutModal">
														<i class="fa-regular fa-pen-to-square me-2"></i>Edit
													</button>
												</div>
												<div class="about-details">
													<div class="about-step">
														<h5>Name</h5>
														<span>Joginder Singh</span>
													</div>
													<div class="about-step">
														<h5>Tell us about yourself and let people know who you are</h5>
														<p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tincidunt interdum nunc et auctor. Phasellus quis pharetra sapien. Integer ligula sem, sodales vitae varius in, varius eget augue.</p>
													</div>
													<div class="about-step">
														<h5>Find me on</h5>
														<div class="social-links">
															<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Facebook" data-bs-original-title="Facebook"><i class="fab fa-facebook-square"></i></a>
															<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Instagram" data-bs-original-title="Instagram"><i class="fab fa-instagram"></i></a>
															<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Twitter" data-bs-original-title="Twitter"><i class="fab fa-twitter"></i></a>
															<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="LinkedIn" data-bs-original-title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
															<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Youtube" data-bs-original-title="Youtube"><i class="fab fa-youtube"></i></a>
															<a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Website" data-bs-original-title="Website"><i class="fa-solid fa-globe"></i></a>
														</div>
													</div>
													<div class="about-step">
														<h5>Address</h5>
														<p class="mb-0">00 Challis St, Newport, Victoria, 0000, Australia</p>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="setting" role="tabpanel" aria-labelledby="setting-tab">
											<div class="row">
												<div class="col-lg-12">
													<div class="p-0 mt-4 main-card">
														<div class="nav custom-tabs" role="tablist">
															<button class="tab-link active" data-bs-toggle="tab" data-bs-target="#tab-01" type="button" role="tab" aria-controls="tab-01" aria-selected="true"><i class="fa-solid fa-envelope me-3"></i>Email Preferences</button>
															<button class="tab-link" data-bs-toggle="tab" data-bs-target="#tab-02" type="button" role="tab" aria-controls="tab-02" aria-selected="false" tabindex="-1"><i class="fa-solid fa-key me-3"></i>Password Settings</button>
															<button class="tab-link" data-bs-toggle="tab" data-bs-target="#tab-03" type="button" role="tab" aria-controls="tab-03" aria-selected="false" tabindex="-1"><i class="fa-solid fa-gear me-3"></i>Privacy Settings</button>
														</div>
													</div>
												</div>
												<div class="col-lg-12">
													<div class="mt-4 main-card">
														<div class="tab-content">
															<div class="tab-pane fade show active" id="tab-01" role="tabpanel">
																<div class="bp-title">
																	<h4>Email Preferences</h4>
																</div>
																<div class="p-4 profile-setting">
																	<div class="setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Receive order confirmation</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">On purchasing an event you will receive an order confirmation email.</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Receive communication from event organisers for my purchased events</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">The organisations whose events you have bought will be able send you further follow up emails.</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Receive event invitations from event organisers sent to my email address</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">Organisations will be able to send you Invitations in their events.</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Receive reminder from Barren for my purchased events</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">After purchasing event you will receive reminder emails before the event starts so that you can get prepared.</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Receive critical notifications on Barren service status and product updates</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">Get updates on new features and insights so that you can make the best use of Barren.</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Receive newsletters from Barren with general and other information</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">Get more insights on Barren that can help to boost your event business.</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Receive notification when someone follows me</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">We will notify you when someone starts following you or your organisation.</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Review review notification when someone shares my events</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-0">We will notify you when someone shares event created by your organisation.</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Receive notification on review related activities</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-0">We will notify you when someone leaves review for your organisation.</p>
																	</div>
																</div>
															</div>
															<div class="tab-pane fade" id="tab-02" role="tabpanel">
																<div class="bp-title">
																	<h4>Password Settings</h4>
																</div>
																<div class="p-4 password-setting">
																	<div class="password-des">
																		<h4>Change password</h4>
																		<p>You can update your password from here. If you can't remember your current password, just log out and click on Forgot password.</p>
																	</div>
																	<div class="change-password-form">
																		<div class="mt-4 form-group">
																			<label class="form-label">Current password*</label>
																			<div class="loc-group position-relative">
																				<input class="form-control h_50" type="password" placeholder="Enter your password">
																				<span class="pass-show-eye"><i class="fas fa-eye-slash"></i></span>
																			</div>
																		</div>
																		<div class="mt-4 form-group">
																			<label class="form-label">New password*</label>
																			<div class="loc-group position-relative">
																				<input class="form-control h_50" type="password" placeholder="Enter your password">
																				<span class="pass-show-eye"><i class="fas fa-eye-slash"></i></span>
																			</div>
																		</div>
																		<div class="mt-4 form-group">
																			<label class="form-label">Confirm new password*</label>
																			<div class="loc-group position-relative">
																				<input class="form-control h_50" type="password" placeholder="Enter your password">
																				<span class="pass-show-eye"><i class="fas fa-eye-slash"></i></span>
																			</div>
																		</div>
																		<button class="mt-5 main-btn btn-hover w-100" type="submit">Update Password</button>
																	</div>
																</div>
															</div>
															<div class="tab-pane fade" id="tab-03" role="tabpanel">
																<div class="bp-title">
																	<h4>Privacy Settings</h4>
																</div>
																<div class="p-4 privacy-setting">
																	<div class="setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Lock my user profile</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">Locking profile hides all kinds of user information, activities and interaction from public profile</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Allow people to contact me</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">People will be able to send you emails through Barren who visits your profile</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Allow people to find and invite me to relevant events</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">Based on your preferences event organisers will be able to send you invitations in their events</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Allow people to follow me</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">People will be able to follow you</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Allow people to see my followings</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">People will be able to see whom and which organisations you are following</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Allow people to see my join date</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">People will be able to see when you have started using Barren</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Allow people to see the events I attend</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-4">People will be able to see the events you have purchased</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Allow people to see the events I join as speaker</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-0">People will be able to see the events you have joined as Speaker</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Allow people to see the events I share</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox" checked="">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-0">People will be able to see the events you have shared in Barren</p>
																	</div>
																	<div class="pt-4 setting-step">
																		<div class="d-flex align-items-center justify-content-between">
																			<h3 class="setting-title">Hide review interactions from public</h3>
																			<label class="m-0 ml-2 btn-switch">
																				<input type="checkbox">
																				<span class="checkbox-slider"></span>
																			</label>
																		</div>
																		<p class="mt-1 mb-0">The reviews and ratings given by you will not appear in your public profile</p>
																	</div>																	
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
											<div class="mt-4 main-card">
												<div class="p-4 card-top">
													<div class="card-event-img">
														<img src="https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/event-imgs/img-7.jpg" alt="">
													</div>
													<div class="card-event-dt">
														<h5>Tutorial on Canvas Painting for Beginners</h5>
														<div class="invoice-id">Invoice ID : <span>BRCCRW-11111111</span></div>
													</div>
												</div>
												<div class="card-bottom">
													<div class="card-bottom-item">
														<div class="card-icon">
															<i class="fa-solid fa-calendar-days"></i>
														</div>
														<div class="card-dt-text">
															<h6>Event Starts on</h6>
															<span>01 June 2022</span>
														</div>
													</div>
													<div class="card-bottom-item">
														<div class="card-icon">
															<i class="fa-solid fa-ticket"></i>
														</div>
														<div class="card-dt-text">
															<h6>Total Tickets</h6>
															<span>1</span>
														</div>
													</div>
													<div class="card-bottom-item">
														<div class="card-icon">
															<i class="fa-solid fa-money-bill"></i>
														</div>
														<div class="card-dt-text">
															<h6>Paid Amount</h6>
															<span>AUD $50.00</span>
														</div>
													</div>
													<div class="card-bottom-item">
														<div class="card-icon">
															<i class="fa-solid fa-money-bill"></i>
														</div>
														<div class="card-dt-text">
															<h6>Invoice</h6>
															<a href="invoice.html">Download</a>
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
		</div>
	</div>
    <!-- Modals End -->
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