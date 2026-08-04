<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Country;
use App\Models\Currency;
use PDF;
use App\Models\PurchasePolicy;
use App\Models\InsurancePlanModels\MotorInsurancePlanComprehensiveCoverFee;
use App\Models\InsurancePlanModels\MotorInsurancePlanCondition;
use App\Models\InsurancePlanModels\MotorInsurancePlanNetPremiumIncreasePercentage;
use App\Models\InsurancePlanModels\MotorInsurancePlanNoClaimDiscount;
use App\Models\InsurancePlanModels\MotorInsurancePlanPolicyCover;
use App\Models\InsurancePlanModels\MotorInsurancePlanAdditionalBenefit;
use App\Models\InsurancePlanModels\MotorInsurancePlansCommissionScheduleCalculation;

class MotorInsurancePlan extends Model
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

    public function fees()
    {
        return $this->hasOne(MotorInsurancePlanComprehensiveCoverFee::class, 'motor_insurance_plan_id', 'id');
    }

    public function plan_conditions()
    {
        return $this->hasMany(MotorInsurancePlanCondition::class, 'motor_insurance_plan_id', 'id');
    }

    public function net_premium_increase_percentages()
    {
        return $this->hasMany(MotorInsurancePlanNetPremiumIncreasePercentage::class, 'motor_insurance_plan_id', 'id');
    }

    public function no_claim_discounts()
    {
        return $this->hasMany(MotorInsurancePlanNoClaimDiscount::class, 'motor_insurance_plan_id', 'id');
    }

    public function policy_covers()
    {
        return $this->hasMany(MotorInsurancePlanPolicyCover::class, 'motor_insurance_plan_id', 'id');
    }

    public function additional_benefits()
    {
        return $this->hasMany(MotorInsurancePlanAdditionalBenefit::class, 'motor_insurance_plan_id', 'id');
    }
    public static function getMotorPlanLimit()
    {
        $plan = DB::table('motor_insurance_plan_policy_covers as c')
            ->join('motor_insurance_plans as m', 'm.id', '=', 'c.motor_insurance_plan_id')
            ->whereNull('m.deleted_at')
            ->whereNull('c.deleted_at')
            ->select('c.cover_limit as limit')
            ->groupBy('c.cover_limit')
            ->orderBy('c.cover_limit', 'asc')
            ->get();

        return $plan;
    }

    public static function getMotorInsurancePlanByLimit($limit)
    {
        return DB::table('motor_insurance_plans as m')
            ->join('motor_insurance_plan_policy_covers as c', 'm.id', '=', 'c.motor_insurance_plan_id')
            ->whereNull('m.deleted_at')
            ->whereNull('c.deleted_at')
            ->where('c.cover_limit', $limit)
            ->select(['m.plan_name', 'm.policy_period'])
            ->groupBy('m.plan_name', 'm.policy_period')
            ->orderBy('m.plan_name', 'asc')
            ->get();
    }
    public function commissionSchedules()
    {
        return $this->hasMany(
            MotorInsurancePlansCommissionScheduleCalculation::class,
            'motor_insurance_plan_id' // change if needed
        );
    }
    
}
