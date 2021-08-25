<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topup;
use App\Models\Saldo;
use Illuminate\Support\Facades\DB;
use Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // return view('home');
        $user = $request->user();
        $username = $user->username;

        if ($user) {
            $saldo = Saldo::where('username', $username)->first();

            $this->initPaymentGateway();
            $topup = Topup::where(['username' => $username, 'payment_status' => 'pending'])->first();

            if (!empty($topup)) {
                $paymentInfo = \Midtrans\Transaction::status($topup->no_topup);

                $data['bank'] = $paymentInfo->va_numbers[0]->bank;
                $data['va_number'] = $paymentInfo->va_numbers[0]->va_number;
                $data['batas_pembayaran'] = $paymentInfo->transaction_time;
                $data['total'] = $paymentInfo->gross_amount;
                // dd($paymentInfo);
                $payment_status = $paymentInfo->transaction_status;

                if ($payment_status == 'success' || $payment_status =='settlement' || $payment_status =='expire') {
                    if ($payment_status =='settlement') {
                        $settlement_time = $paymentInfo->settlement_time;
                    } elseif ($payment_status =='expire') {
                        $settlement_time = null;
                    }

                    Topup::where('no_topup', $topup->no_topup)->update(['payment_date' => $settlement_time, 'payment_status' => $payment_status]);

                    if ($payment_status =='settlement') {
                        Saldo::where('username', $username)->update(['jumlah' => $paymentInfo->gross_amount + $saldo->jumlah]);
                    }
                }
            }

            if ($user->level == 1) {
                $data['penjual'] = DB::select("SELECT count(kd_penjual) as jml_penjual FROM penjual");
                $data['pembeli'] = DB::select("SELECT count(kd_pembeli) as jml_pembeli FROM pembeli");
                $data['produk'] = DB::select("SELECT count(kd_produk) as jml_produk FROM produk");
                $data['terjual'] = DB::select("SELECT sum(jml_produk) as jml_terjual FROM pembelian");
                // $data['penjualan'] = DB::select("SELECT pembelian.*, produk.nama_produk, penjual.username, sum(pembelian.jml_produk) as total_produk, DATE_FORMAT(pembelian.tgl_pembelian, '%M') as bulan FROM pembelian, produk, penjual WHERE pembelian.kd_produk = produk.kd_produk AND penjual.kd_penjual = produk.kd_penjual GROUP BY MONTH(pembelian.tgl_pembelian) ORDER BY MONTH(pembelian.tgl_pembelian)");
                $data['penjualan'] = DB::select("SELECT pembelian.*, produk.kd_produk, produk.nama_produk, penjual.username, sum(pembelian.jml_produk) as total_produk, DATE_FORMAT(pembelian.tgl_pembelian, '%M') as bulan FROM pembelian, produk, penjual WHERE pembelian.kd_produk = produk.kd_produk AND penjual.kd_penjual = produk.kd_penjual GROUP BY produk.kd_produk");
                return view('admin.home', $data);
            } elseif ($user->level == 2) {
                $data['pembeli'] = DB::select("SELECT pembelian.*, penjual.username, SUM(pembelian.total) as total_pembelian FROM pembelian, penjual, produk WHERE produk.kd_penjual = penjual.kd_penjual AND penjual.username = '$username' AND pembelian.kd_produk = produk.kd_produk GROUP BY pembelian.pembeli ORDER BY total_pembelian DESC");
                $data['penjualan'] = DB::select("SELECT pembelian.*, produk.kd_produk, produk.nama_produk, penjual.username, sum(pembelian.jml_produk) as total_produk, DATE_FORMAT(pembelian.tgl_pembelian, '%M') as bulan FROM pembelian, produk, penjual WHERE pembelian.kd_produk = produk.kd_produk AND penjual.kd_penjual = produk.kd_penjual AND penjual.username = '$username' GROUP BY produk.kd_produk");
                // $data['pembeli'] = DB::select("SELECT pembelian.*, penjual.username, SUM(pembelian.total) as total_pembelian FROM pembelian, penjual, produk WHERE produk.kd_penjual = penjual.kd_penjual AND penjual.username = '$username' AND pembelian.kd_produk = produk.kd_produk GROUP BY pembelian.pembeli ORDER BY total_pembelian DESC");
                // $data['penjualan'] = DB::select("SELECT pembelian.*, produk.nama_produk, penjual.username, sum(pembelian.jml_produk) as total_produk, DATE_FORMAT(pembelian.tgl_pembelian, '%M') as bulan FROM pembelian, produk, penjual WHERE pembelian.kd_produk = produk.kd_produk AND penjual.kd_penjual = produk.kd_penjual AND penjual.username = '$username' GROUP BY MONTH(pembelian.tgl_pembelian) ORDER BY MONTH(pembelian.tgl_pembelian)");
                // dd( $data['penjualan']);
                return view('penjual.home', $data);
            } elseif ($user->level == 3) {
                $data['allproduk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual GROUP BY produk.kd_produk");
                return view('pengguna.home', $data);
            } elseif ($user->level == 4) {
                $pengantaran = DB::select("SELECT count(kd_pembelian) as total_pengantaran, DATE_FORMAT(pembelian.tgl_pembelian, '%M') as bulan FROM pembelian WHERE kurir = '$username' GROUP BY MONTH(tgl_pembelian) ORDER BY MONTH(tgl_pembelian) ");
                return view('kurir.home', compact('pengantaran'));
            } else {
                return abort(404);
            }
        }
    }

    public function admin()
    {
        echo 'admin';
    }

    public function kurir()
    {
        echo 'kurir';
    }
}
