<aside id="sidebar" class="js-sidebar ">
    <!-- Content For Sidebar -->
    <div class="h-100">
        <div class="sidebar-logo text-truncate p-2 my-2 mx-3 ms-3">
            <a href="#" class="text-sm d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center overflow-hidden rounded w-8 h-8 bg-black">
                    <x-lucide-building class="w-5 h-5 text-white"/>
                </div>
                <span class="text-truncate">Bootstrap Starter Kit</span>
            </a>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-nav-heading mx-4 mb-2 text-xs text-gray-500">Platform</li>

            <li class="sidebar-item">
                <a href="{{ route('home') }}" wire:navigate
                   class="sidebar-link text-gray-600 font-bold {{ request()->routeIs('home') ? 'active' : '' }}">
                    <x-lucide-house class="w-4 h-4 text-slate-600"/>
                    <span class="ps-2">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('categories.index') }}" wire:navigate
                   class="sidebar-link text-gray-600 font-bold {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                    <x-lucide-layout-grid class="w-4 h-4 text-slate-600"/>
                    <span class="ps-2">Categories</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('products.index') }}" wire:navigate
                   class="sidebar-link text-gray-600 font-bold {{ request()->routeIs('products.index') ? 'active' : '' }}">
                    <x-lucide-package-search class="w-4 h-4 text-slate-600"/>
                    <span class="ps-2">Products</span>
                </a>
            </li>


        </ul>
    </div>
</aside>

<!-- Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>


<!-- Sidebar Dropdown Item Snippet -->
{{--            <li class="sidebar-item">--}}
{{--                <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"--}}
{{--                   aria-expanded="false"><i class="bi bi-receipt pe-2"></i>--}}
{{--                   Sales--}}
{{--                </a>--}}
{{--                <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">--}}
{{--                    <li class="sidebar-item">--}}
{{--                        <a href="#" wire:navigate class="sidebar-link">All Sales</a>--}}
{{--                    </li>--}}
{{--                    <li class="sidebar-item">--}}
{{--                        <a href="#" class="sidebar-link" wire:navigate>Add Sale</a>--}}
{{--                    </li>--}}

{{--                </ul>--}}
{{--            </li>--}}
