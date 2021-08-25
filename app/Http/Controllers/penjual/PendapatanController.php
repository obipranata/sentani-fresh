<?php

namespace App\Http\Controllers\penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $username = $user->username;
        if ($request->bulan) {
            // dd($_GET['bulan']);
            $bulan = $_GET['bulan'];
            $data['pendapatan'] = DB::select("SELECT SUM(pembelian.total) as total_pendapatan, month(pembelian.tgl_pembelian) as bulan, pembelian.* FROM pembelian, produk, penjual WHERE month(pembelian.tgl_pembelian) = '$bulan' AND pembelian.kd_produk = produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual AND penjual.username = '$username' GROUP BY pembelian.no_nota, month(pembelian.tgl_pembelian)");
        } else {
            $data['pendapatan'] = DB::select("SELECT SUM(pembelian.total) as total_pendapatan, month(pembelian.tgl_pembelian) as bulan, pembelian.* FROM pembelian, produk, penjual WHERE pembelian.kd_produk = produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual AND penjual.username = '$username' GROUP BY pembelian.no_nota");
        }

        return view('penjual.pendapatan', $data);
    }

    public function download(Request $request)
    {
        $user = $request->user();
        $username = $user->username;
        $dari = $request->dari;
        $sampai = $request->sampai;
        $data['pendapatan'] = DB::select("SELECT SUM(pembelian.total) as total_pendapatan, pembelian.* FROM pembelian, produk, penjual WHERE pembelian.kd_produk = produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual AND penjual.username = '$username' AND (tgl_pembelian BETWEEN '$dari' AND '$sampai') GROUP BY pembelian.no_nota");
        // return view('admin.pembeli_download', $data);
        $pdf = PDF::loadView('penjual.pendapatan_download', $data);
        return $pdf->download('pendapatan.pdf');
    }
}
