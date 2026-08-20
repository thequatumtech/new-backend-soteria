<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyOtp extends Model
{
    use HasFactory;

    protected $table="verify_otps";
    
    public static function storeData($data)
    {
        $otp=new VerifyOtp();
        $otp->client_id=$data->id;
        $otp->phone=$data->mobile_no;
        $otp->otp=$data->otp;
        $otp->date = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $otp->save();
        return $otp;
    }
}
