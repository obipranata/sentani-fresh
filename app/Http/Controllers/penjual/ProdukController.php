<?php

namespace App\Http\Controllers\penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Detail_produk;
use App\Models\Penjual;
use Illuminate\Support\Facades\DB;
use Session;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->initPaymentGateway();
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $username = $user->username;

        $data['produk'] = Produk::all();
        $data['detail_produk'] = Detail_produk::all();
        $data['allproduk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual AND penjual.username = '$username' GROUP BY produk.kd_produk");
        return view('penjual.produk.index', $data);
    }

    public function cari(Request $request)
    {
        $user = $request->user();

        $username = $user->username;
        $cari = $request->cari;

        $data['produk'] = Produk::all();
        $data['detail_produk'] = Detail_produk::all();
        $data['allproduk'] = DB::select("SELECT produk.*, detail_produk.*, penjual.* FROM produk,detail_produk, penjual WHERE produk.kd_produk = detail_produk.kd_produk AND produk.kd_penjual = penjual.kd_penjual AND penjual.username = '$username' AND produk.nama_produk LIKE '$cari%' GROUP BY produk.kd_produk");
        return view('penjual.produk.index', $data);
    }

    public function show($kd_produk)
    {
        $data['detail_produk'] = DB::select("SELECT * FROM detail_produk WHERE kd_produk = '$kd_produk' ");
        $data['produk'] = collect(\DB::select("SELECT produk.*, kategori.nama_kategori FROM kategori, produk WHERE kategori.kd_kategori = produk.kd_kategori AND produk.kd_produk = '$kd_produk' "))->first();

        return view('penjual.produk.detail', $data);
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('penjual.produk.tambah', compact('kategori'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $username = $user->username;

        $penjual = Penjual::where('username', $username)->first();

        $produk = new Produk;

        $produk->nama_produk = $request->nama_produk;
        $produk->stok = $request->stok;
        $produk->berat = $request->berat;
        $produk->harga = $request->harga;
        $produk->deskripsi = $request->deskripsi;
        $produk->kd_kategori = $request->kd_kategori;
        $produk->kd_penjual = $penjual->kd_penjual;
        $produk->satuan = $request->satuan;
        $produk->status = 0;

        $produk->save();

        for ($i=0; $i < count($request->foto); $i++) {
            $nama_foto = time().'-'.$i.'.'.$request->foto[$i]->extension();
            $request->foto[$i]->move(public_path('foto_produk'), $nama_foto);

            $data = [
            'foto' => $nama_foto,
            'kd_produk' => $produk->id];

            Detail_produk::insert($data);
        }


        return redirect('/produk')->with('success', 'tambahkan');
    }

    public function edit($kd_produk)
    {
        $data['kategori'] = Kategori::all();
        $data['produk'] = collect(\DB::select("SELECT produk.*, kategori.nama_kategori FROM kategori, produk WHERE kategori.kd_kategori = produk.kd_kategori AND produk.kd_produk = '$kd_produk' "))->first();

        return view('penjual.produk.ubah', $data);
    }

    public function update(Request $request, $kd_produk)
    {
        $data = [
            'nama_produk' => $request->nama_produk,
            'stok' => $request->stok,
            'berat' => $request->berat,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'kd_kategori' => $request->kd_kategori,
            'satuan' => $request->satuan,
        ];

        Produk::where('kd_produk', $kd_produk)->update($data);

        return redirect('/produk/'.$kd_produk)->with('success', 'ubah');
    }

    public function destroy($kd_produk)
    {
        $detail_produk = Detail_produk::where('kd_produk', $kd_produk)->get();
        Produk::where('kd_produk', $kd_produk)->delete();
        foreach ($detail_produk as $d) {
            if ($d->foto == 'nope.jpg') {
                Detail_produk::where('kd_produk', $kd_produk)->delete();
            } else {
                Detail_produk::where('kd_produk', $kd_produk)->delete();
                unlink(public_path('foto_produk/'.$d->foto));
            }
        }
        return redirect('/produk')->with('success', 'hapus');
    }

    public function delete($kd_detail_produk)
    {
        $produk = Detail_produk::where('kd_detail_produk', $kd_detail_produk)->first();
        $kd_produk = $produk->kd_produk;
        $detail_produk = collect(\DB::select("SELECT COUNT(kd_produk) as jml FROM detail_produk WHERE kd_produk = '$kd_produk' "))->first();

        if ($detail_produk->jml > 1) {
            Detail_produk::where('kd_detail_produk', $kd_detail_produk)->delete();
            unlink(public_path('foto_produk/'.$produk->foto));
        } else {
            if ($produk->foto == 'nope.jpg') {
                Detail_produk::where('kd_detail_produk', $kd_detail_produk)->delete();
            } else {
                $data = ['foto' => 'nope.jpg'];
                Detail_produk::where('kd_detail_produk', $kd_detail_produk)->update($data);
                unlink(public_path('foto_produk/'.$produk->foto));
            }
        }
        return redirect('/produk/'.$produk->kd_produk)->with('success', 'hapus');
    }

    public function tambahfoto(Request $request, $kd_produk)
    {
        $detail_produk = collect(\DB::select("SELECT COUNT(kd_produk) as jml, foto, kd_detail_produk FROM detail_produk WHERE kd_produk = '$kd_produk' "))->first();

        $nama_foto = time().'.'.$request->foto->extension();

        if ($detail_produk->jml == 1) {
            if ($detail_produk->foto == 'nope.jpg') {
                Detail_produk::where('kd_detail_produk', $detail_produk->kd_detail_produk)->delete();
                $data = [
                    'foto' => $nama_foto,
                    'kd_produk' => $kd_produk
                ];
                $request->foto->move(public_path('foto_produk'), $nama_foto);
            } else {
                $data = [
                    'foto' => $nama_foto,
                    'kd_produk' => $kd_produk
                ];
                $request->foto->move(public_path('foto_produk'), $nama_foto);
            }
        } else {
            $data = [
                'foto' => $nama_foto,
                'kd_produk' => $kd_produk
            ];
            $request->foto->move(public_path('foto_produk'), $nama_foto);
        }
        Detail_produk::insert($data);

        return redirect('/produk/'.$kd_produk)->with('success', 'tambahkan');
    }
}
