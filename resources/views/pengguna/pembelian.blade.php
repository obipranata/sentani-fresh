@extends('layouts.templates')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('/assets/images/bg_1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="">Home</a></span> <span class="mr-2"></span>
                    <span>Pembelian</span></p>
                <h1 class="mb-0 bread">Produk yang telah dibeli</h1>
            </div>
        </div>
    </div>
</div>



<section class="ftco-section ftco-cart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 ftco-animate">
                <div class="cart-list">
                    <table class="table">
                         <thead class="thead-primary">
                            <tr class="text-center">
                                <th>&nbsp;</th>
                                <th>Tgl Pembelian</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Nilai Produk ini</th>
                            </tr> 
                        </thead>
                        <tbody>
                            @foreach ($pembelian as $p)
                                <tr class="text-center">
                                    <td class="image-prod">
                                        <div class="img" style="background-image:url(/foto_produk/{{$p->foto}});"></div>
                                    </td>

                                    <td class="product-name">
                                        <h3>{{$p->tgl_pembelian}}</h3>
                                    </td>
        
                                    <td class="product-name">
                                        <h3>{{$p->nama_produk}}</h3>
                                    </td>
        
        
                                    <td class="quantity">
                                        {{$p->jml_produk}}
                                    </td>
        
                                    <td class="total">Rp. {{number_format($p->total)}}</td>

                                    <td>
                                    @if ($p->status == 1)
                                        <small class="text-danger">Produk ini telah dinilai</small>
                                    @else   

                                        @for ($i = 1; $i <= 5; $i++)

                                            @php
                                                $id = "document.getElementById("."'nilai-produk-"."$p->kd_pembelian-$i"."').submit();";
                                            @endphp
                                            <a href="nilaiproduk/{{$p->kd_pembelian}}/{{$i}}" onclick="event.preventDefault(); {{$id}}">
                                                <span class="ion-ios-star-outline rate-{{$i}}"></span>
                                            </a>

                                            <form id="nilai-produk-{{$p->kd_pembelian}}-{{$i}}" action="nilaiproduk/{{$p->kd_pembelian}}/{{$i}}" method="POST" class="d-none">
                                                @csrf
                                            </form>                                                                    
                                        @endfor
                                    @endif
                                    </td>
                                </tr><!-- END TR-->                               
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection