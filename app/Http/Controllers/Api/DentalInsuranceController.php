<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ClientDentalsInsurance, PurchasePolicy};
use App\Models\InsurancePlanModels\DentalPlan;
use Illuminate\Http\Request;
use PDF;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Currency;
use App\Helpers\InsurancePlanHelper;

class DentalInsuranceController extends Controller
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
        $message = __('messages.api.dental_restriction_message', [
            'type' => __('messages.api.dental_restriction_' . $type)
        ]);

        if ($type === 'country') {
            $message .= __('messages.api.dental_country_contact');
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

    // public function storeDentalInsurance(Request $request)
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
    //             'occupancy_work' => 'nullable',
    //             'city_id' => 'nullable',
    //             'district_id' => 'nullable',
    //             'street_name' => 'nullable',
    //             'building_no' => 'nullable',
    //             'company_name' => 'nullable',
    //             'position' => 'nullable',
    //             'work_nature' => 'nullable',
    //             'company_city_id' => 'nullable',
    //             'company_district_id' => 'nullable',
    //             'company_street_name' => 'nullable',
    //             'company_building_no' => 'nullable',
    //             'company_contact' => 'nullable',
    //             'insurance_limit' => 'nullable',
    //             'inception_date' => 'nullable',
    //             'expiry_date' => 'nullable',
    //             'documents' => 'nullable',
    //             'plan_id' => 'required',
    //             'payment_status' => 'nullable',
    //         ]);

    //         $plan = DentalPlan::findOrFail($data['plan_id']);
    //         $data['insurance_company_id'] = $plan->insurance_company_id ?? 0;

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

    //         $clientAge = Carbon::parse($client->birth_date)->age;

    //         $restrictedCountries = $this->normalizeRestricted($plan->restricted_country_ids);
    //         $restrictedCities    = $this->normalizeRestricted($plan->restricted_city_ids);
    //         $restrictedDistricts = $this->normalizeRestricted($plan->restricted_district_ids);
    //         $restrictedAges      = $this->normalizeRestricted($plan->restricted_age_ids);

    //         if ($clientCountry && in_array($clientCountry, $restrictedCountries)) {
    //             return $this->restrictionError('country');
    //         }

    //         if ($clientCity && in_array($clientCity, $restrictedCities)) {
    //             return $this->restrictionError('city');
    //         }

    //         if ($clientDistrict && in_array($clientDistrict, $restrictedDistricts)) {
    //             return $this->restrictionError('district');
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

    //         $existingDental = PurchasePolicy::find($request->purchase_id ?? 0);

    //         if ($existingDental) {

    //             $dental = ClientDentalsInsurance::find($existingDental->policy_id);
    //             if ($dental) {
    //                 $dental->update($data);
    //             }

    //             $data['purchase_id'] = $existingDental->id;
    //             $dental = PurchasePolicy::updatePurchasePolicy($data);
    //             $data['policy_id'] = $dental->policy_id;
    //         } else {

    //             $data['client_id'] = $request->user_id;
    //             $data['police_no'] = rand(10000000, 99999999);

    //             $dental = ClientDentalsInsurance::create($data);

    //             $data['policy_type'] = 9;
    //             $data['policy_id'] = $dental->id;

    //             $dental = PurchasePolicy::savePurchasePolicy($data);
    //         }

    //         $data = ClientDentalsInsurance::getDentalsInsuranceDetails($data['policy_id']);

    //         $directory = public_path('insurance_pdfs/dentals_policy');
    //         if (!file_exists($directory)) mkdir($directory, 0755, true);

    //         $filename = uniqid() . '_policy_' . $dental->id . '.pdf';
    //         $path = $directory . '/' . $filename;

    //         $policy_limit = $this->cleanNumber($plan->limit);

    //         $purchase['net_premium']       = ($plan->net_premium / 100) * $policy_limit;
    //         // $purchase['fees']              = ($plan->fees / 100) * $policy_limit;
    //         // $purchase['stamps']            = ($plan->stamps / 100) * $policy_limit;
    //         // $purchase['sales_tax']         = ($plan->sales_tax / 100) * $policy_limit;
    //         // $purchase['gross_premium']     = ($plan->gross_premium / 100) * $policy_limit;
    //         $net_premium = $purchase['net_premium'];
    //         $feesPercentage = $plan->fees;
    //         $stampsPercentage = $plan->stamps;
    //         $salesTaxPercentage = $plan->sales_tax;

    //         $cbjTaxPercentage = $plan->cbj ?? 0;
    //         $cbjSalesTaxPercentage = $plan->sales_tax_cbj ?? 0;

    //         // Formulas
    //         $issuanceFees = ($net_premium * $feesPercentage) / 100;
    //         $stampAmount = ($net_premium * $stampsPercentage) / 100;
    //         $cbjContribution = ($net_premium * $cbjTaxPercentage) / 100;
    //         $salesTaxAmount = (($net_premium + $issuanceFees) * $salesTaxPercentage) / 100;
    //         $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;
    //         $grossPremium = $net_premium + $issuanceFees + $stampAmount + $salesTaxAmount + $cbjContribution + $cbjSalesTaxAmount;

    //         $purchase['fees']              = $issuanceFees;
    //         $purchase['stamps']            = $stampAmount;
    //         $purchase['sales_tax']         = $salesTaxAmount;
    //         $purchase['cbj']               = $cbjContribution;
    //         $purchase['sales_tax_cbj']     = $cbjSalesTaxAmount;
    //         $purchase['gross_premium']     = $grossPremium;
    //         $purchase['commission_amount'] = ($plan->commission_amount ?? 0) / 100 * $policy_limit;

    //         $purchase['purchase_id']          = $dental->id;
    //         $purchase['plan_id']              = $plan->id;
    //         $purchase['plan_name']            = $plan->plan_name;
    //         $purchase['policy_plan_limit']    = $policy_limit;
    //         $purchase['insurance_company_id'] = $data->insurance_company_id;
    //         $purchase['inception_date']       = $data->inception_date;
    //         $purchase['expiry_date']          = $data->expiry_date;
    //         $purchase['commission_percentage'] = $data->commission_percentage ?? 0;
    //         $purchase['policy_pdf_url']       = $path;

    //         PurchasePolicy::updatePurchasePolicy($purchase);

    //         $purchaseData = PurchasePolicy::where('id', $dental->id)->first();

    //         $pdf = PDF::loadView('pdf.dentals_policy', [
    //             'data' => $data,
    //             'purchase' => $purchaseData
    //         ]);

    //         $pdf->save($path);

    //         $data['url'] = url('insurance_pdfs/dentals_policy/' . $filename);

    //         $currency = Currency::where('abbreviation', 'JOD')->first();
    //         $abbr = $currency ? $currency->abbreviation : 'JOD';

    //         $data->net_premium       = number_format($purchase['net_premium'], 2) . ' ' . $abbr;
    //         $data->fees              = number_format($purchase['fees'], 2) . ' ' . $abbr;
    //         $data->gross_premium     = number_format($purchase['gross_premium'], 2) . ' ' . $abbr;
    //         $data->sales_tax         = number_format($purchase['sales_tax'], 2) . ' ' . $abbr;
    //         $data->stamps            = number_format($purchase['stamps'], 2) . ' ' . $abbr;
    //         $data->commission_amount = number_format($purchase['commission_amount'], 2) . ' ' . $abbr;

    //         return response()->json([
    //             'status' => true,
    //             'status_code' => 200,
    //             'message' => 'Dental Insurance Plan added successfully',
    //             'data' => $data
    //         ]);
    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'status' => false,
    //             'status_code' => 500,
    //             'message' => $e->getMessage(),
    //             'data' => []
    //         ]);
    //     }
    // }

    public function storeDentalInsurance(Request $request)
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
                'occupancy_work' => 'nullable',
                'city_id' => 'nullable',
                'district_id' => 'nullable',
                'street_name' => 'nullable',
                'building_no' => 'nullable',
                'company_name' => 'nullable',
                'position' => 'nullable',
                'work_nature' => 'nullable',
                'company_city_id' => 'nullable',
                'company_district_id' => 'nullable',
                'company_street_name' => 'nullable',
                'company_building_no' => 'nullable',
                'company_contact' => 'nullable',
                'insurance_limit' => 'nullable',
                'inception_date' => 'nullable',
                'expiry_date' => 'nullable',
                'documents' => 'nullable',
                'plan_id' => 'required',
                'payment_status' => 'nullable',
            ]);

            // $plan = DentalPlan::findOrFail($data['plan_id']);
            $plan = DentalPlan::with('insurance_company.currency')->findOrFail($data['plan_id']);
            $data['insurance_company_id'] = $plan->insurance_company_id ?? 0;

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

            $clientAge = Carbon::parse($client->birth_date)->age;

            $restrictedCountries = $this->normalizeRestricted($plan->restricted_country_ids);
            $restrictedCities    = $this->normalizeRestricted($plan->restricted_city_ids);
            $restrictedDistricts = $this->normalizeRestricted($plan->restricted_district_ids);
            $restrictedAges      = $this->normalizeRestricted($plan->restricted_age_ids);

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

            $existingDental = PurchasePolicy::find($request->purchase_id ?? 0);

            if ($existingDental) {

                $dental = ClientDentalsInsurance::find($existingDental->policy_id);

                if ($dental) {
                    $dental->update($data);
                }

                $data['purchase_id'] = $existingDental->id;
                $dental = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $dental->policy_id;
            } else {

                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $dental = ClientDentalsInsurance::create($data);

                $data['policy_type'] = 9;
                $data['policy_id'] = $dental->id;

                $dental = PurchasePolicy::savePurchasePolicy($data);
            }

            $data = ClientDentalsInsurance::getDentalsInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/dentals_policy');

            if (!file_exists($directory)) mkdir($directory, 0755, true);

            $filename = uniqid() . '_policy_' . $dental->id . '.pdf';
            $path = $directory . '/' . $filename;

            $policy_limit = $this->cleanNumber($plan->limit);

            $net_premium = (float)$plan->net_premium;
            $feesPercentage = (float)$plan->fees;
            $stampsPercentage = (float)$plan->stamps;
            $salesTaxPercentage = (float)$plan->sales_tax;

            $cbjTaxPercentage = (float)$plan->cbj;
            $cbjSalesTaxPercentage = (float)$plan->sales_tax_cbj;

            // Issuance Fees (as % of Net Premium)
            $issuanceFees = ($net_premium * $feesPercentage) / 100;

            // Stamps (as % of Net Premium)
            $stampAmount = ($net_premium * $stampsPercentage) / 100;

            // CBJ Contribution Fund (0.5% of Net Premium)
            $cbjContribution = ($net_premium * $cbjTaxPercentage) / 100;

            // Sales Tax on (Net Premium + Issuance Fees)
            $salesTaxAmount = (($net_premium + $issuanceFees) * $salesTaxPercentage) / 100;

            // Sales Tax on CBJ Fund
            $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;

            // Total Gross Premium
            $grossPremium = $net_premium
                + $issuanceFees
                + $stampAmount
                + $salesTaxAmount
                + $cbjContribution
                + $cbjSalesTaxAmount;

            $purchase['net_premium']       = $net_premium;
            $purchase['fees']              = $issuanceFees;
            $purchase['stamps']            = $stampAmount;
            $purchase['sales_tax']         = $salesTaxAmount;
            $purchase['cbj']               = $cbjContribution;
            $purchase['sales_tax_cbj']     = $cbjSalesTaxAmount;
            $purchase['gross_premium']     = $grossPremium;
            $purchase['commission_amount'] = ($plan->commission_percentage ?? 0) / 100 * $net_premium;
            $purchase['purchase_id']          = $dental->id;
            $purchase['plan_id']              = $data->plan_id;
            $purchase['plan_name']            = $plan->plan_name;
            $purchase['policy_plan_limit']    = $policy_limit;
            $purchase['insurance_company_id'] = $data->insurance_company_id;
            $purchase['inception_date']       = $data->inception_date;
            $purchase['expiry_date']          = $data->expiry_date;
            $purchase['commission_percentage'] = $data->commission_percentage;
            $purchase['policy_pdf_url']       = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);

            $purchaseData = PurchasePolicy::where('id', $dental->id)->first();
            $plan_data = DentalPlan::with('policy_covers')->find($data['plan_id']);

            // $client = Client::with('country.currency')->find($request->user_id);
            // $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';

            $client = Client::with('country.currency')->find($request->user_id);

            // Get insurance company currency first
            $insuranceCompany = $plan->insurance_company;

            if ($insuranceCompany && $insuranceCompany->currency) {
                $abbr = $insuranceCompany->currency->abbreviation;
            } else {
                // Fallback to client's country currency
                $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';
            }

            $pdf = PDF::loadView('pdf.dentals_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'purchase' => $purchaseData,
                'plan' => $plan_data
            ]);

            $pdf->save($path);

            $data['url'] = url('insurance_pdfs/dentals_policy/' . $filename);
            $data->purchase_id = $dental->id;
            $data->purchase_policy_id = $dental->id;

            $data->net_premium       = number_format($purchase['net_premium'], 2) . ' ' . $abbr;
            $data->fees              = number_format($purchase['fees'], 2) . ' ' . $abbr;
            $data->gross_premium     = number_format($purchase['gross_premium'], 2) . ' ' . $abbr;
            $data->sales_tax         = number_format($purchase['sales_tax'], 2) . ' ' . $abbr;
            $data->stamps            = number_format($purchase['stamps'], 2) . ' ' . $abbr;
            $data->cbj               = number_format($purchase['cbj'], 2) . ' ' . $abbr;
            $data->sales_tax_cbj     = number_format($purchase['sales_tax_cbj'], 2) . ' ' . $abbr;
            $data->commission_amount = number_format($purchase['commission_amount'], 2) . ' ' . $abbr;

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.dental_insurance_plan_added_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dental Insurance Purchase Error: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function getDentalInsurancePlan(Request $request)
    {
        try {

            $query = DentalPlan::with([
                'policy_covers',
                'insurance_company',
                'insurance_company.currency'
            ])
                ->whereHas('insurance_company', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->where('plan_name', $request->limit);

            // Filter plans according to client's currency
            $query = InsurancePlanHelper::filterByClientCurrency(
                $query,
                $request->user_id
            );

            $data = $query->get();

            // dd($data);
            $data->transform(function ($item) {
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url(
                        'uploads/insurance_plans/' .
                            $item->id .
                            '/' .
                            $item->insurance_policy_pdf
                    );
                }

                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url(
                        'insurance/' .
                            $item->insurance_company->id .
                            '/' .
                            $item->insurance_company->privacy_policy
                    );
                }

                return $item;
            });

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.dental_insurance_plans_fetched_successfully'),
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
