<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pembeli;
use App\Models\User;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    public function index(){
        $data['pembeli'] = Pembeli::all();
        $data['user'] = User::all();
        return view('admin.pembeli', $data);
    }
}
