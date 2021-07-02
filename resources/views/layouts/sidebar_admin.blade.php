
<div class="sidebar-menu">
    <ul class="menu">

        @php
            $pendapatan = DB::select("SELECT SUM(total)*0.02 as total_pendapatan FROM `pembelian` GROUP BY no_nota");
            foreach ($pendapatan as $p) {
                $total[] = $p->total_pendapatan;
            }
        @endphp

        <h6>Pendapatan (<span class="text-primary">{{number_format(array_sum($total))}}</span>)</h6>
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
        
        <li class="sidebar-title">Laporan</li>
        <li class="sidebar-item {{ Request::segment(2) == 'pembeli' ? 'active' : '' }}">
            <a href="/admin/pembeli" class='sidebar-link'>
                <i class="bi bi-person"></i>
                <span>Pembeli</span>
            </a>
        </li>
        <li class="sidebar-item {{ Request::segment(2) == 'kurir' ? 'active' : '' }}">
            <a href="/admin/kurir" class='sidebar-link'>
                <i class="bi bi-bicycle"></i>
                <span>Kurir</span>
            </a>
        </li>
        <li class="sidebar-item {{ Request::segment(2) == 'penjual' ? 'active' : '' }}">
            <a href="/admin/penjual" class='sidebar-link'>
                <i class="bi bi-basket"></i>
                <span>Penjual</span>
            </a>
        </li>
        <li class="sidebar-item {{ Request::segment(2) == 'penjualan' ? 'active' : '' }}">
            <a href="/admin/penjualan" class='sidebar-link'>
                <i class="bi bi-bag-check"></i>
                <span>Riwayat Penjualan</span>
            </a>
        </li>
        <li class="sidebar-item {{ Request::segment(2) == 'pendapatan' ? 'active' : '' }}">
            <a href="/admin/pendapatan" class='sidebar-link'>
                <i class="bi bi-bag-check"></i>
                <span>Pendapatan pajak 2%</span>
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
