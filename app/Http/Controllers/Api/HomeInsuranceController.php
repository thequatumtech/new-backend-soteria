<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{ClientHomeInsurance, PurchasePolicy};
use App\Models\InsurancePlanModels\{HomePlan, HomePlanPolicyCover};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Currency;

class HomeInsuranceController extends Controller
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

    public function storeHomeInsurance(Request $request)
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
                'place_of_residence' => 'nullable',
                'home_type' => 'nullable',
                'no_of_floor' => 'nullable',
                'no_of_room' => 'nullable',
                'size_of_apartment' => 'nullable',
                'no_of_residence' => 'nullable',
                'home_category' => 'nullable',
                'block_no' => 'nullable',
                'plate_no' => 'nullable',
                'plot_no' => 'nullable',
                'country_id' => 'nullable',
                'city_id' => 'nullable',
                'district_id' => 'nullable',
                'street_name' => 'nullable',
                'building_no' => 'nullable',
                'company_name' => 'nullable',
                'city_id_2' => 'nullable',
                'position' => 'nullable',
                'work_nature' => 'nullable',
                'previous_policy' => 'nullable',
                'company_declined_to_issue' => 'nullable',
                'claims_accidents_past' => 'nullable',
                'protection_system' => 'nullable',
                'insurance_limit' => 'nullable',
                'plan_id' => 'nullable',
                'effective_date' => 'nullable',
                'expiry_date' => 'nullable',
                'rent_contract' => 'nullable',
                'property_document' => 'nullable',
                'content_document' => 'nullable',
                'payment_status' => 'nullable',
            ]);

            if (isset($data['birth_date'])) {
                $data['birth_date'] = $this->normalizeDate($data['birth_date']);
            }
            if ($data['country_id']) {
                $country = \App\Models\Country::find($data['country_id']);
                if (!$country) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected country does not exist.',
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
                        'message' => 'Selected city does not belong to the selected country.',
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
                        'message' => 'Selected district does not belong to the selected city.',
                        'data' => []
                    ], 422);
                }
            }


            $plan_data = HomePlan::find($data['plan_id']);
            $data['insurance_company_id'] = $plan_data->insurance_company_id;


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

            $birthDate = $data['birth_date'] ?? $client->birth_date;
            $clientAge = Carbon::parse($birthDate)->age;

            $restrictedCountries = $this->normalizeRestricted($plan_data->restricted_country_ids);
            $restrictedCities    = $this->normalizeRestricted($plan_data->restricted_city_ids);
            $restrictedDistricts = $this->normalizeRestricted($plan_data->restricted_district_ids);
            $restrictedAges      = $this->normalizeRestricted($plan_data->restricted_age_ids);

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


            $existingHome = PurchasePolicy::find($request->purchase_id ?? 0);

            if ($existingHome) {

                $home = ClientHomeInsurance::find($existingHome->policy_id);

                if ($home) {
                    $home->update($data);
                }

                $data['purchase_id'] = $existingHome->id;
                $data['inception_date'] = $data['effective_date'];

                $home = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $home->policy_id;
            } else {

                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $home = ClientHomeInsurance::create($data);

                $data['policy_type'] = 1;
                $data['policy_id'] = $home->id;
                $data['inception_date'] = $data['effective_date'];

                $home = PurchasePolicy::savePurchasePolicy($data);
            }

            $data = ClientHomeInsurance::getHomeInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/home_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = uniqid() . '_policy_' . $home->id . '.pdf';
            $path = $directory . '/' . $filename;

            $policy_limit = $this->cleanNumber($plan_data->limit);

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

            // CBJ Contribution Fund (0.5% of Net Premium)
            $cbjContribution = ($net_premium * $cbjTaxPercentage) / 100;

            // Sales Tax on (Net Premium + Issuance Fees)
            $salesTaxAmount = (($net_premium + $issuanceFees) * $salesTaxPercentage) / 100;

            // Sales Tax on CBJ Fund
            $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;

            // Total Gross Premium
            $grossPremium = $net_premium + $issuanceFees + $stampAmount + $salesTaxAmount + $cbjContribution + $cbjSalesTaxAmount;

            $purchase['net_premium']       = $net_premium;
            $purchase['fees']              = $issuanceFees;
            $purchase['stamps']            = $stampAmount;
            $purchase['sales_tax']         = $salesTaxAmount;
            $purchase['cbj']               = $cbjContribution;
            $purchase['sales_tax_cbj']     = $cbjSalesTaxAmount;
            $purchase['gross_premium']     = $grossPremium;
            $purchase['commission_amount'] = ($plan_data->commission_percentage ?? 0) / 100 * $net_premium;
            $purchase['purchase_id']          = $home->id;
            $purchase['plan_id']              = $data->plan_id;
            $purchase['plan_name']            = $plan_data->plan_name;
            $purchase['policy_plan_limit']    = $policy_limit;
            $purchase['insurance_company_id'] = $data->insurance_company_id;
            $purchase['inception_date']       = $data->effective_date;
            $purchase['expiry_date']          = $data->expiry_date;
            $purchase['commission_percentage'] = $data->commission_percentage;
            $purchase['policy_pdf_url']       = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);
            $plan = HomePlan::with('policy_covers')->find($plan_data->id);
            $data->purchase_id = $home->id;

            $purchaseData = PurchasePolicy::where('id', $home->id)->first();

            $client = Client::with('country.currency')->find($request->user_id);
            $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';

            $pdf = Pdf::loadView('pdf/home_policy', [
                'data' => $data,
                'purchase' => $purchaseData,
                'plan' => $plan,
                'abbr' => $abbr
            ]);


            $pdf->save($path);

            $url = url('insurance_pdfs/home_policy/' . $filename);
            $data['url'] = $url;



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
                'message' => 'Add Home Insurance Plan successfully',
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


    public function getHomeInsurancePlan(Request $request)
    {
        try {
            $data = HomePlan::with('policy_covers', 'insurance_company')
                ->where('plan_name', 'LIKE', '%' . $request->limit . '%')
                ->get();

            $data->transform(function ($item) {
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Get Home Insurance Plan successfully',
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
