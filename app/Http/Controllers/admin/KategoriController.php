<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Session;

class KategoriController extends Controller
{
    public function index(){
        $kategori = Kategori::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create(){
        return view('admin.kategori.tambah');
    }

    public function store(Request $request){
        $kategori = new Kategori;
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();
        return redirect('/kategori')->with('success','tambahkan');
    }


    public function edit($kd_kategori){
        $kategori = Kategori::where('kd_kategori', $kd_kategori)->first();
        return view('admin.kategori.ubah', compact('kategori'));
    }

    public function update(Request $request, $kd_kategori){

        $data = [
            'nama_kategori' => $request->nama_kategori
        ];

        Kategori::where('kd_kategori', $kd_kategori)->update($data);

    
        return redirect('/kategori')->with('success', 'ubah');
    }

    public function destroy($kd_kategori){
        Kategori::where('kd_kategori', $kd_kategori)->delete();
        return redirect('/kategori')->with('success','hapus');
    }
}
