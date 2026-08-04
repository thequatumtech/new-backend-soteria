<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ClientFamilyMedicalInsurance, FamilyMedicalInsuranceMember, PurchasePolicy};
use App\Models\InsurancePlanModels\{InOutPatientPlan, InPatientPlan, InPatientPlanPricingSchedule, InOutPatientPlanPricingSchedule};
use Illuminate\Http\Request;
use PDF;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Currency;

class ClientFamilyMedicalInsuranceController extends Controller
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
    private function getPremiumForMember($planType, $planId, $age, $gender, $insuranceClass)
    {
        // Normalize gender: 1 = Male, 2 = Female
        // Checks for 'Male', 'male', '1' -> 1, otherwise 2
        $genderVal = (is_string($gender) && strtolower($gender) === 'male') || $gender == 1 ? 1 : 2;

        $pricing = null;
        if ($planType == 1) { // InPatient
            $pricing = InPatientPlanPricingSchedule::where('in_patient_plan_id', $planId)
                ->where('gender', $genderVal)
                ->where('lower_age', '<=', $age)
                ->where('upper_age', '>=', $age)
                ->first();
        } else { // InOutPatient
            $pricing = InOutPatientPlanPricingSchedule::where('in_out_patient_plan_id', $planId)
                ->where('gender', $genderVal)
                ->where('lower_age', '<=', $age)
                ->where('upper_age', '>=', $age)
                ->first();
        }

        if (!$pricing) return 0;

        switch ($insuranceClass) {
            case 'VIP Class':
                return $pricing->vip_class;
            case 'First Class':
                return $pricing->first_class;
            case 'Second Class':
                return $pricing->second_class;
            case 'Third Class':
                return $pricing->third_class;
            default:
                return 0;
        }
    }

    public function storeFamilyMedicalInsurance(Request $request)
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
                'company_company_contact' => 'nullable',
                'existing_policy_status' => 'nullable',
                'existing_policy_company_name' => 'nullable',
                'existing_policy_expiry_date' => 'nullable',
                'existing_policy_card' => 'nullable',
                'height' => 'nullable',
                'wight' => 'nullable',
                'chronic_diseases_id' => 'nullable',
                'previous_operation' => 'nullable',
                'operation_details' => 'nullable',
                'pregnant_status' => 'nullable',
                'pregnant_month' => 'nullable',
                'dangerous_status' => 'nullable',
                'dangerous_id' => 'nullable',
                'passport_front_id' => 'nullable',
                'passport_back_id' => 'nullable',
                'family_book_documents' => 'nullable',
                'personal_picture_documents' => 'nullable',
                'other_documents' => 'nullable',
                'inception_date' => 'nullable',
                'expiry_date' => 'nullable',
                'insurance_type' => 'nullable',
                'insurance_class' => 'nullable',
                'inpatient_deductible_id' => 'nullable|exists:in_patient_deductibles,id',
                'outpatient_deductible_id' => 'nullable|exists:out_patient_deductibles,id',
                'no_of_visits_id' => 'nullable|exists:no_of_visits,id',
                'insurance_limit' => 'nullable',
                'insurance_type_status' => 'nullable',
                'plan_id' => 'nullable',
            ]);

            $existingMedical = PurchasePolicy::find($request->purchase_id ?? 0);
            if ($data['insurance_type'] == 1) {
                // $plan_data = InPatientPlan::find($data['plan_id']);
                $plan_data = InPatientPlan::with([
                    'policy_covers',
                    'additional_benefits'
                ])->find($data['plan_id']);
                $data['insurance_company_id'] = $plan_data->insurance_company_id;
            } else {
                // $plan_data = InOutPatientPlan::find($data['plan_id']);
                $plan_data = InOutPatientPlan::with([
                    'policy_covers',
                    'additional_benefits'
                ])->find($data['plan_id']);
                $data['insurance_company_id'] = $plan_data->insurance_company_id;
            }

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
            $restrictedDangerousActivities = $this->normalizeRestricted($plan_data->restricted_dangerous_activities_ids);

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

            $userDangerousActivities = $this->normalizeRestricted($data['dangerous_id'] ?? '');
            if (!empty($userDangerousActivities)) {
                foreach ($userDangerousActivities as $activityId) {
                    if (in_array($activityId, $restrictedDangerousActivities)) {
                        return $this->restrictionError('dangerous activity');
                    }
                }
            }
            if (!empty($data['inception_date'])) {
                $inceptionDate = Carbon::parse($this->normalizeDate($data['inception_date']));
            } else {
                $inceptionDate = Carbon::today();
                $data['inception_date'] = $inceptionDate->format('Y-m-d');
            }

            if (!empty($plan_data->policy_period)) {
                $data['expiry_date'] = $inceptionDate->copy()
                    ->addDays((int)$plan_data->policy_period)
                    ->format('Y-m-d');
            }
            if ($existingMedical) {
                $Medical = ClientFamilyMedicalInsurance::find($existingMedical->policy_id);
                if ($Medical) {
                    $Medical->update($data);
                    $Medical->toArray();
                }
                $data['purchase_id'] = $existingMedical->id;
                $Medical = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $Medical->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $Medical = ClientFamilyMedicalInsurance::create($data);
                if ($data['insurance_type_status'] == 1) {
                    $data['policy_type'] = 6;
                } else {
                    $data['policy_type'] = 7;
                }
                $data['policy_id'] = $Medical->id;
                $Medical = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientFamilyMedicalInsurance::getClientFamilyMedicalInsuranceDetails($data);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'No medical insurance details found for your age.',
                    'data' => []
                ]);
            }

            if ($data['insurance_type_status'] == 1) {
                $directory = public_path('insurance_pdfs/individual_medical_insurance');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                $filename = uniqid() . '_policy_' . $Medical->id . '.pdf';
                $path = $directory . '/' . $filename;
            } else {
                $directory = public_path('insurance_pdfs/family_medical_insurance');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                $filename = uniqid() . '_policy_' . $Medical->id . '.pdf';
                $path = $directory . '/' . $filename;
            }

            // ---------------------- CALCULATE PREMIUMS ----------------------
            //    $clientGender = ($data['gender'] ?? $client->gender) == 'Male' ? 1 : 2;
            // $clientAge = Carbon::parse($data['birth_date'] ?? $client->birth_date)->age;

            // $basePremium = 0;
            // $pricing = null;

            // if ($data['insurance_type'] == 1) {
            //     $pricing = InPatientPlanPricingSchedule::where('in_patient_plan_id', $data['plan_id'])
            //         ->where('gender', $clientGender)
            //         ->where('lower_age', '<=', $clientAge)
            //         ->where('upper_age', '>=', $clientAge)
            //         ->first();
            // } else {
            //     $pricing = InOutPatientPlanPricingSchedule::where('in_out_patient_plan_id', $data['plan_id'])
            //         ->where('gender', $clientGender)
            //         ->where('lower_age', '<=', $clientAge)
            //         ->where('upper_age', '>=', $clientAge)
            //         ->first();
            // }

            // if ($pricing) {
            //     switch ($data['insurance_class']) {
            //         case 'VIP Class':
            //             $basePremium = $pricing->vip_class;
            //             break;
            //         case 'First Class':
            //             $basePremium = $pricing->first_class;
            //             break;
            //         case 'Second Class':
            //             $basePremium = $pricing->second_class;
            //             break;
            //         case 'Third Class':
            //             $basePremium = $pricing->third_class;
            //             break;
            $totalNetPremium = 0;

            // 1. Calculate Main Client Premium
            $clientGender = $data['gender'] ?? $client->gender;
            $clientAge = Carbon::parse($data['birth_date'] ?? $client->birth_date)->age;

            $totalNetPremium += $this->getPremiumForMember(
                $data['insurance_type'],
                $data['plan_id'],
                $clientAge,
                $clientGender,
                $data['insurance_class']
            );

            // 2. Calculate Family Members Premium
            if (!empty($request->members)) {
                foreach ($request->members as $member) {
                    $memberAge = Carbon::parse($member['birth_date'])->age;
                    $memberGender = $member['gender'];
                    $totalNetPremium += $this->getPremiumForMember(
                        $data['insurance_type'],
                        $data['plan_id'],
                        $memberAge,
                        $memberGender,
                        $data['insurance_class']
                    );
                }
            }

            $policy_limit = $this->cleanNumber($plan_data->limit);
            // $purchase['net_premium']       = ($plan_data->net_premium / 100) * $policy_limit;
            // $purchase['fees']              = ($plan_data->fees / 100) * $policy_limit;
            // $purchase['stamps']            = ($plan_data->stamps / 100) * $policy_limit;// Formula Implementation
            $net_premium = $totalNetPremium;
            $feesPercentage = (float)$plan_data->fees;
            $stampsPercentage = (float)$plan_data->stamps;
            $salesTaxPercentage = (float)$plan_data->sales_tax;

            // Allow for CBJ if added to model later
            $cbjTaxPercentage = (float)($plan_data->cbj ?? 0);
            $cbjSalesTaxPercentage = (float)($plan_data->sales_tax_cbj ?? 0);

            // Issuance Fees (as % of Net Premium)
            $issuanceFees = ($net_premium * $feesPercentage) / 100;

            // Stamps (as % of Net Premium)
            $stampAmount = ($net_premium * $stampsPercentage) / 100;

            // CBJ Contribution Fund (as % of Net Premium)
            $cbjContribution = ($net_premium * $cbjTaxPercentage) / 100;

            // Sales Tax on (Net Premium + Issuance Fees) - Standard formula usually includes fees in tax base
            // Checking HomeInsuranceController: $salesTaxAmount = (($net_premium + $issuanceFees) * $salesTaxPercentage) / 100;
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
            $purchase['commission_amount'] = ($plan_data->commission_percentage ?? 0) / 100 * $net_premium; // Commission usually on Net


            $purchase['purchase_id']          = $Medical->id;
            $purchase['plan_id']              = $data->plan_id;
            $purchase['plan_name']            = $plan_data->plan_name;
            $purchase['policy_plan_limit']    = $policy_limit;
            $purchase['insurance_company_id'] = $data->insurance_company_id;
            $purchase['inception_date']       = $data->inception_date;
            $purchase['expiry_date']          = $data->expiry_date;
            $purchase['commission_percentage'] = $plan_data->commission_percentage;
            $purchase['policy_pdf_url']       = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id = $Medical->id;
            $plan_for_pdf = clone $plan_data;
            // Store the amounts in the plan object for the PDF values
            $plan_for_pdf->net_premium_amount = $net_premium;
            $plan_for_pdf->fees_amount = $issuanceFees;
            $plan_for_pdf->stamps_amount = $stampAmount;
            $plan_for_pdf->sales_tax_amount = $salesTaxAmount;
            $plan_for_pdf->cbj_amount = $cbjContribution;
            $plan_for_pdf->sales_tax_cbj_amount = $cbjSalesTaxAmount;
            $plan_for_pdf->gross_premium_amount = $grossPremium;
            $plan_for_pdf->policy_covers = $plan_data->policy_covers ?? [];
            $plan_for_pdf->additional_benefits = $plan_data->additional_benefits ?? [];
            $client = Client::with('country.currency')->find($request->user_id);
            $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';
            // Ensure percentages are set on the object if they came from fallbacks
            if (!isset($plan_for_pdf->cbj)) $plan_for_pdf->cbj = $cbjTaxPercentage;
            if (!isset($plan_for_pdf->sales_tax_cbj)) $plan_for_pdf->sales_tax_cbj = $cbjSalesTaxPercentage;

            if ($data['insurance_type_status'] == 1) {
                $directory = public_path('insurance_pdfs/individual_medical_insurance');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                $purchaseData = PurchasePolicy::find($Medical->id);
                $pdf = PDF::loadView('pdf/individual_medical_insurance', [
                    'data' => $data,
                    'abbr' => $abbr,
                    'purchaseData' => $purchaseData,
                    'plan' => $plan_for_pdf
                ]);
                $filename = uniqid() . '_policy_' . $Medical->id . '.pdf';
                $path = $directory . '/' . $filename;
                $pdf->save($path);
                $url = url('insurance_pdfs/individual_medical_insurance/' . $filename);
            } else {
                $directory = public_path('insurance_pdfs/family_medical_insurance');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                $purchaseData = PurchasePolicy::find($Medical->id);
                $pdf = PDF::loadView('pdf/family_medical_insurance', [
                    'data' => $data,
                    'abbr' => $abbr,
                    'purchaseData' => $purchaseData,
                    'plan' => $plan_for_pdf
                ]);
                $filename = uniqid() . '_policy_' . $Medical->id . '.pdf';
                $path = $directory . '/' . $filename;
                $pdf->save($path);
                $url = url('insurance_pdfs/family_medical_insurance/' . $filename);
            }
            $data['url'] = $url;

            if (!empty($request->members)) {
                foreach ($request->members as $single) {
                    $family_member = new FamilyMedicalInsuranceMember();
                    $family_member->client_insurance_id = $data['id'];
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
                    $family_member->marital_status = $single['marital_status'];
                    $family_member->occupancy_work = $single['occupancy_work'] ?? null;
                    $family_member->height = $single['height'] ?? null;
                    $family_member->wight = $single['wight'] ?? null;
                    $family_member->chronic_diseases_id = $single['chronic_diseases_id'] ?? null;
                    $family_member->previous_operation = $single['previous_operation'] ?? null;
                    $family_member->operation_details = $single['operation_details'] ?? null;
                    $family_member->pregnant_status = $single['pregnant_status'] ?? null;
                    $family_member->pregnant_month = $single['pregnant_month'] ?? null;
                    $family_member->dangerous_status = $single['dangerous_status'] ?? null;
                    $family_member->dangerous_id = $single['dangerous_id'] ?? null;
                    $family_member->save();
                }
            }


            // $data->net_premium = number_format($plan_data->net_premium, 2) . ' ' . $abbr;
            // $data->fees = number_format($plan_data->fees, 2) . ' ' . $abbr;
            // $data->gross_premium = number_format($plan_data->gross_premium, 2) . ' ' . $abbr;
            // $data->sales_tax = number_format($plan_data->sales_tax, 2) . ' ' . $abbr;
            // $data->stamps = number_format($plan_data->stamps, 2) . ' ' . $abbr;
            // $data->commission_amount = number_format($plan_data->commission_amount, 2) . ' ' . $abbr;
            // $data->commission_percentage = $plan_data->commission_percentage . '%';
            $data->net_premium = number_format($purchase['net_premium'], 2) . ' ' . $abbr;
            $data->fees = number_format($purchase['fees'], 2) . ' ' . $abbr;
            $data->gross_premium = number_format($purchase['gross_premium'], 2) . ' ' . $abbr;
            $data->sales_tax = number_format($purchase['sales_tax'], 2) . ' ' . $abbr;
            $data->stamps = number_format($purchase['stamps'], 2) . ' ' . $abbr;
            $data->cbj = number_format($purchase['cbj'], 2) . ' ' . $abbr;
            $data->sales_tax_cbj = number_format($purchase['sales_tax_cbj'], 2) . ' ' . $abbr;
            $data->commission_amount = number_format($purchase['commission_amount'], 2) . ' ' . $abbr;
            $data->commission_percentage = $plan_data->commission_percentage . '%';


            if ($data['insurance_type_status'] == 1) {
                return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Individual Medical Insurance Plan successfully', 'data' => $data]);
            }


            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Family Medical Insurance Plan successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => array()]);
        }
    }
}
