<nav class="bottom-bar d-flex d-sm-none" aria-label="Mobile primary navigation">
    <a href="{{ route('home') }}" wire:navigate
       class="bottom-bar-item text-muted text-decoration-none
       {{ request()->routeIs('home') ? 'active' : '' }}"
    >
        <span class="bottom-bar-icon">
            <x-lucide-house class="w-4 h-4"/>
        </span>
        <span class="text-xs">Home</span>
    </a>

    <a href="{{ route('categories.index') }}" wire:navigate
       class="bottom-bar-item text-muted text-decoration-none
       {{ request()->routeIs('categories.index') ? 'active' : '' }}">
        <span class="bottom-bar-icon">
            <x-lucide-layout-grid class="w-4 h-4"/>
        </span>
        <span class="text-xs">Categories</span>
    </a>

    <a href="{{ route('install-app') }}"
       class="bottom-bar-item text-muted text-decoration-none
       {{ request()->routeIs('install-app') ? 'active' : '' }}">
        <span class="bottom-bar-icon">
            <x-lucide-smartphone class="w-4 h-4"/>
        </span>
        <span class="text-xs">Install</span>
    </a>

    <a href="{{ route('settings.profile') }}" wire:navigate
       class="bottom-bar-item text-muted text-decoration-none
       {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
        <span class="bottom-bar-icon">
            <x-lucide-user class="w-4 h-4"/>
        </span>
        <span class="text-xs">Profile</span>
    </a>
</nav>
