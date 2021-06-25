<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class KurirController extends Controller
{
    public function index(){
        $data['kurir'] = Kurir::all();
        $data['user'] = User::all();
        return view('admin.kurir', $data);
    }

    public function download(Request $request){
        $dari = $request->dari;
        $sampai = $request->sampai;
        $data['kurir'] = DB::select("SELECT users.nama, users.created_at, kurir.* FROM users, kurir WHERE (users.created_at BETWEEN '$dari' AND '$sampai') AND users.username = kurir.username COLLATE utf8mb4_unicode_ci");
        // return view('admin.pembeli_download', $data);
        $pdf = PDF::loadView('admin.kurir_download',$data);
        return $pdf->download('kurir.pdf');
    }
}
