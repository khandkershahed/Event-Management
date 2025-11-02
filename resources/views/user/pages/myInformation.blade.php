<x-frontend-app-layout :title="'My Information'">
    @include('user.layout.sidebar')
    <div class="wrapper wrapper-body">
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-main-title">
                            <h3><i class="fa-solid fa-circle-info me-3"></i>About My Organisation</h3>
                        </div>
                    </div>
                    <div class="offset-3 col-lg-6 col-md-12">
                        <div class="conversion-setup">
                            <div class="mt-5 main-card">
                                <div class="bp-title position-relative">
                                    <h4>About</h4>
                                    <div class="profile-edit-btn">
                                        <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#orgSettings" class="btn"><i class="fa-solid fa-user-gear"></i></a> -->
                                        <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#orgPrivacySettings" class="btn"><i class="fa-solid fa-gear"></i></a> -->
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#org-profile-update-pop" class="btn">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="about-details">
                                    <div class="text-center about-step">
                                        <div class="user-avatar-img">
                                            <img src="https://gambolthemes.net/html-items/barren-html/disable-demo-link/images/profile-imgs/img-13.jpg" alt="">
                                        </div>
                                        <div class="user-dts">
                                            <h4 class="user-name">John Doe<span class="verify-badge"><i class="fa-solid fa-circle-check"></i></span></h4>
                                            <span class="user-email">johndoe@example.com</span>
                                        </div>
                                    </div>
                                    <div class="about-step">
                                        <h5>Tell us about yourself and let people know who you are</h5>
                                        <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut tincidunt interdum nunc et auctor. Phasellus quis pharetra sapien. Integer ligula sem, sodales vitae varius in, varius eget augue.</p>
                                    </div>
                                    <div class="about-step">
                                        <h5>Find me on</h5>
                                        <div class="social-links">
                                            <a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Facebook" aria-label="Facebook"><i class="fab fa-facebook-square"></i></a>
                                            <a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                                            <a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Twitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                            <a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="LinkedIn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                            <a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Youtube" aria-label="Youtube"><i class="fab fa-youtube"></i></a>
                                            <a href="#" class="social-link" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Website" aria-label="Website"><i class="fa-solid fa-globe"></i></a>
                                        </div>
                                    </div>
                                    <div class="about-step">
                                        <h5>Address</h5>
                                        <p class="mb-0">00 Challis St, Newport, Victoria, 0000, Australia</p>
                                    </div>
                                    <div class="about-step">
                                        <a href="#" class="view-profile-link a-link">View Public Profile<i class="fa-solid fa-arrow-up-right-from-square ms-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modals -->
    <div class="modal fade" id="org-profile-update-pop" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-medium-2 modal-dialog-scrollable modal-dialog-centered modal-sm-height modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orgProfileUpdatepopLabel">Organisation details</h5>
                    <button type="button" class="close-model-btn" data-bs-dismiss="modal" aria-label="Close"><i class="uil uil-multiply"></i></button>
                </div>
                <div class="modal-body">
                    <div class="model-content main-form">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="mt-5 text-center form-group">
                                    <span class="org_design_button btn-file">
                                        <span><i class="fa-solid fa-camera"></i></span>
                                        <input type="file" id="org_avatar" accept="image/*" name="Organisation_avatar">
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Name*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="John Doe">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Profile Link*</label>
                                    <div class="loc-group position-relative">
                                        <input class="form-control h_40" type="text" placeholder="" value="https://www.barren.com/b/organiser/john-doe">
                                        <span class="copy-link">Copy Link</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">About*</label>
                                    <textarea class="form-textarea" placeholder="About"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Email*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="Johndoe@example.com">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Phone*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Website*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Facebook*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Instagram*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Twitter*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">LinkedIn*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Youtube*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <h4 class="address-title">Address</h4>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Address 1*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Address 2*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group main-form">
                                    <label class="form-label">Country*</label>
                                    <div class="dropdown bootstrap-select"><select class="selectpicker" data-size="5" title="Nothing selected" data-live-search="true">
                                            <option class="bs-title-option" value=""></option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria (Österreich)</option>
                                            <option value="Belgium">Belgium (België)</option>
                                            <option value="Bolivia">Bolivia</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Chile">Chile</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Hong Kong">Hong Kong</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="India">India</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherlands">Netherlands</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Philippines">Philippines</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="South Korea">South Korea</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="United States">United States</option>
                                            <option value="Vietnam">Vietnam</option>
                                        </select>
                                        <div class="dropdown-menu ">
                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-1" aria-autocomplete="list"></div>
                                            <div class="inner show" role="listbox" id="bs-select-1" tabindex="-1">
                                                <ul class="dropdown-menu inner show" role="presentation"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">State*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">City/Suburb*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mt-4 form-group">
                                    <label class="form-label">Zip/Post Code*</label>
                                    <input class="form-control h_40" type="text" placeholder="" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="co-main-btn min-width btn-hover h_40" data-bs-toggle="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="main-btn min-width btn-hover h_40">Update</button>
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