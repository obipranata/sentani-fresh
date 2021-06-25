<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Penjual;
use App\Models\User;
use Illuminate\Http\Request;

class PenjualController extends Controller
{
    public function index(){
        $data['penjual'] = Penjual::all();
        $data['user'] = User::all();
        return view('admin.penjual', $data);
    }
}
