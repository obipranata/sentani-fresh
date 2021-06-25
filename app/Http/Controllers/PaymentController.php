<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Saldo;
use Illuminate\Http\Request;
use App\Models\Topup;
use Symfony\Component\Console\Input\Input;

class PaymentController extends Controller
{
    public function notification(Request $request){
        $payload = $request->get_Content();
        $notification = json_decode($payload);

        // $paymentParams = [
        //     'order_id' => 'TOPUP-03',
        //     'number' => uniqid(),
        //     'amount' => 100000.00,
        //     'method' => 'midtrans',
        //     'payloads' => $payload
        // ];

        $validSignatureKey = hash("sha512", $notification->order_id.$notification->status_code.$notification->gross_amount.env('SB-Mid-server-r_F6rNkCjIjTCy-cJhqGiFLp'));

        if($notification->signature_key != $validSignatureKey){
            return response(['message' => 'Invalid signarute'], 403);
        }

        $this->initPaymentGateway();
        $status_code = null;

        $paymentNotification = new \Midtrans\Notification();

        if($notification->transaction_status == 'settlement'){
            return response(['message' => 'Topup sudah dibayar sebelum nya', 422] );
        }

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $order_id = $notification->order_id;
        $fraud = $notification->fraud_status;

        $vaNumber = null;
        $vendorName = null;
        if(!empty($paymentNotification->va_numbers[0])){
            $vaNumber = $paymentNotification->va_numbers[0]->va_number;
            $vendorName = $paymentNotification->va_numbers[0]->bank;
        }

        $paymentStatus = null;
        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card'){
                if($fraud == 'challenge'){
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    $paymentStatus = Topup::CHALLENGE;
                }else {
                    // TODO set payment status in merchant's database to 'Success'
                    $paymentStatus = Topup::SUCCESS;
                }
            }
        
        }else if ($transaction == 'settlement'){
            // TODO set payment status in merchant's database to 'Settlement'
            $paymentStatus = Topup::SETTLEMENT;
        }else if($transaction == 'pending'){
            // TODO set payment status in merchant's database to 'Pending'
            $paymentStatus = Topup::PENDING;
        }else if ($transaction == 'deny') {
            // TODO set payment status in merchant's database to 'Denied'
            $paymentStatus = Topup::DENY;
        }else if ($transaction == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $paymentStatus = Topup::EXPIRE;
        }else if ($transaction == 'cancel') {
            // TODO set payment status in merchant's database to 'Denied'
            $paymentStatus = Topup::CANCEL;
        } 

        $message = 'Status pembayaran : '.$paymentStatus;

        $response = [
            'code' =>  200,
            'message' => $message
        ];

        return response($response,200);
    }

    public function completed(Request $request){
        $no_topup = $request->order_id;
        $user = $request->user();
        $username = $user->username;
        // dd($username);

        $cek_topup = Topup::where('no_topup', $no_topup)->first();
        $saldo = Saldo::where('username', $username)->first();

        if ($cek_topup){
            return abort(404);
        }

        $this->initPaymentGateway();
        $paymentInfo = \Midtrans\Transaction::status($no_topup);

        $transaction = $paymentInfo->transaction_status;
        $type = $paymentInfo->payment_type;
        $fraud = $paymentInfo->fraud_status;

        $payment_date = null;
        $paymentStatus = null;

        $data_topup = [
            'username' => $username,
            'no_topup' => $no_topup,
            'nominal' => $paymentInfo->gross_amount,
            'topup_date' => $paymentInfo->transaction_time,
            'payment_date' => $payment_date,
            'payment_status' => $paymentStatus
        ];

        Topup::insert($data_topup);

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card'){
                if($fraud == 'challenge'){
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    $paymentStatus = Topup::CHALLENGE;
                }else {
                    // TODO set payment status in merchant's database to 'Success'
                    $paymentStatus = Topup::SUCCESS;
                    Topup::where('no_topup', $no_topup)->update(['payment_date' => $paymentInfo->settlement_time, 'payment_status' => $paymentStatus]);
                    Saldo::where('username', $username)->update(['jumlah' => $paymentInfo->gross_amount + $saldo->jumlah]);
                }
            }
        
        }else if ($transaction == 'settlement'){
            // TODO set payment status in merchant's database to 'Settlement'
            $paymentStatus = Topup::SETTLEMENT;
            Topup::where('no_topup', $no_topup)->update(['payment_date' => $paymentInfo->settlement_time, 'payment_status' => $paymentStatus]);
            Saldo::where('username', $username)->update(['jumlah' => $paymentInfo->gross_amount + $saldo->jumlah]);
        }else if($transaction == 'pending'){
            // TODO set payment status in merchant's database to 'Pending'
            $paymentStatus = Topup::PENDING;
            Topup::where('no_topup', $no_topup)->update(['payment_status' => $paymentStatus]);
        }else if ($transaction == 'deny') {
            // TODO set payment status in merchant's database to 'Denied'
            $paymentStatus = Topup::DENY;
            Topup::where('no_topup', $no_topup)->update(['payment_status' => $paymentStatus]);
        }else if ($transaction == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $paymentStatus = Topup::EXPIRE;
            Topup::where('no_topup', $no_topup)->update(['payment_status' => $paymentStatus]);
        }else if ($transaction == 'cancel') {
            // TODO set payment status in merchant's database to 'Denied'
            $paymentStatus = Topup::CANCEL;
            Topup::where('no_topup', $no_topup)->update(['payment_status' => $paymentStatus]);
        } 

        $message = 'Status pembayaran : '.$paymentStatus;

        $response = [
            'code' =>  200,
            'message' => $message
        ];

        // return response($response,200);
        return redirect('/home')->with('success', $message);
    } 

    public function failed(){
        $message = 'Status pembayaran : failed';

        $response = [
            'code' =>  403,
            'message' => $message
        ];

        return response($response,403);
    }

    public function unfinish(){
        $message = 'Status pembayaran : tidak selesai';

        $response = [
            'code' =>  403,
            'message' => $message
        ];

        return response($response,403);
    }
}
