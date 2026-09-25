<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{CriticalIllnessInsurance, PurchasePolicy};
use App\Models\InsurancePlanModels\{CriticalIllnessPlan, CriticalIllnessPlanPolicyCover};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\RenewalPolicy;
use App\Models\Currency;
use App\Helpers\InsurancePlanHelper;

class CriticalIllnessInsuranceController extends Controller
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
        $keyMap = [
            'country'         => __('messages.api.critical_illness_restriction_country'),
            'city'            => __('messages.api.critical_illness_restriction_city'),
            'district'        => __('messages.api.critical_illness_restriction_district'),
            'age'             => __('messages.api.critical_illness_restriction_age'),
            'occupation'      => __('messages.api.critical_illness_restriction_occupation'),
            'chronic disease' => __('messages.api.critical_illness_restriction_chronic_disease'),
        ];

        $typeLabel = $keyMap[$type] ?? $type;

        $message = __('messages.api.critical_illness_restriction_message', ['type' => $typeLabel]);

        if ($type === 'country') {
            $message .= __('messages.api.critical_illness_country_contact');
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

    public function storeCriticalIllnessInsurance(Request $request)
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
                'height' => 'nullable',
                'wight' => 'nullable',
                'chronic_diseases_id' => 'nullable',
                'previous_operation' => 'nullable',
                'operation_details' => 'nullable',
                'previous_insurance_policy' => 'nullable',
                'previous_insurance_policy_details' => 'nullable',
                'insurance_amount' => 'nullable',
                'insurance_plan' => 'nullable',
                'inception_date' => 'required',
                'expiry_date' => 'required',
                'passport_id_documents' => 'nullable',
                'insured_documents' => 'nullable',
                'plan_id' => 'nullable',
                'payment_status' => 'nullable',

                'old_policy_id_for_renew' => 'nullable|integer',
                'renew' => 'nullable|boolean',

            ]);

            // $plan_data = CriticalIllnessPlan::find($data['plan_id']);
            $plan_data = CriticalIllnessPlan::with('insurance_company.currency')->find($data['plan_id']);

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

            $userChronics = $this->normalizeRestricted($data['chronic_diseases_id'] ?? '');

            if (!empty($userChronics)) {
                foreach ($userChronics as $chronicId) {
                    if (in_array($chronicId, $restrictedChronic)) {
                        return $this->restrictionError('chronic disease');
                    }
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

            $existing = PurchasePolicy::find($request->purchase_id ?? 0);

            if ($existing) {
                $critical = CriticalIllnessInsurance::find($existing->policy_id);

                if ($critical) $critical->update($data);

                $data['purchase_id'] = $existing->id;
                $data['inception_date'] = $data['inception_date'];

                $critical = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $critical->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $critical = CriticalIllnessInsurance::create($data);

                $data['policy_type'] = 4;
                $data['policy_id'] = $critical->id;
                $data['inception_date'] = $data['inception_date'];

                $critical = PurchasePolicy::savePurchasePolicy($data);

                if ($request->boolean('renew')) {

                    RenewalPolicy::create([
                        'old_policy_id' => $oldPolicyForRenewal->id,
                        'new_policy_id' => $critical->id,
                    ]);
                }
            }

            $data = CriticalIllnessInsurance::getCriticalIllnessInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/criticalIllness_policy');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = uniqid() . '_policy_' . $critical->id . '.pdf';
            $path = $directory . '/' . $filename;

            $policy_limit = $this->cleanNumber($plan_data->limit);

            $purchase['net_premium'] = ($plan_data->net_premium / 100) * $policy_limit;

            // $purchase['fees'] = ($plan_data->fees / 100) * $policy_limit;
            // $purchase['stamps'] = ($plan_data->stamps / 100) * $policy_limit;
            // $purchase['sales_tax'] = ($plan_data->sales_tax / 100) * $policy_limit;
            // $purchase['gross_premium'] = ($plan_data->gross_premium / 100) * $policy_limit;

            $net_premium = (float)$plan_data->net_premium;
            $feesPercentage = (float)$plan_data->fees;
            $stampsPercentage = (float)$plan_data->stamps;
            $salesTaxPercentage = (float)$plan_data->sales_tax;

            $cbjTaxPercentage = (float)$plan_data->cbj;
            $cbjSalesTaxPercentage = (float)$plan_data->sales_tax_cbj;

            // Issuance Fees (as % of Net Premium)
            $issuanceFees = ($net_premium * $feesPercentage) / 100;

            // Stamps (as % of Net Premium)
            $stampAmount = ($net_premium * $stampsPercentage) / 100;

            // CBJ Contribution Fund
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
            $purchase['commission_amount'] = ($plan_data->commission_percentage ?? 0) / 100 * $net_premium;

            $purchase['purchase_id']           = $critical->id;
            $purchase['plan_id']               = $data->plan_id;
            $purchase['plan_name']             = $plan_data->plan_name;
            $purchase['policy_plan_limit']     = $policy_limit;
            $purchase['insurance_company_id']  = $data->insurance_company_id;
            $purchase['inception_date']        = $data->effective_date ?? $data->inception_date ?? '';
            $purchase['expiry_date']           = $data->expiry_date;
            $purchase['commission_percentage'] = $data->commission_percentage ?? $plan_data->commission_percentage;
            $purchase['policy_pdf_url']        = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);

            $data->purchase_id = $critical->id;
            $data->purchase_policy_id = $critical->id;

            // $client = Client::with('country.currency')->find($request->user_id);
            // $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';

            $client = Client::with('country.currency')->find($request->user_id);

            // Get insurance company currency first
            $insuranceCompany = $plan_data->insurance_company;

            if ($insuranceCompany && $insuranceCompany->currency) {
                $abbr = $insuranceCompany->currency->abbreviation;
            } else {
                // Fallback to client's country currency
                $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';
            }

            $pdf = Pdf::loadView('pdf/criticalIllness_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'plan' => $plan_data,
                'purchase' => (object)$purchase
            ]);

            $pdf->save($path);

            $data['url'] = url('insurance_pdfs/criticalIllness_policy/' . $filename);

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
                'message' => __('messages.api.add_critical_illness_insurance_successfully'),
                'data' => $data
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

    public function getCriticalIllnessInsurancePlan(Request $request)
    {
        try {

            $query = CriticalIllnessPlan::with([
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
                'message' => __('messages.api.get_critical_illness_insurance_plan_successfully'),
                'data' => $data
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
}
