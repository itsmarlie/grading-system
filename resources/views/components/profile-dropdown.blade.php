<div class="dropdown">
    <button class="btn btn-link d-flex align-items-center gap-2 text-decoration-none text-dark" data-bs-toggle="dropdown">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
             style="width:36px;height:36px;font-size:.85rem">
            {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}
        </div>
        <div class="d-none d-md-block text-start lh-sm">
            <div class="fw-semibold small">{{ auth()->user()->display_name }}</div>
            <div class="text-muted" style="font-size:.7rem">{{ ucfirst(auth()->user()->role) }}</div>
        </div>
        <i class="fas fa-chevron-down fa-xs text-muted ms-1"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow">
        <li class="dropdown-header">
            <div class="fw-bold">{{ auth()->user()->display_name }}</div>
            <div class="text-muted small">{{ auth()->user()->email }}</div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2 fa-sm"></i> My Profile</a></li>
        @if(auth()->user()->isAdmin())
        <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="fas fa-cog me-2 fa-sm"></i> Settings</a></li>
        @endif
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2 fa-sm"></i> Logout</button>
            </form>
        </li>
    </ul>
</div>