@extends('layouts.templates_admin')

@section('content_admin')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Penjual</h3>
                <p class="text-subtitle text-muted">penjual yang terdaftar.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Penjual</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="" class="btn btn-primary" >Cetak</a>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Toko</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Username</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach ($penjual as $p)   
                            @foreach ($user as $u)
                                @if ($u->username == $p->username)
                                    @php
                                        $nama = $u->nama;
                                    @endphp
                                
                                @endif
                            @endforeach
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$nama}}</td>
                                <td>{{$p->nama_toko}}</td>
                                <td>{{$p->alamat}}</td>
                                <td>{{$p->no_hp}}</td>
                                <td>{{$p->username}}</td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>
@endsection




