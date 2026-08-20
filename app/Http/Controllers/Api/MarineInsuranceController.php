<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InsurancePlanModels\MarinePlan;
use App\Models\{ClientMarineInsurance, PurchasePolicy};
use Illuminate\Http\Request;
use PDF;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Currency;

class MarineInsuranceController extends Controller
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

    public function storeMarineInsurance(Request $request)
    {
        try {

            $data = $request->validate([
                'company_status' => 'nullable',
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'third_name' => 'nullable',
                'family_name' => 'nullable',
                'nationality' => 'nullable',
                'nationality_no' => 'nullable',
                'id_residence_no' => 'nullable',
                'birth_date' => 'nullable',
                'gender' => 'nullable',
                'company_name' => 'nullable',
                'company_reg_notional_id' => 'nullable',
                'company_reg_no' => 'nullable',
                'company_country_id' => 'nullable',
                'company_city_id' => 'nullable',
                'company_district_id' => 'nullable',
                'company_street_name' => 'nullable',
                'company_building_no' => 'nullable',
                'company_office_no' => 'nullable',
                'company_contact' => 'nullable',
                'owner_first_name' => 'nullable',
                'owner_last_name' => 'nullable',
                'owner_third_name' => 'nullable',
                'owner_family_name' => 'nullable',
                'company_owner_contact' => 'nullable',
                'company_partner_status' => 'nullable',
                'company_authorized_status' => 'nullable',
                'authorized_positions' => 'nullable',
                'company_register_status' => 'nullable',
                'register_document' => 'nullable',
                'vayage_from_id' => 'nullable',
                'through_country_id' => 'nullable',
                'destination_country_id' => 'nullable',
                'type_of_transportation' => 'nullable',
                'type_of_cover' => 'nullable',
                'item_category_id' => 'nullable',
                'item_subcategory_id' => 'nullable',
                'insurance_limit' => 'nullable',
                'bill_no' => 'nullable',
                'effective_date' => 'nullable',
                'expiry_date' => 'nullable',
                'insured_items' => 'nullable',
                'existing_policy_status' => 'nullable',
                'existing_policy_desc' => 'nullable',
                'declined_insurance_status' => 'nullable',
                'declined_insurance_desc' => 'nullable',
                'claims_accident_status' => 'nullable',
                'claims_accident_desc' => 'nullable',
                'billing_of_landing_doc' => 'nullable',
                'copy_of_invoice_doc' => 'nullable',
                'insured_id_doc' => 'nullable',
                'policy_issuer_doc' => 'nullable',
                'company_reg_owner_doc' => 'nullable',
                'career_municipality_license_doc' => 'nullable',
                'company_tax_certificate_doc' => 'nullable',
                'practice_certificate_doc' => 'nullable',
                'plan_id' => 'nullable',
                'payment_status' => 'nullable',
                'trans_shipped_third_country' => 'nullable',
                'dangerous_activities' => 'nullable',
            ]);

            if ($request->has('dangerous_activities')) {
                $data['dangerous_activities'] = is_array($request->dangerous_activities)
                    ? implode(',', $request->dangerous_activities)
                    : $request->dangerous_activities;
            }

            if ($request->has('trans_shipped_third_country')) {
                $data['trans_shipped_third_country'] = is_array($request->trans_shipped_third_country)
                    ? implode(',', $request->trans_shipped_third_country)
                    : $request->trans_shipped_third_country;
            }

            if (!empty($request->effective_date)) {
                $data['effective_date'] = date('Y-m-d', strtotime($request->effective_date));
            }

            if (!empty($request->expiry_date)) {
                $data['expiry_date'] = date('Y-m-d', strtotime($request->expiry_date));
            }

            $data['inception_date'] = $data['effective_date'] ?? null;

            $data['inception_date'] = $data['effective_date'] ?? null;

            $plan_data = MarinePlan::find($data['plan_id']);
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
            $clientDestinationCountry = $data['destination_country_id'] ?? null;


            $clientAge = Carbon::parse($client->birth_date)->age;

            $restrictedCountries = $this->normalizeRestricted($plan_data->restricted_country_ids);
            $restrictedCities    = $this->normalizeRestricted($plan_data->restricted_city_ids);
            $restrictedDistricts = $this->normalizeRestricted($plan_data->restricted_district_ids);
            $restrictedAges      = $this->normalizeRestricted($plan_data->restricted_age_ids);
            $restrictedShipmentCountries      = $this->normalizeRestricted($plan_data->shipment_origin_and_destination_country);

            if ($clientCountry && in_array($clientCountry, $restrictedCountries)) {
                return $this->restrictionError('country');
            }

            if ($clientCity && in_array($clientCity, $restrictedCities)) {
                return $this->restrictionError('city');
            }

            if ($clientDistrict && in_array($clientDistrict, $restrictedDistricts)) {
                return $this->restrictionError('district');
            }
            if ($clientDestinationCountry && in_array($clientDestinationCountry, $restrictedShipmentCountries)) {
                return $this->restrictionError('shipment origin / destination country');
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

            $existingMarine = PurchasePolicy::find($request->purchase_id ?? 0);

            if ($existingMarine) {
                $Marine = ClientMarineInsurance::find($existingMarine->policy_id);
                if ($Marine) $Marine->update($data);

                $data['purchase_id'] = $existingMarine->id;
                $data['inception_date'] = $data['effective_date'];
                $Marine = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $Marine->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $Marine = ClientMarineInsurance::create($data);

                $data['policy_type'] = 11;
                $data['policy_id'] = $Marine->id;
                $data['inception_date'] = $data['effective_date'];

                $Marine = PurchasePolicy::savePurchasePolicy($data);
            }

            $data = ClientMarineInsurance::getMarineInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/marine_policy');
            if (!file_exists($directory)) mkdir($directory, 0755, true);

            $filename = uniqid() . '_policy_' . $Marine->id . '.pdf';
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

            // CBJ Contribution Fund
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

            $purchase['purchase_id']           = $Marine->id;
            $purchase['plan_id']               = $data->plan_id;
            $purchase['plan_name']             = $plan_data->plan_name;
            $purchase['policy_plan_limit']     = $policy_limit;
            $purchase['insurance_company_id']  = $data->insurance_company_id;
            $purchase['inception_date']        = $data->effective_date;
            $purchase['expiry_date']           = $data->expiry_date;
            $purchase['commission_percentage'] = $data->commission_percentage ?? $plan_data->commission_percentage;
            $purchase['policy_pdf_url']        = $path;


            PurchasePolicy::updatePurchasePolicy($purchase);

            $data->purchase_id = $Marine->id;
            $plan_data = MarinePlan::with('policy_covers')->find($data['plan_id']);
            $client = Client::with('country.currency')->find($request->user_id);
            $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';

            $pdf = PDF::loadView('pdf/marine_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'plan' => $plan_data,
                'purchase' => PurchasePolicy::where('id', $Marine->id)->first()
            ]);
            $pdf->save($path);

            $data['url'] = url('insurance_pdfs/marine_policy/' . $filename);



            $data->net_premium       = number_format($purchase['net_premium'], 2) . ' ' . $abbr;
            $data->fees              = number_format($purchase['fees'], 2) . ' ' . $abbr;
            $data->gross_premium     = number_format($purchase['gross_premium'], 2) . ' ' . $abbr;
            $data->sales_tax         = number_format($purchase['sales_tax'], 2) . ' ' . $abbr;
            $data->stamps            = number_format($purchase['stamps'], 2) . ' ' . $abbr;
            $data->commission_amount = number_format($purchase['commission_amount'], 2) . ' ' . $abbr;

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Add Marine Insurance Plan successfully',
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
    public function getMarineInsurancePlan(Request $request)
    {
        try {

            $data = MarinePlan::with('policy_covers', 'insurance_company')
                ->whereHas('insurance_company', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->where('limit', 'LIKE', '%' . $request->limit . '%')
                ->get();

            $data->transform(function ($item) {

                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf =
                        url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
                }

                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy =
                        url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
                }

                return $item;
            });

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Get Marine Insurance Plan successfully',
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
