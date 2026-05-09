<!DOCTYPE html>
<html lang="en" class="h-100">
  <head>
    @include('frontend.layouts.head')
  </head>

  <body class="d-flex flex-column h-100">
    <a class="skip-to-content" href="#main-content">Skip to main content</a>
    <!-- Header Start-->
    @include('frontend.layouts.header')
    <!-- Header End-->
    <!-- Body Start-->
    <main class="wrapper" id="main-content" tabindex="-1">
      {{ $slot }}
    </main>
    <!-- Body End-->
    <!-- Footer Start-->
    @include('frontend.layouts.footer')
    <!-- Footer End-->

    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/OwlCarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('frontend/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/mixitup/dist/mixitup.min.js') }}"></script>
    <script src="{{ asset('frontend/js/custom.js') }}"></script>
    <script src="{{ asset('frontend/js/night-mode.js') }}"></script>
    @stack('scripts')
    <script>
      var containerEl = document.querySelector('[data-ref~="event-filter-content"]');
      if (containerEl && typeof mixitup === 'function') {
        mixitup(containerEl, {
          selectors: {
            target: '[data-ref~="mixitup-target"]',
          },
        });
      }
    </script>
  </body>
</html>
