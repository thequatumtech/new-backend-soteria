<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDiscountCoupon extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function line_of_business()
    {
        return $this->belongsTo(LineOfBusiness::class,'line_of_business_id','id');
    }
}
