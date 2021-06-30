<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PendapatanController extends Controller
{
    public function index(){
        $data['pendapatan'] = DB::select("SELECT SUM(total)*0.02 as total_pendapatan, SUM(total) as total_transaksi ,pembelian.* FROM `pembelian` GROUP BY no_nota");
        return view('admin.pendapatan', $data);
    }

    public function download(Request $request){
        $dari = $request->dari;
        $sampai = $request->sampai;
        $data['pendapatan'] = DB::select("SELECT SUM(total)*0.02 as total_pendapatan, SUM(total) as total_transaksi ,pembelian.* FROM `pembelian` GROUP BY no_nota");
        // return view('admin.pembeli_download', $data);
        $pdf = PDF::loadView('admin.pendapatan_download', $data);
        return $pdf->download('pendapatan.pdf');
    }
}
