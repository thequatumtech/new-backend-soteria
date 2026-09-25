<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPetsInsurance extends Model
{
    use HasFactory;
    protected $table = 'client_pets_insurances';
    protected $fillable = [
        'client_id',
        'police_no',
        'pets_type',
        'first_name',
        'last_name',
        'third_name',
        'family_name',
        'nationality_no',
        'id_residence_no',
        'birth_date',
        'pets_name',
        'pets_dob',
        'gender',
        'type_of_pets',
        'breed',
        'pets_existing_condition_status',
        'pets_existing_condition',
        'insurance_limit',
        'inception_date',
        'expiry_date',
        'plan_id',
        'vaccine_document',
        'pets_picture',
        'pets_passport',
        'personal_picture_documents',
        'pets_permit',
        'payment_status',
    ];

    public static function getPetsInsuranceDetails($id)
    {
        $plan = ClientPetsInsurance::select(
            'client_pets_insurances.*',
            'pet_plans.plan_name',
            'pet_plans.insurance_policy_text',
            'pet_plans.net_premium',
            'pet_plans.limit',
            'pet_plans.fees',
            'pet_plans.stamps',
            'pet_plans.sales_tax',
            'pet_plans.cbj',
            'pet_plans.sales_tax_cbj',
            'pet_plans.gross_premium',
            'pet_plans.commission_percentage',
            'pet_plans.policy_period',
            'insurance_companies.company_name',
            'insurance_company_documents.company_stamp',
            'insurance_company_documents.logo',
              'insurance_company_documents.letterhead',
            'insurance_company_documents.authorized_signature',
            'insurance_companies.id as insurance_company_id'
        )
            ->leftJoin('pet_plans', 'client_pets_insurances.plan_id', '=', 'pet_plans.id')
            ->leftJoin('insurance_companies', 'pet_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            ->where('client_pets_insurances.id', $id)
            ->first();

        return $plan;
    }
}
