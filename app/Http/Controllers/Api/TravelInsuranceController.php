<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsurancePlanModels\TravelPlan;
use App\Models\{ClientTravelInsurance, FamilyTravelInsuranceMember, PurchasePolicy};
use PDF;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Currency;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TravelInsuranceController extends Controller
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

    public function storeTravelInsurance(Request $request)
    {
        try {
            $data = $request->validate([
                'self_family_status' => 'nullable',
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
                'passport_document' => 'nullable',
                'departure_from_country_id' => 'nullable',
                'destination_country_id' => 'nullable',
                'additional_destination_country_id' => 'nullable',
                'geographical_countries' => 'nullable',
                'effective_date' => 'nullable',
                'travel_days' => 'nullable',
                'expiry_date' => 'nullable',
                'insurance_limit' => 'nullable',
                'plan_id' => 'required',
                'payment_status' => 'nullable',
                'multiple_destination' => 'nullable',
                'dangerous_activities' => 'nullable',
            ]);

            if ($request->has('dangerous_activities')) {
                $data['dangerous_activities'] = is_array($request->dangerous_activities)
                    ? implode(',', $request->dangerous_activities)
                    : $request->dangerous_activities;
            }

            if ($request->has('multiple_destination')) {
                $data['multiple_destination'] = is_array($request->multiple_destination)
                    ? implode(',', $request->multiple_destination)
                    : $request->multiple_destination;
            }

            if ($request->has('geographical_countries')) {
                $data['geographical_countries'] = is_array($request->geographical_countries)
                    ? json_encode($request->geographical_countries)
                    : $request->geographical_countries;
            }

            if (!empty($request->effective_date)) {
                $data['effective_date'] = date('Y-m-d', strtotime($request->effective_date));
            }

            if (!empty($request->expiry_date)) {
                $data['expiry_date'] = date('Y-m-d', strtotime($request->expiry_date));
            }

            $data['inception_date'] = $data['effective_date'] ?? null;

            $plan_data = TravelPlan::find($data['plan_id']);
            $data['insurance_company_id'] = $plan_data->insurance_company_id ?? 0;

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
            $userDangerousActivities = [];
            $clientAge = Carbon::parse($client->birth_date)->age;

            $restrictedCountries = $this->normalizeRestricted($plan_data->restricted_country_ids);
            $restrictedCities    = $this->normalizeRestricted($plan_data->restricted_city_ids);
            $restrictedDistricts = $this->normalizeRestricted($plan_data->restricted_district_ids);
            $restrictedAges      = $this->normalizeRestricted($plan_data->restricted_age_ids);
            $restrictedDangerousActivities = $this->normalizeRestricted($plan_data->restricted_dangerous_activities_ids ?? null);

            if ($clientCountry && in_array($clientCountry, $restrictedCountries)) {
                return $this->restrictionError('country');
            }

            if ($clientCity && in_array($clientCity, $restrictedCities)) {
                return $this->restrictionError('city');
            }

            if ($clientDistrict && in_array($clientDistrict, $restrictedDistricts)) {
                return $this->restrictionError('district');
            }

            if (!empty($data['dangerous_activities'])) {
                $userDangerousActivities = is_array($data['dangerous_activities'])
                    ? $data['dangerous_activities']
                    : explode(',', $data['dangerous_activities']);
            }
            foreach ($userDangerousActivities as $activityId) {
                if (in_array(trim($activityId), $restrictedDangerousActivities)) {
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

            // -------------------------
            // Calculate travel days and net premium
            $effectiveDate = Carbon::parse($data['effective_date']);
            $expiryDate    = Carbon::parse($data['expiry_date']);
            $travelDays    = $effectiveDate->diffInDays($expiryDate);

            $pricing = \App\Models\TravelPlanPricingSchedule::where('travel_plan_id', $data['plan_id'])
                ->where('min_days', '<=', $travelDays)
                ->where('max_days', '>=', $travelDays)
                ->first();


            if (!$pricing) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'No pricing available for selected travel duration.',
                    'data' => []
                ], 422);
            }

            $netPremium = (float)$pricing->price;

            $surchargeBand = \App\Models\TravelPlanSurchargeBand::where('travel_plan_id', $data['plan_id'])
                ->where('min_age', '<=', $clientAge)
                ->where('max_age', '>=', $clientAge)
                ->first();

            if ($surchargeBand) {
                $netPremium += ($netPremium * $surchargeBand->surcharge) / 100;
            }

            $netPremium = round($netPremium, 2);
            // -------------------------

            // ---------- JOD Conversion Calculation ----------
            $feesPercentage      = (float)$plan_data->fees;
            $stampsPercentage    = (float)$plan_data->stamps;
            $salesTaxPercentage  = (float)$plan_data->sales_tax;

            $cbjTaxPercentage = (float)($plan_data->cbj ?? 0);
            $cbjSalesTaxPercentage = (float)($plan_data->sales_tax_cbj ?? 0);

            $feesAmount      = ($netPremium * $feesPercentage) / 100;
            $stampsAmount    = ($netPremium * $stampsPercentage) / 100;
            $cbjContribution = ($netPremium * $cbjTaxPercentage) / 100;
            $salesTaxAmount  = (($netPremium + $feesAmount) * $salesTaxPercentage) / 100;
            $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;

            $grossPremium = $netPremium + $feesAmount + $stampsAmount + $salesTaxAmount + $cbjContribution + $cbjSalesTaxAmount;
            // ------------------------------------------------

            $existingtravel = PurchasePolicy::find($request->purchase_id ?? 0);

            if ($existingtravel) {
                $travels = ClientTravelInsurance::find($existingtravel->policy_id);
                if ($travels) {
                    $travels->update($data);
                }

                $data['purchase_id'] = $existingtravel->id;
                $data['inception_date'] = $data['effective_date'];

                $travel = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $travel->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $travel = ClientTravelInsurance::create($data);

                $data['policy_type'] = 10;
                $data['policy_id'] = $travel->id;
                $data['inception_date'] = $data['effective_date'];

                $travel = PurchasePolicy::savePurchasePolicy($data);
            }

            $data = ClientTravelInsurance::getTravelsInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/travel_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = uniqid() . '_policy_' . $travel->id . '.pdf';
            $path = $directory . '/' . $filename;

            // Save purchase with JOD values
            $purchase['net_premium']       = $netPremium;
            $purchase['fees']              = $feesAmount;
            $purchase['stamps']            = $stampsAmount;
            $purchase['sales_tax']         = $salesTaxAmount;
            $purchase['cbj']               = $cbjContribution;
            $purchase['sales_tax_cbj']     = $cbjSalesTaxAmount;
            $purchase['gross_premium']     = $grossPremium;
            $purchase['commission_amount'] = ($plan_data->commission_percentage ?? 0) / 100 * $netPremium;

            $purchase['purchase_id']           = $travel->id;
            $purchase['plan_id']               = $data->plan_id;
            $purchase['plan_name']             = $plan_data->plan_name;
            $purchase['policy_plan_limit']     = $plan_data->limit;
            $purchase['insurance_company_id']  = $data->insurance_company_id;
            $purchase['inception_date']        = $data['effective_date'];
            $purchase['expiry_date']           = $data['expiry_date'];
            $purchase['commission_percentage'] = $plan_data->commission_percentage ?? 0;
            $purchase['policy_pdf_url']        = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);

            $data->purchase_id = $travel->id;
            $plan_for_pdf = clone $plan_data;
            $plan_for_pdf->net_premium_amount = $netPremium;
            $plan_for_pdf->fees_amount = $feesAmount;
            $plan_for_pdf->stamps_amount = $stampsAmount;
            $plan_for_pdf->sales_tax_amount = $salesTaxAmount;
            $plan_for_pdf->cbj_amount = $cbjContribution;
            $plan_for_pdf->sales_tax_cbj_amount = $cbjSalesTaxAmount;
            $plan_for_pdf->gross_premium_amount = $grossPremium;
            if (!isset($plan_for_pdf->cbj)) $plan_for_pdf->cbj = $cbjTaxPercentage;
            if (!isset($plan_for_pdf->sales_tax_cbj)) $plan_for_pdf->sales_tax_cbj = $cbjSalesTaxPercentage;

            $client = Client::with('country.currency')->find($request->user_id);
            $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';

            $pdf = PDF::loadView('pdf/travel_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'plan' => $plan_for_pdf
            ]);
            $pdf->save($path);

            $data['url'] = url('insurance_pdfs/travel_policy/' . $filename);



            // Format values in JOD instead of %
            $data->net_premium = number_format($purchase['net_premium'], 2) . ' ' . $abbr;
            $data->fees = number_format($purchase['fees'], 2) . ' ' . $abbr;
            $data->gross_premium = number_format($purchase['gross_premium'], 2) . ' ' . $abbr;
            $data->sales_tax = number_format($purchase['sales_tax'], 2) . ' ' . $abbr;
            $data->stamps = number_format($purchase['stamps'], 2) . ' ' . $abbr;
            $data->commission_amount = number_format($purchase['commission_amount'], 2) . ' ' . $abbr;

            if (!empty($request->members)) {
                foreach ($request->members as $single) {
                    $family_member = new FamilyTravelInsuranceMember();
                    $family_member->client_insurance_id = $travel->id;
                    $family_member->first_name = $single['first_name'];
                    $family_member->last_name = $single['last_name'];
                    $family_member->third_name = $single['third_name'];
                    $family_member->family_name = $single['family_name'];
                    $family_member->relation = $single['relation'];
                    $family_member->nationality = $single['nationality'];
                    $family_member->nationality_no = $single['nationality_no'];
                    $family_member->id_residence_no = $single['id_residence_no'];
                    $family_member->birth_date = $single['birth_date'];
                    $family_member->gender = $single['gender'];
                    $family_member->place_residence = $single['place_residence'];
                    $family_member->passport_document = $single['passport_document'];
                    $family_member->save();
                }
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Add Travel Insurance Plan successfully',
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

    public function getTravelInsurancePlan(Request $request)
    {
        try {
            $data = TravelPlan::with('policy_covers', 'insurance_company');
            if (!empty($request->plan)) {
                $data->where('plan_name', 'LIKE', '%' . $request->plan . '%');
            }
            $data = $data->get();
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
                'message' => 'Get Travel Insurance Plan successfully',
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
