<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InPatientPlan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'in_patient_plans';
    protected $fillable = [
        'plan_name',
        'insurance_company_id',
        'line_of_business_id',
        'limit',
        'medical_network_ids',
    ];

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
        return $this->hasMany(InPatientPlanPolicyCover::class, 'in_patient_plan_id', 'id');
    }

    public function additional_benefits()
    {
        return $this->hasMany(InPatientPlanAdditionalBenefit::class, 'in_patient_plan_id', 'id');
    }

    public function male_pricing_schedule()
    {
        return $this->hasMany(InPatientPlanPricingSchedule::class, 'in_patient_plan_id', 'id')->where('gender', 1);
    }

    public function female_pricing_schedule()
    {
        return $this->hasMany(InPatientPlanPricingSchedule::class, 'in_patient_plan_id', 'id')->where('gender', 2);
    }
    public static function getInPatientPlanLimit()
    {
        $plan = InPatientPlan::select('limit')->groupBy('limit')->get();
        return $plan;
    }
    public static function getInPatientInsurancePlanByLimit($limit)
    {
        $plan = InPatientPlan::select('plan_name', 'policy_period')
            ->where('limit', $limit)
            ->groupBy('plan_name', 'policy_period')
            ->get();
        return $plan;
    }
       public function pricing_schedule()
    {
        return $this->hasMany(
            InPatientPlanPricingSchedule::class,
            'in_patient_plan_id',
            'id'
        );
    }
}
