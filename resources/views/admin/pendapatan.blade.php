@extends('layouts.templates_admin')

@section('content_admin')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Pendapatan</h3>
                <p class="text-subtitle text-muted">pendapatan didapat dari 2% pajak admin setiap kali pembelian.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pendapatan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#cetak">Cetak</a>
                    </div>
                    <div class="col-md-6">
                        <form action="/admin/pendapatan" method="get">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{-- <label for="bulan">Tampilkan berdasarkan bulan</label> --}}
                                        <select class="form-control" id="bulan" name="bulan">
                                            <option>Tampilkan berdasarkan bulan</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-info">submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Nota</th>
                            <th>Tgl Pembelian</th>
                            <th>Total Transaksi (Rp)</th>
                            <th>Total Pendapatan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach ($pendapatan as $p)   
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$p->no_nota}}</td>
                                <td>{{$p->tgl_pembelian}}</td>
                                <td>{{number_format($p->total_transaksi)}}</td>
                                <td>{{number_format($p->total_pendapatan)}}</td>
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
        <form action="/admin/pendapatan/download" method="post">
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




