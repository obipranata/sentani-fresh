@extends('layouts.templates')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('/assets/images/cart1.jpg');">
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="">Home</a></span> <span class="mr-2"></span>
                    <span>Keranjang</span></p>
                <h1 class="mb-0 bread">Keranjang Saya</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-cart">
    <div class="container">
        <form id="update_keranjang" action="/updatekeranjang/{{ Auth::user()->username }}" method="POST">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="cart-list">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    @if (empty($notif))                                      
                                        <th>&nbsp;</th>
                                    @endif
                                    <th>&nbsp;</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_keranjang as $k)
                                    <tr class="text-center">

                                        @if (empty($notif))                                          
                                            <td>
                                                <a href="" data-id="{{$k->kd_keranjang}}" data-nama="{{$k->nama_produk}}" class="btn btn-danger product-remove hapus">
                                                    
                                                    <span class="ion-ios-close"><span>
                                                </a>
                                            </td>
                                        @endif
        
                                        <td class="image-prod">
                                            <div class="img" style="background-image:url(/foto_produk/{{$k->foto}});"></div>
                                        </td>
        
                                        <td class="product-name">
                                            <h3>{{$k->nama_produk}}</h3>
                                            <p><?= $k->deskripsi ?></p>
                                        </td>
        
                                        <td class="price">Rp. {{number_format($k->harga)}}</td>
        
                                        <td class="quantity">
                                            <div class="input-group mb-3">
                                                <input type="text" name="jumlah[]" class="quantity form-control input-number"
                                                        value="{{$k->jumlah}}" min="1" max="100">
                                            </div>
                                        </td>
        
                                        <td class="total">Rp. {{number_format($k->harga * $k->jumlah)}}</td>
                                    </tr><!-- END TR-->                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if (empty($notif))               
                <div class="row justify-content-end mt-3">
                    <div class="col-md-12">
                        <p>
                            @csrf
                            @method('put')
                            <a class="btn btn-primary btn-block" href="/updatekeranjang/{{ Auth::user()->username }}"
                            onclick="event.preventDefault(); document.getElementById('update_keranjang').submit();">
                            update keranjang
                            </a>
                            
                        </p>
                    </div>
                </div>
            @endif
        </form>
        <div class="row justify-content-end">
            <div class="col-lg-6 mt-5 cart-wrap ftco-animate">
                <div class="cart-total mb-3">
                    <h3>Data Pengiriman</h3>
                    <p>anda dapat mengubah data pengiriman <a href="" class="icon-edit btn btn-warning" data-toggle="modal" data-target="#editAlamatModal"></a></p>
                    <form action="#" class="info">
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" class="form-control text-left px-3" disabled placeholder="" value="{{ Auth::user()->nama }}">
                        </div>
                        <div class="form-group">
                            <label for="country">Alamat</label>
                            <input type="text" class="form-control text-left px-3" disabled placeholder="" value="{{$pembeli->alamat}}">
                        </div>
                        <div class="form-group">
                            <label for="country">No Hp</label>
                            <input type="text" class="form-control text-left px-3" disabled placeholder="" value="{{$pembeli->no_hp}}">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 mt-5 cart-wrap ftco-animate">
                <div class="cart-total mb-3">
                    <h3>Total Pembelian</h3>
                    <div class="row">
                        <div class="col-md-6">
                            Subtotal
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                @foreach ($all_keranjang as $k)
                                    <div class="col-md-12">
                                        Rp. {{number_format($k->harga * $k->jumlah)}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            Ongkos Kirim
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                @foreach ($ongkir as $okr)
                                    <div class="col-md-12">
                                        Rp. {{number_format($okr)}}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="d-flex total-price">
                        <span>Total</span>
                        <span>Rp. {{number_format($total)}}</span>
                    </p>
                </div>
                @if (empty($notif)) 
                        <a href="#" data-harga="{{number_format($harga_produk)}}" class="btn btn-primary py-3 px-4 tombol-checkout">
                            checkout
                            <form action="/kirimnotif/{{ Auth::user()->username }}/{{$harga_produk}}/{{$total_ongkir}}" id="checkout" method="post">
                                @csrf
                            </form>
                        </a>
                @else
                    @if ($notif->status == 1)
                        <p class="text-danger"><i>kurir sedang mengambil pesanan anda pada penjual</i> <span class="text-success">[{{$notif->kurir}}]</span></p> 
                        <a href="#" data-harga="Rp.{{number_format($total_ongkir)}}" class="btn btn-primary py-3 px-4 tombol-bayar-kurir">
                            Transaksi Selesai
                            <form action="/bayarkurir/{{ $notif->kurir }}/{{$total_ongkir}}" id="bayar-kurir" method="post">
                                @csrf
                                @method('PUT')
                            </form>
                        </a>
                    @elseif ($notif->status == 0)
                        <p class="text-warning"><i>menuggu konfirmasi kurir </i>[{{$notif->kurir}}]</p>
                    @elseif ($notif->status == 2)
                        <p class="text-success"><i>kurir sedang mengantar pesanan anda </i>[{{$notif->kurir}}]</p>
                        <a href="#" data-harga="Rp.{{number_format($total_ongkir)}}" class="btn btn-primary py-3 px-4 tombol-bayar-kurir">
                            Transaksi Selesai
                            <form action="/bayarkurir/{{ $notif->kurir }}/{{$total_ongkir}}" id="bayar-kurir" method="post">
                                @csrf
                                @method('PUT')
                            </form>
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>



<!-- Modal -->
<div class="modal fade" id="editAlamatModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <form action="/editalamat" method="post">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Pengiriman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Nama</label>
                    <input type="text" class="form-control text-left px-3" name="nama" placeholder="nama..." value="{{ Auth::user()->nama }}">
                </div>
                <div class="form-group">
                    <label for="country">Alamat</label>
                    <input type="text" class="form-control text-left px-3" name="alamat" placeholder="alamat..." value="{{$pembeli->alamat}}">
                </div>
                <div class="form-group">
                    <label for="country">No Hp</label>
                    <input type="text" class="form-control text-left px-3" name="no_hp" placeholder="No hp..." value="{{$pembeli->no_hp}}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
</div>

@endsection