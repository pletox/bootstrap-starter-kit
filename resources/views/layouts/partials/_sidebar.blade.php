<aside id="sidebar" class="js-sidebar ">
    <!-- Content For Sidebar -->
    <div class="h-100">
        <div class="sidebar-logo ms-2">
            <a href="#">Bootstrap Starter Kit</a>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="{{ route('home') }}" class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}"
                   wire:navigate>
                    <i class="bi bi-house pe-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('customers.index') }}" wire:navigate
                   class="sidebar-link {{ request()->routeIs('customers.index') ? 'active' : '' }}">
                    <i class="bi bi-people pe-2"></i>
                    Customers
                </a>
            </li>

            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                   aria-expanded="false"><i class="bi bi-receipt pe-2"></i>
                   Sales
                </a>
                <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="#" wire:navigate class="sidebar-link">All Sales</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link" wire:navigate>Add Sale</a>
                    </li>

                </ul>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('categories.index') }}" wire:navigate
                   class="sidebar-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                    <i class="bi bi-grid pe-2"></i>
                    Categories
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('products.index') }}" wire:navigate
                   class="sidebar-link {{ request()->routeIs('products.index') ? 'active' : '' }}">
                    <i class="bi bi-file-text pe-2"></i>
                    Products
                </a>
            </li>





        </ul>
    </div>
</aside>
