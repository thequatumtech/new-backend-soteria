<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalAccidentPlan extends Model
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
        return $this->hasMany(PersonalAccidentPlanPolicyCover::class, 'personal_accident_plan_id', 'id');
    }

    public function pricing_schedule()
    {
        return $this->hasMany(PersonalAccidentPlanPricingSchedule::class, 'personal_accident_plan_id', 'id');
    }
    public static function getPersonalAccidentInsurancePlanLimit()
    {
        $plan = PersonalAccidentPlan::select('limit')->groupBy('limit')->get();
        return $plan;
    } 
    public static function getPersonalAccidentInsurancePlanByLimit($limit)
    {
        $plan = PersonalAccidentPlan::select('plan_name')->where('limit',$limit)->groupBy('plan_name')->get();
        return $plan;
    }
    public static function getPersonalAccidentInsurancePeriodsPlanByLimit($limit)
    {
        $plan = PersonalAccidentPlan::selectRaw('personal_accident_plans.id,insurance_periods.name')
        ->leftJoin('insurance_periods','personal_accident_plans.insurance_period_id','insurance_periods.id')
        ->where('personal_accident_plans.limit',$limit)->get();
        return $plan;
    }
}
