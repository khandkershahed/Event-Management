<style>
    footer {
        display: none;
    }
</style>
<nav class="vertical_nav">
    <div class="left_section menu_left" id="js-menu">
        <div class="left_section">
            <ul>
                <li class="menu--item">
                    <a href="{{ route('user.dashboard') }}" class="menu--link active" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Dashboard">
                        <i class="fa-solid fa-gauge menu--icon"></i>
                        <span class="menu--label">Dashboardass</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="{{ route('user.my.events') }}" class="menu--link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Events">
                        <i class="fa-solid fa-calendar-days menu--icon"></i>
                        <span class="menu--label">Events</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="{{ route('user.my.coupons') }}" class="menu--link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Promotion">
                        <i class="fa-solid fa-rectangle-ad menu--icon"></i>
                        <span class="menu--label">Coupons</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="{{ route('user.my.cards') }}" class="menu--link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Payouts">
                        <i class="fa-solid fa-credit-card menu--icon"></i>
                        <span class="menu--label">Bank Cards</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="{{ route('user.my.reports')}}" class="menu--link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Reports">
                        <i class="fa-solid fa-chart-pie menu--icon"></i>
                        <span class="menu--label">Reports</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="{{ route('user.my.subscription') }}" class="menu--link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Subscription">
                        <i class="fa-solid fa-bahai menu--icon"></i>
                        <span class="menu--label">Subscription</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="{{ route('user.my.information') }}" class="menu--link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="About">
                        <i class="fa-solid fa-circle-info menu--icon"></i>
                        <span class="menu--label">About</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="{{ route('user.my.team') }}" class="menu--link team-lock" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="My Team">
                        <i class="fa-solid fa-user-group menu--icon"></i>
                        <span class="menu--label">My Team</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>