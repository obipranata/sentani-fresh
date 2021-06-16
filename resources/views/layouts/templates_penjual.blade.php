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

    <script src="/admin/assets/vendors/apexcharts/apexcharts.js"></script>
    <script src="/admin/assets/js/pages/dashboard.js"></script>

    <script src="/admin/assets/vendors/simple-datatables/simple-datatables.js"></script>

    <link rel="stylesheet" href="/assets/css/ionicons.min.css">

    <link rel="stylesheet" href="/admin/assets/fontawesome/js/all.min.js">

    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    <script src="/admin/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script src="/admin/assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>
    
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

    <!-- Modal -->
    <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="/topup_penjual" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Topup Poin Sentani Fresh</h5>
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
                        <button type="submit" class="btn btn-success">Topup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        $username =  Auth::user()->username;
        $poin = DB::select("SELECT * from poin WHERE username = '$username' "); 
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
                \App\Models\Poin::where('username', $username)->update(['jumlah' => $paymentInfo->gross_amount + $poin[0]->jumlah]);
            }
        }
    @endphp

@if (!empty($topup))
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