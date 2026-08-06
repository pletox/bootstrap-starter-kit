@php
    $user = auth()->user();
    $pageTitle = request()->routeIs('home')
        ? 'Dashboard'
        : trim($__env->yieldContent('title', 'Dashboard'));
@endphp

<nav class="navbar navbar-expand px-3 border-bottom sticky-top main-navbar app-navbar">
    <div class="app-navbar-row d-flex align-items-center w-100">
        <button class="app-topbar-icon app-topbar-menu" id="sidebar-toggle" type="button" aria-label="Toggle sidebar">
            <x-lucide-panel-left class="w-4 h-4"/>
        </button>

        <div class="ms-3 min-w-0">
            <p class="mb-0 fw-semibold app-topbar-title">{{ $pageTitle }}</p>
            <p class="mb-0 text-muted text-xs d-none d-sm-block">{{ config('app.name') }}</p>
        </div>

        <div class="dropdown app-topbar-dropdown ms-auto">
            <button type="button" class="app-profile-button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="menu">
                    <span class="app-profile-avatar">{{ $user?->initials ?? 'GU' }}</span>
                    <span class="app-profile-meta">
                        <span class="app-profile-name">{{ $user?->name ?? 'Guest User' }}</span>
                        <span class="app-profile-role">Team member</span>
                    </span>
                    <x-lucide-chevron-down class="app-profile-chevron"/>
                </button>

            <div class="dropdown-menu dropdown-menu-end app-profile-panel" role="menu">
                <div class="app-profile-panel-head">
                    <span class="app-profile-avatar app-profile-avatar-lg">{{ $user?->initials ?? 'GU' }}</span>
                    <div class="app-profile-panel-id">
                        <div class="app-profile-panel-name">{{ $user?->name ?? 'Guest User' }}</div>
                        <div class="app-profile-panel-email">{{ $user?->email ?? '—' }}</div>
                    </div>
                </div>

                <a href="{{ route('settings.profile') }}" wire:navigate class="app-profile-item" role="menuitem">
                    <x-lucide-user class="w-4 h-4"/>
                    <span>Profile</span>
                </a>

                <div class="app-profile-separator" aria-hidden="true"></div>

                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="app-profile-item app-profile-item-danger" role="menuitem">
                        <x-lucide-log-out class="w-4 h-4"/>
                        <span>Sign out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
