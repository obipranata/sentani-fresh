@extends('layouts.templates')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('/assets/images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="">Home</a></span> <span class="mr-2"><a
                            href="">Produk</a></span> <span>Produk Single</span></p>
                <h1 class="mb-0 bread">Produk Single</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-5 ftco-animate">
                <a href="/foto_produk/{{$produk[0]->foto}}" class="image-popup">
                    <img src="/foto_produk/{{$produk[0]->foto}}" class="img-fluid" alt="Colorlib Template">
                </a>
                <div class="row mt-3">
                    @foreach ($detail_produk as $dp)
                        <div class="col-2-md">
                            <a href="/foto_produk/{{$dp->foto}}" class="image-popup ml-3">
                                <img src="/foto_produk/{{$dp->foto}}" width="100" class="img-fluid" alt="Colorlib Template">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 product-details pl-md-5 ftco-animate">
                <h3>{{$produk[0]->nama_produk}}</h3>
                <div class="rating d-flex">
                    @if (empty($rating))
                        <p class="text-left mr-4">belum ada penilaian</p>
                    @else                       
                        <p class="text-left mr-4">
                            <a href="#" class="mr-2">{{$rating}}</a>

                            @for ($i = 0; $i < $bintang[0]; $i++)
                                <a href="#"><span class="ion-ios-star"></span></a>
                            @endfor
                            
                            @if (!empty($bintang[1]))                               
                                @if ($bintang[1] > 4)
                                    <a href="#"><span class="ion-ios-star-half"></span></a>
                                @endif
                            @endif
                        </p>

                        <p class="text-left mr-4">
                            <a href="#" class="mr-2" style="color: #000;">{{$penilaian[0]->jml_penilaian}} <span
                                    style="color: #bbb;">Penilaian</span></a>
                        </p>
                        <p class="text-left">
                            <a href="#" class="mr-2" style="color: #000;">{{$terjual[0]->terjual}} <span style="color: #bbb;">Terjual</span></a>
                        </p>
                    @endif
                </div>
                <p class="price"><span>Rp{{number_format($produk[0]->harga)}}</span></p>
                <p>
                    <span class="icon-map-pin text-danger"></span>
                    {{$produk[0]->alamat}}
                </p>
                <?= $produk[0]->deskripsi ?>
                <form action="/tambahkeranjang/{{$produk[0]->kd_produk}}" method="POST">
                    @csrf
                    <div class="row mt-4">
                        <div class="w-100"></div>
                        <div class="input-group col-md-6 d-flex mb-3">
                            <span class="input-group-btn mr-2">
                                <button type="button" class="quantity-left-minus btn" data-type="minus" data-field="">
                                    <i class="ion-ios-remove"></i>
                                </button>
                            </span>
                            <input type="text" id="quantity" name="jumlah" class="form-control input-number" value="1" min="1" max="100">
                            <span class="input-group-btn ml-2">
                                <button type="button" class="quantity-right-plus btn" data-type="plus" data-field="" data-max="{{$produk[0]->stok}}">
                                    <i class="ion-ios-add"></i>
                                </button>
                            </span>
                        </div>
                        <div class="input-group col-md-6 d-flex mb-3">
                            tersisa {{$produk[0]->stok}}
                        </div>
                        <div class="w-100"></div>
                        <div class="col-md-12">
                            <p class="text-success">Per {{$produk[0]->satuan}}</p>
                        </div>
                    </div>
                    <button type="submit">+ keranjang</button>
                </form>

                <div class="accordion mt-3" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Lihat komentar
                            </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                                @foreach ($rating_all as $r)                                 
                                    <h5>{{$r->pembeli}}  <span class="ion-ios-star text-success"> <small>{{$r->rating}}</small></h5>
                                    {{$r->komentar}}
                                @endforeach
                            </div>
                        </div>
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
                <span class="subheading">Produk</span>
                <h2 class="mb-4">Produk serupa</h2>
                <p>produk bahan makanan yaitu sayuran, buah, dan daging segar</p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @foreach ($produk_lain as $p)             
                <div class="col-md-6 col-lg-3 ftco-animate">
                    <div class="product">
                        <a href="#" class="img-prod"><img class="img-fluid" src="/foto_produk/{{$p->foto}}"
                                alt="Colorlib Template">
                            {{-- <span class="status">30%</span> --}}
                            <div class="overlay"></div>
                        </a>
                        <div class="text py-3 pb-4 px-3 text-center">
                            <h3><a href="#">{{$p->nama_produk}}</a></h3>
                            <div class="d-flex">
                                <div class="pricing">
                                    <p class="price">
                                        <span class="price-sale">Rp{{number_format($p->harga)}}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="bottom-area d-flex px-3">
                                <div class="m-auto d-flex">
                                    <a href="/single/{{$p->kd_produk}}"
                                        class="add-to-cart d-flex justify-content-center align-items-center text-center">
                                        <span><i class="ion-ios-menu"></i></span>
                                    </a>
                                    <a href="#" class="buy-now d-flex justify-content-center align-items-center mx-1">
                                        <span><i class="ion-ios-cart"></i></span>
                                    </a>
                                    <a href="#" class="heart d-flex justify-content-center align-items-center ">
                                        <span><i class="ion-ios-heart"></i></span>
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