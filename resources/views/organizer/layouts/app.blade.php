<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Organizer Panel' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.bundle.css') }}">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 76px;
            --active-bg: #1e3a8a;
            /* Dark Blue */
        }

        body {
            background: #f3f4f6;
            margin: 0;
            padding: 0;
            /* Prevent horizontal scroll on the entire body */
            overflow-x: hidden;
        }

        /* --- 1. Bulletproof Fixed Sidebar --- */
        .organizer-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            transition: width 0.3s ease;
            display: flex;
            flex-direction: column;
            z-index: 1040;
            /* Ensures sidebar stays above everything */
        }

        .organizer-sidebar-inner {
            padding: 24px 16px;
            overflow-y: auto;
            /* Allows scrolling inside the sidebar */
            overflow-x: hidden;
            flex: 1;
        }

        /* Custom scrollbar for sidebar */
        .organizer-sidebar-inner::-webkit-scrollbar {
            width: 5px;
        }

        .organizer-sidebar-inner::-webkit-scrollbar-track {
            background: transparent;
        }

        .organizer-sidebar-inner::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .organizer-sidebar-header {
            padding: 15px 24px;
            /* margin-bottom: 12px; */
            border-bottom: 1px solid #f3f4f6;
        }

        .sidebar-group-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            margin: 20px 0 8px 12px;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .organizer-sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #4b5563;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 4px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .organizer-sidebar a i {
            font-size: 1.25rem;
            min-width: 24px;
            text-align: center;
        }

        .organizer-sidebar-counter {
            background: #ef4444;
            color: #fff;
            border-radius: 999px;
            font-size: 12px;
            line-height: 1;
            padding: 4px 8px;
            min-width: 22px;
            text-align: center;
            margin-left: auto;
            font-weight: 600;
        }

        .organizer-sidebar a:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .organizer-sidebar a.active {
            background: var(--active-bg);
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(30, 58, 138, 0.2);
        }

        /* --- 2. Main Content Setup --- */
        .organizer-main {
            /* Pushes the content to the right of the fixed sidebar */
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: margin-left 0.3s ease, width 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f3f4f6;
        }

        .organizer-topbar {
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .organizer-content {
            padding: 26px;
            flex: 1;
            /* Prevents wide tables from stretching the page */
            max-width: 100%;
        }

        /* --- 3. Collapsed State Logic --- */
        .sidebar-collapsed .organizer-sidebar {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-collapsed .organizer-main {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .sidebar-collapsed .organizer-sidebar-inner {
            padding: 24px 8px;
        }

        .sidebar-collapsed .sidebar-group-label,
        .sidebar-collapsed .organizer-sidebar a span,
        .sidebar-collapsed .organizer-sidebar-counter,
        .sidebar-collapsed .sidebar-brand-text {
            display: none;
        }

        .sidebar-collapsed .organizer-sidebar a {
            justify-content: center;
            padding: 12px 0;
        }

        .sidebar-collapsed .organizer-sidebar a i {
            margin: 0;
        }

        /* --- Utilities --- */
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 18px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            border: none;
            /* Ensures cards don't blow past their container */
            max-width: 100%;
            overflow-x: auto;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div id="appShell">
        @include('organizer.layouts.sidebar')

        <main class="organizer-main">
            <div class="organizer-topbar border-bottom">
                @include('organizer.layouts.header')
            </div>

            <div class="organizer-content">
                @if (session('success'))
                    <div class="card" style="border-left:4px solid #16a34a">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="card" style="border-left:4px solid #dc2626">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const shell = document.getElementById('appShell');

            // Load saved state from localStorage
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                shell.classList.add('sidebar-collapsed');
            }

            // Toggle functionality
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    shell.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebar-collapsed', shell.classList.contains(
                    'sidebar-collapsed'));
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
