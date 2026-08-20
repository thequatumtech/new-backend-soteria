<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOfficeInsurance extends Model
{
    use HasFactory;

    protected $table = 'client_office_insurances';
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
        'place_residence',
        'company_name',
        'company_register_national_id',
        'company_register_id',
        'office_type',
        'no_of_floor',
        'no_of_room',
        'size_of_apartment',
        'age_of_apartment',
        'no_of_residence',
        'office_category',
        'block_no',
        'plate_no',
        'plot_no',
        'effective_date',
        'expiry_date',
        'no_of_employee',
        'country_id',
        'city_id',
        'district_id',
        'street_name',
        'building_no',
        'office_no',
        'company_telephone',
        'company_owner_name',
        'company_owner_telephone',
        'partner_company_status',
        'authorized_insurance_police_status',
        'auth_company_register_status',
        'provious_insurance_policy',
        'insurance_declined_issue_status',
        'claims_5_year_status',
        'protection_system',
        'insurance_limit',
        'insurance_plan',
        'inception_date',
        'insurance_expiry_date',
        'rent_contract_documents',
        'property_photo_documents',
        'contents_documents',
        'policy_issuer_documents',
        'company_owner_documents',
        'career_municipality_license_documents',
        'owner_id_documents',
        'practice_documents',
        'company_tax_certi_documents',
        'plan_id',
        'payment_status'
    ];
    public static function getOfficeInsuranceDetails($id)
    {
        $plan = ClientOfficeInsurance::select('client_office_insurances.*', 'office_plans.plan_name', 'office_plans.insurance_policy_text','office_plans.net_premium',
        'office_plans.limit',
        'office_plans.fees',
        'office_plans.stamps',
        'office_plans.sales_tax',
        'office_plans.gross_premium',
        'office_plans.commission_percentage',
        'insurance_companies.company_name','insurance_company_documents.company_stamp','insurance_company_documents.logo','insurance_company_documents.authorized_signature','insurance_companies.id as insurance_company_id')
            ->leftJoin('office_plans', 'client_office_insurances.plan_id', '=', 'office_plans.id')
            ->leftJoin('insurance_companies', 'office_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            ->where('client_office_insurances.id', $id)
            ->first();
        return $plan;
    }
}
