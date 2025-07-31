<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon"
        href="{{ !empty($setting->site_favicon) && file_exists(public_path('storage/' . $setting->site_favicon)) ? asset('storage/' . $setting->site_favicon) : asset('images/no_icon.png') }}"
        type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('user/assets/css/bootstrap_icons.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('user/assets/css/font_awesome_6.css') }}"> --}}

    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.2.0/css/all.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

    <!-- Tagify CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
    <!-- Tagify JS -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>



    <link href="{{ asset('user/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('user/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />

    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />


    <link href="{{ asset('user/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    @props(['title'])
    <title>{{ $title ?? config('app.name', 'NewSite') }}</title>
</head>

<body id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed">


    <div class="d-flex flex-column flex-root">

        <div class="page d-flex flex-row flex-column-fluid">

            @include('user.layouts.sidebar')


            <div class="wrapper d-flex flex-column flex-row-fluid pt-lg-17" id="kt_wrapper">

                @include('user.layouts.header')


                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                    {{-- @include('user.layouts.toolbar') --}}

                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div id="kt_content_container" class="container-xxl">
                            @if (session('error'))
                                @foreach ($messages as $item)
                                    <div class="alert alert-danger">
                                        {{ $item }}
                                    </div>
                                @endforeach
                            @endif
                            {{ $slot }}
                        </div>
                    </div>
                </div>


                @include('user.layouts.footer')

            </div>

        </div>

    </div>


    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">

        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1"
                    transform="rotate(90 13 6)" fill="currentColor" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="currentColor" />
            </svg>
        </span>

    </div>



    <script src="{{ asset('user/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('user/assets/js/scripts.bundle.js') }}"></script>


    <script src="{{ asset('user/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('user/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script> --}}
    <script src="{{ asset('user/assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>



    <script src="{{ asset('user/assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('user/assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('user/assets/js/custom/apps/chat/chat.js') }}"></script>
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script> --}}
    <script src="{{ asset('user/assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script src="{{ asset('user/js/custom.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var input = document.querySelector("#tags");
            new Tagify(input);
        });
    </script>

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select the input element with the class 'tags'
            var input = document.querySelector(".tags");

            // Initialize Tagify
            new Tagify(input);
        });
    </script> --}}

    @include('toastr')

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script>
        document.querySelectorAll('.ckeditor').forEach(element => {
            if (!element.classList.contains('ck-editor__editable_inline')) {
                ClassicEditor
                    .create(element)
                    .then(editor => {
                        console.log('CKEditor initialized:', editor);
                        element.editorInstance = editor;
                    })
                    .catch(error => {
                        console.error('CKEditor initialization error:', error);
                    });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#kt_datatable_example").DataTable({
                "language": {
                    "lengthMenu": "Show _MENU_",
                },
                "dom": "<'row'" +
                    "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                    ">" +

                    "<'table-responsive'tr>" +

                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">"
            });
        });
    </script>



</body>

</html>
