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
        // \DB::enableQueryLog();

        $client = ClientPersonalAccidentInsurance::find($id);
        if (!$client) {
            return null;
        }

        $birthdate = new DateTime($client->birth_date);
        $today = new DateTime('today');
        $age = $birthdate->diff($today)->y;

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
            'insurance_companies.id as insurance_company_id',

            'purchase_policy.inception_date',
            'purchase_policy.expiry_date',
            'purchase_policy.net_premium',
            'purchase_policy.fees as plan_fees',
            'purchase_policy.stamps as plan_stamps',
            'purchase_policy.sales_tax as plan_sales_tax',
            'purchase_policy.gross_premium',
            'purchase_policy.plan_name as purchase_plan_name',
            'purchase_policy.policy_plan_limit',
            'purchase_policy.policy_pdf_url'
        )
            ->leftJoin('personal_accident_plans', 'client_personal_accident_insurances.plan_id', '=', 'personal_accident_plans.id')
            ->leftJoin('insurance_companies', 'personal_accident_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')

            ->leftJoin('purchase_policy', 'purchase_policy.policy_no', '=', 'client_personal_accident_insurances.police_no')

            ->where('client_personal_accident_insurances.id', $id)
            ->first();

        if ($plan) {
            $insurancePeriodVal = $plan->periods_val;
            $netPremiumsField = 'year_' . $insurancePeriodVal;

            $plan->net_premium = $plan->$netPremiumsField ?? 0;
            $plan->gross_premium = $plan->$netPremiumsField + $plan->fees + $plan->stamps + $plan->sales_tax ?? 0;
        }

        $plan->age = $age;
        // dd(\DB::getQueryLog());

        return $plan;
    }
}
