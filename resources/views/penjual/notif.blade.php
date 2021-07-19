@extends('layouts.templates_penjual')

@section('content_penjual')
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
                <h4 class="card-title text-success">Daftar Pesanan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    @if (empty($daftar_belanja))
                    <p><span class="bg-danger text-white">Belum ada notifikasi atau permintaan pengantaran pesanan</span></p>
                    @else 
                            @foreach ($daftar_belanja as $d)         
                                @foreach ($notif as $n)
                                    @if ($n->status == 1)
                                        @if ($n->pembeli == $d->username)
                                        <div class="col-lg-4 mb-2">
                                            <div class="card" style="background-color: #F2F7FF">
                                                <img src="/foto_produk/{{$d->foto}}" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{$d->nama_produk}}</h5>
                                                    <p class="card-text">
                                                        Total : Rp. {{number_format($d->harga * $d->jumlah)}}
                                                    </p>
                                                    <p class="card-text">
                                                        Kurir : {{$n->kurir}}
                                                    </p>
                                                    <p class="card-text">
                                                        Jumlah : {{$d->jumlah}}
                                                    </p>
                                                    <form action="/updatenotifpenjual/{{$n->kd_notif}}" method="POST" class="mt-4">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm rounded-pill" type="submit">Konfirmasi</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                @endforeach                                
                            @endforeach
                    @endif
                </div>
            </div>
        </div>

    </section>
</div>
@endsection




