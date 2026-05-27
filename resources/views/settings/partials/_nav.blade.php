<nav class="settings-tabs nav flex-column mb-4" aria-label="Settings navigation">
    <div class="settings-tabs-inner h-100">
        <ul class="settings-tabs-list sidebar-nav">
            <li class="sidebar-item">
                <a href="{{ route('settings.profile') }}" class="sidebar-link text-gray-600 font-bold {{ request()->routeIs('settings.profile') ? 'active' : '' }}"
                   wire:navigate>
                    Profile
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('settings.profile.password-update') }}" class="sidebar-link text-gray-600 font-bold {{ request()->routeIs('settings.profile.password-update') ? 'active' : '' }}"
                   wire:navigate>
                    Password
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('settings.profile.appearance') }}" class="sidebar-link text-gray-600 font-bold {{ request()->routeIs('settings.profile.appearance') ? 'active' : '' }}"
                   wire:navigate>
                    Appearance
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('settings.profile.permissions') }}" class="sidebar-link text-gray-600 font-bold {{ request()->routeIs('settings.profile.permissions') ? 'active' : '' }}"
                   wire:navigate>
                    Permissions
                </a>
            </li>
        </ul>
    </div>
</nav>
