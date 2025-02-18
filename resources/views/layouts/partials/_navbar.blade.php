<nav class="navbar navbar-expand py-1 px-3 shadow-sm border-bottom">
    <button class="btn" id="sidebar-toggle" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse navbar">
        <ul class="navbar-nav">
            <li class="nav-item me-2 theme-toggle">
                <a class="nav-link" href="#"><i class="bi bi-moon"></i> <i class="bi bi-sun"></i></a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                    <img src="https://ui-avatars.com/api/?background=FFCCA6&color=000&name={{urlencode(auth()->user()->name)}}" class="avatar img-fluid rounded" alt="">
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="#" class="dropdown-item"><i class="bi bi-person"></i> Profile</a>
                    <a href="#" class="dropdown-item"><i class="bi bi-gear"></i> Settings</a>
                    <a href="#" onclick="event.preventDefault();$('#logout-form').submit();" class="dropdown-item"><i class="bi bi-box-arrow-left"></i>  Logout</a>

                    <form method="POST" id="logout-form" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
