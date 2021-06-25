<?php

namespace App\Http\Controllers\penjual;
use App\Models\Topup;
use App\Models\Saldo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatPenjualanController extends Controller
{
    public function __construct()
    {
        $this->initPaymentGateway();
    }
    public function index(Request $request){
        $user = $request->user();

        $username = $user->username;

        $data['penjualan'] = DB::select("SELECT pembelian.*, produk.nama_produk, detail_produk.foto, penjual.username FROM pembelian, produk, detail_produk, penjual WHERE pembelian.kd_produk = produk.kd_produk AND detail_produk.kd_produk = produk.kd_produk AND penjual.kd_penjual = produk.kd_penjual AND penjual.username = '$username' GROUP BY produk.kd_produk, pembelian.no_nota ORDER BY pembelian.kd_pembelian");
        return view('penjual.riwayatpenjualan', $data);
    }
}
