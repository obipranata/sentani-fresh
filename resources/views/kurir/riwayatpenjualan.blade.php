@extends('layouts.templates_kurir')

@section('content_kurir')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Riwayat Pengantaran</h3>
                <p class="text-subtitle text-muted">riwayat pengantaran kurir.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Riwayat Pengantaran</li>
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
                            <th>Tgl Pembelian</th>
                            <th>Nama Pembeli</th>
                            <th>Jml Produk</th>
                            <th>Alamat Pembeli</th>
                            <th>Total Ongkir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach ($penjualan as $p)                       
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$p->tgl_pembelian}}</td>
                                <td>{{$p->pembeli}}</td>
                                <td>{{$p->jml}}</td>
                                <td>{{$p->alamat_pembeli}}</td>
                                <td>{{round($p->total_ongkir)}}</td>
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
        <form action="/kurir/riwayatpenjualan/download" method="post">
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

<div id="googleMap" class="bg-white" style="height: 0;"></div>

<script src="/assets/js/jquery.min.js"></script>

  <script type="text/javascript">
    function myMap() {
        var mapProp = {
            center: new google.maps.LatLng(-2.53371, 140.71813),
            zoom: 13,
        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        var marker;

        var startPos;
            var geoSuccess = function(position) {
                startPos = position;
                console.log(startPos.coords.latitude);
                console.log(startPos.coords.longitude);
                let url = "{{ url('/updatelokasi') }} ";

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        'lat' : startPos.coords.latitude,
                        'lng' : startPos.coords.longitude,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data){
                        console.log(data);
                    }
                });
            };
            navigator.geolocation.getCurrentPosition(geoSuccess);
  
        var infowindow = new google.maps.InfoWindow({});
  
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLsBUFqgsrYYjB_jXFkC1Esh8qQv-Yzw4&callback=myMap"></script>
@endsection




