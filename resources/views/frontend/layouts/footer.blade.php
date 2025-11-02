<footer class="mt-auto footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-content">
                        <h4>Company</h4>
                        <ul class="footer-link-list">
                            <li>
                                <a href="{{ route('about') }}" class="footer-link">About Us</a>
                            </li>
                            <li>
                                <a href="{{ route('help.center') }}" class="footer-link">Help Center</a>
                            </li>
                            <li><a href="{{ route('faq') }}" class="footer-link">FAQ</a></li>
                            <li>
                                <a href="{{ route('contact.us') }}" class="footer-link">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-content">
                        <h4>Useful Links</h4>
                        <ul class="footer-link-list">
                            <li>
                                <a href="create.html" class="footer-link">Create Event</a>
                            </li>
                            <li>
                                <a href="{{ route('sell.ticket.online') }}" class="footer-link">Sell Tickets Online</a>
                            </li>
                            <li>
                                <a href="{{ route('privacy.policy') }}" class="footer-link">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="{{ route('terms.conditions') }}" class="footer-link">Terms & Conditions</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-content">
                        <h4>Resources</h4>
                        <ul class="footer-link-list">
                            <li><a href="{{ route('blog') }}" class="footer-link">Blog</a></li>
                            <li>
                                <a href="{{ route('refer.friend') }}" class="footer-link">Refer a Friend</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-content">
                        <h4>Follow Us</h4>
                        <ul class="social-links">
                            <li>
                                <a href="#" class="social-link"><i class="fab fa-facebook-square"></i></a>
                            </li>
                            <li>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            </li>
                            <li>
                                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-content">
                        <h4>Download Mobile App</h4>
                        <div class="download-app-link">
                            <a href="#" class="download-btn"><img src="https://www.gambolthemes.net/html-items/barren-html/disable-demo-link/images/app-store.png" alt="" /></a>
                            <a href="#" class="download-btn"><img src="https://www.gambolthemes.net/html-items/barren-html/disable-demo-link/images/google-play.png"
                                    alt="" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="footer-copyright-text">
                        <p class="mb-0">
                            © 2025, <strong>FlixzaGlobal</strong>. All rights reserved. Powered
                            by {{ $setting->website_name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
