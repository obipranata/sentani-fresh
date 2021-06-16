<?php

namespace App\Http\Controllers\kurir;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use Illuminate\Http\Request;

class KurirController extends Controller
{
    public function updatelokasi(Request $request){
        $user = $request->user();

        $username = $user->username;

        $data_lokasi = [
            'lat' => $_POST['lat'],
            'lng' => $_POST['lng']
        ];
        Kurir::where('username', $username)->update($data_lokasi);
    }
}
