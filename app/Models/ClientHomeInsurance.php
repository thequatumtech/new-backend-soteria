<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InsurancePlanModels\HomePlan;
use App\Models\InsuranceCompany;

class ClientHomeInsurance extends Model
{
    use HasFactory;

    protected $table = 'client_home_insurances';
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
        'place_of_residence',
        'home_type',
        'no_of_floor',
        'no_of_room',
        'size_of_apartment',
        'no_of_residence',
        'home_category',
        'block_no',
        'plate_no',
        'plot_no',
        'country_id',
        'city_id',
        'district_id',
        'street_name',
        'building_no',
        'company_name',
        'city_id_2',
        'position',
        'work_nature',
        'previous_policy',
        'company_declined_to_issue',
        'claims_accidents_past',
        'protection_system',
        'insurance_limit',
        'plan_id',
        'effective_date',
        'expiry_date',
        'rent_contract',
        'property_document',
        'content_document',
        'payment_status',
        'home_age',
    ];

    public static function getHomeInsuranceDetails($id)
    {
        $plan = ClientHomeInsurance::select(
            'client_home_insurances.*',
            'home_plans.plan_name',
            'home_plans.insurance_policy_text',
            'home_plans.net_premium',
            'home_plans.limit',
            'home_plans.fees',
            'home_plans.stamps',
            'home_plans.sales_tax',
            'home_plans.cbj',
            'home_plans.sales_tax_cbj',
            'home_plans.gross_premium',
            'home_plans.commission_percentage',
            'insurance_companies.company_name',
            'insurance_company_documents.company_stamp',
            'insurance_company_documents.logo',
            'insurance_company_documents.letterhead',
            'insurance_company_documents.authorized_signature',
            'insurance_companies.id as insurance_company_id'
        )
            ->leftJoin('home_plans', 'client_home_insurances.plan_id', '=', 'home_plans.id')
            ->leftJoin('insurance_companies', function ($join) {
                $join->on('home_plans.insurance_company_id', '=', 'insurance_companies.id')
                    ->whereNull('insurance_companies.deleted_at');
            })
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            ->where('client_home_insurances.id', $id)
            ->first();
        return $plan;
    }
}
