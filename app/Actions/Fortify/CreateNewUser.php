<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Pembeli;
use App\Models\Penjual;
use App\Models\Saldo;
use App\Models\Kurir;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'nama' => ['required'],
            'username' => [
                'required', 
                'string', 
                'max:255', 
                'alphadash',
                Rule::unique(User::class),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules()
        ])->validate();

        if($input['jenis_daftar'] == 2){
            $data_penjual = [
                'nama_toko' => $input['nama_toko'],
                'alamat' => $input['alamat'],
                'no_hp' => $input['no_hp'],
                'lat' => $input['lat'],
                'lng' => $input['lng'],
                'username' => $input['username']
            ];    
            Penjual::insert($data_penjual);
        }else if($input['jenis_daftar'] == 3){
            $data_pembeli = [
                'alamat' => $input['alamat'],
                'no_hp' => $input['no_hp'],
                'lat' => $input['lat'],
                'lng' => $input['lng'],
                'username' => $input['username']
            ];    
            Pembeli::insert($data_pembeli);
        }else if($input['jenis_daftar'] == 4){
            $data_kurir = [
                'lat' => $input['lat'],
                'lng' => $input['lng'],
                'no_hp' => $input['no_hp'],
                'username' => $input['username']
            ];    
            Kurir::insert($data_kurir);
        }

        $data_saldo = [
            'username' => $input['username'],
            'jumlah' => 0
        ];
        Saldo::insert($data_saldo);

        return User::create([
            'nama' => $input['nama'],
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'level' =>  $input['jenis_daftar']
        ]);


    }
}
