<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientInPatientInsurance;
use App\Models\Client;
use Carbon\Carbon;
use App\Helpers\InsurancePlanHelper;
use App\Models\InsurancePlanModels\{InPatientPlan, InOutPatientPlan};
use Illuminate\Http\Request;

class InPatientController extends Controller
{
    public function storInPatientInsurance(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'inception_date' => 'required',
                'plan_id' => 'required',
            ]);
            // Helper function to generate unique file name
            $generateUniqueFileName = function ($file) {
                return uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            };

            // Process file uploads
            if ($request->hasFile('photo_documents_1')) {
                $validatedData['photo_documents_1'] = $request->file('photo_documents_1')->move('public/InPatientPlan_insurance/photo_documents_1', $generateUniqueFileName($request->file('photo_documents_1')));
            }
            if ($request->hasFile('photo_documents_2')) {
                $validatedData['photo_documents_2'] = $request->file('photo_documents_2')->move('public/InPatientPlan_insurance/photo_documents_2', $generateUniqueFileName($request->file('photo_documents_2')));
            }
            if ($request->hasFile('photo_documents_3')) {
                $validatedData['photo_documents_3'] = $request->file('photo_documents_3')->move('public/InPatientPlan_insurance/photo_documents_3', $generateUniqueFileName($request->file('photo_documents_3')));
            }


            // Set additional fields
            $validatedData['client_id'] = $request->user_id;
            $validatedData['police_no'] = rand(10000000, 99999999);

            // Create the InPatientPlan Insurance record
            $InPatientPlan = ClientInPatientInsurance::create($validatedData);
            return response()->json(['status' => true, 'status_code' => 200, 'message' => __('messages.api.add_inpatient_plan_successfully'), 'data' => $InPatientPlan]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    private function cleanNumber($value)
    {
        if ($value === null) return 0;

        $clean = preg_replace('/[^\d.]/', '', $value);

        return is_numeric($clean) ? (float)$clean : 0;
    }
    public function getInPatientInsurancePlan(Request $request)
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
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate Client Age
            |--------------------------------------------------------------------------
            */

            $clientAge = null;

            if ($client->birth_date) {
                $clientAge = Carbon::parse($client->birth_date)->age;
            }

            if ($clientAge === null) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => __('messages.api.inpatient_client_birth_date_missing'),
                    'data' => []
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Get Client Gender
            |--------------------------------------------------------------------------
            |
            | 1 = Male
            | 2 = Female
            |
            */

            $clientGender = (string) $client->gender;

            if (!in_array($clientGender, ['1', '2'])) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => __('messages.api.inpatient_invalid_client_gender'),
                    'data' => []
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Get Selected Class
            |--------------------------------------------------------------------------
            */

            $selectedClass = strtolower($request->class);

            if (
                !in_array($selectedClass, [
                    'vip',
                    'first',
                    'second',
                    'third'
                ])
            ) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => __('messages.api.inpatient_invalid_class'),
                    'data' => []
                ]);
            }



            // $data = InPatientPlan::with('policy_covers', 'insurance_company','pricing_schedule')
            //     ->whereHas('insurance_company', function ($q) {
            //         $q->whereNull('deleted_at');
            //     })
            //     ->where('limit',$request->limit)
            //     ->get();
            $data = InPatientPlan::with([
                'policy_covers',
                'insurance_company',
                'insurance_company.currency',
                'pricing_schedule'
            ]);

            // Filter according to client's currency
            $data = InsurancePlanHelper::filterByClientCurrency(
                $data,
                $request->user_id
            );

            // Filter by limit
            if ($request->filled('limit')) {
                $data->where('limit', $request->limit);
            }


            $data = $data->get();

            if ($data->isEmpty()) {
                // Only show "no plans for your country" when the result is empty
                // due to the country/currency filter — not because of a limit mismatch.
                // Check if any plans exist for this client's country (ignoring limit).
                if ($request->filled('limit')) {
                    $countryQuery = InPatientPlan::whereHas('insurance_company', function ($q) {
                        $q->whereNull('deleted_at');
                    });
                    InsurancePlanHelper::filterByClientCurrency($countryQuery, $request->user_id);
                    $plansExistForCountry = $countryQuery->exists();
                    if ($plansExistForCountry) {
                        return response()->json([
                            'status'      => false,
                            'status_code' => 404,
                            'message'     => __('messages.api.no_inpatient_insurance_plans_for_country'),
                            'data'        => []
                        ], 404);
                    }
                }
            }

            $data->transform(function ($item) use (
                $clientAge,
                $clientGender,
                $selectedClass
            ) {

                /*
            |--------------------------------------------------------------------------
            | Find Pricing Schedule
            |--------------------------------------------------------------------------
            */

                $pricingSchedule = $item->pricing_schedule
                    ->first(function ($schedule) use (
                        $clientAge,
                        $clientGender
                    ) {

                        return $clientAge >= (int) $schedule->lower_age
                            && $clientAge <= (int) $schedule->upper_age
                            && (string) $schedule->gender === $clientGender;
                    });


                /*
            |--------------------------------------------------------------------------
            | Get Class Price
            |--------------------------------------------------------------------------
            */

                $netPremium = 0;

                if ($pricingSchedule) {

                    switch ($selectedClass) {

                        case 'vip':
                            $netPremium = (float) $pricingSchedule->vip_class;
                            break;

                        case 'first':
                            $netPremium = (float) $pricingSchedule->first_class;
                            break;

                        case 'second':
                            $netPremium = (float) $pricingSchedule->second_class;
                            break;

                        case 'third':
                            $netPremium = (float) $pricingSchedule->third_class;
                            break;
                    }
                }


                /*
            |--------------------------------------------------------------------------
            | Calculate Fees / Taxes / Gross Premium
            |--------------------------------------------------------------------------
            */

                $premium = $this->calculatePremium(
                    $netPremium,
                    $item->fees ?? 0,
                    $item->stamps ?? 0,
                    $item->sales_tax ?? 0,
                    $item->cbj ?? 0,
                    $item->sales_tax_cbj ?? 0
                );


                /*
            |--------------------------------------------------------------------------
            | Add Premium Information
            |--------------------------------------------------------------------------
            */

                $item->client_age = $clientAge;

                $item->client_gender = $clientGender;

                $item->selected_class = $selectedClass;

                $item->pricing_schedule_id = $pricingSchedule?->id;

                $item->net_premium = $premium['net_premium'];

                $item->fees_amount = $premium['fees'];

                $item->stamps_amount = $premium['stamps'];

                $item->sales_tax_amount = $premium['sales_tax'];

                $item->cbj_amount = $premium['cbj'];

                $item->sales_tax_cbj_amount = $premium['sales_tax_on_cbj'];

                $item->gross_premium = $premium['gross_premium'];


                /*
            |--------------------------------------------------------------------------
            | Return Only Matching Pricing Schedule
            |--------------------------------------------------------------------------
            */

                if ($pricingSchedule) {
                    $item->pricing_schedule = collect([
                        $pricingSchedule
                    ]);
                } else {
                    $item->pricing_schedule = collect();
                }


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


            /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_inpatient_insurance_plan_successfully'),
                'data' => $data
            ]);

            // $data->transform(function ($item) {
            //     if (!empty($item->insurance_policy_pdf)) {
            //         $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
            //     }
            //     if (!empty($item->insurance_company->privacy_policy)) {
            //         $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
            //     }
            //     return $item;
            // });
            // return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get In-Patient Insurance Plan successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }

    public function getOutPatientInsurancePlan(Request $request)
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
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Calculate Client Age
        |--------------------------------------------------------------------------
        */

            $clientAge = null;

            if ($client->birth_date) {
                $clientAge = Carbon::parse($client->birth_date)->age;
            }

            if ($clientAge === null) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => __('messages.api.inpatient_client_birth_date_missing'),
                    'data' => []
                ]);
            }


            /*
        |--------------------------------------------------------------------------
        | Get Client Gender
        |--------------------------------------------------------------------------
        |
        | 1 = Male
        | 2 = Female
        |
        */

            $clientGender = (string) $client->gender;

            if (!in_array($clientGender, ['1', '2'])) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => __('messages.api.inpatient_invalid_client_gender'),
                    'data' => []
                ]);
            }


            /*
        |--------------------------------------------------------------------------
        | Get Selected Class
        |--------------------------------------------------------------------------
        */

            $selectedClass = strtolower($request->class);

            if (!in_array($selectedClass, [
                'vip',
                'first',
                'second',
                'third'
            ])) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => __('messages.api.inpatient_invalid_class'),
                    'data' => []
                ]);
            }


            // $data = InOutPatientPlan::with('policy_covers', 'insurance_company', 'pricing_schedule')
            //     ->whereHas('insurance_company', function ($q) {
            //         $q->whereNull('deleted_at');
            //     })
            //     ->where('limit', 'LIKE',$request->limit)
            //     ->get();

            $data = InOutPatientPlan::with([
                'policy_covers',
                'insurance_company',
                'insurance_company.currency',
                'pricing_schedule'
            ]);

            // Filter according to client's currency
            $data = InsurancePlanHelper::filterByClientCurrency(
                $data,
                $request->user_id
            );

            // Filter by limit
            if ($request->filled('limit')) {
                $data->where('limit', $request->limit);
            }
            $data = $data->get();

            if ($data->isEmpty()) {
                // Only show "no plans for your country" when the result is empty
                // due to the country/currency filter — not because of a limit mismatch.
                // Check if any plans exist for this client's country (ignoring limit).
                if ($request->filled('limit')) {
                    $countryQuery = InOutPatientPlan::whereHas('insurance_company', function ($q) {
                        $q->whereNull('deleted_at');
                    });
                    InsurancePlanHelper::filterByClientCurrency($countryQuery, $request->user_id);
                    $plansExistForCountry = $countryQuery->exists();
                    if ($plansExistForCountry) {
                        return response()->json([
                            'status'      => false,
                            'status_code' => 404,
                            'message'     => __('messages.api.no_outpatient_insurance_plans_for_country'),
                            'data'        => []
                        ], 404);
                    }
                }
            }

            /*
        |--------------------------------------------------------------------------
        | Calculate Premium
        |--------------------------------------------------------------------------
        */

            $data->transform(function ($item) use (
                $clientAge,
                $clientGender,
                $selectedClass
            ) {

                /*
            |--------------------------------------------------------------------------
            | Find Matching Pricing Schedule
            |--------------------------------------------------------------------------
            */

                $pricingSchedule = $item->pricing_schedule
                    ->first(function ($schedule) use (
                        $clientAge,
                        $clientGender
                    ) {

                        return $clientAge >= (int) $schedule->lower_age
                            && $clientAge <= (int) $schedule->upper_age
                            && (string) $schedule->gender === $clientGender;
                    });


                /*
            |--------------------------------------------------------------------------
            | Get Price Based On Selected Class
            |--------------------------------------------------------------------------
            */

                $netPremium = 0;

                if ($pricingSchedule) {

                    switch ($selectedClass) {

                        case 'vip':
                            $netPremium = $this->cleanNumber(
                                $pricingSchedule->vip_class
                            );
                            break;

                        case 'first':
                            $netPremium = $this->cleanNumber(
                                $pricingSchedule->first_class
                            );
                            break;

                        case 'second':
                            $netPremium = $this->cleanNumber(
                                $pricingSchedule->second_class
                            );
                            break;

                        case 'third':
                            $netPremium = $this->cleanNumber(
                                $pricingSchedule->third_class
                            );
                            break;
                    }
                }


                /*
            |--------------------------------------------------------------------------
            | Calculate Fees / Taxes / Gross Premium
            |--------------------------------------------------------------------------
            */

                $premium = $this->calculatePremium(
                    $netPremium,
                    $item->fees ?? 0,
                    $item->stamps ?? 0,
                    $item->sales_tax ?? 0,
                    $item->cbj ?? 0,
                    $item->sales_tax_cbj ?? 0
                );


                /*
            |--------------------------------------------------------------------------
            | Add Client Information
            |--------------------------------------------------------------------------
            */

                $item->client_age = $clientAge;

                $item->client_gender = $clientGender;

                $item->selected_class = $selectedClass;


                /*
            |--------------------------------------------------------------------------
            | Pricing Information
            |--------------------------------------------------------------------------
            */

                $item->pricing_schedule_id = $pricingSchedule?->id;


                /*
            |--------------------------------------------------------------------------
            | Premium Information
            |--------------------------------------------------------------------------
            */

                $item->net_premium = $premium['net_premium'];

                $item->fees_amount = $premium['fees'];

                $item->stamps_amount = $premium['stamps'];

                $item->sales_tax_amount = $premium['sales_tax'];

                $item->cbj_amount = $premium['cbj'];

                $item->sales_tax_cbj_amount =
                    $premium['sales_tax_on_cbj'];

                $item->gross_premium = $premium['gross_premium'];


                /*
            |--------------------------------------------------------------------------
            | Return Only Matching Pricing Schedule
            |--------------------------------------------------------------------------
            */

                if ($pricingSchedule) {

                    $item->pricing_schedule = collect([
                        $pricingSchedule
                    ]);
                } else {

                    $item->pricing_schedule = collect();
                }


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


            /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_inout_patient_insurance_plan_successfully'),
                'data' => $data
            ]);


            // $data->transform(function ($item) {
            //     if (!empty($item->insurance_policy_pdf)) {
            //         $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
            //     }
            //     if (!empty($item->insurance_company->privacy_policy)) {
            //         $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
            //     }
            //     return $item;
            // });
            // return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get In & Out Patient Insurance Plan successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }
    // public function getInPatientInsurancePlan(Request $request)
    // {
    //     try {

    //         $data = InPatientPlan::with('policy_covers', 'insurance_company')
    //             ->whereHas('insurance_company', function ($q) {
    //                 $q->whereNull('deleted_at');
    //             })
    //             ->where('limit', 'LIKE', '%' . $request->limit . '%')
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
    //         return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get In-Patient Insurance Plan successfully', 'data' => $data]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
    //     }
    // }

    // public function getOutPatientInsurancePlan(Request $request)
    // {
    //     try {
    //         $data = InOutPatientPlan::with('policy_covers', 'insurance_company')
    //             ->whereHas('insurance_company', function ($q) {
    //                 $q->whereNull('deleted_at');
    //             })
    //             ->where('limit', 'LIKE', '%' . $request->limit . '%')
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
    //         return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get In & Out Patient Insurance Plan successfully', 'data' => $data]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
    //     }
    // }
    private function calculatePremium(
        $netPremium,
        $fees,
        $stamps,
        $salesTax,
        $cbj,
        $salesTaxOnCbj
    ) {
        $netPremium = (float) $netPremium;

        $fees = (float) $fees;
        $stamps = (float) $stamps;
        $salesTax = (float) $salesTax;
        $cbj = (float) $cbj;
        $salesTaxOnCbj = (float) $salesTaxOnCbj;

        $issuanceFees = ($netPremium * $fees) / 100;

        $stampAmount = ($netPremium * $stamps) / 100;

        $cbjContribution = ($netPremium * $cbj) / 100;

        $salesTaxAmount =
            (($netPremium + $issuanceFees) * $salesTax) / 100;

        $cbjSalesTaxAmount =
            ($cbjContribution * $salesTaxOnCbj) / 100;

        $grossPremium =
            $netPremium
            + $issuanceFees
            + $stampAmount
            + $salesTaxAmount
            + $cbjContribution
            + $cbjSalesTaxAmount;

        return [
            'net_premium' => round($netPremium, 2),
            'fees' => round($issuanceFees, 2),
            'stamps' => round($stampAmount, 2),
            'sales_tax' => round($salesTaxAmount, 2),
            'cbj' => round($cbjContribution, 2),
            'sales_tax_on_cbj' => round($cbjSalesTaxAmount, 2),
            'gross_premium' => round($grossPremium, 2),
        ];
    }
}
