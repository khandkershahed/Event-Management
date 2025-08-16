<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">

        <a href="{{ route('admin.dashboard') }}">
            <img alt="Event Tailor"
                src="{{ !empty(optional($setting)->site_logo_black) &&
                file_exists(public_path('storage/' . optional($setting)->site_logo_black))
                    ? asset('storage/' . optional($setting)->site_logo_black)
                    : asset('images/logo.webp') }}"
                class="w-100">
        </a>
        <div id="kt_aside_toggle" class="w-auto px-0 btn btn-icon btn-active-color-primary aside-toggle active"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="aside-minimize">
            <span class="rotate-180 svg-icon svg-icon-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="currentColor"></path>
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor"></path>
                </svg>
            </span>
        </div>
    </div>
    <div class="aside-menu flex-column-fluid">
        <div class="my-5 hover-scroll-overlay-y my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
            data-kt-scroll-offset="0" style="height: 318px;">
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <a class="menu-link d-flex align-items-center {{ Route::is('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">

                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0" viewBox="0 0 66 66" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                    <g>
                                        <path fill="#5d5b68" d="M24.86 51.13h16.27l3.78 7.52H21.09z" opacity="1" data-original="#5d5b68"></path>
                                        <path fill="#848792" d="M16.4 58.65h33.19v4H16.4z" opacity="1" data-original="#848792"></path>
                                        <path fill="#e6e9ee" d="M60.52 3.35H5.48C3.56 3.35 2 4.91 2 6.83v36.23h62V6.83c0-1.92-1.56-3.48-3.48-3.48z" opacity="1" data-original="#e6e9ee" class=""></path>
                                        <path fill="#c8ced6" d="M60.95 3.39c.02.15.05.29.05.44 0 20.01-16.22 36.23-36.23 36.23H2v3h62V6.83c0-1.77-1.33-3.22-3.05-3.44z" opacity="1" data-original="#c8ced6" class=""></path>
                                        <path fill="#848792" d="M2 43.06v4.6c0 1.92 1.56 3.48 3.48 3.48h55.04c1.92 0 3.48-1.56 3.48-3.48v-4.6z" opacity="1" data-original="#848792"></path>
                                        <path fill="#2c92bf" d="M46.11 15.05h4.81v23.01h-4.81zM34.83 38.057h-4.81v-13h4.81z" opacity="1" data-original="#2c92bf"></path>
                                        <path fill="#4ec4a5" d="M54.15 25.06h4.81v13h-4.81z" opacity="1" data-original="#4ec4a5"></path>
                                        <path fill="#30aa87" d="M58.959 38.057h-2v-13h2z" opacity="1" data-original="#30aa87"></path>
                                        <path fill="#4ec4a5" d="M42.874 38.062h-4.81v-6.99h4.81z" opacity="1" data-original="#4ec4a5"></path>
                                        <path fill="#e1533b" d="M17.4 8.36c-.08 0-.15-.01-.23-.01C11.56 8.35 7 12.91 7 18.53c0 2.87 1.19 5.45 3.1 7.3l7.3-7.3z" opacity="1" data-original="#e1533b" class=""></path>
                                        <path fill="#f8ce01" d="M10.1 25.83c1.83 1.78 4.32 2.87 7.08 2.87 5.62 0 10.18-4.56 10.18-10.18H17.4z" opacity="1" data-original="#f8ce01" class=""></path>
                                        <path fill="#5f6fe7" d="M17.4 8.36v10.17h9.95c0-5.55-4.43-10.05-9.95-10.17z" opacity="1" data-original="#5f6fe7" class=""></path>
                                        <circle cx="33" cy="47.1" r="1" fill="#c8ced6" opacity="1" data-original="#c8ced6" class=""></circle>
                                        <path fill="#6e7fed" d="M24.09 35.56H7c-.55 0-1-.45-1-1s.45-1 1-1h17.09c.55 0 1 .45 1 1s-.45 1-1 1zM17.4 39.06H7c-.55 0-1-.45-1-1s.45-1 1-1h10.4c.55 0 1 .45 1 1s-.44 1-1 1z" opacity="1" data-original="#6e7fed"></path>
                                        <path fill="#30aa87" d="M42.87 38.062h-2v-6.99h2z" opacity="1" data-original="#30aa87"></path>
                                        <g fill="#1f81a3">
                                            <path d="M50.914 38.059h-2v-23.01h2zM34.825 38.057h-2v-13h2z" fill="#1f81a3" opacity="1" data-original="#1f81a3"></path>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                        </span>

                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                {{-- Site Content  --}}
                @php
                $menuItems = [
                //====================== Event Management Start ============
                [
                'title' => 'Event Type',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 68 68" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <path fill="#4269c4" d="m56.963 14.478-4.59 46.55a5.28 5.28 0 0 1-5.24 4.75h-36.74c-3.11 0-5.55-2.69-5.24-5.78l4.6-46.55a5.272 5.272 0 0 1 5.24-4.75h36.73c3.11 0 5.54 2.69 5.24 5.78z" opacity="1" data-original="#4269c4" class=""></path>
                        <path fill="#8a9bea" d="m54.133 14.478-4.6 46.55a5.279 5.279 0 0 1-5.24 4.75H7.563c-3.12 0-5.55-2.69-5.24-5.78l4.6-46.55a5.265 5.265 0 0 1 5.24-4.75h36.73c3.11 0 5.54 2.69 5.24 5.78z" opacity="1" data-original="#8a9bea" class=""></path>
                        <path fill="#ffffff" d="M6.623 62.612a1.02 1.02 0 0 1-.775-.343c-.14-.155-.3-.42-.263-.796l4.808-48.67a1.04 1.04 0 0 1 1.037-.94h38.402c.376 0 .623.185.765.341.144.159.308.429.27.813l-4.805 48.655c-.052.527-.508.94-1.037.94H6.623z" opacity="1" data-original="#ffffff" class=""></path>
                        <path fill="#255299" d="M39.194 8.244H22.925l1.729-4.63a2.832 2.832 0 0 1 2.653-1.841h7.506c1.182 0 2.24.734 2.653 1.842z" opacity="1" data-original="#255299" class=""></path>
                        <path fill="#272791" d="M39.191 8.247h-16.27l.8-2.14c1.3.22 2.75.34 4.28.34 4.31 0 8.02-.97 9.64-2.36z" opacity="1" data-original="#272791" class=""></path>
                        <path fill="#255299" d="M43.743 8.708v2.69c0 3.05-2.47 5.51-5.5 5.51h-14.36c-3.03 0-5.5-2.46-5.5-5.51v-2.69c0-1.05.84-1.89 1.87-1.89h21.6c1.05 0 1.89.84 1.89 1.89z" opacity="1" data-original="#255299" class=""></path>
                        <g fill="#272791">
                            <path d="M40.85 13.648c0 .43-4.379.79-9.79.79-5.409 0-9.79-.36-9.79-.79 0-.44 4.381-.8 9.79-.8 5.411 0 9.79.36 9.79.8z" fill="#272791" opacity="1" data-original="#272791" class=""></path>
                            <ellipse cx="31.061" cy="10.088" rx="9.79" ry=".79" fill="#272791" opacity="1" data-original="#272791" class=""></ellipse>
                        </g>
                        <path fill="#aec46e" d="M10.878 44.05a.21.21 0 0 1-.16-.07.208.208 0 0 1-.054-.164l.99-10.026a.214.214 0 0 1 .214-.194h10.995c.077 0 .128.038.157.07.03.033.063.089.056.168l-.99 10.023a.217.217 0 0 1-.214.194z" opacity="1" data-original="#aec46e"></path>
                        <path fill="#617f4d" d="m22.563 38.998-.48 4.86c-.01.11-.1.19-.21.19h-11c-.07 0-.13-.04-.15-.07a.184.184 0 0 1-.06-.16l.38-3.89c1.4.75 3.16 1.19 5.07 1.19 2.61 0 4.94-.83 6.45-2.12z" opacity="1" data-original="#617f4d"></path>
                        <path fill="#aec46e" d="M9.576 58.948a.21.21 0 0 1-.16-.07.208.208 0 0 1-.054-.164l.99-10.026a.214.214 0 0 1 .214-.194h10.995c.077 0 .128.038.157.07.03.033.064.088.056.168l-.99 10.023a.217.217 0 0 1-.214.193H9.576z" opacity="1" data-original="#aec46e"></path>
                        <path fill="#617f4d" d="m21.303 53.509-.52 5.25c-.01.1-.1.19-.21.19h-11a.22.22 0 0 1-.16-.07.195.195 0 0 1-.05-.16l.36-3.66c1.3.46 2.79.72 4.38.72 2.95 0 5.57-.89 7.2-2.27z" opacity="1" data-original="#617f4d"></path>
                        <g fill="#d1dafe">
                            <path d="M41.158 38.604c1.514.179 3.026.417 4.53.796-1.533.24-3.06.332-4.583.373-1.525.05-3.057.011-4.577-.038-1.518-.089-3.036-.187-4.55-.376-1.513-.179-3.025-.408-4.53-.787 1.533-.24 3.06-.341 4.584-.382 1.524-.051 3.045-.012 4.564.047 1.519.079 3.048.178 4.562.367zM32.17 35.133c-1.514-.18-3.026-.408-4.53-.786 1.532-.241 3.059-.332 4.583-.373 1.524-.05 3.045-.012 4.565.037 1.519.079 3.047.188 4.561.367 1.515.18 3.026.418 4.531.796-1.533.241-3.06.342-4.584.383a69.453 69.453 0 0 1-4.576-.047c-1.518-.09-3.036-.188-4.55-.377zM36.219 42.614c.989.155 1.965.36 2.945.715-1.009.274-2 .39-2.998.454-1 .075-1.996.07-2.99.025a24.86 24.86 0 0 1-2.975-.296c-.99-.154-1.965-.36-2.945-.724 1.008-.264 1.999-.38 2.998-.444 1-.075 1.995-.07 2.99-.025.994.045 1.986.13 2.975.295z" fill="#d1dafe" opacity="1" data-original="#d1dafe"></path>
                        </g>
                        <g fill="#d1dafe">
                            <path d="M40.482 53.486c1.515.18 3.026.418 4.531.796-1.533.241-3.06.332-4.584.373-1.524.051-3.056.012-4.576-.037-1.518-.09-3.036-.188-4.55-.377-1.514-.179-3.026-.408-4.53-.786 1.532-.241 3.06-.342 4.583-.383 1.525-.05 3.045-.012 4.565.047 1.519.08 3.048.179 4.561.367zM31.495 50.015c-1.514-.179-3.026-.407-4.531-.786 1.533-.24 3.06-.331 4.584-.373 1.524-.05 3.045-.011 4.565.038a69.45 69.45 0 0 1 4.561.367c1.514.178 3.026.417 4.531.796-1.533.24-3.06.341-4.584.382a69.45 69.45 0 0 1-4.576-.047c-1.518-.089-3.036-.188-4.55-.377zM35.543 57.497c.99.155 1.965.36 2.946.714-1.01.274-2 .39-2.999.454-1 .075-1.995.07-2.99.025a24.86 24.86 0 0 1-2.974-.295c-.99-.155-1.965-.36-2.945-.724 1.008-.265 1.998-.38 2.998-.445 1-.075 1.995-.07 2.99-.025.994.045 1.986.13 2.974.296z" fill="#d1dafe" opacity="1" data-original="#d1dafe"></path>
                        </g>
                        <path fill="#ffffff" d="M19.746 36.433c-.354 1.075-.928 1.918-1.551 2.737a13.093 13.093 0 0 1-2.163 2.113l-.183.135a.67.67 0 0 1-.953-.122l-.012-.013c-.55-.757-1.075-1.551-1.405-2.541.696.22 1.282.538 1.832.892.513-.611 1.075-1.16 1.698-1.662.807-.61 1.662-1.197 2.737-1.539zM18.702 51.156c-.355 1.075-.929 1.918-1.552 2.736a13.093 13.093 0 0 1-2.162 2.114l-.184.135a.67.67 0 0 1-.953-.122l-.012-.013c-.55-.758-1.075-1.552-1.405-2.541.696.22 1.283.538 1.833.892.513-.611 1.075-1.16 1.698-1.662.806-.61 1.662-1.197 2.737-1.539z" opacity="1" data-original="#ffffff" class=""></path>
                        <path fill="#fbad3e" d="m64.593 11.708-12.32 19.91-6.45-3.99 12.33-19.91c.49-.8 1.55-1.06 2.35-.56l3.54 2.19c.8.5 1.05 1.56.55 2.36z" opacity="1" data-original="#fbad3e" class=""></path>
                        <path fill="#ffcecf" d="m52.27 31.617-4.088 2.269-1.115.618a1.018 1.018 0 0 1-1.513-.938l.057-1.273.21-4.67z" opacity="1" data-original="#ffcecf" class=""></path>
                        <path fill="#51504f" d="m48.182 33.886-1.115.618a1.018 1.018 0 0 1-1.513-.938l.057-1.273a2.218 2.218 0 0 1 1.597.293c.494.306.829.775.974 1.3z" opacity="1" data-original="#51504f"></path>
                        <path fill="#fa990e" d="m58.707 12.162 1.666 1.033-10.516 16.962-1.666-1.033z" opacity="1" data-original="#fa990e"></path>
                        <path fill="#ffffff" d="m62.344 15.35 2.254-3.638a1.716 1.716 0 0 0-.555-2.36L60.505 7.16a1.712 1.712 0 0 0-2.356.557l-2.254 3.64z" opacity="1" data-original="#ffffff" class=""></path>
                        <path fill="#51504f" d="m64.593 11.708-1.45 2.35-6.45-3.99 1.46-2.35c.49-.8 1.55-1.06 2.35-.56l3.54 2.19c.8.5 1.05 1.56.55 2.36z" opacity="1" data-original="#51504f"></path>
                        <g fill="#4269c4">
                            <path d="M18.07 22.251a.626.626 0 0 0 .45-.157.604.604 0 0 0 .206-.437.619.619 0 0 0-.166-.454.618.618 0 0 0-.438-.2l-3.836-.158a.654.654 0 0 0-.676.631l-.237 5.806a.639.639 0 0 0 .622.675l3.835.157a.63.63 0 0 0 .45-.157.603.603 0 0 0 .205-.437.62.62 0 0 0-.165-.453.62.62 0 0 0-.438-.202l-3.178-.13.07-1.687 2.833.116a.628.628 0 0 0 .45-.158.602.602 0 0 0 .204-.436.619.619 0 0 0-.165-.454.62.62 0 0 0-.438-.202l-2.833-.116.068-1.677zM25.01 21.236a.618.618 0 0 0-.378.109.74.74 0 0 0-.253.3l-2.254 5.016-1.839-5.19a.733.733 0 0 0-.225-.314.617.617 0 0 0-.369-.139.633.633 0 0 0-.48.168.656.656 0 0 0-.195.464.688.688 0 0 0 .035.247l2.128 5.827.003.007c.194.478.525.59.768.6l.21.009c.243.01.582-.075.818-.542l2.6-5.64a.677.677 0 0 0 .053-.237.655.655 0 0 0-.156-.479.632.632 0 0 0-.466-.206zM30.81 22.773a.626.626 0 0 0 .451-.157.603.603 0 0 0 .205-.437.619.619 0 0 0-.166-.454.618.618 0 0 0-.438-.201l-3.835-.157a.654.654 0 0 0-.676.631l-.238 5.806a.638.638 0 0 0 .17.469c.117.127.274.199.452.206l3.836.157a.63.63 0 0 0 .45-.157.603.603 0 0 0 .205-.437.62.62 0 0 0-.166-.453.62.62 0 0 0-.438-.202l-3.177-.13.069-1.687 2.833.116a.628.628 0 0 0 .45-.158.602.602 0 0 0 .204-.436.619.619 0 0 0-.165-.454.62.62 0 0 0-.438-.202l-2.833-.116.069-1.677zM37.615 21.949a.654.654 0 0 0-.205.454l-.183 4.468-3.097-4.888c-.18-.255-.41-.39-.683-.401l-.125-.005a.803.803 0 0 0-.606.228.828.828 0 0 0-.269.587l-.234 5.71a.637.637 0 0 0 .17.468.672.672 0 0 0 .922.04.628.628 0 0 0 .215-.454l.184-4.477 3.113 4.915a.886.886 0 0 0 .281.26c.12.07.249.108.386.114l.115.005a.83.83 0 0 0 .606-.22.806.806 0 0 0 .278-.586l.233-5.71a.645.645 0 0 0-.173-.473.66.66 0 0 0-.928-.035zM45.18 22.294a.617.617 0 0 0-.439-.201l-4.763-.195a.604.604 0 0 0-.453.168.63.63 0 0 0-.193.435.61.61 0 0 0 .594.645l1.734.071-.216 5.263a.637.637 0 0 0 .17.468c.118.129.274.2.452.208a.655.655 0 0 0 .685-.622l.215-5.263 1.724.07a.625.625 0 0 0 .45-.156.601.601 0 0 0 .205-.437.617.617 0 0 0-.166-.454z" fill="#4269c4" opacity="1" data-original="#4269c4" class=""></path>
                        </g>
                        <path fill="#fbad3e" d="M65.143 63.338a2.267 2.267 0 0 1-1.91 2.1c-2.39.4-4.77.64-7.12.74-1.7.07-3.4.06-5.08-.02-2.29-.1-4.57-.35-6.83-.72a2.305 2.305 0 0 1-1.91-2.07c-.35-4.53-.37-8.81.16-12.69h22.58c.39 3.78.37 8.06.11 12.66z" opacity="1" data-original="#fbad3e" class=""></path>
                        <path fill="#912010" d="M56.109 50.674v15.507c-1.7.068-3.393.06-5.08-.02V50.674z" opacity="1" data-original="#912010"></path>
                        <path fill="#fa990e" d="M66.173 47.118v3.48c0 .44-.34.79-.78.82l-8.26.31-7.13.28-7.49.29a.824.824 0 0 1-.85-.82v-4.36c0-.45.37-.82.82-.82h22.88c.46 0 .81.37.81.82z" opacity="1" data-original="#fa990e"></path>
                        <path fill="#b53016" d="M57.133 46.301v5.43l-7.13.276V46.3z" opacity="1" data-original="#b53016" class=""></path>
                        <path fill="#b53016" d="M53.742 46.301s-1.131-7.066-5.912-6.468c0 0-2.475-.036-1.707 1.877.878 2.188 5.207 4.103 7.619 4.591z" opacity="1" data-original="#b53016" class=""></path>
                        <path fill="#912010" d="M53.742 46.301s-.748-4.67-3.907-4.275c0 0-1.636-.023-1.128 1.241.58 1.446 3.44 2.712 5.035 3.034z" opacity="1" data-original="#912010"></path>
                        <path fill="#b53016" d="M53.742 46.301s1.13-7.066 5.91-6.468c0 0 2.476-.036 1.708 1.877-.878 2.188-5.207 4.103-7.618 4.591z" opacity="1" data-original="#b53016" class=""></path>
                        <path fill="#912010" d="M53.742 46.301s.747-4.67 3.906-4.275c0 0 1.636-.023 1.128 1.241-.58 1.446-3.44 2.712-5.034 3.034zM2.4 11.964l.56-.162a.775.775 0 0 0 .52-.481c.07-.202.152-.398.243-.589a.775.775 0 0 0-.028-.706l-.282-.512a.793.793 0 0 1 .134-.942l.932-.932a.792.792 0 0 1 .942-.134l.513.283c.218.12.482.135.706.028.191-.092.387-.173.589-.244a.775.775 0 0 0 .48-.52l.162-.56a.792.792 0 0 1 .76-.573h.807v14.924H8.63a.792.792 0 0 1-.76-.571l-.162-.558a.775.775 0 0 0-.48-.519 5.948 5.948 0 0 1-.589-.244.775.775 0 0 0-.706.028l-.512.282a.792.792 0 0 1-.943-.134l-.932-.935a.793.793 0 0 1-.133-.943l.28-.507a.775.775 0 0 0 .029-.707 5.908 5.908 0 0 1-.243-.588.774.774 0 0 0-.52-.48l-.56-.163a.793.793 0 0 1-.573-.76v-1.32c0-.353.233-.664.572-.761z" opacity="1" data-original="#912010"></path>
                        <path fill="#b53016" d="M4.21 15.448c.07.201.152.398.243.588a.775.775 0 0 1-.029.707l-.28.507a.792.792 0 0 0 .133.943l.932.935c.249.25.634.304.943.134l.512-.282a.776.776 0 0 1 .706-.028c.192.092.388.173.59.244.234.083.41.28.479.52l.161.557a.792.792 0 0 0 .761.571h1.316a.792.792 0 0 0 .76-.571l.162-.558a.775.775 0 0 1 .48-.519c.201-.07.397-.152.589-.244a.776.776 0 0 1 .706.028l.512.282c.31.17.694.116.943-.134l.932-.935a.792.792 0 0 0 .133-.943l-.28-.507a.775.775 0 0 1-.029-.707c.091-.19.172-.387.243-.588a.775.775 0 0 1 .52-.48l.56-.163a.792.792 0 0 0 .573-.76v-1.32a.792.792 0 0 0-.572-.761l-.56-.162a.775.775 0 0 1-.521-.481 5.885 5.885 0 0 0-.243-.589.775.775 0 0 1 .028-.706l.282-.512a.792.792 0 0 0-.134-.942l-.932-.932a.792.792 0 0 0-.942-.134l-.513.283a.776.776 0 0 1-.706.028 5.973 5.973 0 0 0-.589-.244.775.775 0 0 1-.48-.52l-.162-.56a.792.792 0 0 0-.76-.573H9.361a.792.792 0 0 0-.761.572l-.163.561a.775.775 0 0 1-.48.52 5.97 5.97 0 0 0-.588.244.775.775 0 0 1-.706-.028l-.513-.283a.792.792 0 0 0-.942.134l-.932.932a.792.792 0 0 0-.134.942l.282.512c.12.218.135.482.028.706a5.89 5.89 0 0 0-.243.589.775.775 0 0 1-.52.48l-.56.163a.792.792 0 0 0-.573.76v1.32c0 .353.233.664.572.761l.56.162c.24.07.438.246.521.481z" opacity="1" data-original="#b53016" class=""></path>
                        <circle cx="10.019" cy="13.382" r="4.685" fill="#912010" opacity="1" data-original="#912010"></circle>
                        <circle cx="10.019" cy="13.382" r="3.294" fill="#ffffff" opacity="1" data-original="#ffffff" class=""></circle>
                    </g>
                </svg>',
                'routes' => ['admin.event-type.index', 'admin.event-type.create', 'admin.event-type.edit'],
                'route' => 'admin.event-type.index',
                ],

                [
                'title' => 'Events',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <linearGradient id="a">
                            <stop offset="0" stop-color="#fdb4ba"></stop>
                            <stop offset="1" stop-color="#fe5694"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#a" id="k" x1="32.185" x2="143.581" y1="207.745" y2="267.635" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="b">
                            <stop offset="0" stop-color="#fd3581" stop-opacity="0"></stop>
                            <stop offset=".281" stop-color="#fa317d" stop-opacity=".282"></stop>
                            <stop offset=".56" stop-color="#f02571" stop-opacity=".56"></stop>
                            <stop offset=".838" stop-color="#df115e" stop-opacity=".838"></stop>
                            <stop offset="1" stop-color="#d2024e"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#b" id="l" x1="94.514" x2="102.898" y1="282.03" y2="321.558" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#a" id="m" x1="109.74" x2="155.256" y1="261.766" y2="306.085" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#b" id="n" x1="48.94" x2="139.973" y1="201.341" y2="291.309" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#a" id="o" x1="406.082" x2="482.741" y1="213.257" y2="292.312" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#b" id="p" x1="460.261" x2="400.636" y1="252.071" y2="233.971" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#b" id="q" x1="419.745" x2="412.558" y1="277.163" y2="325.076" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#a" id="r" x1="362.124" x2="398.058" y1="249.794" y2="291.717" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#b" id="s" x1="282.719" x2="398.773" y1="179.152" y2="290.947" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#a" id="t" x1="256" x2="256" y1="140.675" y2="280.865" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="c">
                            <stop offset="0" stop-color="#f1b0a2"></stop>
                            <stop offset="1" stop-color="#ca6e59"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#c" id="u" x1="18.66" x2="40.706" y1="320.08" y2="320.08" gradientTransform="rotate(-5.13 215.369 334.477)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#c" id="v" x1="465.678" x2="494.502" y1="334.647" y2="337.529" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="d">
                            <stop offset="0" stop-color="#eaf9fa"></stop>
                            <stop offset="1" stop-color="#b4d2e2"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#d" id="w" x1="295.979" x2="318.343" y1="325.405" y2="347.77" gradientTransform="rotate(45 307.18 336.491)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="e">
                            <stop offset="0" stop-color="#c8effe"></stop>
                            <stop offset="1" stop-color="#62dbfb"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#e" id="x" x1="174.859" x2="193.603" y1="454.109" y2="472.853" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="y" x1="71.911" x2="100.613" y1="354.886" y2="383.588" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-color="#fd4755"></stop>
                            <stop offset="1" stop-color="#a72b2b"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#e" id="z" x1="413.383" x2="440.264" y1="454.085" y2="480.966" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="f">
                            <stop offset="0" stop-color="#fef0ae"></stop>
                            <stop offset="1" stop-color="#fac600"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#f" id="A" x1="241.918" x2="268.8" y1="57.75" y2="84.632" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#e" id="B" x1="24.419" x2="80.605" y1="65.891" y2="65.891" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="g">
                            <stop offset="0" stop-color="#07b2cd" stop-opacity="0"></stop>
                            <stop offset=".455" stop-color="#05a5ce" stop-opacity=".455"></stop>
                            <stop offset="1" stop-color="#0290cf"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#g" id="C" x1="47.879" x2="66.532" y1="115.689" y2="97.497" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#g" id="D" x1="61.847" x2="61.847" y1="122.859" y2="131.162" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#g" id="E" x1="61.847" x2="61.847" y1="118.714" y2="129.347" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#g" id="F" x1="54.062" x2="36.56" y1="12.775" y2="42.252" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="h">
                            <stop offset="0" stop-color="#8bc727"></stop>
                            <stop offset=".132" stop-color="#83c528"></stop>
                            <stop offset=".343" stop-color="#6bbd2b"></stop>
                            <stop offset=".607" stop-color="#45b131"></stop>
                            <stop offset=".911" stop-color="#11a038"></stop>
                            <stop offset="1" stop-color="#009b3a"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#h" id="G" x1="-408.306" x2="-335.609" y1="-714.211" y2="-714.211" gradientTransform="rotate(-45 971.69 -1281.805)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="i">
                            <stop offset="0" stop-color="#026841" stop-opacity="0"></stop>
                            <stop offset=".238" stop-color="#026441" stop-opacity=".238"></stop>
                            <stop offset=".474" stop-color="#015840" stop-opacity=".475"></stop>
                            <stop offset=".711" stop-color="#01443f" stop-opacity=".711"></stop>
                            <stop offset=".946" stop-color="#00283d" stop-opacity=".946"></stop>
                            <stop offset="1" stop-color="#00213d"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#i" id="H" x1="-377.952" x2="-353.817" y1="-649.778" y2="-673.318" gradientTransform="rotate(-45 971.69 -1281.805)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#i" id="I" x1="-359.879" x2="-359.879" y1="-640.502" y2="-629.759" gradientTransform="rotate(-45 971.69 -1281.805)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#i" id="J" x1="-359.879" x2="-359.879" y1="-645.865" y2="-632.107" gradientTransform="rotate(-45 971.69 -1281.805)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#i" id="K" x1="-369.952" x2="-392.597" y1="-782.936" y2="-744.796" gradientTransform="rotate(-45 971.69 -1281.805)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#f" id="L" x1="-681.471" x2="-700.103" y1="-578.284" y2="-544.002" gradientTransform="rotate(-45 899.105 -1103.79)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient id="j">
                            <stop offset="0" stop-color="#fea613" stop-opacity="0"></stop>
                            <stop offset=".203" stop-color="#fda215" stop-opacity=".203"></stop>
                            <stop offset=".405" stop-color="#fb961b" stop-opacity=".405"></stop>
                            <stop offset=".607" stop-color="#f68225" stop-opacity=".607"></stop>
                            <stop offset=".807" stop-color="#f06633" stop-opacity=".807"></stop>
                            <stop offset="1" stop-color="#e94444"></stop>
                        </linearGradient>
                        <linearGradient xlink:href="#j" id="M" x1="-656.057" x2="-695.183" y1="-562.33" y2="-547.425" gradientTransform="rotate(-45 899.105 -1103.79)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#f" id="N" x1="-533.062" x2="-533.062" y1="-618.403" y2="-580.954" gradientTransform="rotate(-45 899.105 -1103.79)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#j" id="O" x1="731.563" x2="692.437" y1="-4051.328" y2="-4036.423" gradientTransform="rotate(90 -1688.422 -2120.386)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#h" id="P" x1="1230.888" x2="1230.888" y1="-2502.564" y2="-2465.115" gradientTransform="rotate(45 -2715.347 -2093.665)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#i" id="Q" x1="-1848.04" x2="-1887.166" y1="-3966.326" y2="-3951.421" gradientTransform="rotate(180 -765.798 -1773.16)" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#d" id="R" x1="132.12" x2="132.12" y1="175.746" y2="223.912" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#d" id="S" x1="190.002" x2="190.002" y1="175.746" y2="223.912" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#d" id="T" x1="253.86" x2="253.86" y1="175.746" y2="223.912" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#d" id="U" x1="314.531" x2="314.531" y1="175.746" y2="223.912" gradientUnits="userSpaceOnUse"></linearGradient>
                        <linearGradient xlink:href="#d" id="V" x1="378.433" x2="378.433" y1="175.746" y2="223.912" gradientUnits="userSpaceOnUse"></linearGradient>
                        <path fill="url(#k)" d="M32.743 308.001 13.742 198.505c-4.986-8.334-.28-19.074 9.216-21.11 41.82-8.965 83.916-15.501 126.143-19.589v120.897c0 18.556-13.818 34.178-32.227 36.507a1113.316 1113.316 0 0 0-67.928 10.738c-10.429 1.981-19.25-7.778-16.203-17.947z" opacity="1" data-original="url(#k)"></path>
                        <path fill="url(#l)" d="M149.102 213.746v64.959c0 18.55-13.823 34.177-32.227 36.507a1108.754 1108.754 0 0 0-67.927 10.742c-10.429 1.972-19.256-7.785-16.209-17.956l-16.354-94.252z" opacity="1" data-original="url(#l)"></path>
                        <path fill="url(#m)" d="m124.059 283.554-20.922-20.86 10.313-9.549h35.65v25.569c0 18.535-13.785 34.177-32.172 36.507l-.445.056c13.581-4.425 17.691-21.637 7.576-31.723z" opacity="1" data-original="url(#m)"></path>
                        <path fill="url(#n)" d="m124.059 283.554-20.922-20.86 10.313-9.549h35.65v25.569c0 18.535-13.785 34.177-32.172 36.507l-.445.056c13.581-4.425 17.691-21.637 7.576-31.723z" opacity="1" data-original="url(#n)"></path>
                        <path fill="url(#o)" d="m479.257 308.001 19.001-109.496c4.986-8.334.28-19.074-9.216-21.11-41.82-8.965-83.916-15.501-126.143-19.589v120.897c0 18.556 13.818 34.178 32.227 36.507a1113.316 1113.316 0 0 1 67.928 10.738c10.429 1.981 19.25-7.778 16.203-17.947z" opacity="1" data-original="url(#o)"></path>
                        <path fill="url(#p)" d="m479.257 308.001 19.001-109.496c4.986-8.334.28-19.074-9.216-21.11-41.82-8.965-83.916-15.501-126.143-19.589v120.897c0 18.556 13.818 34.178 32.227 36.507a1113.316 1113.316 0 0 1 67.928 10.738c10.429 1.981 19.25-7.778 16.203-17.947z" opacity="1" data-original="url(#p)"></path>
                        <path fill="url(#q)" d="M479.262 307.997c3.047 10.171-5.78 19.928-16.209 17.956a1108.228 1108.228 0 0 0-67.927-10.742c-18.404-2.33-32.227-17.956-32.227-36.507v-64.959h132.718z" opacity="1" data-original="url(#q)"></path>
                        <path fill="url(#r)" d="m387.941 283.554 20.922-20.86-10.313-9.549H362.9v25.569c0 18.535 13.785 34.177 32.172 36.507l.445.056c-13.581-4.425-17.691-21.637-7.576-31.723z" opacity="1" data-original="url(#r)"></path>
                        <path fill="url(#s)" d="m387.941 283.554 20.922-20.86-10.313-9.549H362.9v25.569c0 18.535 13.785 34.177 32.172 36.507l.445.056c-13.581-4.425-17.691-21.637-7.576-31.723z" opacity="1" data-original="url(#s)" class=""></path>
                        <path fill="url(#t)" d="M97.179 252.821 80.605 142.199c-1.443-9.63 4.887-18.701 14.417-20.701 106.479-22.344 215.478-22.344 321.957 0 9.53 2 15.86 11.071 14.417 20.701l-16.575 110.623c-1.307 8.726-9.639 14.589-18.294 12.874-93.06-18.435-187.992-18.435-281.053-.001-8.656 1.715-16.987-4.147-18.295-12.874z" opacity="1" data-original="url(#t)" class=""></path>
                        <path fill="url(#u)" d="M44.995 510.744c-8.722.763-16.411-5.689-17.174-14.411L.061 179.042c-.763-8.722 5.689-16.411 14.411-17.174 8.722-.763 16.411 5.689 17.174 14.411l27.759 317.29c.764 8.722-5.688 16.411-14.41 17.175z" opacity="1" data-original="url(#u)"></path>
                        <path fill="url(#v)" d="M467.005 510.744c8.722.763 16.411-5.689 17.174-14.411l27.759-317.29c.763-8.722-5.689-16.411-14.411-17.174-8.722-.763-16.411 5.689-17.174 14.411l-27.759 317.29c-.763 8.721 5.689 16.41 14.411 17.174z" opacity="1" data-original="url(#v)"></path>
                        <path fill="url(#w)" d="M305.783 359.567a50 50 0 0 0-23.963-22.901c-1.148-.518-1.148-2.161 0-2.679a50.014 50.014 0 0 0 23.963-22.901c.546-1.075 2.078-1.075 2.625 0a50 50 0 0 0 23.963 22.901c1.148.518 1.148 2.161 0 2.679a50.014 50.014 0 0 0-23.963 22.901c-.547 1.076-2.079 1.076-2.625 0z" opacity="1" data-original="url(#w)"></path>
                        <path fill="url(#x)" d="M200.786 481.892a50.01 50.01 0 0 0-33.138.751c-1.178.445-2.34-.716-1.894-1.894a50.01 50.01 0 0 0 .751-33.138c-.374-1.147.709-2.23 1.856-1.856a50.003 50.003 0 0 0 33.138-.751c1.178-.445 2.34.716 1.894 1.894a50.01 50.01 0 0 0-.751 33.138c.374 1.146-.71 2.23-1.856 1.856z" opacity="1" data-original="url(#x)"></path>
                        <path fill="url(#y)" d="M80.101 388.628a50 50 0 0 0-23.963-22.901c-1.148-.518-1.148-2.161 0-2.679a50.014 50.014 0 0 0 23.963-22.901c.546-1.075 2.078-1.075 2.625 0a50 50 0 0 0 23.963 22.901c1.148.518 1.148 2.161 0 2.679a50.014 50.014 0 0 0-23.963 22.901c-.547 1.075-2.079 1.075-2.625 0z" opacity="1" data-original="url(#y)"></path>
                        <path fill="url(#z)" d="M447.362 465.135a50 50 0 0 0-22.901 23.963c-.518 1.148-2.161 1.148-2.679 0a50.014 50.014 0 0 0-22.901-23.963c-1.075-.546-1.075-2.078 0-2.625a50 50 0 0 0 22.901-23.963c.518-1.148 2.161-1.148 2.679 0a50.014 50.014 0 0 0 22.901 23.963c1.075.547 1.075 2.079 0 2.625z" opacity="1" data-original="url(#z)"></path>
                        <path fill="url(#A)" d="M275.897 68.801a50 50 0 0 0-22.901 23.963c-.518 1.148-2.161 1.148-2.679 0a50.014 50.014 0 0 0-22.901-23.963c-1.075-.546-1.075-2.078 0-2.625a50 50 0 0 0 22.901-23.963c.518-1.148 2.161-1.148 2.679 0a50.014 50.014 0 0 0 22.901 23.963c1.076.546 1.076 2.079 0 2.625z" opacity="1" data-original="url(#A)"></path>
                        <path fill="url(#B)" d="M75.755 130.587H58.803a4.849 4.849 0 0 1-4.849-4.849v-23.159c0-9.908-3.858-19.223-10.864-26.229-12.04-12.04-18.671-28.047-18.671-45.074V6.044a4.849 4.849 0 0 1 4.849-4.849H46.22a4.849 4.849 0 0 1 4.849 4.849v25.232c0 9.908 3.858 19.223 10.864 26.228 12.04 12.04 18.67 28.048 18.67 45.074v23.159a4.847 4.847 0 0 1-4.848 4.85z" opacity="1" data-original="url(#B)"></path>
                        <path fill="url(#C)" d="M43.089 76.35c7.006 7.006 10.864 16.321 10.864 26.229v23.159a4.849 4.849 0 0 0 4.849 4.849h16.953a4.849 4.849 0 0 0 4.849-4.849c0-7.602-3.02-14.892-8.395-20.267z" opacity="1" data-original="url(#C)"></path>
                        <path fill="url(#D)" d="M43.089 76.35c7.006 7.006 10.864 16.321 10.864 26.229v23.159a4.849 4.849 0 0 0 4.849 4.849h16.953a4.849 4.849 0 0 0 4.849-4.849c0-7.602-3.02-14.892-8.395-20.267z" opacity="1" data-original="url(#D)"></path>
                        <path fill="url(#E)" d="M80.605 125.738c0-7.602-3.02-14.892-8.395-20.267L43.089 76.35" opacity="1" data-original="url(#E)"></path>
                        <path fill="url(#F)" d="M61.934 57.504c-7.006-7.005-10.864-16.32-10.864-26.228V6.044a4.849 4.849 0 0 0-4.849-4.849H29.268a4.849 4.849 0 0 0-4.849 4.849v4.151a23.644 23.644 0 0 0 6.925 16.719z" opacity="1" data-original="url(#F)"></path>
                        <path fill="url(#G)" d="m503.36 107.331-15.51 15.51a6.275 6.275 0 0 1-8.873 0l-21.188-21.188c-9.065-9.065-21.117-14.057-33.936-14.057-22.031 0-42.742-8.579-58.32-24.157l-23.084-23.084a6.275 6.275 0 0 1 0-8.873l15.51-15.51a6.275 6.275 0 0 1 8.873 0l23.084 23.084c9.065 9.065 21.117 14.057 33.936 14.057 22.031 0 42.742 8.579 58.32 24.157l21.188 21.188a6.273 6.273 0 0 1 0 8.873z" opacity="1" data-original="url(#G)"></path>
                        <path fill="url(#H)" d="M423.852 87.596c12.82 0 24.872 4.992 33.936 14.057l21.188 21.188a6.275 6.275 0 0 0 8.873 0l15.51-15.51a6.275 6.275 0 0 0 0-8.873 37.084 37.084 0 0 0-26.223-10.862z" opacity="1" data-original="url(#H)"></path>
                        <path fill="url(#I)" d="M423.852 87.596c12.82 0 24.872 4.992 33.936 14.057l21.188 21.188a6.275 6.275 0 0 0 8.873 0l15.51-15.51a6.275 6.275 0 0 0 0-8.873 37.084 37.084 0 0 0-26.223-10.862z" opacity="1" data-original="url(#I)"></path>
                        <path fill="url(#J)" d="M503.36 98.458a37.084 37.084 0 0 0-26.223-10.862h-53.285" opacity="1" data-original="url(#J)"></path>
                        <path fill="url(#K)" d="M423.852 53.113c-12.819 0-24.872-4.992-33.936-14.057l-23.084-23.084a6.275 6.275 0 0 0-8.873 0l-15.51 15.51a6.275 6.275 0 0 0 0 8.873l3.797 3.797a30.592 30.592 0 0 0 21.632 8.96z" opacity="1" data-original="url(#K)"></path>
                        <path fill="url(#L)" d="m144.259 387.543-7.246-7.246a6.52 6.52 0 0 0-9.222 0l-16.073 16.073a6.52 6.52 0 0 0 0 9.222l6.324 6.324c21.901 21.901 57.409 21.901 79.31 0l6.324-6.324a6.52 6.52 0 0 0 0-9.222l-16.073-16.073a6.52 6.52 0 0 0-9.222 0l-7.246 7.246c-7.422 7.422-19.454 7.422-26.876 0z" opacity="1" data-original="url(#L)"></path>
                        <path fill="url(#M)" d="m197.352 411.916 6.324-6.324a6.52 6.52 0 0 0 0-9.222l-16.073-16.073a6.52 6.52 0 0 0-9.222 0l-7.246 7.246c-7.422 7.422-19.454 7.422-26.876 0l24.296 24.296c7.884 7.884 20.731 8.083 28.645.228l.152-.151z" opacity="1" data-original="url(#M)"></path>
                        <path fill="url(#N)" d="M263.039 266.338h10.247a6.522 6.522 0 0 0 6.521-6.521v-22.73a6.522 6.522 0 0 0-6.521-6.521h-8.943c-30.972 0-56.081 25.108-56.081 56.081v8.943a6.522 6.522 0 0 0 6.521 6.521h22.73a6.522 6.522 0 0 0 6.521-6.521v-10.247c0-10.497 8.509-19.005 19.005-19.005z" opacity="1" data-original="url(#N)"></path>
                        <path fill="url(#O)" d="M208.262 286.646v8.943a6.522 6.522 0 0 0 6.521 6.521h22.73a6.522 6.522 0 0 0 6.521-6.521v-10.247c0-10.496 8.508-19.004 19.004-19.004h-34.36c-11.15 0-20.374 8.944-20.417 20.093l.001.215z" opacity="1" data-original="url(#O)"></path>
                        <path fill="url(#P)" d="M349.705 440.525v10.247a6.522 6.522 0 0 0 6.521 6.521h22.73a6.522 6.522 0 0 0 6.521-6.521v-8.943c0-30.972-25.108-56.081-56.081-56.081h-8.943a6.522 6.522 0 0 0-6.521 6.521V415a6.522 6.522 0 0 0 6.521 6.521H330.7c10.496 0 19.005 8.509 19.005 19.004z" opacity="1" data-original="url(#P)"></path>
                        <path fill="url(#Q)" d="M329.396 385.749h-8.943a6.522 6.522 0 0 0-6.521 6.521V415a6.522 6.522 0 0 0 6.521 6.521H330.7c10.496 0 19.004 8.508 19.004 19.004v-34.36c0-11.15-8.944-20.374-20.093-20.417l-.215.001z" opacity="1" data-original="url(#Q)"></path>
                        <path fill="url(#R)" d="M123.628 176.856a1590.5 1590.5 0 0 1 16.167-1.059c1.652-.101 2.951.472 3.889 1.716.938 1.245 1.455 2.71 1.554 4.392.082 1.402-.231 2.81-.936 4.221-.706 1.413-1.951 2.173-3.734 2.282-6.425.393-9.637.604-16.06 1.052l1.246 17.857a1561.489 1561.489 0 0 1 28.426-1.767c1.626-.089 2.905.566 3.827 1.956.923 1.392 1.434 3.071 1.536 5.034a10.375 10.375 0 0 1-.911 4.844c-.695 1.549-1.918 2.368-3.672 2.464a2858.752 2858.752 0 0 0-37.467 2.423c-1.824.136-3.502-.157-5.041-.883s-2.376-1.927-2.508-3.606l-5.246-66.655c-.132-1.68.534-3 2.001-3.958 1.467-.959 3.151-1.51 5.051-1.652a1926.992 1926.992 0 0 1 39.02-2.523c1.826-.1 3.183.585 4.066 2.05a10.032 10.032 0 0 1 1.453 4.721c.102 1.963-.247 3.687-1.043 5.168-.797 1.483-2.029 2.268-3.697 2.359-11.668.636-17.501.998-29.16 1.812l1.239 17.752z" opacity="1" data-original="url(#R)"></path>
                        <path fill="url(#S)" d="M158.517 150.494c-.175-.552-.267-.935-.278-1.145-.05-.98.25-1.892.903-2.733.653-.84 1.513-1.587 2.579-2.237a16.55 16.55 0 0 1 3.494-1.59c1.262-.41 2.48-.643 3.65-.696 1.388-.063 2.589.114 3.595.527s1.715 1.175 2.128 2.279c5.857 17.757 11.603 35.552 17.186 53.398 4.33-18.18 8.774-36.333 13.382-54.445.334-1.131.991-1.938 1.967-2.42.976-.481 2.159-.739 3.55-.772 1.169-.027 2.4.121 3.688.444 1.286.323 2.486.773 3.594 1.347s2.018 1.261 2.726 2.055c.707.793 1.071 1.683 1.088 2.665.004.211-.064.599-.199 1.162a4083.825 4083.825 0 0 0-18.505 64.674c-.513 1.841-1.792 3.247-3.836 4.221-2.046.974-4.295 1.504-6.751 1.588-2.459.084-4.74-.291-6.846-1.123-2.108-.832-3.481-2.147-4.117-3.949a4065.41 4065.41 0 0 0-22.998-63.25z" opacity="1" data-original="url(#S)"></path>
                        <path fill="url(#T)" d="M245.319 172.272c6.477-.036 9.716-.041 16.193-.022 1.655.005 2.913.66 3.769 1.962.856 1.303 1.278 2.797 1.269 4.482-.008 1.405-.41 2.789-1.204 4.152-.795 1.365-2.085 2.043-3.871 2.038-6.434-.019-9.652-.014-16.086.022l.101 17.9c11.387-.064 17.081-.053 28.467.057 1.628.016 2.861.751 3.693 2.197.832 1.448 1.234 3.157 1.21 5.121a10.373 10.373 0 0 1-1.219 4.776c-.792 1.502-2.065 2.241-3.821 2.224-12.513-.076-25.013-.07-37.526.019-1.828.019-3.483-.381-4.971-1.204s-2.246-2.075-2.27-3.759c-.324-22.285-.646-44.569-.968-66.854-.024-1.685.724-2.959 2.249-3.822 1.525-.864 3.239-1.306 5.142-1.326 13.027-.138 26.055-.145 39.081-.02 1.828.018 3.14.789 3.925 2.306a10.029 10.029 0 0 1 1.147 4.804c-.024 1.966-.482 3.664-1.371 5.091-.89 1.429-2.169 2.134-3.839 2.118a1597.075 1597.075 0 0 0-29.203-.058c.043 7.119.063 10.678.103 17.796z" opacity="1" data-original="url(#T)"></path>
                        <path fill="url(#U)" d="M331.569 218.636c-1.896-.077-3.641-.425-5.236-1.049-1.595-.623-2.852-1.862-3.772-3.722a2195.75 2195.75 0 0 0-19.159-37.03l-.894 35.789c-.042 1.684-.937 2.924-2.679 3.726-1.742.801-3.631 1.18-5.668 1.138-2.039-.042-3.909-.498-5.617-1.371s-2.55-2.15-2.523-3.833l1.078-66.852c.028-1.754.943-3.022 2.749-3.798 1.806-.775 3.771-1.143 5.894-1.099 1.537.031 2.853.131 3.946.296a8.494 8.494 0 0 1 3.049 1.072c.937.549 1.832 1.397 2.679 2.542s1.755 2.679 2.725 4.601a3477.505 3477.505 0 0 1 16.522 33.076l1.299-35.777c.064-1.752 1.003-2.984 2.824-3.689 1.818-.704 3.792-1.014 5.913-.927s4.063.556 5.819 1.405c1.755.849 2.594 2.154 2.515 3.906l-3.022 66.9c-.076 1.683-.996 2.906-2.753 3.672-1.756.766-3.652 1.106-5.689 1.024z" opacity="1" data-original="url(#U)"></path>
                        <path fill="url(#V)" d="M402.565 145.69c1.825.139 3.082 1.013 3.763 2.613.682 1.602.954 3.28.816 5.029-.16 2.03-.735 3.744-1.72 5.146s-2.311 2.038-3.977 1.911c-6.001-.456-9.001-.673-15.004-1.082l-3.886 57.043c-.114 1.68-1.062 2.882-2.838 3.608s-3.68 1.023-5.714.893c-2.036-.13-3.886-.666-5.555-1.611-1.671-.946-2.456-2.257-2.356-3.938l3.38-57.075a1569.62 1569.62 0 0 0-15.017-.83c-1.669-.086-2.9-.886-3.699-2.4-.799-1.515-1.151-3.289-1.052-5.323a10.546 10.546 0 0 1 1.446-4.885c.879-1.501 2.235-2.209 4.062-2.115 15.795.863 31.572 1.868 47.351 3.016z" opacity="1" data-original="url(#V)"></path>
                    </g>
                </svg>',
                'routes' => ['admin.event.index', 'admin.event.create', 'admin.event.edit'],
                'route' => 'admin.event.index',
                ],
                [
                'title' => 'Events Seat Type',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <path fill="#fc4e51" d="M41.64 17.91a22.367 22.367 0 0 0-22.37 22.36v41.05H64V40.27a22.363 22.363 0 0 0-22.36-22.36z" opacity="1" data-original="#fc4e51" class=""></path>
                        <path fill="#cd2c38" d="M86.36 17.91A22.359 22.359 0 0 0 64 40.27v41.05h44.73V40.27a22.363 22.363 0 0 0-22.37-22.36z" opacity="1" data-original="#cd2c38"></path>
                        <g fill="#576166">
                            <path d="M36.5 88.818h10v20.363h-10zM81.5 88.818h10v20.363h-10z" fill="#576166" opacity="1" data-original="#576166"></path>
                        </g>
                        <path fill="#3e4a4a" d="M119.545 107.432H8.455a1.75 1.75 0 1 0 0 3.5h111.09a1.75 1.75 0 0 0 0-3.5z" opacity="1" data-original="#3e4a4a"></path>
                        <path fill="#b72032" d="M65.636 73.82h49.094v15H65.636z" opacity="1" data-original="#b72032"></path>
                        <path fill="#e23d45" d="M13.27 73.82H64v15H13.27z" opacity="1" data-original="#e23d45"></path>
                        <path fill="#fada23" d="M19.273 64a6 6 0 0 0-6 6v18.818h12V70a6 6 0 0 0-6-6z" opacity="1" data-original="#fada23" class=""></path>
                        <path fill="#e5c217" d="M64 64v24.82h6V70a6.009 6.009 0 0 0-6-6z" opacity="1" data-original="#e5c217"></path>
                        <path fill="#fada23" d="M58 70v18.82h6V64a6 6 0 0 0-6 6z" opacity="1" data-original="#fada23" class=""></path>
                        <path fill="#e5c217" d="M108.727 64a6 6 0 0 0-6 6v18.818h12V70a6 6 0 0 0-6-6zM64 45.2h44.73v7H64z" opacity="1" data-original="#e5c217"></path>
                        <path fill="#fada23" d="M19.27 45.2H64v7H19.27z" opacity="1" data-original="#fada23" class=""></path>
                    </g>
                </svg>',
                'routes' => ['admin.event-seat-type.index', 'admin.event-seat-type.create', 'admin.event-seat-type.edit'],
                'route' => 'admin.event-seat-type.index',
                ],
                [
                'title' => 'Events Seat',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <path fill="#fc4e51" d="M41.64 17.91a22.367 22.367 0 0 0-22.37 22.36v41.05H64V40.27a22.363 22.363 0 0 0-22.36-22.36z" opacity="1" data-original="#fc4e51" class=""></path>
                        <path fill="#cd2c38" d="M86.36 17.91A22.359 22.359 0 0 0 64 40.27v41.05h44.73V40.27a22.363 22.363 0 0 0-22.37-22.36z" opacity="1" data-original="#cd2c38"></path>
                        <g fill="#576166">
                            <path d="M36.5 88.818h10v20.363h-10zM81.5 88.818h10v20.363h-10z" fill="#576166" opacity="1" data-original="#576166"></path>
                        </g>
                        <path fill="#3e4a4a" d="M119.545 107.432H8.455a1.75 1.75 0 1 0 0 3.5h111.09a1.75 1.75 0 0 0 0-3.5z" opacity="1" data-original="#3e4a4a"></path>
                        <path fill="#b72032" d="M65.636 73.82h49.094v15H65.636z" opacity="1" data-original="#b72032"></path>
                        <path fill="#e23d45" d="M13.27 73.82H64v15H13.27z" opacity="1" data-original="#e23d45"></path>
                        <path fill="#fada23" d="M19.273 64a6 6 0 0 0-6 6v18.818h12V70a6 6 0 0 0-6-6z" opacity="1" data-original="#fada23" class=""></path>
                        <path fill="#e5c217" d="M64 64v24.82h6V70a6.009 6.009 0 0 0-6-6z" opacity="1" data-original="#e5c217"></path>
                        <path fill="#fada23" d="M58 70v18.82h6V64a6 6 0 0 0-6 6z" opacity="1" data-original="#fada23" class=""></path>
                        <path fill="#e5c217" d="M108.727 64a6 6 0 0 0-6 6v18.818h12V70a6 6 0 0 0-6-6zM64 45.2h44.73v7H64z" opacity="1" data-original="#e5c217"></path>
                        <path fill="#fada23" d="M19.27 45.2H64v7H19.27z" opacity="1" data-original="#fada23" class=""></path>
                    </g>
                </svg>',
                'routes' => ['admin.event-seat.index', 'admin.event-seat.create', 'admin.event-seat.edit'],
                'route' => 'admin.event-seat.index',
                ],
                [
                'title' => 'Contact Messages',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <path fill="#3d6d93" d="M373.441 512H138.559c-22.758 0-41.207-18.449-41.207-41.207V41.207C97.352 18.449 115.801 0 138.559 0H373.44c22.758 0 41.207 18.449 41.207 41.207v429.586c.001 22.758-18.448 41.207-41.206 41.207z" opacity="1" data-original="#3d6d93"></path>
                        <path fill="#335e80" d="M373.441 0h-30.905c22.758 0 41.207 18.449 41.207 41.207v429.586c0 22.758-18.449 41.207-41.207 41.207h30.905c22.758 0 41.207-18.449 41.207-41.207V41.207C414.648 18.449 396.199 0 373.441 0z" opacity="1" data-original="#335e80"></path>
                        <path fill="#f9f7f8" d="M331.67 39.147c0 7.692-6.235 13.927-13.927 13.927H194.257c-7.692 0-13.927-6.235-13.927-13.927a8.24 8.24 0 0 0-8.241-8.241H143.71c-8.534 0-15.453 6.918-15.453 15.453v419.284c0 8.534 6.918 15.453 15.453 15.453h224.58c8.534 0 15.453-6.918 15.453-15.453V46.358c0-8.534-6.918-15.453-15.453-15.453h-28.379a8.242 8.242 0 0 0-8.241 8.242z" opacity="1" data-original="#f9f7f8"></path>
                        <circle cx="256" cy="26.537" r="7.726" fill="#335e80" opacity="1" data-original="#335e80"></circle>
                        <path fill="#f58a97" d="M177.556 216.331c12.561-15.067 19.89-34.646 19.158-55.961-1.5-43.725-37.318-79.059-81.059-79.997-47.013-1.008-85.37 37.116-84.743 84.044.609 45.54 38.494 81.795 84.038 81.795h75.81c3.671 0 5.51-4.439 2.914-7.035l-15.852-15.852c-1.902-1.902-1.99-4.926-.266-6.994z" opacity="1" data-original="#f58a97"></path>
                        <path fill="#f07281" d="M177.823 223.327c-1.904-1.904-1.991-4.928-.267-6.996 12.561-15.067 19.89-34.646 19.158-55.961-1.5-43.725-37.318-79.059-81.059-79.997a83.502 83.502 0 0 0-17.356 1.438c37.364 7.075 66.168 39.45 67.51 78.559.731 21.314-6.597 40.894-19.158 55.961-1.724 2.068-1.637 5.092.267 6.996l15.852 15.852c2.596 2.596.757 7.035-2.914 7.035h30.905c3.671 0 5.51-4.439 2.914-7.035z" opacity="1" data-original="#f07281"></path>
                        <g fill="#fdd1d5">
                            <circle cx="113.835" cy="163.284" r="7.726" fill="#fdd1d5" opacity="1" data-original="#fdd1d5"></circle>
                            <circle cx="144.74" cy="163.284" r="7.726" fill="#fdd1d5" opacity="1" data-original="#fdd1d5"></circle>
                            <circle cx="82.93" cy="163.284" r="7.726" fill="#fdd1d5" opacity="1" data-original="#fdd1d5"></circle>
                        </g>
                        <path fill="#dedaee" d="M336.691 246.213H222.004a7.726 7.726 0 1 1 0-15.452h114.687a7.726 7.726 0 1 1 0 15.452zM336.691 215.308H222.004a7.726 7.726 0 1 1 0-15.452h114.687a7.726 7.726 0 1 1 0 15.452zM336.691 184.402h-99.234a7.726 7.726 0 1 1 0-15.452h99.234a7.726 7.726 0 1 1 0 15.452zM290.333 431.646H175.646a7.726 7.726 0 1 1 0-15.452h114.687a7.726 7.726 0 1 1 0 15.452zM290.333 400.74H175.646a7.726 7.726 0 1 1 0-15.452h114.687a7.726 7.726 0 1 1 0 15.452zM274.88 369.835h-99.234a7.726 7.726 0 1 1 0-15.452h99.234a7.726 7.726 0 1 1 0 15.452z" opacity="1" data-original="#dedaee"></path>
                        <path fill="#aee69c" d="M334.444 401.764c-12.561-15.067-19.89-34.646-19.158-55.961 1.5-43.725 37.318-79.059 81.059-79.997 47.013-1.008 85.37 37.117 84.743 84.044-.609 45.54-38.494 81.795-84.038 81.795h-75.81c-3.671 0-5.51-4.439-2.914-7.035l15.852-15.852c1.902-1.902 1.99-4.926.266-6.994z" opacity="1" data-original="#aee69c"></path>
                        <path fill="#89daa4" d="M396.344 265.806a82.236 82.236 0 0 0-13.549 1.438c38.732 7.341 67.934 41.613 67.387 82.606-.609 45.54-38.494 81.795-84.038 81.795h30.905c45.544 0 83.43-36.255 84.038-81.795.627-46.927-37.73-85.052-84.743-84.044z" opacity="1" data-original="#89daa4"></path>
                        <g fill="#d5efc8">
                            <circle cx="398.165" cy="348.716" r="7.726" fill="#d5efc8" opacity="1" data-original="#d5efc8"></circle>
                            <circle cx="367.26" cy="348.716" r="7.726" fill="#d5efc8" opacity="1" data-original="#d5efc8"></circle>
                            <circle cx="429.07" cy="348.716" r="7.726" fill="#d5efc8" opacity="1" data-original="#d5efc8"></circle>
                        </g>
                    </g>
                </svg>',
                'routes' => ['admin.contact.index', 'admin.contact.create', 'admin.contact.edit'],
                'route' => 'admin.contact.index',
                ],
                //====================== Event Management End ==============

                //====================== Frontend Management Start ============
                [
                'title' => 'Frontend Management',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 68 68" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <path fill="#e9e9ff" d="M66 14.918v44.061a4.08 4.08 0 0 1-4.083 4.083H6.083A4.08 4.08 0 0 1 2 58.98V14.918z" opacity="1" data-original="#e9e9ff" class=""></path>
                        <path fill="#ed524e" d="M66 14.914V9.032a4.08 4.08 0 0 0-4.083-4.083L6.083 4.938A4.088 4.088 0 0 0 2 9.02v5.894z" opacity="1" data-original="#ed524e" class=""></path>
                        <g fill="#e9e9ff">
                            <path d="M16.538 9.926c0 .983-.787 1.77-1.77 1.77a1.764 1.764 0 0 1-1.772-1.77c0-.984.787-1.771 1.771-1.771.984 0 1.771.787 1.771 1.77zM10.29 9.926c0 .983-.799 1.77-1.77 1.77a1.774 1.774 0 0 1-1.784-1.77c0-.984.8-1.771 1.784-1.771.971 0 1.77.787 1.77 1.77zM22.798 9.926c0 .983-.8 1.77-1.784 1.77a1.771 1.771 0 0 1 0-3.541c.984 0 1.784.787 1.784 1.77zM37.838 8.65a1.276 1.276 0 0 0 0 2.552h22.14c.71 0 1.286-.567 1.286-1.276 0-.71-.576-1.276-1.285-1.276z" fill="#e9e9ff" opacity="1" data-original="#e9e9ff" class=""></path>
                        </g>
                        <path fill="#5382ff" d="M37.045 56.595h.016c-.149.031-.298.055-.44.078.142-.023.283-.055.424-.078z" opacity="1" data-original="#5382ff"></path>
                        <g fill="#fdc72e">
                            <path d="M43.292 37.352c-1.09.141-2.273.04-3.206.62-.572.36-.972.924-1.465 1.379-.768.698-1.764 1.09-2.744 1.442-1.254.447-2.54.83-3.84 1.152-1.16.29-2.383.557-3.332 1.302-.846.681-1.395 1.716-2.28 2.343-1.035.73-2.564.98-2.948 2.171a1.702 1.702 0 0 0-.055.839c.189 1.074 1.27 2.085 1.545 3.237.18.8-.024 1.623-.251 2.414a17.442 17.442 0 0 1-3.896-3.214c-.36-.392-.713-.807-1.035-1.23-2.304-2.987-3.652-6.749-3.652-10.817 0-3.245.862-6.286 2.375-8.904a17.91 17.91 0 0 1 4.523-5.181c.595.416 1.34.525 2.03.752.697.22 1.418.65 1.606 1.349.22.878-.439 1.873.008 2.649.4.698 1.388.698 2.187.603 1.772-.203 3.55-.4 5.338-.603 1.246-.141 2.657-.235 3.598.603.69.604.964 1.584 1.661 2.187.627.557 1.513.714 2.344.776.839.07 1.709.07 2.477.384.784.314 1.473 1.05 1.403 1.897-.078 1.082-1.309 1.709-2.39 1.85zM51.844 39.72a17.723 17.723 0 0 1-1.717 6.96 17.845 17.845 0 0 1-5.596 6.74 8.905 8.905 0 0 1-.98-.517c-.494-.29-.917-.611-1.23-.94-1.035-1.074-2.517-3.308-1.27-4.147 1.38-.894 3.362-1.082 3.268-2.735-.047-.777-.439-1.529-.447-2.32-.008-.784.674-1.686 1.419-1.435.33.11.549.408.854.572.627.337 1.427.016 1.968-.454.548-.463.964-1.066 1.575-1.443.643-.4 1.42-.454 2.156-.282z" fill="#fdc72e" opacity="1" data-original="#fdc72e"></path>
                        </g>
                        <path fill="#70c1f9" d="M51.867 38.99c0 .243-.007.486-.016.73-.736-.173-1.52-.118-2.163.281-.611.377-1.027.98-1.575 1.443-.541.47-1.34.791-1.968.454-.305-.164-.525-.462-.854-.572-.745-.251-1.427.65-1.419 1.435.008.791.4 1.543.447 2.32.094 1.653-1.889 1.841-3.268 2.735-1.247.839.235 3.073 1.27 4.146.313.33.736.651 1.23.941.306.188.635.36.98.517-.188.134-.377.267-.565.392a9.148 9.148 0 0 1-.58.377c-.196.133-.392.242-.588.352-.203.118-.407.228-.61.33-.205.11-.416.211-.62.305-.447.212-.901.408-1.364.572-.157.063-.321.118-.486.173a15.695 15.695 0 0 1-2.618.666c-.023.008-.04.016-.055.008-.29.055-.572.102-.862.133-.415.055-.83.086-1.254.102a11.91 11.91 0 0 1-.925.031c-2.422 0-4.71-.485-6.82-1.371a17.267 17.267 0 0 1-2.468-1.239c.227-.791.43-1.615.25-2.414-.274-1.152-1.355-2.163-1.544-3.237a1.702 1.702 0 0 1 .055-.839c.384-1.191 1.913-1.442 2.947-2.171.886-.627 1.435-1.662 2.281-2.343.949-.745 2.172-1.012 3.332-1.302a40.219 40.219 0 0 0 3.84-1.152c.98-.353 1.976-.744 2.744-1.442.493-.455.893-1.02 1.465-1.38.933-.58 2.117-.478 3.206-.62 1.082-.14 2.313-.767 2.39-1.849.071-.846-.618-1.583-1.402-1.897-.768-.313-1.638-.313-2.477-.384-.83-.062-1.717-.22-2.344-.776-.697-.603-.972-1.583-1.661-2.187-.941-.838-2.352-.744-3.598-.603-1.787.204-3.566.4-5.338.603-.8.095-1.787.095-2.187-.603-.447-.776.212-1.771-.008-2.65-.188-.697-.909-1.128-1.607-1.348-.69-.227-1.434-.336-2.03-.752.479-.384.98-.737 1.49-1.058a17.829 17.829 0 0 1 9.484-2.728c.455 0 .94.016 1.41.063 6.287.486 11.688 4.248 14.447 9.586a17.616 17.616 0 0 1 2.006 8.222z" opacity="1" data-original="#70c1f9"></path>
                        <path fill="#ffe386" d="M51.867 38.99c0 .243-.007.486-.016.73h-.007c.016-.244.023-.487.023-.73z" opacity="1" data-original="#ffe386"></path>
                        <path fill="#70c1f9" d="M25.037 37.838c-.462.517-1.097.839-1.662 1.254-1.763 1.325-2.657 3.715-2.202 5.87.086.393.211.777.321 1.169.079.274.157.549.204.83.125.683.086 1.427-.321 1.992-.377.517-.957.752-1.592.854-2.304-2.987-3.652-6.749-3.652-10.817 0-3.245.862-6.286 2.375-8.904.345.533.588 1.191.729 1.858.196.9.211 1.818.07 2.492-.156.745-.446 1.623.04 2.219.352.446 1.034.517 1.575.336.556-.172 1.003-.548 1.474-.893.446-.321.932-.635 1.48-.721.557-.094 1.177.086 1.482.548.385.588.134 1.395-.321 1.913z" opacity="1" data-original="#70c1f9"></path>
                        <path fill="#70c1f9" d="M51.867 38.99c0 .243-.007.486-.016.73-.736-.173-1.52-.118-2.163.281-.611.377-1.027.98-1.575 1.443-.541.47-1.34.791-1.968.454-.305-.164-.525-.462-.854-.572-.745-.251-1.427.65-1.419 1.435.008.791.4 1.543.447 2.32.094 1.653-1.889 1.841-3.268 2.735-1.247.839.235 3.073 1.27 4.146.313.33.736.651 1.23.941.306.188.635.36.98.517-.188.134-.377.267-.565.392a9.148 9.148 0 0 1-.58.377c-.196.133-.392.242-.588.352-.203.118-.407.228-.61.33-.205.11-.416.211-.62.305-.447.212-.901.408-1.364.572-.157.063-.321.118-.486.173a15.695 15.695 0 0 1-2.618.666c-.023.008-.04.016-.055.008-.29.055-.572.102-.862.133-.415.055-.83.086-1.254.102a11.91 11.91 0 0 1-.925.031c-2.422 0-4.71-.485-6.82-1.371a17.267 17.267 0 0 1-2.468-1.239c.227-.791.43-1.615.25-2.414-.274-1.152-1.355-2.163-1.544-3.237a1.702 1.702 0 0 1 .055-.839c.384-1.191 1.913-1.442 2.947-2.171.886-.627 1.435-1.662 2.281-2.343.949-.745 2.172-1.012 3.332-1.302a40.219 40.219 0 0 0 3.84-1.152c.98-.353 1.976-.744 2.744-1.442.493-.455.893-1.02 1.465-1.38.933-.58 2.117-.478 3.206-.62 1.082-.14 2.313-.767 2.39-1.849.071-.846-.618-1.583-1.402-1.897-.768-.313-1.638-.313-2.477-.384-.83-.062-1.717-.22-2.344-.776-.697-.603-.972-1.583-1.661-2.187-.941-.838-2.352-.744-3.598-.603-1.787.204-3.566.4-5.338.603-.8.095-1.787.095-2.187-.603-.447-.776.212-1.771-.008-2.65-.188-.697-.909-1.128-1.607-1.348-.69-.227-1.434-.336-2.03-.752.479-.384.98-.737 1.49-1.058a17.829 17.829 0 0 1 9.484-2.728c.455 0 .94.016 1.41.063 6.287.486 11.688 4.248 14.447 9.586a17.616 17.616 0 0 1 2.006 8.222z" opacity="1" data-original="#70c1f9"></path>
                        <path fill="#ffffff" d="M54.484 35.328v7.324c0 .537-.424.96-.96.96H14.476a.953.953 0 0 1-.96-.96v-7.324c0-.537.424-.96.96-.96h39.048c.536 0 .96.423.96.96z" opacity="1" data-original="#ffffff"></path>
                        <path fill="#ed524e" d="M22.605 34.367h-8.129a.953.953 0 0 0-.96.961v7.324c0 .537.424.96.96.96h8.129z" opacity="1" data-original="#ed524e" class=""></path>
                        <path fill="#e9e9ff" d="M16.63 40.42a2.452 2.452 0 0 1 .006-3.463 2.452 2.452 0 0 1 3.464-.007 2.457 2.457 0 0 1 0 3.47 2.457 2.457 0 0 1-3.47 0zm2.699-2.699a1.36 1.36 0 0 0-1.921.007 1.36 1.36 0 0 0-.007 1.921 1.365 1.365 0 0 0 1.928 0 1.365 1.365 0 0 0 0-1.928z" opacity="1" data-original="#e9e9ff" class=""></path>
                        <path fill="#e9e9ff" d="M15.383 41.664a.545.545 0 0 1 0-.771l1.245-1.245a.545.545 0 1 1 .772.771l-1.245 1.245a.545.545 0 0 1-.772 0z" opacity="1" data-original="#e9e9ff" class=""></path>
                        <g fill="#4f5d99">
                            <path d="M25.382 41.002a.494.494 0 0 1-.179-.243l-1.151-3.375a.436.436 0 0 1-.025-.137c0-.103.035-.19.106-.26a.352.352 0 0 1 .26-.105c.075 0 .144.02.206.06a.3.3 0 0 1 .126.159l.949 3.122.94-3.05a.407.407 0 0 1 .15-.21.406.406 0 0 1 .248-.081.408.408 0 0 1 .397.292l.941 3.05.95-3.123a.298.298 0 0 1 .125-.158.372.372 0 0 1 .207-.061c.102 0 .189.035.26.105.07.07.105.157.105.26 0 .043-.009.089-.025.137l-1.151 3.375a.497.497 0 0 1-.763.243.542.542 0 0 1-.186-.252l-.86-2.684-.86 2.684a.542.542 0 0 1-.186.252.474.474 0 0 1-.292.097.474.474 0 0 1-.292-.097zM31.805 41.002a.494.494 0 0 1-.178-.243l-1.152-3.375a.436.436 0 0 1-.024-.137c0-.103.035-.19.105-.26a.352.352 0 0 1 .26-.105c.075 0 .144.02.207.06a.3.3 0 0 1 .125.159l.95 3.122.94-3.05a.407.407 0 0 1 .15-.21.406.406 0 0 1 .247-.081.408.408 0 0 1 .397.292l.942 3.05.949-3.123a.298.298 0 0 1 .125-.158.372.372 0 0 1 .207-.061c.103 0 .19.035.26.105.07.07.105.157.105.26 0 .043-.008.089-.024.137l-1.152 3.375a.497.497 0 0 1-.762.243.542.542 0 0 1-.187-.252l-.86-2.684-.86 2.684c-.037.103-.1.187-.186.252s-.184.097-.292.097a.474.474 0 0 1-.292-.097zM38.229 41.002a.494.494 0 0 1-.179-.243L36.9 37.384a.436.436 0 0 1-.025-.137c0-.103.035-.19.106-.26a.352.352 0 0 1 .26-.105c.075 0 .144.02.206.06a.3.3 0 0 1 .126.159l.949 3.122.94-3.05a.407.407 0 0 1 .15-.21.406.406 0 0 1 .248-.081.408.408 0 0 1 .397.292l.941 3.05.95-3.123a.298.298 0 0 1 .125-.158.372.372 0 0 1 .207-.061c.102 0 .189.035.26.105.07.07.105.157.105.26 0 .043-.009.089-.025.137l-1.151 3.375a.497.497 0 0 1-.763.243.542.542 0 0 1-.186-.252l-.86-2.684-.86 2.684c-.038.103-.1.187-.186.252s-.184.097-.292.097-.206-.032-.292-.097z" fill="#4f5d99" opacity="1" data-original="#4f5d99"></path>
                        </g>
                        <path fill="#70c1f9" d="M18.367 19.363v1.006a.231.231 0 0 1-.229.229H5.918a.231.231 0 0 1-.23-.23v-1.005c0-.125.105-.23.23-.23h12.22c.125 0 .23.105.23.23z" opacity="1" data-original="#70c1f9"></path>
                        <path fill="#ed524e" d="M13.712 23.096v1.007a.231.231 0 0 1-.229.229H5.917a.231.231 0 0 1-.228-.23v-1.006c0-.124.104-.229.228-.229h7.566c.125 0 .229.105.229.23z" opacity="1" data-original="#ed524e" class=""></path>
                        <g fill="#fff" opacity=".2">
                            <path d="m9.07 4.943 19.544 58.12h19.68L28.738 4.942zM39.39 4.943h-5.593l19.555 58.12h5.578z" fill="#ffffff" opacity="1" data-original="#ffffff"></path>
                        </g>
                    </g>
                </svg>',

                'routes' => [
                'admin.banner.index',
                'admin.banner.create',
                'admin.banner.edit',

                'admin.service.index',
                'admin.service.create',
                'admin.service.edit',

                'admin.blog-category.index',
                'admin.blog-category.create',
                'admin.blog-category.edit',

                'admin.blog-post.index',
                'admin.blog-post.create',
                'admin.blog-post.edit',

                'admin.contact.index',
                'admin.contact.create',
                'admin.contact.edit',

                'admin.subscription.index',
                'admin.subscription.create',
                'admin.subscription.edit',
                ],

                'subMenu' => [
                [
                'title' => 'Banner',
                'routes' => ['admin.banner.index', 'admin.banner.create', 'admin.banner.edit'],
                'route' => 'admin.banner.index',
                ],

                [
                'title' => 'Services',
                'routes' => ['admin.service.index', 'admin.service.create', 'admin.service.edit'],
                'route' => 'admin.service.index',
                ],

                [
                'title' => 'Blog Category',
                'routes' => [
                'admin.blog-category.index',
                'admin.blog-category.create',
                'admin.blog-category.edit',
                ],
                'route' => 'admin.blog-category.index',
                ],

                [
                'title' => 'Blog',
                'routes' => [
                'admin.blog-post.index',
                'admin.blog-post.create',
                'admin.blog-post.edit',
                ],
                'route' => 'admin.blog-post.index',
                ],

                [
                'title' => 'Contact',
                'routes' => ['admin.contact.index', 'admin.contact.create', 'admin.contact.edit'],
                'route' => 'admin.contact.index',
                ],

                [
                'title' => 'Subscription',

                'routes' => [
                'admin.subscription.index',
                'admin.subscription.create',
                'admin.subscription.edit',
                ],

                'route' => 'admin.subscription.index',
                ],
                ],
                ],
                //====================== Frontend Management End ==============

                // ========================= Setting Start ====================
                [
                'title' => 'Web Settings',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <path fill="#ddeafb" d="M5.127 125.764 0 134.999v266.983c0 17.618 14.282 31.9 31.9 31.9h448.2c17.618 0 31.9-14.282 31.9-31.9V134.999l-5.127-9.235z" opacity="1" data-original="#ddeafb" class=""></path>
                        <path fill="#cbe2ff" d="M459.765 125.764v223.983c0 17.618-14.282 31.9-31.9 31.9H0v20.335c0 17.618 14.282 31.9 31.9 31.9h448.2c17.618 0 31.9-14.282 31.9-31.9V134.999l-5.127-9.235z" opacity="1" data-original="#cbe2ff"></path>
                        <path fill="#698ed5" d="M480.1 46.963H31.9c-17.618 0-31.9 14.282-31.9 31.9v56.136h512V78.863c0-17.618-14.282-31.9-31.9-31.9z" opacity="1" data-original="#698ed5" class=""></path>
                        <circle cx="57.783" cy="90.981" r="14.312" fill="#dd636e" opacity="1" data-original="#dd636e"></circle>
                        <circle cx="101.809" cy="90.981" r="14.312" fill="#ffe07d" opacity="1" data-original="#ffe07d"></circle>
                        <circle cx="145.835" cy="90.981" r="14.312" fill="#86f1a7" opacity="1" data-original="#86f1a7"></circle>
                        <path fill="#bed8fb" d="M221.751 279.823H47.136c-5.523 0-10-4.477-10-10V187.9c0-5.523 4.477-10 10-10h174.615c5.523 0 10 4.477 10 10v81.923c0 5.523-4.477 10-10 10zM219.488 396.106h-55.421c-5.523 0-10-4.477-10-10v-57.481c0-5.523 4.477-10 10-10h55.421c5.523 0 10 4.477 10 10v57.481c0 5.523-4.478 10-10 10z" opacity="1" data-original="#bed8fb" class=""></path>
                        <path fill="#698ed5" d="M115.38 334.602H43.507a7.726 7.726 0 1 1 0-15.452h71.873c4.268 0 7.726 3.459 7.726 7.726s-3.458 7.726-7.726 7.726zM115.38 367.627H43.507a7.726 7.726 0 1 1 0-15.452h71.873c4.268 0 7.726 3.459 7.726 7.726s-3.458 7.726-7.726 7.726zM468.716 194.432h-40.805a7.726 7.726 0 1 1 0-15.452h40.805c4.268 0 7.726 3.459 7.726 7.726s-3.458 7.726-7.726 7.726zM392.075 194.432h-123.86a7.726 7.726 0 1 1 0-15.452h123.86c4.268 0 7.726 3.459 7.726 7.726s-3.458 7.726-7.726 7.726zM468.716 227.458H268.215a7.726 7.726 0 1 1 0-15.452h200.501c4.268 0 7.726 3.459 7.726 7.726s-3.458 7.726-7.726 7.726z" opacity="1" data-original="#698ed5" class=""></path>
                        <path fill="#736e6e" d="m466.964 384.87-15.959-6.715a79.395 79.395 0 0 0 .125-23.342l16.038-6.539a6.204 6.204 0 0 0 3.403-8.088l-10.009-24.552a6.204 6.204 0 0 0-8.088-3.403l-16.039 6.538a79.415 79.415 0 0 0-16.409-16.601l6.715-15.959a6.206 6.206 0 0 0-3.313-8.126L398.991 267.8a6.205 6.205 0 0 0-8.126 3.313l-6.715 15.959a79.361 79.361 0 0 0-23.342-.125l-6.539-16.038a6.204 6.204 0 0 0-8.088-3.403l-24.552 10.009a6.205 6.205 0 0 0-3.403 8.088l6.538 16.039a79.391 79.391 0 0 0-16.601 16.409l-15.959-6.715a6.205 6.205 0 0 0-8.126 3.313l-10.283 24.438a6.205 6.205 0 0 0 3.313 8.126l15.959 6.715a79.361 79.361 0 0 0-.125 23.342l-16.038 6.539a6.205 6.205 0 0 0-3.403 8.088l10.009 24.552a6.205 6.205 0 0 0 8.088 3.403l16.039-6.538a79.415 79.415 0 0 0 16.409 16.601l-6.715 15.959a6.206 6.206 0 0 0 3.313 8.126l24.438 10.283a6.205 6.205 0 0 0 8.126-3.313l6.715-15.959a79.361 79.361 0 0 0 23.342.125l6.539 16.038a6.204 6.204 0 0 0 8.088 3.403l24.552-10.009a6.204 6.204 0 0 0 3.403-8.088l-6.539-16.038a79.415 79.415 0 0 0 16.601-16.409l15.959 6.715a6.206 6.206 0 0 0 8.126-3.313l10.283-24.438a6.207 6.207 0 0 0-3.313-8.127zm-110.641 18.513c-20.623-8.678-30.308-32.432-21.63-53.055s32.432-30.307 53.055-21.63c20.623 8.678 30.307 32.432 21.63 53.055s-32.431 30.308-53.055 21.63z" opacity="1" data-original="#736e6e"></path>
                        <circle cx="372.036" cy="366.041" r="10.865" fill="#736e6e" opacity="1" data-original="#736e6e"></circle>
                        <path fill="#595454" d="m466.964 384.87-15.959-6.715a79.395 79.395 0 0 0 .125-23.342l16.038-6.539a6.204 6.204 0 0 0 3.403-8.088l-10.009-24.552a6.204 6.204 0 0 0-8.088-3.403l-16.039 6.538a79.391 79.391 0 0 0-16.409-16.601l6.715-15.959a6.205 6.205 0 0 0-3.313-8.126L398.991 267.8a6.203 6.203 0 0 0-8.125 3.313l-4.859 11.547c24.69 13.564 41.429 39.808 41.429 69.968 0 44.064-35.721 79.784-79.784 79.784a79.633 79.633 0 0 1-25.974-4.348c.776.63 1.565 1.246 2.369 1.849l-6.715 15.959a6.204 6.204 0 0 0 3.313 8.125l24.438 10.283a6.205 6.205 0 0 0 8.126-3.313l6.715-15.959a79.361 79.361 0 0 0 23.342.125l6.539 16.038a6.204 6.204 0 0 0 8.088 3.403l24.552-10.009a6.204 6.204 0 0 0 3.403-8.088l-6.538-16.038a79.391 79.391 0 0 0 16.601-16.409l15.959 6.715a6.204 6.204 0 0 0 8.125-3.313l10.283-24.438a6.206 6.206 0 0 0-3.314-8.124z" opacity="1" data-original="#595454"></path>
                        <path fill="#4073c8" d="M480.1 46.963h-20.335v88.036H512V78.863c0-17.618-14.282-31.9-31.9-31.9z" opacity="1" data-original="#4073c8"></path>
                    </g>
                </svg>',
                'routes' => [
                'admin.settings.index',

                'admin.faq.index',
                'admin.faq.create',
                'admin.faq.edit',

                'admin.terms.index',
                'admin.terms.create',
                'admin.terms.edit',

                'admin.privacy.index',
                'admin.privacy.create',
                'admin.privacy.edit',
                ],

                'subMenu' => [
                [
                'title' => 'Setting',
                'routes' => ['admin.settings.index'],
                'route' => 'admin.settings.index',
                ],

                [
                'title' => 'FAQs',
                'routes' => ['admin.faq.index', 'admin.faq.create', 'admin.faq.edit'],
                'route' => 'admin.faq.index',
                ],

                [
                'title' => 'Term & Condition',
                'routes' => ['admin.terms.index', 'admin.terms.create', 'admin.terms.edit'],
                'route' => 'admin.terms.index',
                ],

                [
                'title' => 'Privacy Policy',
                'routes' => ['admin.privacy.index', 'admin.privacy.create', 'admin.privacy.edit'],
                'route' => 'admin.privacy.index',
                ],
                ],
                ],
                // ========================= Setting End ======================

                // =================== Management Section Start ===============
                [
                'title' => 'User Management',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="hovered-paths">
                    <g>
                        <path fill="#484868" d="M434.029 321.841H397.2c23.019 0 37.3-17.862 43.618-38.183l30.709-6.524a8 8 0 0 0 6.338-7.825v-71.347a8 8 0 0 0-6.339-7.825l-40.071-8.508-14.242-34.4 22.314-34.37a8 8 0 0 0-1.055-10.014l-50.459-50.427A8 8 0 0 0 378 51.366l-34.367 22.313-34.375-14.24-8.534-40.1A8 8 0 0 0 292.9 13h-71.348a8 8 0 0 0-7.826 6.34l-8.507 40.1-34.4 14.241-34.369-22.315a8 8 0 0 0-10.014 1.053l-50.427 50.427a8 8 0 0 0-1.053 10.013l22.313 34.37-14.24 34.4-40.1 8.507a8 8 0 0 0-6.34 7.826v71.347a8 8 0 0 0 6.339 7.825L71 283.092c6.223 20.556 20.551 38.748 43.8 38.749H77.943c-27.971 0-50.861 21.261-50.861 47.244v64.474h100.95V499h255.936v-65.441h100.95v-64.474c0-25.985-22.89-47.244-50.889-47.244zm-80.049-82.654a47.218 47.218 0 0 0-4.251 19.615c0 1.695.062 3.435.181 5.206a98.048 98.048 0 0 1-187.842-.074c.116-1.745.175-3.461.175-5.132a47.254 47.254 0 0 0-4.221-19.558 99.346 99.346 0 0 1-.066-3.415 98.044 98.044 0 1 1 196.088 0 98.53 98.53 0 0 1-.064 3.358z" opacity="1" data-original="#484868" class="hovered-path"></path>
                        <path fill="#eae8e8" d="M484.918 369.085c0-25.983-22.89-47.244-50.889-47.244h-73.7a52.959 52.959 0 0 0-34.045 12.2l-.016.042a79.2 79.2 0 0 0-16.524-1.754H202.225a79.069 79.069 0 0 0-16.445 1.739l-.067-.027a52.959 52.959 0 0 0-34.045-12.2H77.943c-27.971 0-50.861 21.261-50.861 47.244v64.474h100.95V499h255.936v-65.441h100.95z" opacity="1" data-original="#eae8e8"></path>
                        <path fill="#ddd7d7" d="M309.747 332.333h-19.783c40.811 0 74.221 30.981 74.221 68.893V499h19.783v-97.774c0-37.912-33.41-68.893-74.221-68.893zM194.382 499v-83.333a7.537 7.537 0 1 0-15.073 0V499z" opacity="1" data-original="#ddd7d7"></path>
                        <path fill="#ddd7d7" d="M332.663 499v-83.333a7.537 7.537 0 1 0-15.073 0V499z" opacity="1" data-original="#ddd7d7"></path>
                        <path fill="#ffddab" d="M397.2 211.365a47.448 47.448 0 0 1 47.432 47.435c0 27.806-15.1 63.039-47.437 63.039-33.356 0-47.466-36.835-47.466-63.039a47.455 47.455 0 0 1 47.471-47.435zM114.805 211.365A47.43 47.43 0 0 0 67.368 258.8c0 27.806 15.076 63.039 47.437 63.039 33.356 0 47.438-36.835 47.438-63.039a47.431 47.431 0 0 0-47.438-47.437z" opacity="1" data-original="#ffddab"></path>
                        <path fill="#f9ce95" d="M397.2 211.365a47.71 47.71 0 0 0-8.483.77 47.454 47.454 0 0 1 38.958 46.665c0 25.26-12.468 56.644-38.992 62.179a41.659 41.659 0 0 0 8.512.86c32.333 0 47.437-35.233 47.437-63.039a47.448 47.448 0 0 0-47.432-47.435zM114.805 211.365a47.653 47.653 0 0 0-8.478.77 47.434 47.434 0 0 1 38.959 46.665c0 23.855-11.673 56.514-38.929 62.18a41.3 41.3 0 0 0 8.448.859c33.356 0 47.438-36.835 47.438-63.039a47.431 47.431 0 0 0-47.438-47.435z" opacity="1" data-original="#f9ce95"></path>
                        <path fill="#ad1e1e" d="m255.986 499-25.928-26.48 15.933-112.22a17.023 17.023 0 0 1-6.876-7.7c-1.546-3.369-1.27-12.315.746-15.436a10.541 10.541 0 0 1 9-4.832h14.249a10.475 10.475 0 0 1 9 4.832c2.043 3.121 2.319 12.067.773 15.436a17.237 17.237 0 0 1-6.9 7.7l15.96 112.216z" opacity="1" data-original="#ad1e1e"></path>
                        <path fill="#ffddab" d="M255.986 332.333c24.133 0 45.008-20.736 55.114-50.889 16.871-1.739 27.695-42.329 14.055-46.554a8.887 8.887 0 0 0-7.677.939c11.625-49.067 2.126-57.847-13.2-64.336-13.226-14.662-32.141-23-48.294-23-32.969 0-77.479 34.708-61.492 87.337a8.864 8.864 0 0 0-7.676-.939c-13.613 4.225-2.817 44.815 14.082 46.554 10.078 30.153 30.981 50.889 55.086 50.889z" opacity="1" data-original="#ffddab"></path>
                        <path fill="#7da8ff" d="M434.029 321.841h-73.7a52.959 52.959 0 0 0-34.045 12.2l-.016.042c32.928 7.02 57.7 34.5 57.7 67.139v32.333h100.95v-64.47c0-25.985-22.89-47.244-50.889-47.244zM128.032 401.226c0-32.64 24.787-60.156 57.748-67.154l-.067-.027a52.959 52.959 0 0 0-34.045-12.2H77.943c-27.971 0-50.861 21.261-50.861 47.244v64.474h100.95z" opacity="1" data-original="#7da8ff"></path>
                        <path fill="#5090ef" d="M128.032 433.559v-32.333c0-32.64 24.787-60.156 57.748-67.154l-.067-.027a47.615 47.615 0 0 0-2.175-1.712h-1.1c-40.81 0-74.194 31.009-74.194 68.893v32.333zM383.968 433.559v-32.333c0-32.64-24.788-60.156-57.748-67.154l.067-.027a51.122 51.122 0 0 1 2.174-1.712h1.1c40.81 0 74.193 31.009 74.193 68.893v32.333z" opacity="1" data-original="#5090ef"></path>
                        <path fill="#d22e2e" d="M272.885 352.6c1.546-3.369 1.27-12.315-.773-15.436a10.475 10.475 0 0 0-9-4.832h-14.25a10.541 10.541 0 0 0-9 4.832c-2.016 3.121-2.292 12.067-.746 15.436a17.023 17.023 0 0 0 6.876 7.7h19.991a17.237 17.237 0 0 0 6.902-7.7z" opacity="1" data-original="#d22e2e"></path>
                        <path fill="#7c6359" d="M304.28 171.493c-13.226-14.662-32.141-23-48.294-23-32.969 0-77.479 34.708-61.492 87.337C219.51 203.391 273.8 222.518 296.1 199c4.339 18.923 12.111 28.635 21.379 36.832 11.621-49.07 2.126-57.851-13.199-64.339z" opacity="1" data-original="#7c6359"></path>
                        <path fill="#f9ce95" d="M325.155 234.89a9.7 9.7 0 0 0-4.734-.287 37.63 37.63 0 0 1-2.943 1.226 66.748 66.748 0 0 1-11.425-12.692c.458 18.535-2.328 39.464-10.026 58.307-9.036 26.962-26.686 46.382-47.574 50.193a41.871 41.871 0 0 0 7.533.7c24.133 0 45.008-20.736 55.114-50.889 16.871-1.743 27.7-42.333 14.055-46.558z" opacity="1" data-original="#f9ce95"></path>
                        <path fill="#604c42" d="M317.478 235.829a37.63 37.63 0 0 0 2.943-1.226 7.774 7.774 0 0 0-2.943 1.226z" opacity="1" data-original="#604c42"></path>
                        <path fill="#604c42" d="M304.28 171.493c-13.226-14.662-32.141-23-48.294-23a51.545 51.545 0 0 0-7.6.585c14.261 2.1 29.59 9.965 40.821 22.416 8.994 3.808 16.195 25.31 16.846 51.644a66.748 66.748 0 0 0 11.425 12.692c11.622-49.068 2.127-57.849-13.198-64.337z" opacity="1" data-original="#604c42"></path>
                        <g fill="#3d3d54">
                            <path d="m280.959 19.335 8.534 40.1 46.441 19.238 7.7-5-34.375-14.24-8.534-40.1A8 8 0 0 0 292.9 13h-19.766a8 8 0 0 1 7.825 6.335zM471.522 190.137l-40.071-8.508-14.242-34.4 22.314-34.37a8 8 0 0 0-1.055-10.014l-50.455-50.427A8 8 0 0 0 378 51.366l-6.55 4.253 47.25 47.226a8 8 0 0 1 1.055 10.014l-22.313 34.37 14.241 34.4 40.071 8.508a8 8 0 0 1 6.339 7.825v71.347a8 8 0 0 1-6.338 7.825l-9.695 2.06a81.774 81.774 0 0 1-1.27 4.527l.021-.063 30.709-6.524a8 8 0 0 0 6.338-7.825v-71.347a8 8 0 0 0-6.336-7.825zM354.044 235.829a97.62 97.62 0 0 1-.077 3.387 47.621 47.621 0 0 1 15.061-18.591 114.046 114.046 0 0 0-226.057.007 47.581 47.581 0 0 1 15.063 18.637 98.196 98.196 0 0 1-.078-3.44 98.044 98.044 0 1 1 196.088 0zM162.072 263.89a85.385 85.385 0 0 1-6.133 26.58 114.905 114.905 0 0 0 39.8 42.136c2.139-.178 4.3-.273 6.49-.273h36.413a98.351 98.351 0 0 1-76.57-68.443zM349.907 263.961a98.352 98.352 0 0 1-76.544 68.372h36.384q3.294 0 6.515.274a114.912 114.912 0 0 0 39.789-42.117 85.272 85.272 0 0 1-6.144-26.529z" fill="#3d3d54" opacity="1" data-original="#3d3d54"></path>
                        </g>
                    </g>
                </svg>',

                'routes' => [
                'admin.user.index',
                'admin.user.create',
                'admin.user.edit',

                'admin.staff.index',
                'admin.staff.create',
                'admin.staff.edit',
                ],

                'subMenu' => [
                [
                'title' => 'Staff',
                'routes' => ['admin.staff.index', 'admin.staff.create', 'admin.staff.edit'],
                'route' => 'admin.staff.index',
                ],

                [
                'title' => 'User',
                'routes' => ['admin.user.index', 'admin.user.create', 'admin.user.edit'],
                'route' => 'admin.user.index',
                ],
                ],
                ],
                // =================== Management Section End =================

                // =================== Role & Permission Start ================
                // [
                // 'title' => 'Role & Permission',
                // 'icon' => 'icons/duotune/ecommerce/ecm002.svg',

                // 'routes' => [
                // 'all.role',
                // 'all.permission',
                // 'all.admin.permission',
                // 'add.roles.permission',
                // 'all.roles.permission',
                // ],

                // 'subMenu' => [
                // [
                // 'title' => 'All Admin',
                // 'routes' => ['all.admin.permission'],
                // 'route' => 'all.admin.permission',
                // ],
                // [
                // 'title' => 'Role',
                // 'routes' => ['all.role'],
                // 'route' => 'all.role',
                // ],
                // [
                // 'title' => 'Permission',
                // 'routes' => ['all.permission'],
                // 'route' => 'all.permission',
                // ],
                // ],
                // ],
                // ================== Role & Permission End ===================
                ];
                @endphp

                {{-- @if (Auth::guard('admin')->user()->can('brand.menu') || Auth::guard('admin')->user()->can('permission.menu') || Auth::guard('admin')->user()->can('role.menu') || Auth::guard('admin')->user()->can('admin.menu') || Auth::guard('admin')->user()->can('web_setting.menu')) --}}

                @foreach ($menuItems as $item)
                @if (empty($item['subMenu']))
                {{-- Single menu item --}}
                <div class="menu-item">
                    <a class="menu-link {{ Route::is(...$item['routes']) ? 'active' : '' }}"
                        href="{{ route($item['route']) }}">
                        <span class="menu-icon">
                            {!! $item['icon'] !!}
                        </span>
                        <span class="menu-title">{{ $item['title'] }}</span>
                    </a>
                </div>
                @else
                {{-- Menu item with submenus --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ Route::is(...$item['routes']) ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            {!! $item['icon'] !!}
                        </span>
                        <span class="menu-title">{{ $item['title'] }}</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div
                        class="menu-sub menu-sub-accordion {{ Route::is(...$item['routes']) ? 'menu-active-bg' : '' }}">
                        @foreach ($item['subMenu'] as $subItem)
                        @if (isset($subItem['subMenu']))
                        {{-- Handle 3rd level submenu if exists --}}
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ $subItem['title'] }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div
                                class="menu-sub menu-sub-accordion {{ Route::is(...array_column($subItem['subMenu'], 'route')) ? 'here show' : '' }}">
                                @foreach ($subItem['subMenu'] as $subSubItem)
                                <div class="menu-item">
                                    <a class="menu-link {{ Route::is($subSubItem['route']) ? 'active' : '' }}"
                                        href="{{ route($subSubItem['route']) }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">{{ $subSubItem['title'] }}</span>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        {{-- Normal submenu item --}}
                        <div class="menu-item">
                            <a class="menu-link {{ Route::is(...$subItem['routes']) ? 'active' : '' }}"
                                href="{{ route($subItem['route']) }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ $subItem['title'] }}</span>
                            </a>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach


                {{-- @endif --}}

            </div>
        </div>
    </div>

    <div class="px-5 pt-5 aside-footer flex-column-auto pb-7" id="kt_aside_footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            <a href="{{ route('admin.logout') }}" class="btn btn-custom btn-primary w-100"
                onclick="event.preventDefault();this.closest('form').submit();">
                <span class="btn-label">
                    @csrf
                    {{ __('Log Out') }}
                </span>
            </a>
        </form>
    </div>

</div>