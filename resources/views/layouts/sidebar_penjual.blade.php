
<div class="sidebar-menu">
    <ul class="menu">
        @php
            $username =  Auth::user()->username;
            $saldo = DB::select("SELECT * from saldo WHERE username = '$username' "); 
            $topup = \App\Models\Topup::where(['username' => $username, 'payment_status' => 'pending'])->first();
        @endphp
        <h6>SF Saldo (<span class="text-primary">{{number_format($saldo[0]->jumlah)}}</span>)</h6> <span></span>
        <li class="sidebar-title">Menu</li>

        <li class="sidebar-item {{Request::segment(1) == 'home' ? 'active' : ''}} ">
            <a href="/home" class='sidebar-link'>
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::segment(1) == 'notifpenjual' ? 'active' : '' }}">
            <a href="/notifpenjual" class='sidebar-link'>
                <i class="bi bi-list"></i>
                <span>Notifikasi</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::segment(1) == 'produk' ? 'active' : '' }}">
            <a href="/produk" class='sidebar-link'>
                <i class="bi bi-briefcase"></i>
                <span>Produk</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::segment(1) == 'riwayatpenjualan' ? 'active' : '' }}">
            <a href="/riwayatpenjualan" class='sidebar-link'>
                <i class="bi bi-clock"></i>
                <span>Riwayat Penjualan</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::segment(1) == 'pendapatan' ? 'active' : '' }}">
            <a href="/pendapatan" class='sidebar-link'>
                <i class="bi bi-bag-check"></i>
                <span>Pendapatan</span>
            </a>
        </li>

        @if (!empty($topup))           
            {{-- <li class="sidebar-item">
                <a href="#" class='sidebar-link' data-toggle="modal" data-target="#transaksiModal">
                    <i class="ion-ios-card"></i>
                    <span>Selesaikan transaksi</span>
                </a>
            </li> --}}
        @else
            {{-- <li class="sidebar-item">
                <a href="#" class='sidebar-link' data-toggle="modal" data-target="#topupModal">
                    <i class="ion-ios-card"></i>
                    <span>Topup Saldo</span>
                </a>
            </li> --}}
        @endif
        
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
