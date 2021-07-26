<?php

namespace App\Http\Controllers\kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use Illuminate\Http\Request;
use App\Models\Notif;
use App\Models\Pembeli;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Session;

class NotifController extends Controller
{
    public function notif(Request $request)
    {
        $user = $request->user();
        $username = $user->username;

        $notif = Notif::where('kurir', $username)->first();
        $data = [];
        if (!empty($notif)) {
            $pembeli = $notif->pembeli;
            $data['total_ongkir'] = $this->_total_ongkir($pembeli);
            $data['notif'] = $notif;
            $data['pembeli'] = Pembeli::where('username', $pembeli)->first();
            $data['user'] = User::where('username', $pembeli)->first();
            $data['penjual'] = User::all();
            $data['daftar_belanja'] = DB::select("SELECT keranjang.*, produk.*, penjual.lat, penjual.lng, penjual.kd_penjual,penjual.nama_toko, penjual.alamat, detail_produk.foto, penjual.username as penjual, penjual.no_hp as no_penjual FROM penjual, produk, keranjang, detail_produk WHERE penjual.kd_penjual = produk.kd_penjual AND keranjang.kd_produk = produk.kd_produk AND keranjang.kd_produk = detail_produk.kd_produk AND keranjang.username = '$pembeli' GROUP BY produk.kd_produk ORDER BY keranjang.kd_keranjang");
            // dd($data['daftar_belanja']);
        }

        // dd($daftar_belanja);

        return view('kurir.notif', $data);
    }

    private function _total_ongkir($pembeli)
    {
        $all_keranjang = DB::select("SELECT keranjang.*, produk.*, penjual.lat, penjual.lng, penjual.kd_penjual, detail_produk.foto FROM penjual, produk, keranjang, detail_produk WHERE penjual.kd_penjual = produk.kd_penjual AND keranjang.kd_produk = produk.kd_produk AND keranjang.kd_produk = detail_produk.kd_produk AND keranjang.username = '$pembeli' GROUP BY produk.kd_produk ORDER BY keranjang.kd_keranjang");

        $pembeli = Pembeli::where('username', $pembeli)->first();

        // dapatkan tarif jarak antara pembeli dan penjual
        $jarak_normal = $this->_jarak($pembeli->lat, $pembeli->lng, $all_keranjang[0]->lat, $all_keranjang[0]->lng);
        $berat = [];
        $list_ongkir = [];
        $ongkir_normal = $jarak_normal;
        array_push($list_ongkir, $ongkir_normal);

        $data['all_keranjang'] = $all_keranjang;

        $produk_pertama = $all_keranjang[0]->kd_penjual;

        foreach ($all_keranjang as $k) {
            if ($k->satuan == 'Gram' || $k->satuan == 'gram') {
                $gram = $k->berat * 0.001;
                array_push($berat, $gram);
            } else {
                array_push($berat, $k->berat);
            }

            $biaya_produk[] = $k->harga * $k->jumlah;
            $produk[] = $k->kd_penjual;
            if ($produk_pertama == $k->kd_penjual) {
                // echo $k->harga + 0;
            } else {
                $lat1= $all_keranjang[0]->lat;
                $lat2= $k->lat;
                $lon1= $all_keranjang[0]->lng;
                $lon2= $k->lng;

                $jarak = $this->_jarak($lat1, $lon1, $lat2, $lon2);
                
               
                $list_ongkir[] = $jarak;
                
                // if ($jarak > 100) {
                //     $list_ongkir[] = 1000 + $jarak;
                // }
            }
        }
        if (array_sum($berat) <= 5) {
            $hasil_berat = (5000);
        } elseif (array_sum($berat)<=10) {
            $hasil_berat = (10000);
        } elseif (array_sum($berat)<=15) {
            $hasil_berat = (15000);
        } elseif (array_sum($berat)<=20) {
            $hasil_berat = (20000);
        } elseif (array_sum($berat)<=25) {
            $hasil_berat = (25000);
        } elseif (array_sum($berat)<=30) {
            $hasil_berat = (30000);
        } elseif (array_sum($berat)<=35) {
            $hasil_berat = (35000);
        } elseif (array_sum($berat)<=40) {
            $hasil_berat = (40000);
        } elseif (array_sum($berat)<=45) {
            $hasil_berat = (45000);
        } elseif (array_sum($berat)<=50) {
            $hasil_berat = (50000);
        } elseif (array_sum($berat)>50) {
            $hasil_berat = (1 * array_sum($berat));
        }
        
        $data['ongkir'] = array_unique($list_ongkir);

        return round((array_sum($data['ongkir'])* 0.001), 2) * $hasil_berat;
    }

    private function _jarak($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meter = $kilometers/0.0010000;
        return $meter;
    }

    public function updatenotif($kd_notif)
    {
        $data_update = [
            'status' => 1
        ];
        Notif::where('kd_notif', $kd_notif)->where('kd_notif', $kd_notif)->update($data_update);
        return redirect('/notif')->with('success', 'dikirim');
    }

    public function kirimnotifbaru($pembeli, $kurir_reject)
    {

        // dd($kurir_reject);
        $kurir = DB::select("SELECT * FROM kurir");
        $data_kurir_reject = Kurir::where('username', $kurir_reject)->first();

        foreach ($kurir as $kr) {
            if ($kr->username != $kurir_reject) {
                $lat1= $data_kurir_reject->lat;
                $lat2= $kr->lat;
                $lon1= $data_kurir_reject->lng;
                $lon2= $kr->lng;

                $jarak = $this->_jarak($lat1, $lon1, $lat2, $lon2);

                $daftar_kurir[] = ['jarak'=> $jarak, 'kurir'=> $kr->username];
            }
        }

        sort($daftar_kurir);
        $kurir_terpilih = min($daftar_kurir);

        $username_kurir = $kurir_terpilih['kurir'];
        // dd($kurir_terpilih);
        $waktu = time();
        DB::select("UPDATE notif SET kurir = '$username_kurir', waktu = $waktu WHERE pembeli = '$pembeli' ");

        // dd($notif);
        // dd(min($daftar_kurir));
        return redirect('/notif')->with('success', 'dialihkan ke kurir terdekat');
    }

    public function kirimpesan($pembeli)
    {
        $data_pembeli = DB::table('pembeli')->where('username', $pembeli)->first();
        $content = array(
            "en" => "hallo, $pembeli silahkan konfirmasi bahwa transaksi telah selesai"
            );
        
        $fields = array(
            'app_id' => "3b514bae-20de-4dd3-9f4c-a159eb52f569",
            'include_player_ids' => array($data_pembeli->player_id),
            'data' => array("foo" => "bar"),
            'contents' => $content
        );
        
        $fields = json_encode($fields);
        // print("\nJSON sent:\n");
        // print($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);
        
        return redirect('/notif')->with('success', 'dikirim');
    }
}
