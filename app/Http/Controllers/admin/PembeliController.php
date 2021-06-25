<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PembeliController extends Controller
{
    public function index(){
        $data['pembeli'] = Pembeli::all();
        $data['user'] = User::all();
        return view('admin.pembeli', $data);
    }
    public function download(Request $request){
        $dari = $request->dari;
        $sampai = $request->sampai;
        $data['pembeli'] = DB::select("SELECT users.nama, users.created_at, pembeli.* FROM users, pembeli WHERE (users.created_at BETWEEN '$dari' AND '$sampai') AND users.username = pembeli.username COLLATE utf8mb4_unicode_ci");
        // return view('admin.pembeli_download', $data);
        $pdf = PDF::loadView('admin.pembeli_download',$data);
        return $pdf->download('pembeli.pdf');
    }
}
