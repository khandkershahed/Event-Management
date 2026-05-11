<header class="header">
    <div class="header-inner">
        <nav class="pt-0 pb-0 navbar navbar-expand-lg bg-barren barren-head fixed-top justify-content-sm-start">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon">
                        <i class="fa-solid fa-bars"></i>
                    </span>
                </button>
                <a class="order-1 ml-2 navbar-brand order-lg-0 ml-lg-0 me-auto" href="{{ route('homepage') }}">
                    <div class="res-main-logo">
                        <img src="{{ !empty($setting->site_logo_black) && file_exists(public_path('storage/' . $setting->site_logo_black)) ? asset('storage/' . $setting->site_logo_black) : asset('images/logo.webp') }}"
                            alt="" />
                    </div>
                    <div class="main-logo" id="logo">
                        <img src="{{ !empty($setting->site_logo_black) && file_exists(public_path('storage/' . $setting->site_logo_black)) ? asset('storage/' . $setting->site_logo_black) : asset('images/logo.webp') }}"
                            alt="" />
                        <img class="logo-inverse"
                            src="{{ !empty($setting->site_logo_white) && file_exists(public_path('storage/' . $setting->site_logo_white)) ? asset('storage/' . $setting->site_logo_white) : asset('images/logo.webp') }}"
                            alt="" />
                    </div>
                </a>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <div class="offcanvas-logo" id="offcanvasNavbarLabel">
                            <img src="{{ !empty($setting->site_logo_black) && file_exists(public_path('storage/' . $setting->site_logo_black)) ? asset('storage/' . $setting->site_logo_black) : asset('images/logo.webp') }}"
                                alt="" />
                        </div>
                        <button type="button" class="close-btn" data-bs-dismiss="offcanvas" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="offcanvas-top-area">
                            <div class="create-bg">
                                <a href="" class="offcanvas-create-btn">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    <span>Create Event</span>
                                </a>
                            </div>
                        </div>
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe_5">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('homepage') }}">Home</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Explore Events
                                </a>
                                <ul class="dropdown-menu dropdown-submenu">
                                    <li><a class="dropdown-item" href="{{ route('all.events') }}">Explore Events</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('venue.event.create') }}">Venue Event
                                            Detail View</a></li>
                                    <li><a class="dropdown-item" href="{{ route('online.event.create') }}">Online Event
                                            Detail View</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('blog') }}">
                                    Blog
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('about') }}">
                                    About Us
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="offcanvas-footer">
                        <div class="offcanvas-social">
                            <h5>Follow Us</h5>
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
                    </div>
                </div>
                <div class="order-2 right-header">
                    <ul class="align-self-stretch">
                        <li>
                            <a href="{{ route('event.create') }}" class="create-btn btn-hover">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>Create Event</span>
                            </a>
                        </li>
                        @auth
                        {{-- Check  --}}
                            @if (auth()->user()->organizerProfile)
                                <li class="dropdown account-dropdown">
                                    <a href="{{ route('organizer.profile') }}" class="account-link" role="button"
                                        id="accountClick" data-bs-auto-close="outside" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <img src="{{ !empty(Auth::user()->profile_image) && file_exists(public_path('storage/' . Auth::user()->profile_image)) ? asset('storage/' . Auth::user()->profile_image) : asset('https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)) }}"
                                            alt="" />
                                        <i class="fas fa-caret-down arrow-icon"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-account dropdown-menu-end"
                                        aria-labelledby="accountClick">
                                        <li>
                                            <div class="dropdown-account-header">
                                                <div class="account-holder-avatar">
                                                    <img src="{{ !empty(Auth::user()->profile_image) && file_exists(public_path('storage/' . Auth::user()->profile_image)) ? asset('storage/' . Auth::user()->profile_image) : asset('https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)) }}"
                                                        alt="" />
                                                </div>
                                                <h5>{{ Auth::user()->name }}</h5>
                                                <p>{{ Auth::user()->email }}</p>
                                            </div>
                                        </li>
                                        <li class="profile-link">
                                            <a href="{{ route('organizer.dashboard') }}" class="link-item">My Dashboard</a>
                                            <a href="{{ route('organizer.profile') }}" class="link-item">My Profile</a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button onclick="this.form.submit()"
                                                    class="bg-white border-0 link-item text-start">Sign Out</button>
                                                {{-- <a href="javascript:void(0)" onclick="this.form.submit(); return false;" class="link-item">Sign Out</a> --}}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <li class="dropdown account-dropdown">
                                    <a href="{{ route('user.my.profile') }}" class="account-link" role="button"
                                        id="accountClick" data-bs-auto-close="outside" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <img src="{{ !empty(Auth::user()->profile_image) && file_exists(public_path('storage/' . Auth::user()->profile_image)) ? asset('storage/' . Auth::user()->profile_image) : asset('https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)) }}"
                                            alt="" />
                                        <i class="fas fa-caret-down arrow-icon"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-account dropdown-menu-end"
                                        aria-labelledby="accountClick">
                                        <li>
                                            <div class="dropdown-account-header">
                                                <div class="account-holder-avatar">
                                                    <img src="{{ !empty(Auth::user()->profile_image) && file_exists(public_path('storage/' . Auth::user()->profile_image)) ? asset('storage/' . Auth::user()->profile_image) : asset('https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)) }}"
                                                        alt="" />
                                                </div>
                                                <h5>{{ Auth::user()->name }}</h5>
                                                <p>{{ Auth::user()->email }}</p>
                                            </div>
                                        </li>
                                        <li class="profile-link">
                                            <a href="{{ route('user.dashboard') }}" class="link-item">My Dashboard</a>
                                            <a href="{{ route('user.my.profile') }}" class="link-item">My Profile</a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button onclick="this.form.submit()"
                                                    class="bg-white border-0 link-item text-start">Sign Out</button>
                                                {{-- <a href="javascript:void(0)" onclick="this.form.submit(); return false;" class="link-item">Sign Out</a> --}}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @else
                            <li class="dropdown account-dropdown"
                                style="vertical-align: middle; background: #eee; border-radius: 50%;padding: 12px 8px;">
                                <a href="#" class="account-link" role="button" id="accountClick"
                                    data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i>
                                    <i class="fas fa-caret-down arrow-icon"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-account dropdown-menu-end"
                                    aria-labelledby="accountClick">
                                    <li class="profile-link">
                                        <a href="{{ route('login') }}" class="link-item">User Sign In</a>
                                    </li>
                                    <li class="profile-link">
                                        <a href="{{ route('register') }}" class="link-item">User Sign Up</a>
                                    </li>
                                    <li class="profile-link">
                                        <a href="{{ route('organizer.login') }}" class="link-item">Organizer Sign In</a>
                                    </li>
                                    <li class="profile-link">
                                        <a href="{{ route('organizer.register') }}" class="link-item">Organizer Sign Up</a>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                        <li>
                            <div class="night_mode_switch__btn">
                                <div id="night-mode" class="fas fa-moon fa-sun"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="overlay"></div>
    </div>
</header>
