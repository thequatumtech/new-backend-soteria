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
use App\Models\InsuranceCompany;
use App\Helpers\InsurancePlanHelper;

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
        $message = __('messages.api.home_restriction_message', [
            'type' => __('messages.api.home_restriction_' . $type)
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
                'home_age' => 'nullable',
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

            $plan_data = HomePlan::find($data['plan_id']);
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
            $restrictedHomeAges = $this->normalizeRestricted($plan_data->restricted_home_age_ids);

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
            // if (!empty($restrictedHomeAges) && !empty($data['home_age'])) {
            //     $clientHomeAges = $this->normalizeRestricted($data['home_age']);

            //     $hasMatch = !empty(array_intersect(
            //         array_map('intval', $clientHomeAges),
            //         array_map('intval', $restrictedHomeAges)
            //     ));

            //     if ($hasMatch) {
            //         return $this->restrictionError('home_age');
            //     }
            // }
            if (!empty($restrictedHomeAges) && isset($data['home_age']) && $data['home_age'] !== '') {
                    $clientHomeAges = array_map('intval', $this->normalizeRestricted($data['home_age']));

                    foreach ($restrictedHomeAges as $range) {
                        $range = str_replace(' ', '', (string) $range);

                        foreach ($clientHomeAges as $homeAge) {
                            if (str_contains($range, '-')) {
                                // Range like "10-20" → block if inside the range
                                [$min, $max] = explode('-', $range);
                                if ($homeAge >= (int) $min && $homeAge <= (int) $max) {
                                    return $this->restrictionError('home_age');
                                }
                            } else {
                                // Single value like "20" or "20+" → block 20 and above
                                if ($homeAge >= (int) rtrim($range, '+')) {
                                    return $this->restrictionError('home_age');
                                }
                            }
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
            $purchase['purchase_id']       = $home->id;
            $purchase['plan_id']           = $data->plan_id;
            $purchase['plan_name']         = $plan_data->plan_name;
            $purchase['policy_plan_limit'] = $policy_limit;
            $purchase['insurance_company_id'] = $data->insurance_company_id;
            $purchase['inception_date']    = $data->effective_date;
            $purchase['expiry_date']       = $data->expiry_date;
            $purchase['commission_percentage'] = $data->commission_percentage;
            $purchase['policy_pdf_url']    = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);

            // $plan = HomePlan::with('policy_covers')->find($plan_data->id);
            $plan = HomePlan::with([
                'policy_covers',
                'insurance_company.currency'
            ])->find($plan_data->id);

            $data->purchase_id = $home->id;
            $data->purchase_policy_id = $home->id;

            $purchaseData = PurchasePolicy::where('id', $home->id)->first();

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
                'message' => __('messages.api.add_home_insurance_plan_successfully'),
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

    // public function getHomeInsurancePlan(Request $request)
    // {
    //     try {
    //         // $data = HomePlan::with('policy_covers', 'insurance_company')
    //         //     ->whereHas('insurance_company', function ($q) {
    //         //         $q->whereNull('deleted_at');
    //         //     })
    //         //     ->where('plan_name', 'LIKE', '%' . $request->limit . '%')
    //         //     ->get();
    //          $query = HomePlan::with([
    //             'policy_covers',
    //             'insurance_company',
    //             'insurance_company.currency'
    //         ]);

    //         // Filter according to client's currency
    //         $query = InsurancePlanHelper::filterByClientCurrency(
    //             $query,
    //             $request->user_id
    //         );

    //         // Filter by HomePlan limit if supplied
    //         if ($request->filled('limit')) {
    //             $query->where('limit', $request->limit);
    //         }

    //         $data = $query->get();

    //         if ($data->isEmpty()) {
    //             // Only show "no plans for your country" when the result is empty
    //             // due to the country/currency filter — not because of a limit mismatch.
    //             // Check if any plans exist for this client's country (ignoring limit).
    //             if ($request->filled('limit')) {
    //                 $countryQuery = HomePlan::whereHas('insurance_company', function ($q) {
    //                     $q->whereNull('deleted_at');
    //                 });

    //                 InsurancePlanHelper::filterByClientCurrency($countryQuery, $request->user_id);
    //                 $plansExistForCountry = $countryQuery->exists();

    //                 if ($plansExistForCountry) {
    //                     // Plans exist for the country but none match the given limit — return empty normally.
    //                     return response()->json([
    //                         'status'      => false,
    //                         'status_code' => 404,
    //                         'message'     => 'No home insurance plans are available for your country at this time. Please contact us for further information.',
    //                         'data'        => []
    //                     ], 404);
    //                 }
    //             }

    //         }

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
    //             'message' => 'Get Home Insurance Plan successfully',
    //             'data' => $data,
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

    public function getHomeInsurancePlan(Request $request)
    {
        try {
            $query = HomePlan::with([
                'policy_covers',
                'insurance_company',
                'insurance_company.currency'
            ]);

            // Filter according to client's currency
            $query = InsurancePlanHelper::filterByClientCurrency(
                $query,
                $request->user_id
            );

            // Filter by HomePlan limit if supplied
            // if ($request->filled('limit')) {
            //     $query->where('plan_name', 'LIKE', '%' . $request->limit . '%');
            // }
            if ($request->filled('limit')) {
                $query->where('plan_name', $request->limit);
            }

            // Log the request parameters
            \Log::info('Home Insurance Request Params: ', $request->all());
            \Log::info('Home Insurance Limit Value: ' . $request->limit . ' | Type: ' . gettype($request->limit));

            // Log the SQL query and bindings before execution
            $sql = vsprintf(
                str_replace('?', '%s', $query->toSql()),
                array_map(fn($binding) => is_string($binding) ? "'$binding'" : $binding, $query->getBindings())
            );
            \Log::info('Home Insurance Query SQL: ' . $sql);

            $data = $query->get();

            // Log the retrieved data count
            \Log::info('Home Insurance Raw Count: ' . $data->count());
            \Log::info('Home Insurance Data: ', $data->toArray());

            // Log all available plans without limit filter to see what exists
            $allPlans = HomePlan::with(['insurance_company'])
                ->whereHas('insurance_company', function ($q) {
                    $q->whereNull('deleted_at')->where('currency_id', 1);
                })
                ->get(['id', 'limit', 'plan_name']);

            \Log::info('All Available Plans (id, limit, plan_name): ', $allPlans->toArray());

            if ($data->isEmpty()) {
                if ($request->filled('limit')) {
                    $countryQuery = HomePlan::whereHas('insurance_company', function ($q) {
                        $q->whereNull('deleted_at');
                    });

                    InsurancePlanHelper::filterByClientCurrency($countryQuery, $request->user_id);
                    $plansExistForCountry = $countryQuery->exists();

                    if ($plansExistForCountry) {
                        return response()->json([
                            'status'      => false,
                            'status_code' => 404,
                            'message'     => __('messages.api.no_home_insurance_plans_for_country'),
                            'data'        => []
                        ], 404);
                    }
                }
            }

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
                'message' => __('messages.api.get_home_insurance_plan_successfully'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('Home Insurance Error: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
}
