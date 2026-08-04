<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LifePlan extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function insurance_company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id', 'id');
    }

    public function line_of_business()
    {
        return $this->belongsTo(LineOfBusiness::class, 'line_of_business_id', 'id');
    }

    public function policy_covers()
    {
        return $this->hasMany(LifePlanPolicyCover::class, 'life_plan_id', 'id');
    }

    public function pricing_schedule()
    {
        return $this->hasMany(LifePlanPricingSchedule::class, 'life_plan_id', 'id');
    }
    // public static function getLifeInsurancePlanLimit()
    // {
    //     // $plan = LifePlan::selectRaw('CAST(`limit` AS DECIMAL(10, 2)) as normalized_limit')->groupBy('normalized_limit')->get();
    //     $plan = LifePlan::selectRaw('CAST(`limit` AS DECIMAL(10, 2)) as normalized_limit')->get();
    //     return $plan;
    // }
    public static function getLifeInsurancePlanLimit()
    {
        // $plan = LifePlan::selectRaw('CAST(`limit` AS DECIMAL(10, 2)) as normalized_limit')->groupBy('normalized_limit')->get();
        $plan = LifePlan::select('limit')->groupBy('limit')->get();
        return $plan;
    }
    public static function getLifeInsurancePlanByLimit($limit)
    {
        $plan = LifePlan::select('plan_name')->where('limit', $limit)->groupBy('plan_name')->get();
        return $plan;
    }
    public static function getLifeInsurancePeriodByLimit($limit)
    {
        $plan = LifePlan::select('plan_name')
            ->where('limit', $limit)->groupBy('plan_name')->get();
        return $plan;
    }
}
