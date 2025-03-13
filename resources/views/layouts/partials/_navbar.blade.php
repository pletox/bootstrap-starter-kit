<nav class="navbar navbar-expand py-1 px-3 shadow-sm border-bottom">
    <button class="btn" id="sidebar-toggle" type="button">
        <x-lucide-panel-left class="w-4 h-4 text-slate-600" />
    </button>
    <div class="navbar-collapse navbar">
        <ul class="navbar-nav">
            <li class="nav-item me-2 theme-toggle">
                <a class="nav-link" href="#"><i class="bi bi-moon"></i> <i class="bi bi-sun"></i></a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                    <x-user-avatar class="w-8 h-8 bg-slate-400" text-size="text-xs" shape="rounded" :user="auth()->user()" />
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="#" class="dropdown-item"><i class="bi bi-person"></i> Profile</a>
                    <a href="#" class="dropdown-item"><i class="bi bi-gear"></i> Settings</a>
                    <a href="#" onclick="event.preventDefault();$('#logout-form').submit();" class="dropdown-item"><i
                            class="bi bi-box-arrow-left"></i> Logout</a>

                    <form method="POST" id="logout-form" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
