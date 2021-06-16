@extends('layouts.templates_penjual')

@section('content_penjual')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{$produk->nama_produk}}</h3>
                <p class="text-subtitle text-muted">detail produk.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$produk->nama_produk}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="" class="btn btn-success tombol-tambah" data-toggle="modal" data-target="#tambahFotoModal">Foto Produk +</a>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($detail_produk as $d)                      
                        <div class="col-3">
                            <a href="" >
                                <div class="card">
                                    <div class="card-content">
                                        <img class="card-img-top img-fluid" src="/foto_produk/{{$d->foto}}"
                                            alt="Card image cap" />
                                        <div class="card-body">
                                            <a href="" data-id="{{$d->kd_detail_produk}}" data-nama="{{$d->foto}}" class="btn btn-sm btn-danger hapus">
                                                <form action="/detailproduk/{{$d->kd_detail_produk}}" id="delete{{$d->kd_detail_produk}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <i class="fas fa-window-close"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h4>
                            Detail 
                            <small>
                                <a href="/produk/{{$produk->kd_produk}}/edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </small>
                        </h4> 
                        <p>Berat: {{$produk->berat}}</p>
                        <p>Kategori: {{$produk->nama_kategori}}</p>
                        <p>Stok: {{$produk->stok}}</p>
                        <p>Satuan: <?= $produk->satuan ?></p>
                        <p> <?= $produk->deskripsi ?></p>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

  <!-- Modal -->
  <div class="modal fade" id="tambahFotoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="/tambahfoto/{{$produk->kd_produk}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Foto Produk</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="file" class="form-control-file" name="foto">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
      </div>
    </div>
  </div>

@endsection

