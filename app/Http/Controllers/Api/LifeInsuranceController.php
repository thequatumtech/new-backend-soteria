<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{ClientLifeInsurance, PurchasePolicy};
use App\Models\InsurancePlanModels\{LifePlan, LifePlanPolicyCover, LifePlanPricingSchedule};
use DateTime;
use PDF;
use App\Models\InsurancePeriod;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;
use App\Helpers\InsurancePlanHelper;
use App\Models\RenewalPolicy;
use Illuminate\Support\Facades\Log;

class LifeInsuranceController extends Controller
{
    private function normalizeRestricted($value)
    {
        if (!$value) return [];

        if (is_string($value) && str_starts_with($value, '[')) {
            return array_map('trim', json_decode($value, true));
        }

        if (is_string($value)) {
            return array_map('trim', explode(',', $value));
        }

        if (is_numeric($value)) {
            return [(int)$value];
        }

        return [];
    }

    private function restrictionError($type)
    {
        $restrictionTypes = [
            'country' => __('messages.api.life_restriction_country'),
            'city' => __('messages.api.life_restriction_city'),
            'district' => __('messages.api.life_restriction_district'),
            'age' => __('messages.api.life_restriction_age'),
            'occupation' => __('messages.api.life_restriction_occupation'),
            'chronic disease' => __('messages.api.life_restriction_chronic_disease'),
        ];

        $restrictionType = $restrictionTypes[$type] ?? $type;

        $message = __('messages.api.life_restriction_message', [
            'type' => $restrictionType
        ]);

        if ($type === 'country') {
            $message .= __('messages.api.life_country_contact');
        }

        return response()->json([
            'status' => false,
            'status_code' => 422,
            'message' => $message,
            'data' => []
        ], 422);
    }

    private function normalizeDate($date)
    {
        if (!$date) return null;

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
            return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
        }

