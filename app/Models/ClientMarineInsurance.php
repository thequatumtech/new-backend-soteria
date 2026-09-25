<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMarineInsurance extends Model
{
    use HasFactory;

    protected $table = 'client_marine_insurances';
    protected $fillable = [
        'client_id',
        'police_no',
        'company_status',
        'first_name',
        'last_name',
        'third_name',
        'family_name',
        'nationality',
        'nationality_no',
        'id_residence_no',
        'birth_date',
        'gender',
        'company_name',
        'company_reg_notional_id',
        'company_reg_no',
        'company_country_id',
        'company_city_id',
        'company_district_id',
        'company_street_name',
        'company_building_no',
        'company_office_no',
        'company_contact',
        'owner_first_name',
        'owner_last_name',
        'owner_third_name',
        'owner_family_name',
        'company_owner_contact',
        'company_partner_status',
        'company_authorized_status',
        'authorized_positions',
        'company_register_status',
        'register_document',
        'vayage_from_id',
        'through_country_id',
        'destination_country_id',
        'type_of_transportation',
        'type_of_cover',
        'item_category_id',
        'item_subcategory_id',
        'insurance_limit',
        'bill_no',
        'effective_date',
        'expiry_date',
        'insured_items',
        'existing_policy_status',
        'existing_policy_desc',
        'declined_insurance_status',
        'declined_insurance_desc',
        'claims_accident_status',
        'claims_accident_desc',
        'billing_of_landing_doc',
        'copy_of_invoice_doc',
        'insured_id_doc',
        'policy_issuer_doc',
        'company_reg_owner_doc',
        'career_municipality_license_doc',
        'company_tax_certificate_doc',
        'practice_certificate_doc',
        'plan_id',
        'trans_shipped_third_country',
        'dangerous_activities',
        'payment_status'
    ];
    public static function getMarineInsuranceDetails($id)
    {
        $plan = ClientMarineInsurance::select('client_marine_insurances.*', 'marine_plans.plan_name', 'marine_plans.insurance_policy_text','marine_plans.net_premium',
        'marine_plans.limit',
        'marine_plans.fees',
        'marine_plans.stamps',
        'marine_plans.sales_tax',
        'marine_plans.cbj',
        'marine_plans.sales_tax_cbj',
        'marine_plans.gross_premium',
        'marine_plans.commission_percentage',
        'insurance_companies.company_name','insurance_company_documents.company_stamp','insurance_company_documents.logo',
            'insurance_company_documents.letterhead','insurance_company_documents.authorized_signature','insurance_companies.id as insurance_company_id')
            ->leftJoin('marine_plans', 'client_marine_insurances.plan_id', '=', 'marine_plans.id')
            ->leftJoin('insurance_companies', 'marine_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            ->where('client_marine_insurances.id', $id)
            ->first();
        return $plan;
    }
}
