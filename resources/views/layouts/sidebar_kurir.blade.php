
<div class="sidebar-menu">
    <ul class="menu">
        @php
            $username =  Auth::user()->username;
            $saldo = DB::select("SELECT * from saldo WHERE username = '$username' "); 
        @endphp
        <h6>SF Saldo (<span class="text-primary">{{number_format($saldo[0]->jumlah)}}</span>)</h6>
        <li class="sidebar-title">Menu</li>

        <li class="sidebar-item {{Request::segment(1) == 'home' ? 'active' : ''}} ">
            <a href="/home" class='sidebar-link'>
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::segment(1) == 'notif' ? 'active' : '' }}">
            <a href="/notif" class='sidebar-link'>
                <i class="bi bi-list"></i>
                <span>Notifikasi</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::segment(2) == 'riwayatpenjualan' ? 'active' : '' }}">
            <a href="/kurir/riwayatpenjualan" class='sidebar-link'>
                <i class="bi bi-clock"></i>
                <span>Riwayat Pengantaran</span>
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
