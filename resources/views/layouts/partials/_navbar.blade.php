<nav class="navbar navbar-expand py-1 px-3 border-bottom">
    <button class="btn p-2 py-1 border-0" id="sidebar-toggle" type="button">
        <x-lucide-panel-left class="w-4 h-4 text-slate-600"/>
    </button>
    <div class="navbar-collapse navbar py-1">
        <p class="mb-0 ms-2">Dashboard</p>
        <ul class="navbar-nav">

            <li class="nav-item dropdown">
                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                    <x-avatar color="light" :letters="auth()->user()->initials"
                              size="sm" shape="semi"/>
                </a>
                <div class="dropdown-menu dropdown-menu-end border shadow-sm py-0">
                    <div class="d-flex align-items-center gap-2 border-bottom p-2">
                        <x-avatar color="light" :letters="auth()->user()->initials"
                                  size="sm" shape="semi"/>
                        <div>
                            <p class="mb-0 text-truncate text-sm"
                               style="font-weight: 500;">{{ auth()->user()->name }}</p>
                            <p class="mb-1 text-muted text-xs text-truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="m-1">
                        <a href="{{ route('settings.profile') }}" wire:navigate
                           class="rounded dropdown-item p-1 text-sm">
                            <x-lucide-settings class="w-4 h-4 text-slate-600"/>
                            <span class="ms-3"> Settings</span>
                        </a>
                    </div>
                    <hr class="m-0" style="color: lightgray;">
                    <div class="m-1">
                        <a href="#" onclick="event.preventDefault();$('#logout-form').submit();"
                           class="rounded dropdown-item p-1 text-sm">
                            <x-lucide-log-out class="w-4 h-4 text-slate-600"/>
                            <span class="ms-3"> Logout </span>
                        </a>
                    </div>
                    <form method="POST" id="logout-form" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
