<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTravelInsurance extends Model
{
    use HasFactory;

    protected $table = 'client_travel_insurances';
    protected $fillable = [
        'client_id',
        'police_no',
        'self_family_status',
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
        'passport_document',
        'departure_from_country_id',
        'destination_country_id',
        'additional_destination_country_id',
        'geographical_area_id',
        'effective_date',
        'travel_days',
        'expiry_date',
        'insurance_limit',
        'plan_id',
        'payment_status',
        'multiple_destination',
        'dangerous_activities'
    ];
    
    public static function getTravelsInsuranceDetails($id)
    {
        $plan = ClientTravelInsurance::select('client_travel_insurances.*', 'travel_plans.plan_name', 'travel_plans.insurance_policy_text','travel_plans.net_premium',
        'travel_plans.limit',
        'travel_plans.fees',
        'travel_plans.stamps',
        'travel_plans.sales_tax',
        'travel_plans.gross_premium',
        'travel_plans.commission_percentage',
        'insurance_companies.company_name','insurance_company_documents.company_stamp','insurance_company_documents.logo','insurance_company_documents.authorized_signature','insurance_companies.id as insurance_company_id')
            ->leftJoin('travel_plans', 'client_travel_insurances.plan_id', '=', 'travel_plans.id')
            ->leftJoin('insurance_companies', 'travel_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            ->where('client_travel_insurances.id', $id)
            ->first();

        return $plan;
    }
}
