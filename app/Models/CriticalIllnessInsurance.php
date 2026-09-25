<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use App\Models\Client;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\CriticalIllnessPlan;

class CriticalIllnessInsurance extends Model
{
    use HasFactory;

    protected $table = 'critical_illness_insurances';
    protected $fillable = [
        'client_id',
        'police_no',
        'first_name',
        'last_name',
        'third_name',
        'family_name',
        'nationality',
        'nationality_no',
        'id_residence_no',
        'birth_date',
        'gender',
        'beneficiary_first_name',
        'beneficiary_last_name',
        'beneficiary_third_name',
        'marital_status',
        'place_residence',
        'occupancy_work',
        'city_id',
        'district_id',
        'street_name',
        'building_no',
        'company_name',
        'position',
        'work_nature',
        'company_city_id',
        'company_district_id',
        'company_street_name',
        'company_building_no',
        'company_contact',
        'height',
        'wight',
        'chronic_diseases_id',
        'previous_operation',
        'operation_details',
        'previous_insurance_policy',
        'previous_insurance_policy_details',
        'insurance_amount',
        'insurance_plan',
        'inception_date',
        'expiry_date',
        'passport_id_documents',
        'insured_documents',
        'plan_id',
        'payment_status'
    ];

    public static function getCriticalIllnessInsuranceDetails($id)
    {
        // Find the birth date of the client
        $client = CriticalIllnessInsurance::find($id);

        if (!$client) {
            return null; // Handle case where the client is not found
        }

        $birthdate = new DateTime($client->birth_date);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;

        // Fetch the criticalIllness insurance details
        $plan = CriticalIllnessInsurance::select(
            'critical_illness_insurances.*',
            'critical_illness_plans.plan_name',
            'critical_illness_plans.fees',
            'critical_illness_plans.stamps',
            'critical_illness_plans.sales_tax',
            'critical_illness_plans.cbj',
            'critical_illness_plans.sales_tax_cbj',
            'critical_illness_plans.commission_percentage',
            'critical_illness_plans.insurance_policy_text',
            'insurance_companies.company_name',
            'insurance_company_documents.company_stamp',
            'insurance_company_documents.logo',
              'insurance_company_documents.letterhead',
            'insurance_company_documents.letterhead',
            'insurance_company_documents.authorized_signature',
            'insurance_companies.id as insurance_company_id'
        )
            ->leftJoin('critical_illness_plans', 'critical_illness_insurances.plan_id', '=', 'critical_illness_plans.id')
            ->leftJoin('insurance_companies', 'critical_illness_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            // ->leftJoin('insurance_periods', 'critical_illness_insurances.insurance_period', '=', 'insurance_periods.id')
            ->where('critical_illness_insurances.id', $id)
            ->first();

        if ($plan) {
            // Dynamically set the net_premium based on insurance_periods.name
            $insurancePeriodVal = $plan->periods_val; // Assuming this returns a value like '2'
            $netPremiumsField = 'year_' . $insurancePeriodVal;

            // Set net_premium based on the dynamically generated field
            $plan->net_premium = $plan->$netPremiumsField ?? 0;
            $plan->gross_premium = $plan->$netPremiumsField + $plan->fees + $plan->stamps + $plan->sales_tax ?? 0;
        }
        return $plan;
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function insurance_company()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id', 'id');
    }

    public function critical_illness_plan()
    {
        return $this->belongsTo(CriticalIllnessPlan::class, 'plan_id');
    }

    // NEW → This is what your controller/PDF expects
    public function purchase_policy()
    {
        return $this->hasOne(PurchasePolicy::class, 'policy_id', 'id');
    }
}
