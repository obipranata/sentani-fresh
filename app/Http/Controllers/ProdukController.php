<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Topup;
use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\Notif;
use App\Models\Pembeli;
use App\Models\Saldo;
use App\Models\Pembelian;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\New_;
use Session;

class ProdukController extends Controller
{

    public function __construct()
    {
        $this->initPaymentGateway();
    }

    public function index(Request $request){
        $user = $request->user();
        if ($user) {
            $username = $user->username;
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

                    if ($payment_status =='settlement'){
                        $settlement_time = $paymentInfo->settlement_time;
                    } else if ($payment_status =='expire'){
                        $settlement_time = null;
                    }

                    Topup::where('no_topup', $topup->no_topup)->update(['payment_date' => $settlement_time, 'payment_status' => $payment_status]);

                    if ($payment_status =='settlement') {
                        Saldo::where('username', $username)->update(['jumlah' => $paymentInfo->gross_amount + $saldo->jumlah]);
                    }
                }
            }
        }

        $data['allproduk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual GROUP BY produk.kd_produk");


        return view('pengguna.home', $data);
    }

    public function produk(Request $request){
        $cari = $request->cari;
        if($cari == ''){
            $data['allproduk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual GROUP BY produk.kd_produk");
        }else{
            $data['allproduk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual AND produk.nama_produk LIKE '$cari%' GROUP BY produk.kd_produk");
        }
        $data['kategori'] = DB::select("SELECT * FROM kategori");
        return view('pengguna.produk', $data);
    }

    public function pilihproduk($kd_kategori){
        $data['allproduk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual  AND produk.kd_kategori = '$kd_kategori' GROUP BY produk.kd_produk");
        $data['kategori'] = DB::select("SELECT * FROM kategori");
        return view('pengguna.produk', $data);
    }

    public function single($kd_produk){
        
        $data['produk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual AND produk.kd_produk = '$kd_produk' GROUP BY produk.kd_produk");

        $rating = DB::select("SELECT pembelian.kd_produk, AVG(rating.rating) as jml, rating.status, rating.komentar FROM pembelian, rating WHERE pembelian.kd_pembelian = rating.kd_pembelian AND rating.status = 1 AND pembelian.kd_produk = '$kd_produk' GROUP BY pembelian.kd_produk");

        $data['rating_all']= DB::select("SELECT rating.status, rating.komentar, pembelian.pembeli, rating.rating FROM pembelian, rating WHERE pembelian.kd_pembelian = rating.kd_pembelian AND rating.status = 1 AND pembelian.kd_produk = '$kd_produk' ");

        // dd($data['rating_all']);
        $data['penilaian'] = DB::select("SELECT pembelian.kd_produk, rating.status, count(pembelian.kd_pembelian) as jml_penilaian FROM pembelian, rating WHERE pembelian.kd_pembelian = rating.kd_pembelian AND rating.status = 1 AND pembelian.kd_produk = '$kd_produk' GROUP BY pembelian.kd_produk");

        $data['terjual'] = DB::select("SELECT pembelian.kd_produk, rating.status, sum(pembelian.jml_produk) as terjual FROM pembelian, rating WHERE pembelian.kd_pembelian = rating.kd_pembelian AND pembelian.kd_produk = '$kd_produk' GROUP BY pembelian.kd_produk");

        $data['detail_produk'] = DB::select("SELECT * FROM detail_produk WHERE kd_produk = '$kd_produk' ORDER BY kd_detail_produk");

        if(!empty($rating)){
            $data['rating'] = round($rating[0]->jml,1);
            $data['bintang'] = explode('.',$data['rating']);
        }

        $kd_kategori = $data['produk'][0]->kd_kategori;

        $data['produk_lain'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual AND produk.kd_kategori = '$kd_kategori'  GROUP BY produk.kd_produk");

        return view('pengguna.single', $data);
    }

    public function tambahkeranjang(Request $request, $kd_produk){
        $user = $request->user();

        $username = $user->username;

        $produk = Produk::where('kd_produk', $kd_produk)->first();
        if(!$request->jumlah){
            $jumlah = 1;
        } else {
            $jumlah = $request->jumlah;
            if($jumlah > $produk->stok){
                return redirect('/single/'.$kd_produk)->with('error', 'permintaan melebihi stok!');
            }
        }

        $cek_keranjang = DB::select("SELECT * FROM keranjang WHERE kd_produk = '$kd_produk' AND username = '$username' ");

        if(empty($cek_keranjang)){
            $data_insert = [
                'jumlah' => $jumlah,
                'kd_produk' => $kd_produk,
                'username' => $username
            ];    
            Keranjang::insert($data_insert);
        }else{
            $data_update = [
                'jumlah' => $cek_keranjang[0]->jumlah + $jumlah,
                'kd_produk' => $kd_produk,
                'username' => $username
            ];   
            Keranjang::where('kd_produk', $kd_produk)
            ->where('username', $username)->update($data_update);
        }
        return redirect('/')->with('success', 'ditambahkan');
    }

    public function keranjang(Request $request){

        $user = $request->user();

        $username = $user->username;

        $all_keranjang = DB::select("SELECT keranjang.*, produk.*, penjual.lat, penjual.lng, penjual.kd_penjual, detail_produk.foto FROM penjual, produk, keranjang, detail_produk WHERE penjual.kd_penjual = produk.kd_penjual AND keranjang.kd_produk = produk.kd_produk AND keranjang.kd_produk = detail_produk.kd_produk AND keranjang.username = '$username' GROUP BY produk.kd_produk ORDER BY keranjang.kd_keranjang");

        if (empty($all_keranjang)){
            return redirect('/')->with('error', 'oops... tidak ada produk didalam keranjang');
        }

        $pembeli = Pembeli::where('username', $username)->first();
        $data['pembeli'] = $pembeli;

        $data['notif'] = Notif::where(['pembeli' => $username])->first();

        // dapatkan tarif jarak antara pembeli dan penjual
        $jarak_normal = $this->_jarak($pembeli->lat,$pembeli->lng,$all_keranjang[0]->lat,$all_keranjang[0]->lng);
        $list_ongkir = [];
        $ongkir_normal = $jarak_normal + 1000;
        array_push($list_ongkir, $ongkir_normal);

        $data['all_keranjang'] = $all_keranjang;

        $produk_pertama = $all_keranjang[0]->kd_penjual;

        foreach ($all_keranjang as $k){
            $biaya_produk[] = $k->harga * $k->jumlah;
            $produk[] = $k->kd_penjual;
            if($produk_pertama == $k->kd_penjual){
                // echo $k->harga + 0;
            }else{
                $lat1= $all_keranjang[0]->lat;
                $lat2= $k->lat;
                $lon1= $all_keranjang[0]->lng;
                $lon2= $k->lng;

                $jarak = $this->_jarak($lat1,$lon1,$lat2,$lon2);
                
                if($jarak > 100){
                    $list_ongkir[] = 1000 + $jarak;
                }
            }
        }
        $data['ongkir'] = array_unique($list_ongkir);

        $data['harga_produk'] = array_sum($biaya_produk);

        $data['total_ongkir'] = array_sum($data['ongkir']);

        // total
        $data['total'] = array_sum($biaya_produk) + array_sum($data['ongkir']);
        // dd(array_sum($biaya_produk));

        return view('pengguna.cart', $data);
    }

    private function _jarak($lat1, $lon1, $lat2, $lon2){
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meter = $kilometers/0.0010000;
        return $meter;
    }

    public function updatekeranjang(Request $request, $username){

        $user = $request->user();

        $username = $user->username;

        $keranjang = DB::select("SELECT * FROM keranjang WHERE username = '$username' "); 
        
        $gagal_update = [];
        for ($i=0; $i<count($keranjang); $i++){
            $kd_keranjang = $keranjang[$i]->kd_keranjang;
            $kd_produk = $keranjang[$i]->kd_produk;
            $jumlah = $request->jumlah[$i];
            
            $produk = DB::select("SELECT * FROM produk WHERE kd_produk = '$kd_produk' ");

            if ($produk[0]->kd_produk == $kd_produk){
                if (($produk[0]->stok - $jumlah) >= 0){
                    DB::select("UPDATE keranjang SET jumlah = '$jumlah' WHERE kd_keranjang = '$kd_keranjang' ");
                }else{
                    $gagal_update[] = $produk[0]->nama_produk. ' hanya tersisa '.$produk[0]->stok;
                }
            }
            
        }
        
        // dd($gagal_update);
        
        if(!empty($gagal_update)){
            // dd('tidak kosong');
            return redirect('/keranjang')->with('errorupdate', $gagal_update);
        }

        return redirect('/keranjang')->with('success', 'ubah');
    }

    public function hapuskeranjang($kd_keranjang){
        DB::select("DELETE FROM keranjang WHERE kd_keranjang = '$kd_keranjang' ");
        return redirect('/keranjang')->with('success', 'hapus');
    }

    public function kirimnotif(Request $request, $username, $harga_produk, $total_ongkir){
        $user = $request->user();
        $username = $user->username;

        $all_keranjang = DB::select("SELECT keranjang.*, produk.*, penjual.lat, penjual.lng, penjual.kd_penjual, detail_produk.foto FROM penjual, produk, keranjang, detail_produk WHERE penjual.kd_penjual = produk.kd_penjual AND keranjang.kd_produk = produk.kd_produk AND keranjang.kd_produk = detail_produk.kd_produk AND keranjang.username = '$username' GROUP BY produk.kd_produk ORDER BY keranjang.kd_keranjang");

        $k1 = DB::select("SELECT * FROM kurir"); 
        $k2 = DB::select("SELECT * FROM notif"); 

        foreach($k1 as $k){
            $a[] = $k->username;
        }
        foreach($k2 as $k){
            $b[] = $k->kurir;
        }

        if(empty($k2)){
            $result = $a;
        }else{
            $result=array_diff($a,$b);
        }

        foreach ($result as $r){
            foreach($k1 as $k){
                if($r == $k->username){
                    $kurir[]= [
                        'username' => $r,
                        'lat' => $k->lat,
                        'lng' => $k->lng,
                        'player_id' => $k->player_id
                    ];
                }
            }
        }

        if (!empty($kurir)){
            $saldo = Saldo::where('username', $username)->first();
            if ($saldo->jumlah >= ($harga_produk + $total_ongkir)){
                $jumlah = $saldo->jumlah - $harga_produk;
                DB::select("UPDATE saldo SET jumlah = '$jumlah' WHERE username = '$username' ");
            }else{
                return redirect('/keranjang')->with('error','maaf saldo anda tidak cukup !');
            }
        } else {
            return redirect('/keranjang')->with('error','maaf tidak ada kurir yang siap!');
        }

        foreach($kurir as $kr){
            $lat1= $all_keranjang[0]->lat;
            $lat2= $kr['lat'];
            $lon1= $all_keranjang[0]->lng;
            $lon2= $kr['lng'];

            $jarak = $this->_jarak($lat1,$lon1,$lat2,$lon2);

            $daftar_kurir[] = ['jarak'=> $jarak, 'kurir'=> $kr['username'], 'player_id' => $kr['player_id']];
        }

        sort($daftar_kurir);

        // dd($daftar_kurir);
        $kurir_terpilih = min($daftar_kurir);

        $notif = [
            'pembeli' => $username,
            'kurir' => $kurir_terpilih['kurir'],
            'waktu' => time()
        ];

        Notif::insert($notif);
        $this->_sendMessage($username, $kurir_terpilih['player_id']);
        // dd($notif);
        // dd(min($daftar_kurir));
        return redirect('/keranjang')->with('success','sedang diproses... tunggu kurir mengkonfirmasi belanjaan anda!');
    }

    private function _sendMessage($pembeli, $kurir){
        $content = array(
            "en" => "@$pembeli sedang memesan produk, harap kamu segera konfirmasi pengantaran"
            );
        
        $fields = array(
            'app_id' => "3b514bae-20de-4dd3-9f4c-a159eb52f569",
            'include_player_ids' => array($kurir),
            'data' => array("foo" => "bar"),
            'contents' => $content
        );
        
        $fields = json_encode($fields);
        // print("\nJSON sent:\n");
        // print($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    public function bayarkurir(Request $request, $kurir, $total_ongkir){
        $user = $request->user();
        $username = $user->username;

        $toko = DB::select("SELECT penjual.kd_penjual, penjual.nama_toko,penjual.username, sum(keranjang.jumlah*produk.harga) as laku FROM penjual, produk, keranjang, detail_produk WHERE penjual.kd_penjual = produk.kd_penjual AND keranjang.kd_produk = produk.kd_produk AND keranjang.kd_produk = detail_produk.kd_produk AND keranjang.username = '$username' GROUP BY produk.kd_penjual ORDER BY keranjang.kd_keranjang ");

        $poin_pembeli = Saldo::where('username', $username)->first();
        $poin_kurir = Saldo::where('username', $kurir)->first();
        $total_kurir = $total_ongkir + $poin_kurir->jumlah ;

        // dd($poin_pembeli->jumlah);

        if ($poin_pembeli->jumlah >= $total_ongkir){
            $jumlah_pembeli = $poin_pembeli->jumlah - $total_ongkir;
            $jumlah_kurir = $total_kurir;
            DB::select("UPDATE saldo SET jumlah = '$jumlah_pembeli' WHERE username = '$username' ");
            DB::select("UPDATE saldo SET jumlah = '$jumlah_kurir' WHERE username = '$kurir' ");

            // saldo akan masuk ke para penjual
            foreach($toko as $t){
                $laku = $t->laku;
                $penjual = $t->username;
                $poin_penjual = Saldo::where('username', $penjual)->first();
                $pendapatan_bersih = ($laku - ($laku * 0.02)) + $poin_penjual->jumlah;
                DB::select("UPDATE saldo SET jumlah = '$pendapatan_bersih' WHERE username = '$penjual' ");
            }

            $this->_pembelian($username, $kurir, $total_ongkir);
            DB::select("DELETE FROM notif WHERE pembeli = '$username' ");
            DB::select("DELETE FROM keranjang WHERE username = '$username' ");

            return redirect('/')->with('success','transaksi pembayaran selesai!');
        }else{
            return redirect('/keranjang')->with('error','maaf saldo anda tidak cukup !');
        }
    }

    private function _pembelian($pembeli, $kurir, $total_ongkir){
        $keranjang = DB::select("SELECT keranjang.*, produk.*, penjual.lat, penjual.lng, penjual.kd_penjual, detail_produk.foto FROM penjual, produk, keranjang, detail_produk WHERE penjual.kd_penjual = produk.kd_penjual AND keranjang.kd_produk = produk.kd_produk AND keranjang.kd_produk = detail_produk.kd_produk AND keranjang.username = '$pembeli' GROUP BY produk.kd_produk ORDER BY keranjang.kd_keranjang");

        foreach($keranjang as $k){
            $pembelian = [
                'no_nota' => time(),
                'kd_produk' => $k->kd_produk,
                'jml_produk' => $k->jumlah,
                'total' => $k->jumlah * $k->harga,
                'total_ongkir' => $total_ongkir,
                'tgl_pembelian' => date('Y-m-d'),
                'pembeli' => $pembeli,
                'kurir' => $kurir
            ];

            $kd_pembelian = DB::table('pembelian')->insertGetId(
                $pembelian
            );
            // Pembelian::insert($pembelian);

            $rating = [
                'rating' => 0,
                'kd_pembelian' => $kd_pembelian,
                'komentar' => '',
                'status' => 0,
            ];

            Rating::insert($rating);
        }
    }

    public function pembelian(Request $request){
        $user = $request->user();

        $username = $user->username;

        $data['pembelian'] = DB::select("SELECT pembelian.*, rating.*, produk.nama_produk, detail_produk.foto FROM pembelian, rating, produk, detail_produk WHERE pembelian.kd_pembelian = rating.kd_pembelian AND produk.kd_produk = pembelian.kd_produk AND produk.kd_produk = detail_produk.kd_produk AND pembelian.pembeli = '$username' ");

        return view('pengguna.pembelian',$data);
    }

    public function nilaiproduk(Request $request, $kd_pembelian, $bintang){
        $user = $request->user();

        $username = $user->username;

        $data['pembelian'] = DB::select("SELECT pembelian.*, rating.*, produk.nama_produk, detail_produk.foto FROM pembelian, rating, produk, detail_produk WHERE pembelian.kd_pembelian = rating.kd_pembelian AND produk.kd_produk = pembelian.kd_produk AND produk.kd_produk = detail_produk.kd_produk AND pembelian.pembeli = '$username' AND pembelian.kd_pembelian = '$kd_pembelian' GROUP BY produk.kd_produk ");
        $data['bintang'] = $bintang;

        return view('pengguna.nilaiproduk',$data);
    }

    public function insertnilai(Request $request, $kd_pembelian, $bintang){
        $komentar = $request->komentar;
        DB::select("UPDATE rating SET rating = '$bintang', status = 1, komentar='$komentar' WHERE kd_pembelian = '$kd_pembelian' ");
        return redirect('/pembelian')->with('success','terima kasih sudah melakukan penilaian');
    }

    public function editalamat(Request $request){
        $user = $request->user();
        $username = $user->username;

        $nama = $request->nama;
        $alamat = $request->alamat;
        $no_hp = $request->no_hp;

        $pembeli = [
            'alamat' => $alamat,
            'no_hp' => $no_hp
        ];
        User::where('username', $username)->update(['nama' => $nama]);
        Pembeli::where('username', $username)->update($pembeli);
        return redirect('/keranjang')->with('success','data telah diubah...');
    }

    public function updateplayerid(Request $request){
        $user = $request->user();

        $username = $user->username;

        $data_player_id = [
            'player_id' => $_POST['player_id']
        ];
        Pembeli::where('username', $username)->update($data_player_id);
    }

}
