@extends('layouts.templates_penjual')

@section('content_penjual')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Produk</h3>
                <p class="text-subtitle text-muted">beberapa produk penjualan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Produk</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-danger">
                        Bahan makanan yang dijual pada aplikasi ini adalah bahan makanan yang telah dibersihkan dan siap untuk dimasak saat sampai pada tangan pembeli.
                    </li>
                    <li class="list-group-item list-group-item-warning">
                        setiap pembelian dari hasil penjualan akan dipotong 2% oleh sistem.
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section mt-2">
        <div class="card">
            <div class="card-header">
                <a href="/produk/create" class="btn btn-primary tombol-tambah" >Produk +</a>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($allproduk as $p)                      
                        <div class="col-lg-3">
                            <a href="/produk/{{$p->kd_produk}}" >
                                <div class="card">
                                    <div class="card-content">
                                        <img class="card-img-top img-fluid" src="foto_produk/{{$p->foto}}"
                                            alt="Card image cap" />
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                Rp{{number_format($p->harga)}}
                                                <a href="#" data-id="{{$p->kd_produk}}" data-nama="{{$p->nama_produk}}" class="btn btn-sm btn-danger hapus">
                                                    <form action="/produk/{{$p->kd_produk}}" id="delete{{$p->kd_produk}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <i class="fas fa-window-close"></i>
                                                </a>
                                            </h4>
                                            <p class="card-text">
                                                {{$p->nama_produk}}.
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </section>
</div>
@endsection




