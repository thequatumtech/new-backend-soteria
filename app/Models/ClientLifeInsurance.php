<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use Carbon\Carbon;

class ClientLifeInsurance extends Model
{
    use HasFactory;

    protected $table = 'client_life_insurances';
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
        'beneficiary_first_name',
        'beneficiary_last_name',
        'beneficiary_third_name',
        'marital_status',
        'place_residence',
        'occupancy_work',
        'american_notionality_status',
        'country_id',
        'city_id',
        'district_id',
        'street_name',
        'building_no',
        'employee_status',
        'company_name',
        'position',
        'work_nature',
        'employee_city_id',
        'employee_district_id',
        'employee_street_name',
        'employee_building_no',
        'company_contact',
        'height',
        'wight',
        'chronic_diseases_id',
        'previous_operation',
        'operation_details',
        'company_declined_policy',
        'declined_policy_details',
        'exiting_life_insur',
        'exiting_life_insur_details',
        'insurance_amount',
        'effective_date',
        'insurance_period',
        'photo_documents',
        'insured_documents',
        'family_book_documents',
        'plan_id',
        'payment_status'
    ];
    public static function getLifeInsuranceDetails($id)
    {
        // Find the birth date of the client
        $client = ClientLifeInsurance::find($id);

        if (!$client) {
            return null; // Handle case where the client is not found
        }

        // $birthdate = new DateTime($client->birth_date);
        // $today   = new DateTime('today');
        // $age = $birthdate->diff($today)->y;
        $age = 0;
        if ($client->birth_date) {
            try {
                $age = Carbon::parse($client->birth_date)->age;
            } catch (\Exception $e) {
                // Fallback to manual diff if parse fails
                $birthdate = new DateTime($client->birth_date);
                $today   = new DateTime('today');
                $age = $birthdate->diff($today)->y;
            }
        }

        // Fetch the life insurance details
        $plan = ClientLifeInsurance::select(
            'client_life_insurances.*',
            'life_plans.plan_name',
            'life_plans.fees',
            'life_plans.stamps',
            'life_plans.sales_tax',
            'life_plans.cbj',
            'life_plans.sales_tax_cbj',
            'life_plans.commission_percentage',
            'life_plans.insurance_policy_text',
            'insurance_companies.company_name',
            'insurance_company_documents.company_stamp',
            'insurance_company_documents.logo',
            'insurance_company_documents.letterhead',
            'insurance_company_documents.authorized_signature',
            'life_plan_pricing_schedules.year_1',
            'life_plan_pricing_schedules.year_2',
            'life_plan_pricing_schedules.year_3',
            'life_plan_pricing_schedules.year_4',
            'life_plan_pricing_schedules.year_5',
            'life_plan_pricing_schedules.year_6',
            'life_plan_pricing_schedules.year_7',
            'life_plan_pricing_schedules.year_8',
            'life_plan_pricing_schedules.year_9',
            'life_plan_pricing_schedules.year_10',
            'life_plan_pricing_schedules.year_11',
            'life_plan_pricing_schedules.year_12',
            'life_plan_pricing_schedules.year_13',
            'insurance_periods.name as periods_val',
            'insurance_companies.id as insurance_company_id'
        )
            ->leftJoin('life_plans', 'client_life_insurances.plan_id', '=', 'life_plans.id')
            ->leftJoin('life_plan_pricing_schedules', function ($join) use ($age) {
                $join->on('life_plans.id', '=', 'life_plan_pricing_schedules.life_plan_id')
                    ->where('life_plan_pricing_schedules.age', '=', $age)
                    ->whereNull('life_plan_pricing_schedules.deleted_at');
            })
            ->leftJoin('insurance_companies', 'life_plans.insurance_company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_company_documents', 'insurance_companies.id', '=', 'insurance_company_documents.insurance_id')
            ->leftJoin('insurance_periods', 'client_life_insurances.insurance_period', '=', 'insurance_periods.id')
            ->where('client_life_insurances.id', $id)
            ->first();

        if ($plan) {
            // // Dynamically set the net_premium based on insurance_periods.name
            // $insurancePeriodVal = $plan->periods_val; // Assuming this returns a value like '2'
            // $netPremiumsField = 'year_' . $insurancePeriodVal;

            // // Set net_premium based on the dynamically generated field
            // $plan->net_premium = $plan->$netPremiumsField ?? 0;
            // $plan->gross_premium = $plan->$netPremiumsField+$plan->fees+$plan->stamps+$plan->sales_tax ?? 0;
            // Store the rates before they are overwritten by amounts
            $plan->net_premium_rate = $plan->net_premium; // This is the rate from life_plans if applicable
            $plan->fees_rate = $plan->fees;
            $plan->stamps_rate = $plan->stamps;
            $plan->sales_tax_rate = $plan->sales_tax;

            // Dynamically set the net_premium (amount) based on insurance_periods.name
            $insurancePeriodVal = $plan->periods_val;
            // $netPremiumsField = 'year_' . $insurancePeriodVal;
            // $netPremiumAmount = $plan->$netPremiumsField ?? 0;
            $yearNum = preg_replace('/[^0-9]/', '', $insurancePeriodVal);
            $netPremiumsField = 'year_' . $yearNum;
            $netPremiumAmount = $plan->$netPremiumsField ?? 0;

            if (is_null($netPremiumAmount)) {
                $netPremiumAmount = 0;
            }

            if (is_string($netPremiumAmount) && strtolower(trim($netPremiumAmount)) === 'n/a') {
                throw new \Exception("Plan not processible. Please select another plan or contact support.");
            }
            // Fallback
            if (!is_numeric($netPremiumAmount) || $netPremiumAmount == 0) {
                $netPremiumAmount = $plan->net_premium_rate;
            }

            $plan->net_premium = $netPremiumAmount;

            // Calculate other components as amounts if they are percentages
            $feesAmount = $netPremiumAmount * ($plan->fees_rate / 100);
            $stampsAmount = $netPremiumAmount * ($plan->stamps_rate / 100);
            $taxAmount = $netPremiumAmount * ($plan->sales_tax_rate / 100);

            $plan->fees = $feesAmount;
            $plan->stamps = $stampsAmount;
            $plan->sales_tax = $taxAmount;

            $plan->gross_premium = $netPremiumAmount + $feesAmount + $stampsAmount + $taxAmount;
        }

        return $plan;
    }
}
