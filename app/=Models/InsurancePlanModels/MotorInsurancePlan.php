<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorInsurancePlan extends Model
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

}
