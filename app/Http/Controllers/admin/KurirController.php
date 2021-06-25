<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\User;
use Illuminate\Http\Request;

class KurirController extends Controller
{
    public function index(){
        $data['kurir'] = Kurir::all();
        $data['user'] = User::all();
        return view('admin.kurir', $data);
    }
}
