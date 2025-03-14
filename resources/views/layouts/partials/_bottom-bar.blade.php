<div class="bottom-bar fixed-bottom bg-white shadow-lg d-flex d-sm-none justify-content-around py-2 border-top">
    <a href="{{ route('home') }}" wire:navigate
       class="bottom-bar-item text-muted text-lg text-decoration-none d-flex flex-column align-items-center
       {{ request()->routeIs('home') ? 'active' : '' }}"
    >
        <x-lucide-house class="w-4 h-4"/>
        <span class="text-xs">Home</span>
    </a>

    <a href="{{ route('categories.index') }}" wire:navigate
       class="bottom-bar-item text-muted text-lg text-decoration-none d-flex flex-column align-items-center
       {{ request()->routeIs('categories.index') ? 'active' : '' }}">
        <x-lucide-layout-grid class="w-4 h-4"/>
        <span class="text-xs">Categories</span>
    </a>

    {{--    <!-- Floating Action Button (FAB) -->--}}
    {{--    <div class="fab-container">--}}
    {{--        <button class="btn btn-dark rounded-circle fab-button shadow">--}}
    {{--            <x-lucide-plus class="w-5 h-5 text-white" />--}}
    {{--        </button>--}}
    {{--    </div>--}}

    <a href="{{ route('products.index') }}" wire:navigate
       class="bottom-bar-item text-muted text-lg text-decoration-none d-flex flex-column align-items-center
       {{ request()->routeIs('products.index') ? 'active' : '' }}">
        <x-lucide-package-search class="w-4 h-4"/>
        <span class="text-xs">Products</span>
    </a>
    <a href="{{ route('settings.profile') }}" wire:navigate
       class="bottom-bar-item text-muted text-lg text-decoration-none d-flex flex-column align-items-center
       {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
        <x-lucide-user class="w-4 h-4"/>
        <span class="text-xs">Profile</span>
    </a>
</div>
