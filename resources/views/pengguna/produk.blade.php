@extends('layouts.templates')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('/assets/images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Produk</span></p>
                <h1 class="mb-0 bread">Pilih Produk</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3 text-center">
                <ul class="product-category">
                    <li>
                        <a href="/produks" >
                            Semua
                            <form action="/produks" method="post">
                                @csrf
                            </form>
                        </a>
                    </li>
                    @foreach ($kategori as $kt)
                        <li>
                            <a href="/produk/{{$kt->kd_kategori}}" onclick="event.preventDefault(); document.getElementById('pilih-kategori-{{$kt->kd_kategori}}').submit();">
                                {{$kt->nama_kategori}}
                                <form action="/produk/{{$kt->kd_kategori}}" method="post" id="pilih-kategori-{{$kt->kd_kategori}}">
                                    @csrf
                                </form>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <form action="/produks" class="search-form" method="POST">
                    @csrf
                    <div class="form-group">
                        <span class="icon ion-ios-search"></span>
                        <input type="text" class="form-control" placeholder="cari produk..." name="cari">
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            @foreach ($allproduk as $p)             
                <div class="col-md-6 col-lg-3 ftco-animate">
                    <div class="product">
                        <a href="/single/{{$p->kd_produk}}" class="img-prod"><img class="img-fluid" src="/foto_produk/{{$p->foto}}"
                                alt="Colorlib Template">
                            {{-- <span class="status">30%</span> --}}
                            <div class="overlay"></div>
                        </a>
                        <div class="text py-3 pb-4 px-3 text-center">
                            <h3><a href="/single/{{$p->kd_produk}}">{{$p->nama_produk}}</a></h3>
                            <div class="d-flex">
                                <div class="pricing">
                                    <p class="price">
                                        <span class="price-sale">Rp{{number_format($p->harga)}}</span>
                                        <p class="text-success">Per {{$p->satuan}}</p>
                                        <p><span class="icon-home"></span> {{$p->nama_toko}}</p>
                                    </p>
                                </div>
                            </div>
                            <div class="bottom-area d-flex px-3">
                                <div class="m-auto d-flex">
                                    <a href="/single/{{$p->kd_produk}}"
                                        class="add-to-cart d-flex justify-content-center align-items-center text-center">
                                        <span><i class="ion-ios-menu"></i></span>
                                    </a>
                                    <a href="/tambahkeranjang/{{$p->kd_produk}}" class="buy-now d-flex justify-content-center align-items-center mx-1" onclick="event.preventDefault(); document.getElementById('produk-{{$p->kd_produk}}').submit();">
                                        <form action="/tambahkeranjang/{{$p->kd_produk}}" method="post" id="produk-{{$p->kd_produk}}">
                                            @csrf
                                        </form>
                                        <span><i class="ion-ios-cart"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection