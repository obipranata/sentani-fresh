@extends('layouts.templates_kurir')

@section('content_kurir')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Notifikasi</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Pesanan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    @if (empty($daftar_belanja))
                    <p><span class="bg-danger text-white">Belum ada notifikasi atau permintaan pengantaran pesanan</span></p>
                    @else                 
                    <h6>Ongkos Kirim <span class="text-success">Rp.{{number_format($total_ongkir)}}</span></h6>
                        <ul class="list-group">
                            @foreach ($daftar_belanja as $d)                                         
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span> {{$d->nama_produk}} <small class="text-primary">({{$d->nama_toko}})</small></span>
                                    <span> {{$d->alamat}} </span>
                                    <span class="badge bg-warning badge-pill badge-round ml-1">{{$d->jumlah}}</span>
                                </li>
                            @endforeach
                        </ul>

                        <p class="text-danger">pengantaran sesuai dengan alamat dibawah ini</p>
                        <p>Nama: {{$user->nama}}</p>
                        <p>Alamat: {{$pembeli->alamat}}, {{$pembeli->no_hp}}</p>
                        @if ($notif->status == 0)
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="/updatenotif/{{$notif->kd_notif}}" method="POST" class="mt-4">
                                        @csrf
                                        <button class="btn btn-success rounded-pill" type="submit">Konfirmasi</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form action="/kirimnotifbaru/{{$pembeli->username}}/{{Auth::user()->username}}" method="POST" class="mt-4">
                                        @csrf
                                        <button class="btn btn-danger rounded-pill" type="submit">Batal</button>
                                    </form>
                                </div>
                            </div>                        
                        @else
                            <form action="/kirimpesan/{{$pembeli->username}}" method="POST" class="mt-4">
                                @csrf
                                <button class="btn btn-danger rounded-pill" type="submit">Kirim notif ke penjual</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </section>
</div>
@endsection




