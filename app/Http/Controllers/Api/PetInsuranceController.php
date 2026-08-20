<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\InsurancePlanModels\PetPlan;
use App\Models\{ClientPetsInsurance, PurchasePolicy, InsuranceCompany};
use Illuminate\Http\Request;
use PDF;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\Currency;
use Illuminate\Support\Facades\Log;
use App\Models\Ages;
use App\Models\PetBreed;

class PetInsuranceController extends Controller
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

    public function storePetsInsurance(Request $request)
    {
        try {

            $data = $request->validate([
                'id' => 'nullable',
                'pets_type' => 'nullable',
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'third_name' => 'nullable',
                'family_name' => 'nullable',
                'nationality_no' => 'nullable',
                'id_residence_no' => 'nullable',
                'birth_date' => 'nullable',
                'pets_name' => 'nullable',
                'pets_dob' => 'nullable',
                'gender' => 'nullable',
                'type_of_pets' => 'nullable',
                'breed' => 'nullable',
                'pets_existing_condition_status' => 'nullable',
                'pets_existing_condition' => 'nullable',
                'insurance_limit' => 'nullable',
                'inception_date' => 'nullable',
                'expiry_date' => 'nullable',
                'plan_id' => 'required',
                'vaccine_document' => 'nullable',
                'pets_picture' => 'nullable',
                'pets_passport' => 'nullable',
                'personal_picture_documents' => 'nullable',
                'pets_permit' => 'nullable',
                'payment_status' => 'nullable',
            ]);

            $plan_data = PetPlan::find($data['plan_id']);
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

            $clientAge = Carbon::parse($client->birth_date)->age;
            $petAge = 0;
            if (!empty($data['pets_dob'])) {
                $petAge = Carbon::parse($data['pets_dob'])->age;
            }
            $restrictedCountries  = $this->normalizeRestricted($plan_data->restricted_country_ids);
            $restrictedCities     = $this->normalizeRestricted($plan_data->restricted_city_ids);
            $restrictedDistricts  = $this->normalizeRestricted($plan_data->restricted_district_ids);
            $restrictedAges       = $this->normalizeRestricted($plan_data->restricted_age_ids);
            $restrictedPetAgeIds  = $this->normalizeRestricted($plan_data->restricted_pet_age_ids);
            $restrictedBreedIds   = $this->normalizeRestricted($plan_data->restricted_pet_breed_ids);

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
            if (!empty($restrictedPetAgeIds)) {
                $petDob = !empty($data['pets_dob']) ? Carbon::parse($data['pets_dob']) : null;

                if ($petDob) {
                    $petAgeInYears  = $petDob->age;
                    $petAgeInMonths = (int) $petDob->diffInMonths(Carbon::now());

                    $restrictedAgeRecords = Ages::whereIn('id', $restrictedPetAgeIds)->get();

                    foreach ($restrictedAgeRecords as $ageRecord) {
                        if ($ageRecord->type === 'year' && $petAgeInYears == $ageRecord->age) {
                            return $this->restrictionError('pet age');
                        }
                        if ($ageRecord->type === 'month' && $petAgeInMonths == $ageRecord->age) {
                            return $this->restrictionError('pet age');
                        }
                    }
                }
            }

            if (!empty($restrictedBreedIds) && !empty($data['breed'])) {
                $breedInput = strtolower(trim($data['breed']));

                $restrictedBreeds = PetBreed::whereIn('id', $restrictedBreedIds)
                    ->get()
                    ->map(fn($b) => strtolower(trim($b->breed)))
                    ->toArray();

                if (in_array($breedInput, $restrictedBreeds)) {
                    return $this->restrictionError('breed');
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

            $existingPet = PurchasePolicy::find($request->purchase_id ?? 0);

            if ($existingPet) {

                $pets = ClientPetsInsurance::find($existingPet->policy_id);
                if ($pets) $pets->update($data);

                $data['purchase_id'] = $existingPet->id;
                $pet = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $pet->policy_id;
            } else {

                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $pet = ClientPetsInsurance::create($data);

                $data['policy_type'] = 8;
                $data['policy_id'] = $pet->id;

                $pet = PurchasePolicy::savePurchasePolicy($data);
            }

            $data = ClientPetsInsurance::getPetsInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/pet_policy');
            if (!file_exists($directory)) mkdir($directory, 0755, true);

            $filename = uniqid() . '_policy_' . $pet->id . '.pdf';
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
            $purchase['purchase_id']          = $pet->id;
            $purchase['plan_id']              = $data->plan_id;
            $purchase['plan_name']            = $plan_data->plan_name;
            $purchase['policy_plan_limit']    = $policy_limit;
            $purchase['insurance_company_id'] = $data->insurance_company_id;
            $purchase['inception_date'] = $data->effective_date ?? $data->inception_date ?? '';
            $purchase['expiry_date']          = $data->expiry_date;
            $purchase['commission_percentage'] = $data->commission_percentage;
            $purchase['policy_pdf_url']       = $path;

            PurchasePolicy::updatePurchasePolicy($purchase);

            $data->purchase_id = $pet->id;

            $purchaseData = PurchasePolicy::where('id', $pet->id)->first();
            $plan_data = PetPlan::with('policy_covers')->find($data['plan_id']);
            $client = Client::with('country.currency')->find($request->user_id);
            $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';

            $pdf = Pdf::loadView('pdf/pet_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'purchase' => $purchaseData,
                'plan' => $plan_data
            ]);

            $pdf->save($path);

            $url = url('insurance_pdfs/pet_policy/' . $filename);
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
                'message' => 'Add Pet Insurance Plan successfully',
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

    public function getPetsInsurancePlan(Request $request)
    {
        try {

            $data = PetPlan::with('policy_covers', 'insurance_company')
            ->whereHas('insurance_company', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->where('plan_name', 'LIKE', '%' . $request->limit . '%')
            ->get();

            $data->transform(function ($item) {
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
                }

                $item->period_days = (int)$item->policy_period;

                return $item;
            });

            return response()->json([
                'status'      => true,
                'status_code' => 200,
                'message'     => 'Get Pets Insurance Plan successfully',
                'data'        => $data
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'message'     => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data'        => []
            ]);
        }
    }
    // public function getPetsInsurancePlan(Request $request)
    // {
    //     try {

    //         $query = PetPlan::with(['policy_covers', 'insurance_company']);

    //         // ✅ Search by plan name (optional)
    //         if ($request->filled('plan_name')) {
    //             $query->where('plan_name', 'LIKE', '%' . $request->plan_name . '%');
    //         }

    //         // ✅ Optional filter by company (if needed)
    //         if ($request->filled('insurance_company_id')) {
    //             $query->where('insurance_company_id', $request->insurance_company_id);
    //         }

    //         // ✅ Pagination or limit support
    //         $limit = $request->limit ?? null;

    //         if ($limit) {
    //             $data = $query->limit((int)$limit)->get();
    //         } else {
    //             $data = $query->get();
    //         }

    //         // ✅ Transform response safely
    //         $data->transform(function ($item) {

    //             // PDF URL
    //             if (!empty($item->insurance_policy_pdf)) {
    //                 $item->insurance_policy_pdf = url(
    //                     'uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf
    //                 );
    //             }

    //             // Safe relationship check (IMPORTANT)
    //             if (!empty($item->insurance_company?->privacy_policy)) {
    //                 $item->insurance_company->privacy_policy = url(
    //                     'insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy
    //                 );
    //             }

    //             // Type casting
    //             $item->period_days = (int) $item->policy_period;

    //             return $item;
    //         });

    //         return response()->json([
    //             'status'      => true,
    //             'status_code' => 200,
    //             'message'     => 'Get Pets Insurance Plan successfully',
    //             'data'        => $data
    //         ]);
    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'status'      => false,
    //             'status_code' => 500,
    //             'message'     => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
    //             'data'        => []
    //         ]);
    //     }
    // }
}
