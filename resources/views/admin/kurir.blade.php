@extends('layouts.templates_admin')

@section('content_admin')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Kurir</h3>
                <p class="text-subtitle text-muted">kurir yang terdaftar.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kurir</li>
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
                            <th>No HP</th>
                            <th>Username</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach ($kurir as $k)   
                            @foreach ($user as $u)
                                @if ($u->username == $k->username)
                                    @php
                                        $nama = $u->nama;
                                    @endphp
                                
                                @endif
                            @endforeach
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$nama}}</td>
                                <td>{{$k->no_hp}}</td>
                                <td>{{$k->username}}</td>
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>
@endsection