        return $date;
    }

    public function storeLifeInsurance(Request $request)
    {
        try {

            $data = $request->validate([
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'third_name' => 'nullable',
                'family_name' => 'nullable',
                'nationality' => 'nullable',
                'nationality_no' => 'nullable',
                'id_residence_no' => 'nullable',
                'birth_date' => 'nullable',
                'gender' => 'nullable',
                'beneficiary_first_name' => 'nullable',
                'beneficiary_last_name' => 'nullable',
                'beneficiary_third_name' => 'nullable',
                'marital_status' => 'nullable',
                'place_residence' => 'nullable',
                'occupancy_work' => 'nullable',
                'american_notionality_status' => 'nullable',
                'country_id' => 'nullable',
                'city_id' => 'nullable',
                'district_id' => 'nullable',
                'street_name' => 'nullable',
                'building_no' => 'nullable',
                'employee_status' => 'nullable',
                'company_name' => 'nullable',
                'position' => 'nullable',
                'work_nature' => 'nullable',
                'employee_city_id' => 'nullable',
                'employee_district_id' => 'nullable',
                'employee_street_name' => 'nullable',
                'employee_building_no' => 'nullable',
                'company_contact' => 'nullable',
                'height' => 'nullable',
                'wight' => 'nullable',
                'chronic_diseases_id' => 'nullable',
                'previous_operation' => 'nullable',
                'operation_details' => 'nullable',
                'company_declined_policy' => 'nullable',
                'declined_policy_details' => 'nullable',
                'exiting_life_insur' => 'nullable',
                'exiting_life_insur_details' => 'nullable',
                'insurance_amount' => 'nullable',
                'effective_date' => 'nullable',
                'insurance_period' => 'nullable',
                'photo_documents' => 'nullable',
                'insured_documents' => 'nullable',
                'family_book_documents' => 'nullable',
                'plan_id' => 'nullable',
                'payment_status' => 'nullable',

                'old_policy_id_for_renew' => 'nullable|integer',
                'renew' => 'nullable|boolean',
            ]);

            if ($data['country_id']) {
                $country = \App\Models\Country::find($data['country_id']);

                if (!$country) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.country_not_exist'),
                        'data' => []
                    ], 422);
                }
            }

            if ($data['country_id'] && $data['city_id']) {
                $city = \App\Models\Cities::where('id', $data['city_id'])
                    ->where('country_id', $data['country_id'])
                    ->first();

                if (!$city) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.city_not_belong_country'),
                        'data' => []
                    ], 422);
                }
            }

            if ($data['city_id'] && $data['district_id']) {
                $district = \App\Models\District::where('id', $data['district_id'])
                    ->where('city_id', $data['city_id'])
                    ->first();

                if (!$district) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.district_not_belong_city'),
                        'data' => []
                    ], 422);
                }
            }
            $oldPolicyForRenewal = null;

            if ($request->boolean('renew')) {

                $oldPolicyForRenewal = PurchasePolicy::find(
                    $request->old_policy_id_for_renew
                );

                if (!$oldPolicyForRenewal) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.policy_not_found'),
                        'data' => [],
                    ], 422);
                }
            }
            $existingLife = PurchasePolicy::find($request->purchase_id ?? 0);

            $plan_data = LifePlan::with([
                'policy_covers',
                'insurance_company.currency'
            ])->find($data['plan_id']);

            if (!$plan_data) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.life_plan_not_exist'),
                    'data' => []
                ], 422);
            }

            $data['insurance_company_id'] = $plan_data->insurance_company_id;

            $client = Client::find($request->user_id);

            if (!$client) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.client_not_found'),
                    'data' => []
                ], 422);
            }

            $clientCountry  = $client->country_id;
            $clientCity     = $client->city_id;
            $clientDistrict = $client->district_id;
            $clientOccupation = $client->occupation_id;
            $clientAge = Carbon::parse($client->birth_date)->age;

            $restrictedCountries   = $this->normalizeRestricted($plan_data->restricted_country_ids);
            $restrictedCities      = $this->normalizeRestricted($plan_data->restricted_city_ids);
            $restrictedDistricts   = $this->normalizeRestricted($plan_data->restricted_district_ids);
            $restrictedAges        = $this->normalizeRestricted($plan_data->restricted_age_ids);
            $restrictedOccupations = $this->normalizeRestricted($plan_data->restricted_occupation_ids);
            $restrictedChronic     = $this->normalizeRestricted($plan_data->restricted_chronic_ids);

            if ($clientCountry && in_array($clientCountry, $restrictedCountries)) {
                return $this->restrictionError('country');
            }

            if ($clientCity && in_array($clientCity, $restrictedCities)) {
                return $this->restrictionError('city');
            }

            if ($clientDistrict && in_array($clientDistrict, $restrictedDistricts)) {
                return $this->restrictionError('district');
            }

            foreach ($restrictedAges as $range) {
                $range = str_replace(' ', '', $range);

                if (str_contains($range, '-')) {
                    [$min, $max] = explode('-', $range);

                    if ($clientAge >= (int)$min && $clientAge <= (int)$max) {
                        return $this->restrictionError('age');
                    }
                } else {
                    if ((int)$range === $clientAge) {
                        return $this->restrictionError('age');
                    }
                }
            }

            if ($clientOccupation && in_array($clientOccupation, $restrictedOccupations)) {
                return $this->restrictionError('occupation');
            }

            if ($clientAge < 21 || $clientAge > 60) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.life_age_not_eligible'),
                    'data' => []
                ], 422);
            }

            $userChronics = $this->normalizeRestricted($data['chronic_diseases_id'] ?? '');

            if (!empty($userChronics)) {
                foreach ($userChronics as $chronicId) {
                    if (in_array($chronicId, $restrictedChronic)) {
                        return $this->restrictionError('chronic disease');
                    }
                }
            }

            $effective_date = $data['effective_date'];

            $pricingSchedule = LifePlanPricingSchedule::where('life_plan_id', $data['plan_id'])
                ->where('age', $clientAge)
                ->whereNull('deleted_at')
                ->first();

            if (!$pricingSchedule) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.life_no_pricing_schedule'),
                    'data' => []
                ], 422);
            }

            $years = (int) ($data['insurance_period'] ?? 0);

            $yearColumn = 'year_' . $years;

            $netPremiumValue = $pricingSchedule->$yearColumn ?? null;

            if ($netPremiumValue === null || $netPremiumValue === '') {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.life_no_pricing_available', ['years' => $years]),
                    'data' => []
                ], 422);
            }

            if (is_string($netPremiumValue) && strtolower(trim($netPremiumValue)) === 'n/a') {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.life_no_pricing_available', ['years' => $years]),
                    'data' => []
                ], 422);
            }

            if (!is_numeric($netPremiumValue)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.life_no_pricing_available', ['years' => $years]),
                    'data' => []
                ], 422);
            }

            $netPremiumFromSchedule = (float) $netPremiumValue;

            $expiry_date = date('Y-m-d', strtotime("+$years years", strtotime($effective_date)));

            if ($existingLife) {
                $Life = ClientLifeInsurance::find($existingLife->policy_id);

                if ($Life) {
                    $Life->update($data);
                }

                $data['purchase_id'] = $existingLife->id;
                $data['inception_date'] = $data['effective_date'];
                $data['expiry_date'] = $expiry_date;

                $Life = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $Life->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $Life = ClientLifeInsurance::create($data);

                $data['policy_type'] = 3;
                $data['policy_id'] = $Life->id;
                $data['inception_date'] = $data['effective_date'];
                $data['expiry_date'] = $expiry_date;

                $Life = PurchasePolicy::savePurchasePolicy($data);


                if ($request->boolean('renew')) {

                    RenewalPolicy::create([
                        'old_policy_id' => $oldPolicyForRenewal->id,
                        'new_policy_id' => $Life->id,
                    ]);
                }
            }

            try {
                $data = ClientLifeInsurance::getLifeInsuranceDetails($data['policy_id']);

                if (!$data) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.life_insurance_details_not_found'),
                        'data' => []
                    ], 422);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => $e->getMessage(),
                    'data' => []
                ], 422);
            }

            $purchaseRecord = PurchasePolicy::find($Life->id);

            if ($purchaseRecord) {
                $data->inception_date = $purchaseRecord->inception_date;
                $data->expiry_date = $purchaseRecord->expiry_date;
            }

            $directory = public_path('insurance_pdfs/life_policy');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = uniqid() . '_policy_' . $Life->id . '.pdf';
            $path = $directory . '/' . $filename;

            $netPremium = $netPremiumFromSchedule;
            $policy_limit = (float)$plan_data->limit ?? 0;

            $feesPercentage   = (float)($plan_data->fees ?? 0);
            $stampsPercentage = (float)($plan_data->stamps ?? 0);
            $taxPercentage    = (float)($plan_data->sales_tax ?? 0);
            $cbjTaxPercentage      = (float)($plan_data->cbj ?? 0);
            $cbjSalesTaxPercentage = (float)($plan_data->sales_tax_cbj ?? 0);

            // Calculate amounts dynamically
            $feesAmount   = ($netPremium * $feesPercentage) / 100;
            $stampsAmount = ($netPremium * $stampsPercentage) / 100;
            $taxAmount    = (($netPremium + $feesAmount) * $taxPercentage) / 100;
            $cbjContribution    = ($netPremium * $cbjTaxPercentage) / 100;
            $cbjSalesTaxAmount  = ($cbjContribution * $cbjSalesTaxPercentage) / 100;

            $grossPremium = $netPremium
                + $feesAmount
                + $stampsAmount
                + $taxAmount
                + $cbjContribution
                + $cbjSalesTaxAmount;

            $purchase['net_premium']           = $netPremium;
            $purchase['fees']                  = $feesAmount;
            $purchase['stamps']                = $stampsAmount;
            $purchase['sales_tax']             = $taxAmount;
            $purchase['cbj']                   = $cbjContribution;
            $purchase['sales_tax_cbj']         = $cbjSalesTaxAmount;
            $purchase['gross_premium']         = $grossPremium;
            $purchase['commission_amount']     = $plan_data->commission_amount;
            $purchase['commission_percentage'] = $plan_data->commission_percentage;
            $purchase['inception_date'] = $data->inception_date;
            $purchase['expiry_date']    = $data->expiry_date;
            $purchase['purchase_id']    = $Life->id;
            $purchase['plan_id']        = $data['plan_id'];
            $purchase['plan_name']      = $plan_data->plan_name;
            $purchase['policy_plan_limit'] = $plan_data->limit;
            $purchase['insurance_company_id'] = $data['insurance_company_id'];
            $purchase['policy_pdf_url'] = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);

            $data->purchase_id = $Life->id;
            $data->purchase_policy_id = $Life->id;

            // ── FIX: push the freshly computed values onto $data BEFORE building the PDF ──
            $data->net_premium    = $netPremium;
            $data->fees           = $feesAmount;
            $data->stamps         = $stampsAmount;
            $data->sales_tax      = $taxAmount;
            $data->cbj            = $cbjContribution;
            $data->sales_tax_cbj  = $cbjSalesTaxAmount;
            $data->gross_premium  = $grossPremium;
            // ────────────────────────────────────────────────────────────────────────────

            // Prepare unformatted data for PDF
            $plan_for_pdf = clone $plan_data;
            $plan_for_pdf->net_premium_amount = $netPremium;
            $plan_for_pdf->fees_amount = $feesAmount;
            $plan_for_pdf->stamps_amount = $stampsAmount;
            $plan_for_pdf->sales_tax_amount = $taxAmount;
            $plan_for_pdf->cbj_amount = $cbjContribution;
            $plan_for_pdf->sales_tax_cbj_amount = $cbjSalesTaxAmount;
            $plan_for_pdf->gross_premium_amount = $grossPremium;

            $client = Client::with('country.currency')->find($request->user_id);

            // Get insurance company currency first
            $insuranceCompany = $plan_data->insurance_company;

            if ($insuranceCompany && $insuranceCompany->currency) {
                $abbr = $insuranceCompany->currency->abbreviation;
            } else {
                // Fallback to client's country currency
                $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';
            }

            $pdf = PDF::loadView('pdf/life_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'plan' => $plan_for_pdf
            ]);

            $pdf->save($path);

            $url = url('insurance_pdfs/life_policy/' . $filename);
            $data['url'] = $url;

            $data->commission_percentage = $data->commission_percentage . '%';

            // Format for JSON response — happens AFTER the PDF has already been rendered
            $data->net_premium       = number_format($data['net_premium'], 2) . ' ' . $abbr;
            $data->fees              = number_format($data['fees'], 2) . ' ' . $abbr;
            $data->gross_premium     = number_format($data['gross_premium'], 2) . ' ' . $abbr;
            $data->sales_tax         = number_format($data['sales_tax'], 2) . ' ' . $abbr;
            $data->stamps            = number_format($data['stamps'], 2) . ' ' . $abbr;
            $data->cbj               = number_format($data['cbj'], 2) . ' ' . $abbr;
            $data->sales_tax_cbj     = number_format($data['sales_tax_cbj'], 2) . ' ' . $abbr;

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.add_life_insurance_plan_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('API Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'   => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    // public function getLifeInsurancePlan(Request $request)
    // {
    //     try {
    //         $data = LifePlan::with('policy_covers', 'insurance_company')
    //             ->whereHas('insurance_company', function ($q) {
    //                 $q->whereNull('deleted_at');
    //             })
    //             ->where('limit',$request->limit)
    //             ->get();

    //         $data->transform(function ($item) {
    //             if (!empty($item->insurance_policy_pdf)) {
    //                 $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
    //             }

    //             if (!empty($item->insurance_company->privacy_policy)) {
    //                 $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
    //             }

    //             return $item;
    //         });

    //         return response()->json([
    //             'status' => true,
    //             'status_code' => 200,
    //             'message' => 'Get Life Insurance Plan successfully',
    //             'data' => $data
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'status_code' => 500,
    //             'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
    //             'data' => []
    //         ]);
    //     }
    // }

    public function getLifeInsurancePlan(Request $request)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Get Client
            |--------------------------------------------------------------------------
            */

            $client = Client::find($request->user_id);

            if (!$client) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => __('messages.api.client_not_found'),
                    'data' => []
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Get Client Age
            |--------------------------------------------------------------------------
            */

            if (empty($client->birth_date)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.life_client_birth_date_not_found'),
                    'data' => []
                ], 422);
            }

            $age = Carbon::parse($client->birth_date)->age;

            Log::info('Life Insurance Plan API', [
                'client_id' => $client->id,
                'birth_date' => $client->birth_date,
                'age' => $age,
                'limit' => $request->limit,
                'insurance_years_period' => $request->insurance_years_period,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Get Plans
            |--------------------------------------------------------------------------
            */

            $query = LifePlan::with([
                'policy_covers',
                'insurance_company',
                'insurance_company.currency'
            ])
                ->whereHas('insurance_company', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->when($request->filled('limit'), function ($query) use ($request) {
                    $query->where('limit', $request->limit);
                });

            // Filter plans according to client's currency
            $query = InsurancePlanHelper::filterByClientCurrency(
                $query,
                $request->user_id
            );

            $data = $query->get();

            /*
            |--------------------------------------------------------------------------
            | No Plans Available For Client's Country
            |--------------------------------------------------------------------------
            */

            if ($data->isEmpty()) {

                if ($request->filled('limit')) {

                    $countryQuery = LifePlan::with([
                        'insurance_company',
                        'insurance_company.currency'
                    ])->whereHas('insurance_company', function ($q) {
                        $q->whereNull('deleted_at');
                    });

                    $countryQuery = InsurancePlanHelper::filterByClientCurrency(
                        $countryQuery,
                        $request->user_id
                    );

                    $plansExistForCountry = $countryQuery->exists();

                    if ($plansExistForCountry) {
                        // Plans exist for the country but none match the given limit — return empty normally.
                        return response()->json([
                            'status'                  => false,
                            'status_code'             => 404,
                            'message'                 => __('messages.api.no_life_insurance_plans_for_country'),
                            'client_age'              => $age,
                            'insurance_years_period'  => $request->insurance_years_period,
                            'data'                    => []
                        ], 404);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Transform Plans
            |--------------------------------------------------------------------------
            */

            $data->transform(function ($item) use ($request, $age) {

                /*
                |--------------------------------------------------------------------------
                | PDF URL
                |--------------------------------------------------------------------------
                */

                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url(
                        'uploads/insurance_plans/' .
                            $item->id .
                            '/' .
                            $item->insurance_policy_pdf
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Privacy Policy URL
                |--------------------------------------------------------------------------
                */

                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url(
                        'insurance/' .
                            $item->insurance_company->id .
                            '/' .
                            $item->insurance_company->privacy_policy
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Default Values
                |--------------------------------------------------------------------------
                */

                $item->client_age = $age;
                $item->insurance_years_period = null;
                $item->premium_rate = null;

                $item->net_premium = 0;
                $item->fees_amount = 0;
                $item->stamps_amount = 0;
                $item->sales_tax_amount = 0;
                $item->cbj_amount = 0;
                $item->sales_tax_cbj_amount = 0;
                $item->gross_premium = 0;

                /*
                |--------------------------------------------------------------------------
                | Calculate Premium When Insurance Period Is Provided
                |--------------------------------------------------------------------------
                */

                if ($request->filled('insurance_years_period')) {

                    $years = (int) $request->insurance_years_period;

                    /*
                    |--------------------------------------------------------------------------
                    | Validate Period
                    |--------------------------------------------------------------------------
                    */

                    if ($years < 1 || $years > 13) {
                        return $item;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Dynamic Column
                    |--------------------------------------------------------------------------
                    |
                    | 1  -> year_1
                    | 5  -> year_5
                    | 10 -> year_10
                    | 13 -> year_13
                    |
                    */

                    $yearColumn = 'year_' . $years;

                    /*
                    |--------------------------------------------------------------------------
                    | Find Pricing By Client Age
                    |--------------------------------------------------------------------------
                    */

                    $pricingSchedule = LifePlanPricingSchedule::where(
                        'life_plan_id',
                        $item->id
                    )
                        ->where('age', $age)
                        ->whereNull('deleted_at')
                        ->first();

                    if (!$pricingSchedule) {
                        return $item;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Get Rate
                    |--------------------------------------------------------------------------
                    */

                    $rate = (float) ($pricingSchedule->{$yearColumn} ?? 0);

                    if ($rate <= 0) {
                        return $item;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Calculate Net Premium
                    |--------------------------------------------------------------------------
                    |
                    | Example:
                    |
                    | Limit = 50,000
                    | Rate = 12%
                    |
                    | Net Premium = 50,000 * 12 / 100
                    |             = 6,000
                    |
                    */

                    $policyLimit = (float) $item->limit;

                    // $netPremium = (
                    //     $policyLimit * $rate
                    // ) / 100;

                    $netPremium = $rate;

                    /*
                    |--------------------------------------------------------------------------
                    | Calculate All Premium Values
                    |--------------------------------------------------------------------------
                    */

                    $premium = $this->calculatePremium(
                        $netPremium,
                        $item
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Add Values To Response
                    |--------------------------------------------------------------------------
                    */

                    $item->insurance_years_period = $years;
                    $item->premium_rate = $rate;

                    $item->net_premium = $premium['net_premium'];
                    $item->fees_amount = $premium['fees'];
                    $item->stamps_amount = $premium['stamps'];
                    $item->sales_tax_amount = $premium['sales_tax'];
                    $item->cbj_amount = $premium['cbj'];
                    $item->sales_tax_cbj_amount = $premium['sales_tax_cbj'];
                    $item->gross_premium = $premium['gross_premium'];
                }

                return $item;
            });

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_life_insurance_plan_successfully'),
                'client_age' => $age,
                'insurance_years_period' => $request->insurance_years_period,
                'data' => $data
            ]);
        } catch (\Exception $e) {

            Log::error('Life Insurance Plan API Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function getLifeInsuranceDetails(Request $request)
    {
        try {
            $insuranceid = $request->insurance_id;

            if ($insuranceid) {
                $ins = InsurancePeriod::where('id', $insuranceid)->firstOrFail();
            }
        } catch (\Exception $e) {
        }
    }

    private function calculatePremium(
        float $netPremium,
        $plan
    ): array {
        $feesPercentage = (float) ($plan->fees ?? 0);
        $stampsPercentage = (float) ($plan->stamps ?? 0);
        $taxPercentage = (float) ($plan->sales_tax ?? 0);
        $cbjTaxPercentage = (float) ($plan->cbj ?? 0);
        $cbjSalesTaxPercentage = (float) ($plan->sales_tax_cbj ?? 0);

        // Fees
        $feesAmount = ($netPremium * $feesPercentage) / 100;

        // Stamps
        $stampsAmount = ($netPremium * $stampsPercentage) / 100;

        // Sales Tax
        $taxAmount = (
            ($netPremium + $feesAmount)
            * $taxPercentage
        ) / 100;

        // CBJ Contribution
        $cbjContribution = (
            $netPremium
            * $cbjTaxPercentage
        ) / 100;

        // CBJ Sales Tax
        $cbjSalesTaxAmount = (
            $cbjContribution
            * $cbjSalesTaxPercentage
        ) / 100;

        // Gross Premium
        $grossPremium =
            $netPremium
            + $feesAmount
            + $stampsAmount
            + $taxAmount
            + $cbjContribution
            + $cbjSalesTaxAmount;

        return [
            'net_premium' => round($netPremium, 2),
            'fees' => round($feesAmount, 2),
            'stamps' => round($stampsAmount, 2),
            'sales_tax' => round($taxAmount, 2),
            'cbj' => round($cbjContribution, 2),
            'sales_tax_cbj' => round($cbjSalesTaxAmount, 2),
            'gross_premium' => round($grossPremium, 2),

            // Percentages also returned if needed
            'fees_percentage' => $feesPercentage,
            'stamps_percentage' => $stampsPercentage,
            'sales_tax_percentage' => $taxPercentage,
            'cbj_percentage' => $cbjTaxPercentage,
            'sales_tax_cbj_percentage' => $cbjSalesTaxPercentage,
        ];
    }
}