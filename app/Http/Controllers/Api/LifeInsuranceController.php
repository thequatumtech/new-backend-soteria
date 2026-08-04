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
            ]);

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
                        'message' => 'Selected city does not belong to selected country.',
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
                        'message' => 'Selected district does not belong to selected city.',
                        'data' => []
                    ], 422);
                }
            }

            $existingLife = PurchasePolicy::find($request->purchase_id ?? 0);
            // $plan_data = LifePlan::find($data['plan_id']);
            $plan_data = LifePlan::with('policy_covers')->find($data['plan_id']);
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
                    'message' => 'You are not eligible for this plan as your age is outside the required range (21-60).',
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

            $years = (int) $data['insurance_period'];

            $yearColumn = 'year_' . $years;
            $pricingSchedule = LifePlanPricingSchedule::where('life_plan_id', $data['plan_id'])
                ->where('age', $clientAge)
                ->first();

            // Log::info('Insurance Period Debug', [
            //     'insurance_period' => $data['insurance_period'],
            //     'parsed_years'     => $years,
            //     'year_column'      => $yearColumn,
            //     'client_age'       => $clientAge,
            //     'plan_id'          => $data['plan_id'],
            //     'pricing_found'    => $pricingSchedule ? true : false,
            //     'net_premium'      => $pricingSchedule?->$yearColumn ?? 'N/A',
            // ]);

            if (!$pricingSchedule) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'No pricing schedule found for your age and selected plan.',
                    'data' => []
                ], 422);
            }

            if (!isset($pricingSchedule->$yearColumn) || $pricingSchedule->$yearColumn === null) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => "No pricing available for {$years} year(s) in this plan.",
                    'data' => []
                ], 422);
            }

            $netPremiumFromSchedule = (float) $pricingSchedule->$yearColumn;

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
            }

            try {
                $data = ClientLifeInsurance::getLifeInsuranceDetails($data['policy_id']);

                if (!$data) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Insurance details not found',
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
            $policy_limit = (float)$plan_data->limit ?? 0; // Assuming limit is available in plan

            // $cbjTaxPercentage = (float)$plan_data->cbj ?? 0;
            // $cbjSalesTaxPercentage = (float)$plan_data->sales_tax_cbj ?? 0;

            // // CBJ Contribution Fund (as % of Net Premium)
            // $cbjContribution = ($netPremium * $cbjTaxPercentage) / 100;

            // // Sales Tax on CBJ Fund
            // $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;

            // $feesAmount = $data->fees;
            // $stampsAmount = $data->stamps;
            // $taxAmount = $data->sales_tax;
            // $grossPremium = $data->gross_premium;


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
            $grossPremium = $netPremium + $feesAmount + $stampsAmount + $taxAmount + $cbjContribution + $cbjSalesTaxAmount;

            $purchase['net_premium']           = $netPremium;
            $purchase['fees']                  = $feesAmount;
            $purchase['stamps']                = $stampsAmount;
            $purchase['sales_tax']             = $taxAmount;
            $purchase['cbj']                   = $cbjContribution;
            $purchase['sales_tax_cbj']         = $cbjSalesTaxAmount;

            // Recalculate gross premium based on dynamic net premium
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
            // Prepare unformatted data for PDF - needs to match the dynamic calculation
            $plan_for_pdf = clone $plan_data;
            $plan_for_pdf->net_premium_amount = $netPremium;
            $plan_for_pdf->fees_amount = $feesAmount;
            $plan_for_pdf->stamps_amount = $stampsAmount;
            $plan_for_pdf->sales_tax_amount = $taxAmount;
            $plan_for_pdf->cbj_amount = $cbjContribution;
            $plan_for_pdf->sales_tax_cbj_amount = $cbjSalesTaxAmount;
            $plan_for_pdf->gross_premium_amount = $grossPremium;
            // $plan_for_pdf->gross_premium = $purchase['gross_premium'];

            $client = Client::with('country.currency')->find($request->user_id);
            $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';


            $pdf = PDF::loadView('pdf/life_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'plan' => $plan_for_pdf
            ]);
            $pdf->save($path);

            $url = url('insurance_pdfs/life_policy/' . $filename);
            $data['url'] = $url;

            // $data->net_premium = $data->net_premium;
            // $data->fees = $data->fees;
            // $data->gross_premium = $data->gross_premium;
            // $data->sales_tax = $data->sales_tax;
            // $data->stamps = $data->stamps;
            $data->commission_percentage = $data->commission_percentage . '%';


            $data->net_premium       = number_format($data['net_premium'], 2) . ' ' . $abbr;
            $data->fees              = number_format($data['fees'], 2) . ' ' . $abbr;
            $data->gross_premium     = number_format($data['gross_premium'], 2) . ' ' . $abbr;
            $data->sales_tax         = number_format($data['sales_tax'], 2) . ' ' . $abbr;
            $data->stamps            = number_format($data['stamps'], 2) . ' ' . $abbr;
            $data->cbj               = number_format($data['cbj'], 2) . ' ' . $abbr;
            $data->sales_tax_cbj     = number_format($data['sales_tax_cbj'], 2) . ' ' . $abbr;
            // $data->commission_amount = number_format($data['commission_amount'], 2) . ' ' . $abbr;
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Add Life Insurance Plan successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('API Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
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


    public function getLifeInsurancePlan(Request $request)
    {
        try {
            $data = LifePlan::with('policy_covers', 'insurance_company')
                ->where('limit', 'LIKE', '%' . $request->limit . '%')
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
                'message' => 'Get Life Insurance Plan successfully',
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
}
