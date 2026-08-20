<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCoupon extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function insurance_company()
    {
        return $this->belongsTo(InsuranceCompany::class,'insurance_company_id','id');
    }

    public function line_of_business()
    {
        return $this->belongsTo(LineOfBusiness::class,'line_of_business_id','id');
    }
}
