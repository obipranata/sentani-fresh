@extends('layouts.templates_admin')

@section('content_admin')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Kategori</h3>
                <p class="text-subtitle text-muted">beberapa kategori penjualan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="/kategori/create" class="btn btn-primary tombol-tambah" >Kategori +</a>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Email</th>
                            <th class="text-center" colspan="2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0; ?>
                        @foreach ($kategori as $k) 
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{$k->nama_kategori}}</td>
                                <td class="text-center">
                                    <a href="/kategori/{{$k->kd_kategori}}/edit" class="badge bg-success">Ubah</a>
                                </td>
                                <td class="text-center">
                                    <a href="#" data-id="{{$k->kd_kategori}}" data-nama="{{$k->nama_kategori}}" class="btn btn-sm btn-danger hapus">
                                        <form action="/kategori/{{$k->kd_kategori}}" id="delete{{$k->kd_kategori}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>
@endsection

