
<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Menu</li>

        <li class="sidebar-item {{Request::segment(1) == 'home' ? 'active' : ''}} ">
            <a href="/home" class='sidebar-link'>
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::segment(1) == 'kategori' ? 'active' : '' }}">
            <a href="/kategori" class='sidebar-link'>
                <i class="bi bi-list"></i>
                <span>Kategori</span>
            </a>
        </li>
        
        <li class="sidebar-title">Keluar</li>
        <li class="sidebar-item">
            <a href="{{ route('logout') }}" class='sidebar-link' onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                <i class="bi bi-lock"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
