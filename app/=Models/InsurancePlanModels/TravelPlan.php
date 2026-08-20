<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelPlan extends Model
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
        return $this->hasMany(TravelPlanPolicyCover::class, 'travel_plan_id', 'id');
    }

    public function pricing_schedule()
    {
        return $this->hasMany(TravelPlanPricingSchedule::class, 'travel_plan_id', 'id');
    }

    public function surcharge_bands()
    {
        return $this->hasMany(TravelPlanSurchargeBand::class, 'travel_plan_id', 'id');
    }
    public static function getTravelPlanLimit()
    {
        $plan = TravelPlan::select('limit')->groupBy('limit')->get();
        return $plan;
    } 
    public static function getTravelInsurancePlanByLimit($limit)
    {
        $plan = TravelPlan::select('plan_name');
        if ($limit != 0) {
            $plan->where('limit', $limit);
        }
        $plan = $plan->groupBy('plan_name')->get();
        return $plan;
    }
}
