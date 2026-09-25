<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ClientFamilyMedicalInsurance extends Model
{
    use HasFactory;
    protected $table = 'client_family_medical_insurances';
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
        'company_company_contact',
        'existing_policy_status',
        'existing_policy_company_name',
        'existing_policy_expiry_date',
        'existing_policy_card',
        'height',
        'wight',
        'chronic_diseases_id',
        'previous_operation',
        'operation_details',
        'pregnant_status',
        'pregnant_month',
        'dangerous_status',
        'dangerous_id',
        'passport_front_id',
        'passport_back_id',
        'family_book_documents',
        'personal_picture_documents',
        'other_documents',
        'inception_date',
        'expiry_date',
        'insurance_type',
        'insurance_class',
        'inpatient_deductible_id',
        'outpatient_deductible_id',
        'no_of_visits_id',
        'insurance_limit',
        'plan_id',
        'insurance_type_status',
        'payment_status',
    ];
    public static function getClientFamilyMedicalInsuranceDetails($data)
    {
        $dob = $data['birth_date'];
        // Calculate age from date of birth
        $age = Carbon::parse($dob)->age;

        // Find the birth date of the client
        $client = ClientFamilyMedicalInsurance::find($data['policy_id']);

        if (!$client) {
            return null; // Handle case where the client is not found
        }

        if ($data['insurance_type'] == 1) {
            // Fetch the critical illness insurance details
            $plan = ClientFamilyMedicalInsurance::select(
                'client_family_medical_insurances.*',
                'in_patient_plans.plan_name',
                'in_patient_plans.fees',
                'in_patient_plans.stamps',
                'in_patient_plans.sales_tax',
                'in_patient_plans.insurance_policy_text',
                'in_patient_plans.commission_percentage',
                'insurance_companies.company_name',
                'insurance_company_documents.company_stamp',
                'insurance_company_documents.logo',
                'insurance_company_documents.letterhead',
                'insurance_company_documents.authorized_signature',
                'insurance_companies.id as insurance_company_id',
                'in_patient_plan_pricing_schedules.vip_class',
                'in_patient_plan_pricing_schedules.first_class',
                'in_patient_plan_pricing_schedules.second_class',
                'in_patient_plan_pricing_schedules.third_class'
            )
                ->leftJoin('in_patient_plans', 'client_family_medical_insurances.plan_id', '=', 'in_patient_plans.id')
                ->leftJoin('insurance_companies', 'in_patient_plans.insurance_company_id', '=', 'insurance_companies.id')
                ->leftJoin('in_patient_plan_pricing_schedules', 'in_patient_plans.id', '=', 'in_patient_plan_pricing_schedules.in_patient_plan_id')
                ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
                ->where('client_family_medical_insurances.id', $data['policy_id'])
                ->where('in_patient_plan_pricing_schedules.gender', $data['gender'] == 'male' ? 1 : 2)
                ->where('in_patient_plan_pricing_schedules.lower_age', '<=', $age) // Age comparison for lower bound
                ->where('in_patient_plan_pricing_schedules.upper_age', '>=', $age) // Age comparison for upper bound
                ->first();
        } else {
            // Fetch the other insurance type details
            $plan = ClientFamilyMedicalInsurance::select(
                'client_family_medical_insurances.*',
                'in_out_patient_plans.plan_name',
                'in_out_patient_plans.fees',
                'in_out_patient_plans.stamps',
                'in_out_patient_plans.sales_tax',
                // 'in_patient_plans.commission_percentage',
                'in_out_patient_plans.commission_percentage',
                'in_out_patient_plans.insurance_policy_text',
                'insurance_companies.company_name',
                'insurance_company_documents.company_stamp',
                'insurance_company_documents.logo',
                'insurance_company_documents.authorized_signature',
                'insurance_companies.id as insurance_company_id',
                'in_out_patient_plan_pricing_schedules.vip_class',
                'in_out_patient_plan_pricing_schedules.first_class',
                'in_out_patient_plan_pricing_schedules.second_class',
                'in_out_patient_plan_pricing_schedules.third_class'
            )
                ->leftJoin('in_out_patient_plans', 'client_family_medical_insurances.plan_id', '=', 'in_out_patient_plans.id')
                ->leftJoin('in_out_patient_plan_pricing_schedules', 'in_out_patient_plans.id', '=', 'in_out_patient_plan_pricing_schedules.in_out_patient_plan_id')
                ->leftJoin('insurance_companies', 'in_out_patient_plans.insurance_company_id', '=', 'insurance_companies.id')
                ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
                ->where('client_family_medical_insurances.id', $data['policy_id'])
                ->where('in_out_patient_plan_pricing_schedules.gender', $data['gender'] == 'male' ? 1 : 2)
                ->where('in_out_patient_plan_pricing_schedules.lower_age', '<=', $age) // Age comparison for lower bound
                ->where('in_out_patient_plan_pricing_schedules.upper_age', '>=', $age) // Age comparison for upper bound
                ->first();
        }
        if ($plan) {
            // Dynamically set the net_premium based on insurance_periods.name
            // $insurancePeriodVal = $plan->periods_val; // Assuming this returns a value like '2'
            // $netPremiumsField = 'year_' . $insurancePeriodVal;

            $insuranceClass = $data['insurance_class'] ?? null;

            if ($insuranceClass === 'VIP Class') {
                $plan->net_premium = $plan->vip_class ?? 0;
            } elseif ($insuranceClass === 'First Class') {
                $plan->net_premium = $plan->first_class ?? 0;
            } elseif ($insuranceClass === 'Second Class') {
                $plan->net_premium = $plan->second_class ?? 0;
            } elseif ($insuranceClass === 'Third Class') {
                $plan->net_premium = $plan->third_class ?? 0;
            } else {
                $plan->net_premium = 0;
            }

            // $plan->gross_premium = $plan->net_premium + $plan->fees + $plan->stamps + $plan->sales_tax ?? 0;
               // Calculate components
            $feesPercentage = $plan->fees ?? 0;
            $stampsPercentage = $plan->stamps ?? 0;
            $salesTaxPercentage = $plan->sales_tax ?? 0;

            // CBJ Check (safe access)
            $cbjTaxPercentage = $plan->cbj ?? 0;
            $cbjSalesTaxPercentage = $plan->sales_tax_cbj ?? 0;

            $issuanceFees = ($plan->net_premium * $feesPercentage) / 100;
            $stampAmount = ($plan->net_premium * $stampsPercentage) / 100;
            $cbjContribution = ($plan->net_premium * $cbjTaxPercentage) / 100;
            $salesTaxAmount = (($plan->net_premium + $issuanceFees) * $salesTaxPercentage) / 100;
            $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;

            $plan->gross_premium = $plan->net_premium + $issuanceFees + $stampAmount + $salesTaxAmount + $cbjContribution + $cbjSalesTaxAmount;

        }
        return $plan;
    }
}
