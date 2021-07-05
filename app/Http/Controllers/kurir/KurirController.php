<?php

namespace App\Http\Controllers\kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class KurirController extends Controller
{
    public function updatelokasi(Request $request){
        $user = $request->user();

        $username = $user->username;

        $data_lokasi = [
            'lat' => $_POST['lat'],
            'lng' => $_POST['lng']
        ];
        Kurir::where('username', $username)->update($data_lokasi);
    }

    public function updateplayerid(Request $request){
        $user = $request->user();

        $username = $user->username;

        $data_player_id = [
            'player_id' => $_POST['player_id']
        ];
        Kurir::where('username', $username)->update($data_player_id);
    }

    public function riwayatpengantaran(Request $request){
        $user = $request->user();

        $username = $user->username;

        $data['penjualan'] = DB::select("SELECT pembelian.*, produk.nama_produk, detail_produk.foto, penjual.username, penjual.alamat, sum(pembelian.jml_produk) as jml, pembeli.alamat as alamat_pembeli FROM pembelian, produk, detail_produk, penjual , pembeli WHERE pembelian.kd_produk = produk.kd_produk AND detail_produk.kd_produk = produk.kd_produk AND penjual.kd_penjual = produk.kd_penjual AND pembelian.kurir = '$username' AND pembeli.username = pembelian.pembeli GROUP BY pembelian.no_nota ORDER BY pembelian.kd_pembelian DESC");
        return view('kurir.riwayatpenjualan', $data);
    }

    public function download(Request $request){
        $user = $request->user();

        $username = $user->username;
        $dari = $request->dari;
        $sampai = $request->sampai;
        $data['penjualan'] = DB::select("SELECT pembelian.*, produk.nama_produk, detail_produk.foto, penjual.username, penjual.alamat, sum(pembelian.jml_produk) as jml, pembeli.alamat as alamat_pembeli FROM pembelian, produk, detail_produk, penjual , pembeli WHERE pembelian.kd_produk = produk.kd_produk AND detail_produk.kd_produk = produk.kd_produk AND penjual.kd_penjual = produk.kd_penjual AND pembelian.kurir = '$username' AND pembeli.username = pembelian.pembeli AND (pembelian.tgl_pembelian BETWEEN '$dari' AND '$sampai') GROUP BY pembelian.no_nota ORDER BY pembelian.kd_pembelian DESC");
        // (pembelian.tgl_pembelian BETWEEN '$dari' AND '$sampai')
        $pdf = PDF::loadView('kurir.riwayatpenjualan_download',$data);
        return $pdf->download('riwayatpenjualan.pdf');
    }
}
