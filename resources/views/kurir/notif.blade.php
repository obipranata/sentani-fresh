@extends('layouts.templates_kurir')

@section('content_kurir')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Notifikasi</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Pesanan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    @if (empty($daftar_belanja))
                    <p><span class="bg-danger text-white">Belum ada notifikasi atau permintaan pengantaran pesanan</span></p>
                    @else                 
                    <h6>Ongkos Kirim <span class="text-success">Rp.{{number_format($total_ongkir)}}</span></h6>

                        <div class="row">
                            @foreach ($daftar_belanja as $d)
                                <div class="col-lg-4">
                                    <div class="card" style="background-color: #F2F7FF">
                                        <img src="/foto_produk/{{$d->foto}}" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">{{$d->nama_produk}}</h5>
                                            <p class="card-text">
                                                Toko : {{$d->nama_toko}}
                                            </p>
                                            <p class="card-text">
                                                Alamat Toko : {{$d->alamat}}
                                            </p>
                                            <p class="card-text">
                                                Penjual : {{$d->penjual}}
                                            </p>
                                            <p class="card-text">
                                                No Telp : {{$d->no_penjual}}
                                            </p>
                                            <p class="card-text">
                                                Jumlah : {{$d->jumlah}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <p class="text-danger">pengantaran sesuai dengan alamat dibawah ini</p>
                        <p>Nama Pembeli: {{$user->nama}}</p>
                        <p>Alamat: {{$pembeli->alamat}}, {{$pembeli->no_hp}}</p>
                        @if ($notif->status == 0)
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="/updatenotif/{{$notif->kd_notif}}" method="POST" class="mt-4">
                                        @csrf
                                        <button class="btn btn-success rounded-pill" type="submit">Konfirmasi</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form action="/kirimnotifbaru/{{$pembeli->username}}/{{Auth::user()->username}}" method="POST" class="mt-4">
                                        @csrf
                                        <button class="btn btn-danger rounded-pill" type="submit">Batal</button>
                                    </form>
                                </div>
                            </div>                        
                        @else
                            <form action="/kirimpesan/{{$pembeli->username}}" method="POST" class="mt-4">
                                @csrf
                                <button class="btn btn-success rounded-pill" type="submit">Kirim notif ke pembeli</button>
                            </form>
                        @endif
                    @endif
                    <br>
                    <div id="map" class="bg-white" style="height: 400px; width: 100%"></div>
                </div>
            </div>
        </div>

    </section>
</div>

<div id="googleMap" class="bg-white" style="height: 0;"></div>

<script src="/assets/js/jquery.min.js"></script>

<script type="text/javascript">
    var cord = [];
</script>

@foreach ($daftar_belanja as $dr)
    <script>
        cord.push([
            {{$dr->lat}},{{$dr->lng}},"{{$dr->nama_toko}}","{{$dr->alamat}}","{{$dr->penjual}}"
        ]);
    </script>
@endforeach

  <script type="text/javascript">
    function myMap() {
        var mapProp = {
            center: new google.maps.LatLng(-2.53371, 140.71813),
            zoom: 13,
        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        var mapToko = new google.maps.Map(document.getElementById("map"), mapProp);
        var marker;
        var lokasisekarang = new google.maps.InfoWindow({});
        window.onload = function() {
            var startPos;
            var geoSuccess = function(position) {
                startPos = position;
                console.log(startPos.coords.latitude);
                console.log(startPos.coords.longitude);

                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(startPos.coords.latitude, startPos.coords.longitude),
                    map: mapToko,
                    icon: "/assets/images/current.png",
                    animation: google.maps.Animation.BOUNCE
                });
                var infowindowText = "<div class='text-center'><strong>Posisi Saat Ini</strong> Lat : " +
                    startPos.coords.latitude + " | Long: " + startPos.coords.longitude + "</strong></div>";
                lokasisekarang.setContent(infowindowText);
                lokasisekarang.open(mapToko, marker);
                marker.addListener('click', function() {
                    lokasisekarang.open(mapToko, marker);
                });

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
        }
        var locations = cord;
        var infowindow = new google.maps.InfoWindow({});
        var marker_tetap, count;

            for (count = 0; count < locations.length; count++) {
                marker_tetap = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[count][0], locations[count][1]),
                    map: mapToko,
                });

                google.maps.event.addListener(marker_tetap, 'click', (function(marker_tetap, count) {
                    var layanan;
                    var Fasilitas;
                    return function() {
                        infowindow.setContent(
                            `
                            <h6 class='az-content-label mg-b-5'> Toko: ${locations[count][2]} </h6>
                            <hr><h5> Penjual: ${locations[count][4]} </h5> 
                            <hr><p> Alamat: ${locations[count][3]} </p> 
                                
                                <p class='az-content-label mg-b-5' font-size='9px'>Titik Kordinat</p>
                                <p>${locations[count][0]}, ${locations[count][1]}</p>
                            `
                        );
                        infowindow.open(mapToko, marker_tetap);
                    }
                })(marker_tetap, count));

            }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLsBUFqgsrYYjB_jXFkC1Esh8qQv-Yzw4&callback=myMap"></script>
@endsection




