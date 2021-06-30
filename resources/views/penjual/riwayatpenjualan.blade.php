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
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#cetak">Cetak</a>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th></th>
                            <th>Produk</th>
                            <th>No nota</th>
                            <th>Tgl pembelian</th>
                            <th>Jml produk</th>
                            <th>Total</th>
                            <th>Pembeli</th>
                            <th>Kurir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach ($penjualan as $p)                       
                            <tr>
                                <td>{{++$i}}</td>
                                <td>
                                    <img class=" img-fluid" src="/foto_produk/{{$p->foto}}"alt="Card image cap" width="80" />
                                </td>
                                <td>{{$p->nama_produk}}</td>
                                <td>{{$p->no_nota}}</td>
                                <td>{{$p->tgl_pembelian}}</td>
                                <td>{{$p->jml_produk}}</td>
                                <td>{{$p->total}}</td>
                                <td>{{$p->pembeli}}</td>
                                <td>{{$p->kurir}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

  <!-- Modal -->
  <div class="modal fade" id="cetak" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Filter berdasarkan tanggal</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="/penjual/riwayatpenjualan/download" method="post">
            @csrf
            <div class="modal-body">
                    <div class="row">
                    <div class="col">
                        <input type="date" class="form-control" name="dari">
                    </div>
                    to
                    <div class="col">
                        <input type="date" class="form-control" name="sampai">
                    </div>
                    </div>
                
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
      </div>
    </div>
  </div>
@endsection




