<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        $data['allproduk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual GROUP BY produk.kd_produk");
        return view('admin.produk.index', $data);
    }

    public function show($kd_produk)
    {
        $data['detail_produk'] = DB::select("SELECT * FROM detail_produk WHERE kd_produk = '$kd_produk' ");
        $data['produk'] = collect(\DB::select("SELECT produk.*, kategori.nama_kategori FROM kategori, produk WHERE kategori.kd_kategori = produk.kd_kategori AND produk.kd_produk = '$kd_produk' ORDER BY produk.status ASC "))->first();

        return view('admin.produk.detail', $data);
    }

    public function update(Request $request, $kd_produk)
    {
        $status = $request->status;

        DB::table('produk')->where('kd_produk', $kd_produk)->update(['status' => $status]);

        return redirect("/admin/produk");
    }
}
