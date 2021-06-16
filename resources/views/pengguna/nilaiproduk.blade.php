@extends('layouts.templates')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('/assets/images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="">Home</a></span> <span class="mr-2"><a
                            href="">Nilai Produk</a></span></p>
                <h1 class="mb-0 bread">Penilaian anda tentang produk ini</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-5 ftco-animate">
                <a href="/foto_produk/{{$pembelian[0]->foto}}" class="image-popup">
                    <img src="/foto_produk/{{$pembelian[0]->foto}}" class="img-fluid" alt="Colorlib Template">
                </a>
            </div>
            <div class="col-lg-6  pl-md-5 ftco-animate">
                <h3>{{$pembelian[0]->nama_produk}}</h3>
                <div class="rating d-flex">
                @for ($i = 0; $i < $bintang; $i++)
                    <span class="ion-ios-star text-success mr-2"></span>
                @endfor
                @for ($i = 0; $i < 5-$bintang; $i++)
                    <span class="ion-ios-star-outline text-success mr-2"></span>
                @endfor
                </div>

                <form action="/insertnilai/{{$pembelian[0]->kd_pembelian}}/{{$bintang}}" method="POST">
                    @csrf
                        <div class="form-group">
                            <textarea cols="30" rows="7" class="form-control" placeholder="pendapat anda.." name="komentar"></textarea>
                        </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection