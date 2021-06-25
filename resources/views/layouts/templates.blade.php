@if (Auth::user())
    @if (Auth::user()->level != 3)
        <script>window.location.replace("/home");</script>
    @endif
@endif
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sentani Fresh</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/animate.css">

    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/assets/css/magnific-popup.css">

    <link rel="stylesheet" href="/assets/css/aos.css">

    <link rel="stylesheet" href="/assets/css/ionicons.min.css">

    <link rel="stylesheet" href="/assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="/assets/css/jquery.timepicker.css">


    <link rel="stylesheet" href="/assets/css/flaticon.css">
    <link rel="stylesheet" href="/assets/css/icomoon.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="goto-here">
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="/">Sentani Fresh</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a href="/" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="/produks" class="nav-link">Produk</a></li>
                    {{-- <li class="nav-item"><a href="" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="" class="nav-link">Contact</a></li> --}}

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li> --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Registrasi</a>
                                <div class="dropdown-menu" aria-labelledby="dropdown04">
                                    <a class="dropdown-item" href="/daftar-pembeli">Pembeli</a>
                                    <a class="dropdown-item" href="/daftar-kurir">Kurir</a>
                                    <a class="dropdown-item" href="/daftar-penjual">Penjual</a>
                                </div>
                            </li>
                        @endif
                        @else
                            @php
                                $username =  Auth::user()->username;
                                $keranjang = DB::select("SELECT count(kd_keranjang) as jml FROM keranjang WHERE username = '$username' "); 
                                $saldo = DB::select("SELECT * from saldo WHERE username = '$username' "); 
                                $pembelian = DB::select("SELECT * from pembelian WHERE pembeli = '$username' ");
                                $topup = \App\Models\Topup::where(['username' => $username, 'payment_status' => 'pending'])->first();

                                if (!empty($topup)) {
                                    $paymentInfo = \Midtrans\Transaction::status($topup->no_topup);

                                    $bank = $paymentInfo->va_numbers[0]->bank;
                                    $va_number = $paymentInfo->va_numbers[0]->va_number;
                                    $batas_pembayaran = $paymentInfo->transaction_time;
                                    $total = $paymentInfo->gross_amount;
                                        
                                    $payment_status = $paymentInfo->transaction_status;

                                    if ($payment_status == 'success' || $payment_status =='settlement') {
                                        \App\Models\Topup::where('no_topup', $topup->no_topup)->update(['payment_date' => $paymentInfo->settlement_time, 'payment_status' => $payment_status]);
                                        \App\Models\Saldo::where('username', $username)->update(['jumlah' => $paymentInfo->gross_amount + $saldo[0]->jumlah]);
                                    }
                                }

                            @endphp
                            <li class="nav-item cta cta-colored">
                                <a href="/keranjang" class="nav-link">
                                    <span class="icon-shopping_cart"></span>
                                    [{{$keranjang[0]->jml}}]
                                </a>
                            </li>
                            @if (!empty($pembelian))                               
                                <li class="nav-item">
                                    <a href="/pembelian" class="nav-link">
                                        Pembelian
                                    </a>
                                </li>
                            @endif

                            @if (!empty($topup))                               
                                <li class="nav-item">
                                    <a href="" class="nav-link" data-toggle="modal" data-target="#transaksiModal">Selesaikan transaksi(<span class="text-success">Info</span>)</a>
                                </li>
                            @else  
                                @if (Auth::user()->level == 3)
                                    <li class="nav-item">
                                        <a href="" class="nav-link" data-toggle="modal" data-target="#topupModal">Isi SF saldo (<span class="text-success">{{number_format($saldo[0]->jumlah)}}</span>)</a>
                                    </li>
                                @endif                             
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->username }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <!-- END nav -->

    @yield('content')


    <footer class="ftco-footer ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">

                    <p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());
                        </script> made with <i class="icon-heart text-danger" aria-hidden="true"></i> by <a href="#"
                            target="_blank">Yolanda</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </p>
                </div>
            </div>
        </div>
    </footer>



    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                stroke="#F96D00" /></svg></div>


    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/jquery-migrate-3.0.1.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery.easing.1.3.js"></script>
    <script src="/assets/js/jquery.waypoints.min.js"></script>
    <script src="/assets/js/jquery.stellar.min.js"></script>
    <script src="/assets/js/owl.carousel.min.js"></script>
    <script src="/assets/js/jquery.magnific-popup.min.js"></script>
    <script src="/assets/js/aos.js"></script>
    <script src="/assets/js/jquery.animateNumber.min.js"></script>
    <script src="/assets/js/bootstrap-datepicker.js"></script>
    <script src="/assets/js/scrollax.min.js"></script>
    <script src="/assets/js/main.js"></script>

    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script> --}}
    <script src="/admin/assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Modal -->
<div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/topup" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Topup Saldo Sentani Fresh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nominal</label>
                        <input type="number" class="form-control text-left px-3" name="nominal" placeholder="nominal...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Topup</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if (!empty($topup))
    <!-- Modal transaksi-->
    <div class="modal fade" id="transaksiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Info pembayaran topup</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            VA number
                            <span class="text-success">{{$va_number}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Bank
                            <span class="text-success">{{$bank}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Bayar Sebelum
                            <span class="text-success">{{$batas_pembayaran}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total
                            <span class="text-success">IDR {{number_format($total)}}</span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Kembali</button>
                </div>
            </div>
    </div>
    </div>
@endif

    
    @if ($message = Session::get('success'))
        <div class="info">
            {{$message}}
        </div>
        <script>
            let message = '{{$message}}';
            Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text:  message
            })
        </script>
    @endif

    @if ($message = Session::get('error'))
        <div class="info-gagal">
            {{$message}}
        </div>
        <script>
            let message = '{{$message}}';
            Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text:  message
            })
        </script>
    @endif

    @if ($message = Session::get('errorupdate'))
        <div class="info-gagal">
            @foreach ($message as $m)
                {{$m}}
            @endforeach
        </div>
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: $('.info-gagal').html()
            })
        </script>
    @endif

    <script>
        $(document).ready(function(){
    
        var quantitiy=0;
           $('.quantity-right-plus').click(function(e){
                
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var quantity = parseInt($('#quantity').val());
                let max = $(this).data("max");
                // If is not undefined
                    
                if(max > quantity){
                    $('#quantity').val(quantity + 1);
                }
                    // Increment
                
            });
    
             $('.quantity-left-minus').click(function(e){
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var quantity = parseInt($('#quantity').val());
                
                // If is not undefined
              
                    // Increment
                    if(quantity>1){
                    $('#quantity').val(quantity - 1);
                    }
            });
            
        });
    </script>

<script>
    $(".hapus").click(function(e){
        e.preventDefault();

        // id = e.target.dataset.id;
        id = $(this).data("id");
        // nama = e.target.dataset.nama;
        nama =$(this).data('nama');
        Swal.fire({
        title: 'Apakah anda yakin hapus ' +nama,
        text: `data ${nama} akan hilang.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#82AE47',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
        }).then((result) => {
        if (result.isConfirmed) {
            $(`#delete${id}`).submit();
        }
        })
    })

    $(".tombol-checkout").click(function(e){
        e.preventDefault();

        // id = e.target.dataset.id;
        // nama = e.target.dataset.nama;
        harga =$(this).data('harga');
        Swal.fire({
        title: 'anda yakin ingin checkout?',
        text: `total belanjaan anda ${harga}. belum termasuk biaya ongkir`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#82AE47',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
        }).then((result) => {
        if (result.isConfirmed) {
            $(`#checkout`).submit();
        }
        })
    })

    $(".tombol-bayar-kurir").click(function(e){
        e.preventDefault();

        // id = e.target.dataset.id;
        // nama = e.target.dataset.nama;
        harga =$(this).data('harga');
        Swal.fire({
        title: `Total ongkos kirim ${harga}`,
        text: `pastikan produk yang anda pesan sudah di terima !`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#82AE47',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya'
        }).then((result) => {
        if (result.isConfirmed) {
            $(`#bayar-kurir`).submit();
        }
        })
    })

</script>

<script type="text/javascript">
    function myMap() {
        var mapProp = {
            center: new google.maps.LatLng(-2.53371, 140.71813),
            zoom: 13,
        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        var marker;

        window.onload = function() {
            var startPos;
            var geoSuccess = function(position) {
                startPos = position;
                console.log(startPos.coords.latitude);
                console.log(startPos.coords.longitude);

                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(startPos.coords.latitude, startPos.coords.longitude),
                    map: map,
                    icon: "/assets/lokasi2.png",
                    animation: google.maps.Animation.BOUNCE
                });
                var infowindowText = "<div class='text-center'><strong>Posisi Saat Ini</strong> Lat : " +
                    startPos.coords.latitude + " | Long: " + startPos.coords.longitude + "</strong></div>";
                infowindow.setContent(infowindowText);
                infowindow.open(map, marker);
                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            };
            navigator.geolocation.getCurrentPosition(geoSuccess);
        };

        var infowindow = new google.maps.InfoWindow({});

        function taruhMarker(peta, posisiTitik) {
            // membuat Marker
            if (marker) {
                // pindahkan marker
                marker.setPosition(posisiTitik);
            } else {
                // buat marker baru
                marker = new google.maps.Marker({
                    position: posisiTitik,
                    map: peta,
                    draggable: true
                });
                console.log(marker);
            }
        }

        google.maps.event.addListener(map, 'click', function(event) {
            taruhMarker(this, event.latLng);
            $("#latLngs").text('Titik Kordinat : ' + event.latLng);
            $("#lat").val(event.latLng.lat());
            $("#lng").val(event.latLng.lng());
        });


    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLsBUFqgsrYYjB_jXFkC1Esh8qQv-Yzw4&callback=myMap"></script>

</body>

</html>