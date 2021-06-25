<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request){
        $data['user'] = User::all();

        $data['penjualan'] = DB::select("SELECT pembelian.*, produk.nama_produk, detail_produk.foto, penjual.username FROM pembelian, produk, detail_produk, penjual WHERE pembelian.kd_produk = produk.kd_produk AND detail_produk.kd_produk = produk.kd_produk AND penjual.kd_penjual = produk.kd_penjual GROUP BY produk.kd_produk, pembelian.no_nota ORDER BY pembelian.kd_pembelian");
        return view('admin.riwayatpenjualan', $data);
    }
}
