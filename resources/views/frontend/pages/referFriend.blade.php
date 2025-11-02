<x-frontend-app-layout :title="'Refer a Friend'">

    <div class="breadcrumb-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-10">
                    <div class="barren-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Refer a Friend</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="event-dt-block p-80">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-6">
                    <div class="main-card p_30">
                        <div class="text-center total-refer-count">
                            <h2>0</h2>
                            <span>Successful Refers</span>
                        </div>
                    </div>
                    <div class="mt-4 main-card p_30">
                        <div class="text-center total-refer-count">
                            <h2>$0.00</h2>
                            <span>Total Grand</span>
                        </div>
                    </div>
                    <div class="mt-4 main-card">
                        <div class="bp-title">
                            <h4>Table of contents</h4>
                        </div>
                        <div class="bp-content faq-widget-content">
                            <ul class="faq-widget-links pt_30">
                                <li>
                                    <p><i class="fa-solid fa-share-nodes me-3"></i>Invite friend to join Barren</p>
                                </li>
                                <li>
                                    <p><i class="fa-solid fa-paper-plane me-3"></i>They signup and subscribe to premium plan</p>
                                </li>
                                <li>
                                    <p><i class="fa-solid fa-ticket me-3"></i>You get a $50.00 voucher</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-6">
                    <div class="mt-5 main-card refer-card p_30 mt-lg-0">
                        <div class="refer-content">
                            <h5 class="fs-16">Refer a Friend</h5>
                            <h3 class="fs-24">Get a $50.00 voucher for each friend you refer to Barren</h3>
                            <div class="refer-copy-input">
                                <h5 class="fs-16">Share your referral link</h5>
                                <div class="referral-link-input">
                                    <input class="form-control" type="text" value="https://demo.barren.com/register?ref=JohnDoe" autocomplete="off" onclick="this.select();" readonly="">
                                </div>
                            </div>
                            <div class="user-social-links-group">
                                <a href="#" class="user-social-link"><i class="fa-solid fa-envelope"></i></a>
                                <a href="#" class="user-social-link"><i class="fa-brands fa-facebook-messenger"></i></a>
                                <a href="#" class="user-social-link"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="user-social-link"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 main-card">
                        <div class="bp-title">
                            <h4>Your voucher (0)</h4>
                        </div>
                        <div class="bp-content faq-widget-content pt_30">
                            <div class="d-flex align-items-center justify-content-center flex-column min-height-180">
                                <div class="referral-icon">
                                    <i class="fa-solid fa-gift"></i>
                                </div>
                                <p class="mt-4 font-16 text-light3">No Event found</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 main-card">
                        <div class="bp-title">
                            <h4>How do I use my 'Refer a Friend' voucher?</h4>
                        </div>
                        <div class="bp-content faq-widget-content">
                            <p class="mt-4 mb-0">Add your voucher in the Voucher/Coupon code field on Barren checkout page and the voucher amount will be automatically deducted from the total order amount.</p>
                            <p class="mt-4 mb-0">You can only use your voucher for booking a ticket or upgrading subscription plan on Barren.com.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-app-layout>