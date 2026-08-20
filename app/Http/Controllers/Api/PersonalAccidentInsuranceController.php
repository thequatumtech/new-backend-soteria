<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ClientPersonalAccidentInsurance, PurchasePolicy};
// use App\Models\InsurancePlanModels\PersonalAccidentPlan;
use App\Models\InsurancePlanModels\{PersonalAccidentPlan, PersonalAccidentPlanPricingSchedule};
use App\Models\InsurancePeriod;
use Illuminate\Http\Request;
use PDF;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Currency;

class PersonalAccidentInsuranceController extends Controller
{
    private function cleanNumber($value)
    {
        if ($value === null) return 0;

        $clean = preg_replace('/[^\d.]/', '', $value);

        return is_numeric($clean) ? (float)$clean : 0;
    }
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
        $message = "You are not eligible for this plan due to {$type} restriction.";

        if ($type === 'country') {
            $message .= ' Please contact us for further information.';
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
    // public function storePersonalAccidentInsurance(Request $request)
    // {
    //     try {
    //         $data = $request->validate([
    //             'first_name' => 'nullable',
    //             'last_name' => 'nullable',
    //             'third_name' => 'nullable',
    //             'family_name' => 'nullable',
    //             'nationality' => 'nullable',
    //             'nationality_no' => 'nullable',
    //             'id_residence_no' => 'nullable',
    //             'birth_date' => 'nullable',
    //             'gender' => 'nullable',
    //             'marital_status' => 'nullable',
    //             'place_residence' => 'nullable',
    //             'company_name' => 'nullable',
    //             'position' => 'nullable',
    //             'work_nature' => 'nullable',
    //             'city_id' => 'nullable',
    //             'district_id' => 'nullable',
    //             'street_name' => 'nullable',
    //             'building_no' => 'nullable',
    //             'company_contact' => 'nullable',
    //             'company_city_id' => 'nullable',
    //             'inception_date' => 'nullable',
    //             'inception_period' => 'nullable',
    //             'occupany_type_work' => 'nullable',
    //             'photo_documents_1' => 'nullable',
    //             'photo_documents_2' => 'nullable',
    //             'photo_documents_3' => 'nullable',
    //             'plan_id' => 'nullable',
    //             'payment_status' => 'nullable',
    //             'dangerous_field' => 'nullable',
    //         ]);

    //         $plan = PersonalAccidentPlan::find($data['plan_id']);
    //         if (!$plan) {
    //             return response()->json([
    //                 'status' => false,
    //                 'status_code' => 404,
    //                 'message' => 'Plan not found',
    //                 'data' => []
    //             ]);
    //         }

    //         $data['insurance_company_id'] = $plan->insurance_company_id;
    //         // dd($data);
    //         $client = Client::find($request->user_id);
    //         if (!$client) {
    //             return response()->json([
    //                 'status' => false,
    //                 'status_code' => 422,
    //                 'message' => 'Client not found.',
    //                 'data' => []
    //             ], 422);
    //         }

    //         $clientCountry  = $client->country_id;
    //         $clientCity     = $client->city_id;
    //         $clientDistrict = $client->district_id;
    //         $clientOccupation = $client->occupation_id;
    //         $clientAge = Carbon::parse($client->birth_date)->age;

    //         $restrictedCountries   = $this->normalizeRestricted($plan->restricted_country_ids);
    //         $restrictedCities      = $this->normalizeRestricted($plan->restricted_city_ids);
    //         $restrictedDistricts   = $this->normalizeRestricted($plan->restricted_district_ids);
    //         $restrictedAges        = $this->normalizeRestricted($plan->restricted_age_ids);
    //         $restrictedOccupations = $this->normalizeRestricted($plan->restricted_occupation_ids);
    //         $userDangerousFields = $this->normalizeRestricted($request->dangerous_field ?? null);
    //         $data['dangerous_field'] = !empty($userDangerousFields) ? implode(',', $userDangerousFields) : null;
    //         $restrictedDangerousFields = $this->normalizeRestricted($plan->restricted_dangerous_activities_ids ?? null);

    //         if ($clientCountry && in_array($clientCountry, $restrictedCountries)) {
    //             return $this->restrictionError('country');
    //         }

    //         if ($clientCity && in_array($clientCity, $restrictedCities)) {
    //             return $this->restrictionError('city');
    //         }

    //         if ($clientDistrict && in_array($clientDistrict, $restrictedDistricts)) {
    //             return $this->restrictionError('district');
    //         }
    //         if (!empty($restrictedDangerousFields) && !empty($userDangerousFields)) {
    //             if (array_intersect($userDangerousFields, $restrictedDangerousFields)) {
    //                 return $this->restrictionError('dangerous activity');
    //             }
    //         }

    //         foreach ($restrictedAges as $range) {
    //             $range = str_replace(' ', '', $range);

    //             if (str_contains($range, '-')) {
    //                 [$min, $max] = explode('-', $range);
    //                 if ($clientAge >= (int)$min && $clientAge <= (int)$max) {
    //                     return $this->restrictionError('age');
    //                 }
    //             } else {
    //                 if ((int)$range === $clientAge) {
    //                     return $this->restrictionError('age');
    //                 }
    //             }
    //         }

    //         if ($clientOccupation && in_array($clientOccupation, $restrictedOccupations)) {
    //             return $this->restrictionError('occupation');
    //         }

    //         $effective_date = $data['inception_date'];

    //         $months = $plan->policy_period ?? 0;
    //         if ($plan->insurance_period) {
    //             $periodName = strtolower($plan->insurance_period->name);
    //             $number = (int)preg_replace('/[^0-9]/', '', $periodName);
    //             if (str_contains($periodName, 'year')) {
    //                 $months = $number * 12;
    //             } elseif (str_contains($periodName, 'month')) {
    //                 $months = $number;
    //             }
    //         }

    //         $expiry_date = date('Y-m-d', strtotime("+$months months", strtotime($effective_date)));

    //         $data['expiry_date'] = $expiry_date;

    //         $existingPurchase = PurchasePolicy::find($request->purchase_id ?? 0);

    //         if ($existingPurchase) {
    //             $clientPolicy = ClientPersonalAccidentInsurance::find($existingPurchase->policy_id);
    //             if ($clientPolicy) {
    //                 $clientPolicy->update($data);
    //             }

    //             $data['purchase_id'] = $existingPurchase->id;
    //             PurchasePolicy::updatePurchasePolicy($data);

    //             $policy_id = $existingPurchase->policy_id;
    //         } else {
    //             $data['client_id'] = $request->user_id;
    //             $data['police_no'] = rand(10000000, 99999999);

    //             $clientPolicy = ClientPersonalAccidentInsurance::create($data);

    //             $data['policy_type'] = 5;
    //             $data['policy_id'] = $clientPolicy->id;

    //             $purchase = PurchasePolicy::savePurchasePolicy($data);

    //             $policy_id = $clientPolicy->id;
    //         }
    //         // Fetch pricing rate based on age and months
    //         // $pricing_rate = 0;
    //         // if ($clientAge && $months > 0) {
    //         //     $pricingSchedule = PersonalAccidentPlanPricingSchedule::where('personal_accident_plan_id', $plan->id)
    //         //         ->where('age', $clientAge)
    //         //         ->first();

    //         //     if ($pricingSchedule) {
    //         //         $monthKey = 'm_' . $months;
    //         //         // Check if the column exists or is set in the model
    //         //         if (isset($pricingSchedule->$monthKey)) {
    //         //             $pricing_rate = $pricingSchedule->$monthKey;
    //         //         }
    //         //     }
    //         // }
    //         // Fetch pricing rate based on age and months
    //         $pricing_rate = 0;
    //         if ($clientAge && $months > 0) {
    //             $pricingSchedule = PersonalAccidentPlanPricingSchedule::where('personal_accident_plan_id', $plan->id)
    //                 ->where('age', $clientAge)
    //                 ->first();

    //             // if ($pricingSchedule && !empty($pricingSchedule->json_data)) {
    //             //     // json_data may already be array (if cast in model) or a raw JSON string
    //             //     $scheduleData = is_array($pricingSchedule->json_data)
    //             //         ? $pricingSchedule->json_data
    //             //         : json_decode($pricingSchedule->json_data, true);

    //             //     $monthKey = 'm_' . $months;

    //             //     if (is_array($scheduleData) && isset($scheduleData[$monthKey]) && $scheduleData[$monthKey] !== '') {
    //             //         $pricing_rate = (float) $scheduleData[$monthKey];
    //             //     }
    //                  if ($pricingSchedule) {
    //     $jsonData = $pricingSchedule->json_data;

    //     // If json_data is not automatically cast to array
    //     if (is_string($jsonData)) {
    //         $jsonData = json_decode($jsonData, true);
    //     }

    //     $monthKey = 'm_' . $months;

    //     if (isset($jsonData[$monthKey])) {
    //         $pricing_rate = (float) $jsonData[$monthKey];
    //     }
    //             }
    //         }
    //         $net_premium_rate = $pricing_rate > 0 ? $pricing_rate : $plan->net_premium;


    //         $policy_limit = $this->cleanNumber($plan->limit);
    //         $net_premium = ($net_premium_rate / 100) * $policy_limit;

    //         $feesPercentage = (float)$plan->fees;
    //         $stampsPercentage = (float)$plan->stamps;
    //         $salesTaxPercentage = (float)$plan->sales_tax;

    //         $cbjTaxPercentage = (float)$plan->cbj ?? 0;
    //         $cbjSalesTaxPercentage = (float)$plan->sales_tax_cbj ?? 0;

    //         // Issuance Fees (as % of Net Premium)
    //         $issuanceFees = ($net_premium * $feesPercentage) / 100;

    //         // Stamps (as % of Net Premium)
    //         $stampAmount = ($net_premium * $stampsPercentage) / 100;

    //         // CBJ Contribution Fund (as % of Net Premium)
    //         $cbjContribution = ($net_premium * $cbjTaxPercentage) / 100;

    //         // Sales Tax on (Net Premium + Issuance Fees)
    //         $salesTaxAmount = (($net_premium + $issuanceFees) * $salesTaxPercentage) / 100;

    //         // Sales Tax on CBJ Fund
    //         $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;

    //         // Total Gross Premium
    //         $grossPremium = $net_premium + $issuanceFees + $stampAmount + $salesTaxAmount + $cbjContribution + $cbjSalesTaxAmount;


    //         $purchaseData = [
    //             'net_premium'           => $net_premium,
    //             'fees'                  => $issuanceFees,
    //             'stamps'                => $stampAmount,
    //             'sales_tax'             => $salesTaxAmount,
    //             'cbj'                   => $cbjContribution,
    //             'sales_tax_cbj'         => $cbjSalesTaxAmount,
    //             'gross_premium'         => $grossPremium,
    //             'commission_amount'     => ($plan->commission_amount ?? 0) / 100 * $policy_limit,
    //             'commission_percentage' => $plan->commission_percentage,
    //             'policy_plan_limit'     => $policy_limit,
    //             'plan_id'               => $plan->id,
    //             'plan_name'             => $plan->plan_name,
    //             'insurance_company_id'  => $plan->insurance_company_id,
    //             'inception_date'        => $data['inception_date'],
    //             'expiry_date'           => $expiry_date,
    //             'purchase_id'           => $existingPurchase->id ?? $purchase->id,
    //         ];

    //         PurchasePolicy::updatePurchasePolicy($purchaseData);

    //         $purchasePolicy = PurchasePolicy::where('policy_id', $policy_id)->first();
    //         $responseData = ClientPersonalAccidentInsurance::getClientPersonalAccidentInsuranceDetails($policy_id);

    //         $client = Client::with('country.currency')->find($request->user_id);
    //         $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';
    //         if ($purchasePolicy) {


    //             $responseData->net_premium           = number_format($purchaseData['net_premium'], 2) . ' ' . $abbr;
    //             $responseData->fees                  = number_format($purchaseData['fees'], 2) . ' ' . $abbr;
    //             $responseData->stamps                = number_format($purchaseData['stamps'], 2) . ' ' . $abbr;
    //             $responseData->sales_tax             = number_format($purchaseData['sales_tax'], 2) . ' ' . $abbr;
    //             $responseData->cbj                   = number_format($purchaseData['cbj'], 2) . ' ' . $abbr;
    //             $responseData->sales_tax_cbj         = number_format($purchaseData['sales_tax_cbj'], 2) . ' ' . $abbr;
    //             $responseData->gross_premium         = number_format($purchaseData['gross_premium'], 2) . ' ' . $abbr;
    //             $responseData->commission_amount     = number_format($purchaseData['commission_amount'], 2) . ' ' . $abbr;
    //             $responseData->commission_percentage = $purchaseData['commission_percentage'];
    //         }


    //         $responseData->purchase_id = $purchasePolicy->id ?? null;

    //         $directory = public_path('insurance_pdfs/personal_accident_policy');
    //         if (!file_exists($directory)) {
    //             mkdir($directory, 0755, true);
    //         }

    //         $filename = uniqid() . '_policy_' . $policy_id . '.pdf';
    //         $path = $directory . '/' . $filename;
    //         // Prepare unformatted data for PDF - needs to match the dynamic calculation
    //         $plan_for_pdf = clone $plan;
    //         // Store the amounts in the plan object for the PDF values
    //         $plan_for_pdf->net_premium_amount = $purchaseData['net_premium'];
    //         $plan_for_pdf->fees_amount = $purchaseData['fees'];
    //         $plan_for_pdf->stamps_amount = $purchaseData['stamps'];
    //         $plan_for_pdf->sales_tax_amount = $purchaseData['sales_tax'];
    //         $plan_for_pdf->cbj_amount = $purchaseData['cbj'];
    //         $plan_for_pdf->sales_tax_cbj_amount = $purchaseData['sales_tax_cbj'];
    //         $plan_for_pdf->gross_premium_amount = $purchaseData['gross_premium'];
    //         $plan_for_pdf->covers = $plan->policy_covers ?? [];
    //         $pdf = PDF::loadView('pdf/personal_accident_policy', [
    //             'data' => $responseData,
    //             'purchase' => $purchaseData,
    //             'abbr' => $abbr,
    //             'plan' => $plan_for_pdf
    //         ]);

    //         $pdf->save($path);
    //         $responseData->url = url('insurance_pdfs/personal_accident_policy/' . $filename);

    //         return response()->json([
    //             'status' => true,
    //             'status_code' => 200,
    //             'message' => 'Add Personal Accident Insurance Plan successfully',
    //             'data' => $responseData
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

 public function storePersonalAccidentInsurance(Request $request)
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
                'marital_status' => 'nullable',
                'place_residence' => 'nullable',
                'company_name' => 'nullable',
                'position' => 'nullable',
                'work_nature' => 'nullable',
                'city_id' => 'nullable',
                'district_id' => 'nullable',
                'street_name' => 'nullable',
                'building_no' => 'nullable',
                'company_contact' => 'nullable',
                'company_city_id' => 'nullable',
                'inception_date' => 'nullable',
                'inception_period' => 'nullable',
                'occupany_type_work' => 'nullable',
                'photo_documents_1' => 'nullable',
                'photo_documents_2' => 'nullable',
                'photo_documents_3' => 'nullable',
                'plan_id' => 'nullable',
                'payment_status' => 'nullable',
                'dangerous_field' => 'nullable',
            ]);

            $plan = PersonalAccidentPlan::find($data['plan_id']);
            if (!$plan) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Plan not found',
                    'data' => []
                ]);
            }

            $data['insurance_company_id'] = $plan->insurance_company_id;
            // dd($data);
            $client = Client::find($request->user_id);
            if (!$client) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Client not found.',
                    'data' => []
                ], 422);
            }

            $clientCountry  = $client->country_id;
            $clientCity     = $client->city_id;
            $clientDistrict = $client->district_id;
            $clientOccupation = $client->occupation_id;
            $clientAge = Carbon::parse($client->birth_date)->age;

            $restrictedCountries   = $this->normalizeRestricted($plan->restricted_country_ids);
            $restrictedCities      = $this->normalizeRestricted($plan->restricted_city_ids);
            $restrictedDistricts   = $this->normalizeRestricted($plan->restricted_district_ids);
            $restrictedAges        = $this->normalizeRestricted($plan->restricted_age_ids);
            $restrictedOccupations = $this->normalizeRestricted($plan->restricted_occupation_ids);
            $userDangerousFields = $this->normalizeRestricted($request->dangerous_field ?? null);
            $data['dangerous_field'] = !empty($userDangerousFields) ? implode(',', $userDangerousFields) : null;
            $restrictedDangerousFields = $this->normalizeRestricted($plan->restricted_dangerous_activities_ids ?? null);

            if ($clientCountry && in_array($clientCountry, $restrictedCountries)) {
                return $this->restrictionError('country');
            }

            if ($clientCity && in_array($clientCity, $restrictedCities)) {
                return $this->restrictionError('city');
            }

            if ($clientDistrict && in_array($clientDistrict, $restrictedDistricts)) {
                return $this->restrictionError('district');
            }
            if (!empty($restrictedDangerousFields) && !empty($userDangerousFields)) {
                if (array_intersect($userDangerousFields, $restrictedDangerousFields)) {
                    return $this->restrictionError('dangerous activity');
                }
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

            $effective_date = $data['inception_date'];

            $months = $plan->policy_period ?? 0;
            if ($plan->insurance_period) {
                $periodName = strtolower($plan->insurance_period->name);
                $number = (int)preg_replace('/[^0-9]/', '', $periodName);
                if (str_contains($periodName, 'year')) {
                    $months = $number * 12;
                } elseif (str_contains($periodName, 'month')) {
                    $months = $number;
                }
            }

            $expiry_date = date('Y-m-d', strtotime("+$months months", strtotime($effective_date)));

            $data['expiry_date'] = $expiry_date;

            $existingPurchase = PurchasePolicy::find($request->purchase_id ?? 0);

            if ($existingPurchase) {
                $clientPolicy = ClientPersonalAccidentInsurance::find($existingPurchase->policy_id);
                if ($clientPolicy) {
                    $clientPolicy->update($data);
                }

                $data['purchase_id'] = $existingPurchase->id;
                PurchasePolicy::updatePurchasePolicy($data);

                $policy_id = $existingPurchase->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $clientPolicy = ClientPersonalAccidentInsurance::create($data);

                $data['policy_type'] = 5;
                $data['policy_id'] = $clientPolicy->id;

                $purchase = PurchasePolicy::savePurchasePolicy($data);

                $policy_id = $clientPolicy->id;
            }
            $clientAge = Carbon::parse($client->birth_date)->age;

            $months = (int) ($data['inception_period'] ?? 0);


            // Fetch pricing rate based on age and months
            $pricing_rate = 0;
            if ($clientAge && $months > 0) {


                $pricingSchedule = PersonalAccidentPlanPricingSchedule::where('personal_accident_plan_id', $plan->id)
                    ->where('age', $clientAge)
                    ->first();

                // if ($pricingSchedule) {
                //     $monthKey = 'm_' . $months;
                //     // Check if the column exists or is set in the model
                //     if (isset($pricingSchedule->$monthKey)) {
                //         $pricing_rate = $pricingSchedule->$monthKey;
                //     }
                // }

                if ($pricingSchedule) {

                    $jsonData = $pricingSchedule->json_data;

                    // If json_data is not automatically cast to array
                    if (is_string($jsonData)) {
                        $jsonData = json_decode($jsonData, true);
                    }


                    
                    $monthKey = 'm_' . $months;


                    

                    if (is_array($jsonData) && isset($jsonData[$monthKey])) {

                        $pricing_rate = (float) $jsonData[$monthKey];

                    }
                }
            }
            $net_premium_rate = $pricing_rate > 0 ? $pricing_rate : $plan->net_premium;

           
            $policy_limit = $this->cleanNumber($plan->limit);

            $net_premium = ($net_premium_rate / 100) * $policy_limit;




            $feesPercentage = (float)$plan->fees;
            $stampsPercentage = (float)$plan->stamps;
            $salesTaxPercentage = (float)$plan->sales_tax;

            $cbjTaxPercentage = (float)$plan->cbj ?? 0;
            $cbjSalesTaxPercentage = (float)$plan->sales_tax_cbj ?? 0;



            // Issuance Fees (as % of Net Premium)
            $issuanceFees = ($net_premium * $feesPercentage) / 100;


           
            // Stamps (as % of Net Premium)
            $stampAmount = ($net_premium * $stampsPercentage) / 100;


            // CBJ Contribution Fund (as % of Net Premium)
            $cbjContribution = ($net_premium * $cbjTaxPercentage) / 100;

           

            // Sales Tax on (Net Premium + Issuance Fees)
            $salesTaxAmount = (($net_premium + $issuanceFees) * $salesTaxPercentage) / 100;


            

            // Sales Tax on CBJ Fund
            $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;



            // Total Gross Premium
            $grossPremium = $net_premium + $issuanceFees + $stampAmount + $salesTaxAmount + $cbjContribution + $cbjSalesTaxAmount;

            
            // ===============

            $purchaseData = [
                'net_premium'           => $net_premium,
                'fees'                  => $issuanceFees,
                'stamps'                => $stampAmount,
                'sales_tax'             => $salesTaxAmount,
                'cbj'                   => $cbjContribution,
                'sales_tax_cbj'         => $cbjSalesTaxAmount,
                'gross_premium'         => $grossPremium,
                'commission_amount'     => ($plan->commission_amount ?? 0) / 100 * $policy_limit,
                'commission_percentage' => $plan->commission_percentage,
                'policy_plan_limit'     => $policy_limit,
                'plan_id'               => $plan->id,
                'plan_name'             => $plan->plan_name,
                'insurance_company_id'  => $plan->insurance_company_id,
                'inception_date'        => $data['inception_date'],
                'expiry_date'           => $expiry_date,
                'purchase_id'           => $existingPurchase->id ?? $purchase->id,
            ];
            PurchasePolicy::updatePurchasePolicy($purchaseData);

            $purchasePolicy = PurchasePolicy::where('policy_id', $policy_id)->first();
            $responseData = ClientPersonalAccidentInsurance::getClientPersonalAccidentInsuranceDetails($policy_id);

            $client = Client::with('country.currency')->find($request->user_id);
            $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';
            if ($purchasePolicy) {


                $responseData->net_premium           = number_format($purchaseData['net_premium'], 2) . ' ' . $abbr;
                $responseData->fees                  = number_format($purchaseData['fees'], 2) . ' ' . $abbr;
                $responseData->stamps                = number_format($purchaseData['stamps'], 2) . ' ' . $abbr;
                $responseData->sales_tax             = number_format($purchaseData['sales_tax'], 2) . ' ' . $abbr;
                $responseData->cbj                   = number_format($purchaseData['cbj'], 2) . ' ' . $abbr;
                $responseData->sales_tax_cbj         = number_format($purchaseData['sales_tax_cbj'], 2) . ' ' . $abbr;
                $responseData->gross_premium         = number_format($purchaseData['gross_premium'], 2) . ' ' . $abbr;
                $responseData->commission_amount     = number_format($purchaseData['commission_amount'], 2) . ' ' . $abbr;
                $responseData->commission_percentage = $purchaseData['commission_percentage'];
            }


            $responseData->purchase_id = $purchasePolicy->id ?? null;

            $directory = public_path('insurance_pdfs/personal_accident_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = uniqid() . '_policy_' . $policy_id . '.pdf';
            $path = $directory . '/' . $filename;
            // Prepare unformatted data for PDF - needs to match the dynamic calculation
            $plan_for_pdf = clone $plan;
            // Store the amounts in the plan object for the PDF values
            $plan_for_pdf->net_premium_amount = $purchaseData['net_premium'];
            $plan_for_pdf->fees_amount = $purchaseData['fees'];
            $plan_for_pdf->stamps_amount = $purchaseData['stamps'];
            $plan_for_pdf->sales_tax_amount = $purchaseData['sales_tax'];
            $plan_for_pdf->cbj_amount = $purchaseData['cbj'];
            $plan_for_pdf->sales_tax_cbj_amount = $purchaseData['sales_tax_cbj'];
            $plan_for_pdf->gross_premium_amount = $purchaseData['gross_premium'];
            $plan_for_pdf->covers = $plan->policy_covers ?? [];
            $pdf = PDF::loadView('pdf/personal_accident_policy', [
                'data' => $responseData,
                'purchase' => $purchaseData,
                'abbr' => $abbr,
                'plan' => $plan_for_pdf
            ]);

            $pdf->save($path);
            $responseData->url = url('insurance_pdfs/personal_accident_policy/' . $filename);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Add Personal Accident Insurance Plan successfully',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    // public function getPersonalAccidentPlanInsurancePlan(Request $request)
    // {
    //     try {
    //         // $data = PersonalAccidentPlan::with('policy_covers', 'insurance_company')
    //         //     ->where('limit', 'LIKE', '%' . $request->limit . '%')
    //         //     ->get();
    //         $query = PersonalAccidentPlan::with('policy_covers', 'insurance_company')
    //             ->whereHas('insurance_company', function ($q) {
    //                 $q->whereNull('deleted_at');
    //             })
    //             ->where('limit', 'LIKE', '%' . $request->limit . '%');
    //         if ($request->insurance_company_id) {
    //             $query->where('insurance_company_id', $request->insurance_company_id);
    //         }
    //         // Support 'insurance_company' param as alias for ID, just in case
    //         if ($request->insurance_company && !$request->insurance_company_id) {
    //             if (is_numeric($request->insurance_company)) {
    //                 $query->where('insurance_company_id', $request->insurance_company);
    //             }
    //         }

    //         $periodInput = $request->input('insurance_period') ?? $request->input('insurance_period_id');

    //         if ($periodInput) {
    //             // If input is numeric, assume it's an ID
    //             if (is_numeric($periodInput)) {
    //                 $query->where('insurance_period_id', $periodInput);
    //             } else {
    //                 // If input is string (e.g. "6 Months"), try to find period by name
    //                 $period = InsurancePeriod::where('name', 'LIKE', '%' . $periodInput . '%')->first();
    //                 if ($period) {
    //                     $query->where('insurance_period_id', $period->id);
    //                 }
    //             }
    //         }

    //         $data = $query->get();
    //         foreach ($data as $item) {
    //             if (!empty($item->insurance_policy_pdf)) {
    //                 $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
    //             }
    //             if (!empty($item->insurance_company->privacy_policy)) {
    //                 $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
    //             }
    //         }
    //         if ($data->isEmpty()) {
    //             return response()->json([
    //                 'status' => true,
    //                 'status_code' => 200,
    //                 'message' => 'No plans found for the selected criteria',
    //                 'data' => []
    //             ]);
    //         }


    //         return response()->json([
    //             'status' => true,
    //             'status_code' => 200,
    //             'message' => 'Get Personal Accident Plan Insurance Plan successfully',
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
    // public function getPersonalAccidentPlanInsurancePlan(Request $request)
    // {
    //     try {
    //         // $data = PersonalAccidentPlan::with('policy_covers', 'insurance_company')
    //         //     ->where('limit', 'LIKE', '%' . $request->limit . '%')

    //         //     ->get();
    //         $query = PersonalAccidentPlan::with('policy_covers', 'insurance_company', 'pricing_schedule')
    //             ->whereHas('insurance_company', function ($q) {
    //                 $q->whereNull('deleted_at');
    //             })
    //             ->where('limit', 'LIKE', '%' . $request->limit . '%');
    //         if ($request->insurance_company_id) {
    //             $query->where('insurance_company_id', $request->insurance_company_id);
    //         }
    //         // Support 'insurance_company' param as alias for ID, just in case
    //         if ($request->insurance_company && !$request->insurance_company_id) {
    //             if (is_numeric($request->insurance_company)) {
    //                 $query->where('insurance_company_id', $request->insurance_company);
    //             }
    //         }

    //         $periodInput = $request->input('insurance_period') ?? $request->input('insurance_period_id');

    //         if ($periodInput) {
    //             // If input is numeric, assume it's an ID
    //             if (is_numeric($periodInput)) {
    //                 $query->where('insurance_period_id', $periodInput);
    //             } else {
    //                 // If input is string (e.g. "6 Months"), try to find period by name
    //                 $period = InsurancePeriod::where('name', 'LIKE', '%' . $periodInput . '%')->first();
    //                 if ($period) {
    //                     $query->where('insurance_period_id', $period->id);
    //                 }
    //             }
    //         }

    //         $data = $query->get();
    //         foreach ($data as $item) {
    //             if (!empty($item->insurance_policy_pdf)) {
    //                 $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
    //             }
    //             if (!empty($item->insurance_company->privacy_policy)) {
    //                 $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
    //             }
    //         }
    //         if ($data->isEmpty()) {
    //             return response()->json([
    //                 'status' => true,
    //                 'status_code' => 200,
    //                 'message' => 'No plans found for the selected criteria',
    //                 'data' => []
    //             ]);
    //         }


    //         return response()->json([
    //             'status' => true,
    //             'status_code' => 200,
    //             'message' => 'Get Personal Accident Plan Insurance Plan successfully',
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
       public function getPersonalAccidentPlanInsurancePlan(Request $request)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Build Query
            |--------------------------------------------------------------------------
            */
            $query = PersonalAccidentPlan::with(
                'policy_covers',
                'insurance_company',
                'pricing_schedule'
            )
                ->whereHas('insurance_company', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->where('limit', 'LIKE', '%' . $request->limit . '%');


            /*
            |--------------------------------------------------------------------------
            | Filter By Insurance Company ID
            |--------------------------------------------------------------------------
            */
            if ($request->insurance_company_id) {

                $query->where(
                    'insurance_company_id',
                    $request->insurance_company_id
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Support insurance_company as alias
            |--------------------------------------------------------------------------
            */
            if (
                $request->insurance_company &&
                !$request->insurance_company_id
            ) {

                if (is_numeric($request->insurance_company)) {

                    $query->where(
                        'insurance_company_id',
                        $request->insurance_company
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Get Plans
            |--------------------------------------------------------------------------
            */
            $data = $query->get();



            $periodInput = $request->input('insurance_period')
                ?? $request->input('insurance_period_id');


            if (
                $periodInput !== null &&
                $periodInput !== '' &&
                is_numeric($periodInput)
            ) {

                $month = (int) $periodInput;

                /*
                |--------------------------------------------------------------------------
                | Prevent invalid month
                |--------------------------------------------------------------------------
                */
                if ($month >= 1 && $month <= 12) {

                    $monthKey = 'm_' . $month;


                    /*
                    |--------------------------------------------------------------------------
                    | Filter Pricing Schedule
                    |--------------------------------------------------------------------------
                    */
                    foreach ($data as $item) {

                        $item->pricing_schedule = $item->pricing_schedule
                            ->map(function ($schedule) use ($monthKey) {


                                $jsonData = $schedule->json_data ?? [];


                                if (is_string($jsonData)) {

                                    $decodedData = json_decode(
                                        $jsonData,
                                        true
                                    );

                                    $jsonData = is_array($decodedData)
                                        ? $decodedData
                                        : [];
                                }


                                if (!is_array($jsonData)) {
                                    $jsonData = [];
                                }

                                $age = $jsonData['age']
                                    ?? $schedule->age;


                                $filteredJsonData = [
                                    'age' => $age,
                                ];


                                if (array_key_exists($monthKey, $jsonData)) {

                                    $filteredJsonData[$monthKey] =
                                        $jsonData[$monthKey];
                                }


                                $schedule->json_data = $filteredJsonData;

                                $schedule->makeHidden([
                                    'm_1',
                                    'm_2',
                                    'm_3',
                                    'm_4',
                                    'm_5',
                                    'm_6',
                                    'm_7',
                                    'm_8',
                                    'm_9',
                                    'm_10',
                                    'm_11',
                                    'm_12',
                                ]);


                                return $schedule;
                            })
                            ->values();
                    }
                }
            }


            foreach ($data as $item) {

                /*
                |--------------------------------------------------------------------------
                | Insurance Policy PDF
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
                | Insurance Company Privacy Policy
                |--------------------------------------------------------------------------
                */
                if (
                    !empty(
                    $item->insurance_company->privacy_policy
                )
                ) {

                    $item->insurance_company->privacy_policy = url(
                        'insurance/' .
                        $item->insurance_company->id .
                        '/' .
                        $item->insurance_company->privacy_policy
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | No Plans Found
            |--------------------------------------------------------------------------
            */
            if ($data->isEmpty()) {

                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'No plans found for the selected criteria',
                    'data' => []
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Success Response
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Get Personal Accident Plan Insurance Plan successfully',
                'data' => $data
            ]);


        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
