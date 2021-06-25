<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Penjual;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PenjualController extends Controller
{
    public function index(){
        $data['penjual'] = Penjual::all();
        $data['user'] = User::all();
        return view('admin.penjual', $data);
    }

    public function download(Request $request){
        $dari = $request->dari;
        $sampai = $request->sampai;
        $data['penjual'] = DB::select("SELECT users.nama, users.created_at, penjual.* FROM users, penjual WHERE (users.created_at BETWEEN '$dari' AND '$sampai') AND users.username = penjual.username COLLATE utf8mb4_unicode_ci");
        // return view('admin.pembeli_download', $data);
        $pdf = PDF::loadView('admin.penjual_download',$data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('penjual.pdf');
    }
}
