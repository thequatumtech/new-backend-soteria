<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
class ClientPersonalAccidentInsurance extends Model
{
    use HasFactory;

    protected $table = 'client_personal_accident_insurances';
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
        'company_name',
        'position',
        'work_nature',
        'city_id',
        'district_id',
        'street_name',
        'building_no',
        'company_contact',
        'company_city_id',
        'inception_date',
        'inception_period',
        'occupany_type_work',
        'photo_documents_1',
        'photo_documents_2',
        'photo_documents_3',
        'photo_documents_4',
        'dangerous_field',
        'plan_id',
        'payment_status'
    ];
    
    public static function getClientPersonalAccidentInsuranceDetails($id)
    {
        // Find the birth date of the client
        $client = ClientPersonalAccidentInsurance::find($id);
    
        if (!$client) {
            return null; // Handle case where the client is not found
        }
    
        $birthdate = new DateTime($client->birth_date);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
    
        // Fetch the criticalIllness insurance details
        $plan = ClientPersonalAccidentInsurance::select(
                'client_personal_accident_insurances.*', 
                'personal_accident_plans.plan_name', 
                'personal_accident_plans.fees',
                'personal_accident_plans.stamps',
                'personal_accident_plans.sales_tax',
                'personal_accident_plans.commission_percentage',
                'personal_accident_plans.insurance_policy_text',
                'insurance_companies.company_name',
                'insurance_company_documents.company_stamp',
                'insurance_company_documents.logo',
                'insurance_company_documents.authorized_signature',
                'insurance_companies.id as insurance_company_id'
            )
            ->leftJoin('personal_accident_plans', 'client_personal_accident_insurances.plan_id', '=', 'personal_accident_plans.id')
            ->leftJoin('insurance_companies', 'personal_accident_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            // ->leftJoin('insurance_periods', 'client_personal_accident_insurances.insurance_period', '=', 'insurance_periods.id')
            ->where('client_personal_accident_insurances.id', $id)
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
