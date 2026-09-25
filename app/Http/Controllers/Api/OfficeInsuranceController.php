<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{ClientOfficeInsurance, PurchasePolicy};
use App\Models\InsurancePlanModels\{OfficePlan, OfficePlanPolicyCover};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Currency;
use App\Helpers\InsurancePlanHelper;

class OfficeInsuranceController extends Controller
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
        $message = __('messages.api.office_restriction_message', [
            'type' => __('messages.api.office_restriction_' . $type)
        ]);

        if ($type === 'country') {
            $message .= __('messages.api.country_contact');
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

    public function storeOfficeInsurance(Request $request)
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
                'place_residence' => 'nullable',
                'company_name' => 'nullable',
                'company_register_national_id' => 'nullable',
                'company_register_id' => 'nullable',
                'office_type' => 'nullable',
                'no_of_floor' => 'nullable',
                'no_of_room' => 'nullable',
                'size_of_apartment' => 'nullable',
                'age_of_apartment' => 'nullable',
                'no_of_residence' => 'nullable',
                'office_category' => 'nullable',
                'block_no' => 'nullable',
                'plate_no' => 'nullable',
                'plot_no' => 'nullable',
                'effective_date' => 'nullable',
                'expiry_date' => 'nullable',
                'no_of_employee' => 'nullable',
                'country_id' => 'nullable',
                'city_id' => 'nullable',
                'district_id' => 'nullable',
                'street_name' => 'nullable',
                'building_no' => 'nullable',
                'office_no' => 'nullable',
                'company_telephone' => 'nullable',
                'company_owner_name' => 'nullable',
                'company_owner_telephone' => 'nullable',
                'partner_company_status' => 'nullable',
                'authorized_insurance_police_status' => 'nullable',
                'auth_company_register_status' => 'nullable',
                'provious_insurance_policy' => 'nullable',
                'insurance_declined_issue_status' => 'nullable',
                'claims_5_year_status' => 'nullable',
                'protection_system' => 'nullable',
                'insurance_limit' => 'nullable',
                'insurance_plan' => 'nullable',
                'inception_date' => 'nullable',
                'insurance_expiry_date' => 'nullable',
                'rent_contract_documents' => 'nullable',
                'property_photo_documents' => 'nullable',
                'contents_documents' => 'nullable',
                'policy_issuer_documents' => 'nullable',
                'company_owner_documents' => 'nullable',
                'career_municipality_license_documents' => 'nullable',
                'owner_id_documents' => 'nullable',
                'practice_documents' => 'nullable',
                'company_tax_certi_documents' => 'nullable',
                'plan_id' => 'required',
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

            $plan_data = OfficePlan::find($data['plan_id']);

            if (!$plan_data) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => __('messages.api.office_plan_not_exist'),
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

            $birthDate = $data['birth_date'] ?? $client->birth_date;
            $clientAge = Carbon::parse($birthDate)->age;

            $restrictedCountries = $this->normalizeRestricted($plan_data->restricted_country_ids);
            $restrictedCities    = $this->normalizeRestricted($plan_data->restricted_city_ids);
            $restrictedDistricts = $this->normalizeRestricted($plan_data->restricted_district_ids);
            $restrictedAges      = $this->normalizeRestricted($plan_data->restricted_age_ids);
            $restrictedProtectionSystems = $this->normalizeRestricted($plan_data->restricted_protection_system_ids);
            $restrictedOfficeAges = $this->normalizeRestricted($plan_data->restricted_office_age_ids);

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
            if (!empty($restrictedProtectionSystems) && $data['protection_system']) {
                $clientProtectionSystems = $this->normalizeRestricted($data['protection_system']);

                $hasMatch = !empty(array_intersect(
                    array_map('intval', $clientProtectionSystems),
                    array_map('intval', $restrictedProtectionSystems)
                ));

                if ($hasMatch) {
                    return $this->restrictionError('protection_system');
                }
            }
            // if (!empty($restrictedOfficeAges) && !empty($data['age_of_apartment'])) {
            //     $clientOfficeAges = $this->normalizeRestricted($data['age_of_apartment']);

            //     $hasMatch = !empty(array_intersect(
            //         array_map('intval', $clientOfficeAges),
            //         array_map('intval', $restrictedOfficeAges)
            //     ));

            //     if ($hasMatch) {
            //         return $this->restrictionError('office_age');
            //     }
            // }
            if (!empty($restrictedOfficeAges) && isset($data['age_of_apartment']) && $data['age_of_apartment'] !== '') {
                $clientOfficeAges = array_map('intval', $this->normalizeRestricted($data['age_of_apartment']));

                foreach ($restrictedOfficeAges as $range) {
                    $range = str_replace(' ', '', (string) $range);

                    foreach ($clientOfficeAges as $officeAge) {
                        if (str_contains($range, '-')) {
                            // Range like "10-20" → block if inside the range
                            [$min, $max] = explode('-', $range);
                            if ($officeAge >= (int) $min && $officeAge <= (int) $max) {
                                return $this->restrictionError('office_age');
                            }
                        } else {
                            // Single value like "20" or "20+" → block 20 and above
                            if ($officeAge >= (int) rtrim($range, '+')) {
                                return $this->restrictionError('office_age');
                            }
                        }
                    }
                }
            }
            $existingPolicy = PurchasePolicy::find($request->purchase_id ?? 0);

            if ($existingPolicy) {
                $office = ClientOfficeInsurance::find($existingPolicy->policy_id);

                if ($office) {
                    $office->update($data);
                }

                $data['purchase_id'] = $existingPolicy->id;
                $data['inception_date'] = $data['effective_date'];

                $office = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $office->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $office = ClientOfficeInsurance::create($data);

                $data['policy_type'] = 2;
                $data['policy_id'] = $office->id;
                $data['inception_date'] = $data['effective_date'];

                $office = PurchasePolicy::savePurchasePolicy($data);
            }

            $data = ClientOfficeInsurance::getOfficeInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/office_policy');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = uniqid() . '_policy_' . $office->id . '.pdf';
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

            $purchase['purchase_id']           = $office->id;
            $purchase['plan_id']               = $data->plan_id;
            $purchase['plan_name']             = $plan_data->plan_name;
            $purchase['policy_plan_limit']     = $policy_limit;
            $purchase['insurance_company_id']  = $data->insurance_company_id;
            $purchase['inception_date']        = $data->effective_date;
            $purchase['expiry_date']            = $data->expiry_date;
            $purchase['commission_percentage'] = $data->commission_percentage ?? $plan_data->commission_percentage;
            $purchase['policy_pdf_url']        = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);

            $data->purchase_id = $office->id;
            $data->purchase_policy_id = $office->id;

            $client = Client::with('country.currency')->find($request->user_id);

            // $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';
            $insuranceCompany = $plan_data->insurance_company;

            // Use insurance company's currency first
            if ($insuranceCompany && $insuranceCompany->currency) {
                $abbr = $insuranceCompany->currency->abbreviation;
            } else {
                // Fallback to client's country currency
                $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';
            }

            $pdf = Pdf::loadView('pdf.office_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'plan' => $plan_data,
                'purchase' => PurchasePolicy::where('id', $office->id)->first()
            ]);

            $pdf->save($path);

            $data['url'] = url('insurance_pdfs/office_policy/' . $filename);

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
                'message' => __('messages.api.add_office_insurance_plan_successfully'),
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

    public function getOfficeInsurancePlan(Request $request)
    {
        try {

            $query = OfficePlan::with([
                'policy_covers',
                'insurance_company',
                'insurance_company.currency'
            ]);

            // Filter according to client's currency
            $query = InsurancePlanHelper::filterByClientCurrency(
                $query,
                $request->user_id
            );

            // Filter by OfficePlan limit
            if ($request->filled('limit')) {
                $query->where('plan_name', $request->limit);
            }

            $data = $query->get();

            $data->transform(function ($item) {
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url(
                        'uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf
                    );
                }

                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url(
                        'insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy
                    );
                }

                return $item;
            });

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_office_insurance_plan_successfully'),
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
