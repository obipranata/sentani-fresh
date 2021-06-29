<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjual - Sentani Fresh</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/admin/assets/css/bootstrap.css">

    <link rel="stylesheet" href="/admin/assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="/admin/assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="/admin/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/admin/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="/admin/assets/css/app.css">
    <link rel="shortcut icon" href="/admin/assets/images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <script src="/admin/assets/vendors/ckeditor/style.js"></script>
    <script src="/admin/assets/vendors/ckeditor/ckeditor.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        window.OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
            appId: "3b514bae-20de-4dd3-9f4c-a159eb52f569",
            });
        });
    </script>
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
                @include('layouts.sidebar_penjual')
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('content_penjual')

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

$("#tambah-foto").click(function(){
    console.log('okee')
    $(".foto-produk").append(`
                                        <div class="col-12">
                            <div class="form-group">
                                <input type="file" class="form-control-file" name="foto[]">
                            </div>
                        </div>
    `);
});
</script>


</body>

</html>

<script>
    OneSignal.push(function() {

    /* These examples are all valid */
    var isPushSupported = OneSignal.isPushNotificationsSupported();
        if (isPushSupported) {
            // Push notifications are supported
            console.log('supported');
            OneSignal.isPushNotificationsEnabled(function(isEnabled) {
                if (isEnabled){
                    console.log("Push notifications are enabled!");
                    OneSignal.getUserId(function(userId) {
                        console.log("OneSignal User ID:", userId);
                        // (Output) OneSignal User ID: 270a35cd-4dda-4b3f-b04e-41d7463a2316    

                        let url = "{{ url('/updateplayeridpenjual') }} ";

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                'player_id' : userId,
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(data){
                                console.log(data);
                            }
                        });
                        
                    });
                }
                else{
                    console.log("Push notifications are not enabled yet.");    
                    OneSignal.push(function() {
                        OneSignal.showSlidedownPrompt();
                    });
                }
            });
        } else {
            // Push notifications are not supported
            console.log('not supported');
        }
    });
</script>
