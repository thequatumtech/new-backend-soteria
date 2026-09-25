<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InOutPatientPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'in_out_patient_plans';

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
        return $this->hasMany(InOutPatientPlanPolicyCover::class, 'in_out_patient_plan_id', 'id');
    }

    public function additional_benefits()
    {
        return $this->hasMany(InOutPatientPlanAdditionalBenefit::class, 'in_out_patient_plan_id', 'id');
    }

    public function male_pricing_schedule()
    {
        return $this->hasMany(InOutPatientPlanPricingSchedule::class, 'in_out_patient_plan_id', 'id')
            ->where('gender', 1);
    }

    public function female_pricing_schedule()
    {
        return $this->hasMany(InOutPatientPlanPricingSchedule::class, 'in_out_patient_plan_id', 'id')
            ->where('gender', 2);
    }

    public static function getInOutPatientPlanLimit()
    {
        return self::select('limit')->groupBy('limit')->get();
    }

    public static function getInOutPatientInsurancePlanByLimit($limit)
    {
        return self::select('plan_name', 'policy_period')
            ->where('limit', $limit)
            ->groupBy('plan_name', 'policy_period')
            ->get();
    }
       public function pricing_schedule()
    {
        return $this->hasMany(
            InOutPatientPlanPricingSchedule::class,
            'in_out_patient_plan_id',
            'id'
        );
    }
}
