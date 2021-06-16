<?php

namespace App\Http\Controllers;

use App\Models\Topup;
use App\Models\User;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function index(Request $request){
        $user = $request->user();

        $username = $user->username;

        $user = User::where('username', $username)->first();
        $nominal = $request->nominal;
        $topup = Topup::where(['username' => $username, 'payment_status' => 'pending'])->first();

        if (!empty($topup)){
            return redirect('/')->with('error','selesaikan dulu transaksi Topup anda!');
        }

        // $this->_generatePaymentToken($user, $nominal);
        return Redirect($this->_generatePaymentToken($user, $nominal));
    }

    private function _generatePaymentToken($user, $nominal){
        $this->initPaymentGateway();

        $shipping_address = [
            'first_name' => $user->nama,
            'last_name' => '',
            'email' => $user->email
        ];
        $customerDetails = [
            'first_name' => $user->nama,
            'last_name' => '',
            'email' => $user->email,
            'shipping_address' => $shipping_address
        ];

        $params = [
            'enabled_payments' => \App\Models\Topup::PAYMENT_CHANNELS,
            'transaction_details' => [
                'order_id' => $user->id.'-'.time(),
                'gross_amount' => $nominal
            ],
            'customer_details' => $customerDetails,
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit' => \App\Models\Topup::EXPIRY_UNIT,
                'duration' => \App\Models\Topup::EXPIRY_DURATION
            ]
        ];

        $snap = \Midtrans\Snap::createTransaction($params);
        return $snap->redirect_url;
    }
}
