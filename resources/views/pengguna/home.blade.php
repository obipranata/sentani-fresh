@extends('layouts.templates')

@section('content')
<section id="home-section" class="hero">
    <div class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url(/assets/images/bg1.jpg">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

                    <div class="col-md-12 ftco-animate text-center">
                        <h1 class="mb-2">Pesan sekarang</h1>
                        <h2 class="subheading mb-4">Tersedia sayuran, buah-buahan, bumbu, ikan dan daging segar</h2>
                        <p><a href="/produks" class="btn btn-primary">Details</a></p>
                    </div>

                </div>
            </div>
        </div>

        <div class="slider-item" style="background-image: url(/assets/images/bg3.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

                    <div class="col-sm-12 ftco-animate text-center">
                        <h1 class="mb-2">100% Fresh</h1>
                        <h2 class="subheading mb-4">Tersedia sayuran, buah-buahan, bumbu, ikan dan daging segar</h2>
                        <p><a href="#" class="btn btn-primary">Details</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row no-gutters ftco-services justify-content-center">
            <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
                <div class="media block-6 services mb-md-0 mb-4">
                    <div class="icon bg-color-2 d-flex justify-content-center align-items-center mb-2">
                        <span class="flaticon-diet"></span>
                    </div>
                    <div class="media-body">
                        <h3 class="heading">Selalu Segar</h3>
                        <span>Paket Produk Baik</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
                <div class="media block-6 services mb-md-0 mb-4">
                    <div class="icon bg-color-3 d-flex justify-content-center align-items-center mb-2">
                        <span class="flaticon-award"></span>
                    </div>
                    <div class="media-body">
                        <h3 class="heading">Kualitas Super</h3>
                        <span>Produk berkualitas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section ftco-category ftco-no-pt">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 order-md-last align-items-stretch d-flex">
                        <div class="category-wrap-2 ftco-animate img align-self-stretch d-flex"
                            style="background-image: url(/assets/images/category.jpg);">
                            <div class="text text-center">
                                <h2>Produk segar</h2>
                                <p>dapat diantarkan langsung kerumah anda</p>
                                <p><a href="/produks" class="btn btn-primary">Beli Sekarang</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="category-wrap ftco-animate img mb-4 d-flex align-items-end"
                            style="background-image: url(/assets/images/buah.jpg);">
                            <div class="text px-3 py-1">
                                <h2 class="mb-0"><a href="#">Buah</a></h2>
                            </div>
                        </div>
                        <div class="category-wrap ftco-animate img d-flex align-items-end"
                            style="background-image: url(/assets/images/category-1.jpg);">
                            <div class="text px-3 py-1">
                                <h2 class="mb-0"><a href="#">Sayur</a></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="category-wrap ftco-animate img mb-4 d-flex align-items-end"
                    style="background-image: url(/assets/images/rempah.jpg);">
                    <div class="text px-3 py-1">
                        <h2 class="mb-0"><a href="#">Bumbu</a></h2>
                    </div>
                </div>
                <div class="category-wrap ftco-animate img d-flex align-items-end"
                    style="background-image: url(/assets/images/ikan-daging.jpg);">
                    <div class="text px-3 py-1">
                        <h2 class="mb-0"><a href="#">Ikan & Daging</a></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-3 pb-3">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <span class="subheading">Beberapa produk segar terbaru</span>
                <!-- <h2 class="mb-4">Products</h2> -->
                <p>Buah-buahan, sayuran, bumbu, ikan dan daging.</p>
            </div>
        </div>
    </div>
    <div class="container">
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