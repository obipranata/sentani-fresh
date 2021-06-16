@extends('layouts.templates_penjual')

@section('content_penjual')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Insert Produk</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Insert Produk</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                {{-- <h4 class="card-title"></h4> --}}
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form form-vertical" action="/produk" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="nama_produk">Nama Produk</label>
                                        <input type="text" id="nama_produk"
                                            class="form-control" name="nama_produk"
                                            placeholder="nama produk">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="stok">Stok</label>
                                        <input type="number" id="stok"
                                            class="form-control" name="stok"
                                            placeholder="stok">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="berat">Berat</label>
                                        <input type="number" id="berat"
                                            class="form-control" name="berat"
                                            placeholder="berat">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <input type="number" id="harga"
                                            class="form-control" name="harga"
                                            placeholder="harga">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <select class="form-control" id="kategori" name="kd_kategori">
                                            <option selected disabled>--Pilih Kategori--</option>
                                            @foreach ($kategori as $k)
                                                <option value="{{$k->kd_kategori}}">{{$k->nama_kategori}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="satuan">Satuan</label>
                                        <input type="text" id="satuan"
                                            class="form-control" name="satuan"
                                            placeholder="cth: kg, g">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea class="ckeditor" id="ckedtor" name="deskripsi"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-2">
                                    <button type="button" class="btn btn-success" id="tambah-foto">+</button>
                                </div>
                            </div>
                            <br>
                            <div class="row foto-produk">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="file" class="form-control-file" name="foto[]">
                                    </div>
                                </div>
                            </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection

