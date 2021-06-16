<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurir - Sentani Fresh</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/admin/assets/css/bootstrap.css">

    <link rel="stylesheet" href="/admin/assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="/admin/assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="/admin/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/admin/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="/admin/assets/css/app.css">
    <link rel="shortcut icon" href="/admin/assets/images/favicon.svg" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            {{-- <a href="index.html"><img src="/admin/assets/images/logo/logo.png" alt="Logo" srcset=""></a> --}}
                            <span class="text-success">Sentani Fresh</span>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                {{-- @yield('sidebar_admin') --}}
                @include('layouts.sidebar_kurir')
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('content_kurir')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2021 &copy; Sentani Fresh</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by Yolanda</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script type="text/javascript">
        var cord = [];
    </script>
    
    <div id="googleMap" class="bg-white" style="height: 0;"></div>

    <script src="/assets/js/jquery.min.js"></script>
    <script src="/admin/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>

    {{-- <script src="/admin/assets/vendors/apexcharts/apexcharts.js"></script> --}}
    <script src="/admin/assets/js/pages/dashboard.js"></script>

    <script src="/admin/assets/vendors/simple-datatables/simple-datatables.js"></script>

    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    <script src="/admin/assets/js/main.js"></script>
    <script src="/admin/assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script> --}}

    
        @if ($message = Session::get('success'))
            <div class="info">
                {{$message}}
            </div>
            <script>
                Swal.fire(
                'Berhasil!',
                "Data telah di "+$(".info").html(),
                'success'
                )
            </script>
        @endif

    <script>
        $(".hapus").click(function(e){
            id = e.target.dataset.id;
            nama = e.target.dataset.nama;
            Swal.fire({
            title: 'Apakah anda yakin hapus ' +nama,
            text: `data ${nama} akan hilang.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya'
            }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete${id}`).submit();
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

</body>

</html>

