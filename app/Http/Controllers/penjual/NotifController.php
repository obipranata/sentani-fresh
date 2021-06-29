<?php

namespace App\Http\Controllers\penjual;

use App\Http\Controllers\Controller;
use App\Models\Notif;
use App\Models\Penjual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $username = $user->username;

        $notif = DB::select("SELECT * FROM notif");
        $data = [];
        if(!empty($notif)){
            $data['daftar_belanja'] = DB::select("SELECT keranjang.*, produk.*, penjual.lat, penjual.lng, penjual.kd_penjual,penjual.nama_toko, penjual.alamat, detail_produk.foto FROM penjual, produk, keranjang, detail_produk WHERE penjual.kd_penjual = produk.kd_penjual AND keranjang.kd_produk = produk.kd_produk AND keranjang.kd_produk = detail_produk.kd_produk AND penjual.username = '$username' GROUP BY produk.kd_produk ORDER BY keranjang.kd_keranjang");
            $data['notif'] = $notif;
        }

        // dd($daftar_belanja);

        return view('penjual.notif',$data);
    }

    public function updatenotif($kd_notif){
        $data_update = [
            'status' => 2
        ];   
        Notif::where('kd_notif', $kd_notif)->where('kd_notif', $kd_notif)->update($data_update);
        return redirect('/notifpenjual')->with('success', 'dikirim');
    }

    public function updateplayerid(Request $request){
        $user = $request->user();

        $username = $user->username;

        $data_player_id = [
            'player_id' => $_POST['player_id']
        ];
        Penjual::where('username', $username)->update($data_player_id);
    }
}
