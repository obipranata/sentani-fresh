<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payment';
    public $timestamps = false;

    public const PAYMENT_CHANNELS = ["credit_card", "cimb_clicks",
    "bca_klikbca", "bca_klikpay", "bri_epay", "echannel", "permata_va",
    "bca_va", "bni_va", "bri_va", "other_va", "gopay", "indomaret",
    "danamon_online", "akulaku", "shopeepay"];

    public const EXPIRY_DURATION = 180;
    public const EXPIRY_UNIT = 'minutes';

    public const CHALLENGE = 'challenge';
    public const SUCCESS = 'success';
    public const PENDING = 'pending';
    public const DENY = 'deny';
    public const EXPIRE = 'expire';
    public const CANCEL = 'cancel';

    public const PAYMENTCODE = 'PAY';
}
