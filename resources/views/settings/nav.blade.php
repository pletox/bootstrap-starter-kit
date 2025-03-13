<nav class="nav flex-column mb-4">
    <div class="h-100">
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="{{ route('settings.profile') }}" class="sidebar-link {{ request()->routeIs('settings.profile') ? 'active' : '' }}"
                   wire:navigate>
                    Profile
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('settings.profile.password-update') }}" class="sidebar-link {{ request()->routeIs('settings.profile.password-update') ? 'active' : '' }}"
                   wire:navigate>
                    Password
                </a>
            </li>
        </ul>
    </div>
</nav>

