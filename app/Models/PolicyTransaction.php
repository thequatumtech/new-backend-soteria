<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyTransaction extends Model
{
    use HasFactory;
    protected $table = 'policy_transactions';
    protected $fillable = [
        'date',
        'client_id',
        'transaction_id',
        'purchase_id',
        'amount',
        'payment_type',
        'payment_status',
        'full_responce'
    ];
    public static function storePolicyTransaction($request)
    {
        $policy=new PolicyTransaction();
        $policy->date=date('m-d-Y H:i:s');
        $policy->client_id=$request->user_id;
        $policy->transaction_id=$request->transaction_id;
        $policy->client_coupon_id=$request->client_coupon_id;
        $policy->purchase_id=$request->purchase_id;
        $policy->amount=$request->amount;
        $policy->payment_type=$request->payment_type;
        $policy->payment_status=$request->payment_status;
        $policy->full_responce=$request->full_responce;
        $policy->save();
        return $policy;
    }
}
