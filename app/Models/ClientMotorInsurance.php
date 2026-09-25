<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;

class ClientMotorInsurance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'client_motor_insurances';
    protected $fillable = ['police_no','client_id',
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
    'occupancy',
    'city_id',
    'district_id',
    'street_name',
    'building_no',
    'user_mobile_no',
    'company_name',
    'position',
    'work_nature',
    'company_contact_no',
    'inception_date',
    'expiry_date',
    'no_accident_3_year',
    'no_ticket_12_month',
    'no_point_12_month',
    'vahicle_no',
    'obtain_vahicle_info',
    'vahicle_type_id',
    'vahicle_brand_id',
    'vahicle_category_id',
    'vahicle_color_id',
    'vahicle_register_no',
    'engine_no',
    'chassis_no',
    'engine_type_id',
    'engine_capacity',
    'vehicle_manufacturing_date',
    'vehicle_value',
    'insurance_type',
    'residence_id_front',
    'residence_id_back',
    'vehicle_license_front',
    'vehicle_license_back',
    'vehicle_photo_front',
    'vehicle_photo_back',
    'vehicle_photo_right',
    'vehicle_photo_left',
    'carseer_documents',
    'autoscore_documents',
    'customs_declaration',
    'plan_id',
    'national_id_number',
    'residency_number',
];

    public static function getMotorInsuranceDetails($id)
    {
        // Find the birth date of the client
        $client = ClientMotorInsurance::find($id);
    
        if (!$client) {
            return null; // Handle case where the client is not found
        }
    
        $birthdate = new DateTime($client->birth_date);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
    
        // Fetch the criticalIllness insurance details
        $plan = ClientMotorInsurance::select(
                'client_motor_insurances.*', 
                'motor_insurance_plans.plan_name', 
                'motor_insurance_plan_comprehensive_cover_fees.fees',
                'motor_insurance_plan_comprehensive_cover_fees.stamps',
                'motor_insurance_plan_comprehensive_cover_fees.sales_tax',
                'motor_insurance_plan_comprehensive_cover_fees.cbj',
                'motor_insurance_plan_comprehensive_cover_fees.sales_tax_cbj',
                'motor_insurance_plan_comprehensive_cover_fees.commission_percentage',
                'motor_insurance_plans.insurance_policy_text',
                'insurance_companies.company_name',
                'insurance_company_documents.company_stamp',
                'insurance_company_documents.logo',
            'insurance_company_documents.letterhead',
                'insurance_company_documents.authorized_signature',
                'insurance_companies.id as insurance_company_id'
            )
            ->leftJoin('motor_insurance_plans', 'client_motor_insurances.plan_id', '=', 'motor_insurance_plans.id')
            ->leftJoin('motor_insurance_plan_comprehensive_cover_fees', 'motor_insurance_plans.id', '=', 'motor_insurance_plan_comprehensive_cover_fees.motor_insurance_plan_id')
            ->leftJoin('insurance_companies', 'motor_insurance_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            // ->leftJoin('insurance_periods', 'client_motor_insurances.insurance_period', '=', 'insurance_periods.id')
            ->where('client_motor_insurances.id', $id)
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