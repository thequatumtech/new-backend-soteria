<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DentalPlan extends Model
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

    public function policy_covers()
    {
        return $this->hasMany(DentalPlanPolicyCover::class, 'dental_plan_id', 'id');
    }
    public static function getDentalPlanLimit()
    {
        $plan = DentalPlan::select('limit')->groupBy('limit')->get();
        return $plan;
    }
    public static function getDentalInsurancePlanByLimit($limit)
    {
        $plan = DentalPlan::select('plan_name')->where('limit',$limit)->groupBy('plan_name')->get();
        return $plan;
    }
}
