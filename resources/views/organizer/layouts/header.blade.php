<div class="d-flex justify-content-between align-items-center bg-white px-4 py-3 shadow-sm rounded-bottom">

    <div class="d-flex align-items-center">
        <button id="sidebarToggle"
            class="btn btn-primary d-flex align-items-center justify-content-center shadow-sm me-3 rounded-3"
            style="width: 40px; height: 40px; background-color: #1e3a8a; border-color: #1e3a8a;">
            <i class="bi bi-list fs-5 text-white"></i>
        </button>

        <div>
            <h1 class="h5 fw-bold mb-0 text-dark" style="letter-spacing: -0.02rem;">
                {{ $title ?? 'Organizer Dashboard' }}
            </h1>
            <div class="d-flex align-items-center mt-1">
                <span class="badge bg-soft-primary text-primary me-2"
                    style="font-size: 0.7rem; background-color: #e7f1ff;">OFFICIAL</span>
                <small class="text-muted fw-medium">
                    {{ optional(auth()->user()->organizerProfile)->organization_name }}
                </small>
            </div>
        </div>
    </div>

    <div class="dropdown">
        <button class="btn btn-light border-0 d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm-hover"
            type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center"
                style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: 600; background-color: #1e3a8a;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <span class="fw-semibold text-dark d-none d-md-inline-block" style="font-size: 0.9rem;">
                {{ auth()->user()->name }}
            </span>
            <i class="bi bi-chevron-down small text-muted"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 p-2" aria-labelledby="userMenu"
            style="min-width: 200px;">
            <li>
                <h6 class="dropdown-header small text-uppercase tracking-wider">Account Settings</h6>
            </li>
            <li>
                <a class="dropdown-item rounded-2 py-2" href="{{ route('organizer.profile') }}">
                    <i class="bi bi-person me-2"></i> My Profile
                </a>
            </li>
            <li>
                <a class="dropdown-item rounded-2 py-2" href="{{ route('homepage') }}">
                    <i class="bi bi-house me-2"></i> Back to Homepage
                </a>
            </li>
            <li>
                <hr class="dropdown-divider opacity-50">
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item rounded-2 py-2 text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
