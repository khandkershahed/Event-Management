<div class="card">
    <div class="card-header align-items-center">
        <h3 class="fw-bold my-2">
            Complete Details
        </h3>
    </div>
    <div class="card-body">
        <div class="row g-6 g-xl-9">
            <div class="col-xl-6">
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Name</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->title }}. {{ $user->first_name }} {{ $user->last_name }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">email</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->email }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">phone</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->phone }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">House/Road/Block</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->address_one }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">City</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->address_two }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Zipcode</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->zipcode }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">State</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->state }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Current Suppliers</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->current_suppliers }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Annual Spend</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->annual_spend }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">Newsletter Preference</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->newsletter_preference }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-6" style="border-left: 1px solid #eee;">
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Country</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->country }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Company Name</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->company_name }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Company Registration Number</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->company_registration_number }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Company Vat Number</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->company_vat_number }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Selling Platforms</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->selling_platforms }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Customer Type</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->customer_type }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Referral Source</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->referral_source }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Buying Group Membership</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->buying_group_membership }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Website Address</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->website_address }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="row mb-7">
                    <label class="col-lg-6 fw-semibold text-muted">Buying Group Name</label>
                    <div class="col-lg-6 fv-row">
                        <span class="fw-semibold text-gray-800 fs-6">{{ $user->buying_group_name }}</span>
                    </div>
                </div>
                <div class="separator separator-dashed my-3"></div>
            </div>
        </div>
    </div>
</div>
