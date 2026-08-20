<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
class ClientDentalsInsurance extends Model
{
    use HasFactory;

    protected $table = 'client_dentals_insurances';
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
        'insurance_limit',
        'inception_date',
        'expiry_date',
        'documents',
        'plan_id',
        'payment_status',
    ];
    public static function getDentalsInsuranceDetails($id)
    {
        // Find the birth date of the client
        $client = ClientDentalsInsurance::find($id);
    
        if (!$client) {
            return null; // Handle case where the client is not found
        }
    
        $birthdate = new DateTime($client->birth_date);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
    
        // Fetch the criticalIllness insurance details
        $plan = ClientDentalsInsurance::select(
                'client_dentals_insurances.*', 
                'dental_plans.plan_name', 
                'dental_plans.fees',
                'dental_plans.stamps',
                'dental_plans.sales_tax',
                'dental_plans.commission_percentage',
                'dental_plans.insurance_policy_text',
                'insurance_companies.company_name',
                'insurance_company_documents.company_stamp',
                'insurance_company_documents.logo',
                'insurance_company_documents.authorized_signature',
                'insurance_companies.id as insurance_company_id'
            )
            ->leftJoin('dental_plans', 'client_dentals_insurances.plan_id', '=', 'dental_plans.id')
            ->leftJoin('insurance_companies', 'dental_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            // ->leftJoin('insurance_periods', 'client_dentals_insurances.insurance_period', '=', 'insurance_periods.id')
            ->where('client_dentals_insurances.id', $id)
            ->first();
    
        if ($plan) {
            // Dynamically set the net_premium based on insurance_periods.name
            $insurancePeriodVal = $plan->periods_val; // Assuming this returns a value like '2'
            $netPremiumsField = 'year_' . $insurancePeriodVal;
    
            // Set net_premium based on the dynamically generated field
            $plan->net_premium = $plan->$netPremiumsField ?? 0;
            $plan->gross_premium = $plan->$netPremiumsField+$plan->fees+$plan->stamps+$plan->sales_tax ?? 0;
        }
        return $plan;
    }
}
