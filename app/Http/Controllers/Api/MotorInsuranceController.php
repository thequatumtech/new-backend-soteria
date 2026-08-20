<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{ClientMotorInsurance, PurchasePolicy, MotorPlan};
use App\Models\InsurancePlanModels\{MotorInsurancePlan, MotorInsurancePlanNoClaimDiscount, MotorInsurancePlanNetPremiumIncreasePercentage, MotorInsurancePlanCondition, MotorInsurancePlansCommissionScheduleCalculation, MotorInsurancePlanComprehensiveCoverPremium, MotorInsurancePlan3MonthsCompulsoryPremium, MotorInsurancePlan6MonthsCompulsoryPremium, MotorInsurancePlan9MonthsCompulsoryPremium, MotorInsurancePlan12MonthsCompulsoryPremium, MotorInsurancePlanTotalLossPremium};
use PDF;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MotorInsuranceController extends Controller
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
    public function storeMotorInsurance(Request $request)
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
                'occupancy' => 'nullable',
                'city_id' => 'nullable',
                'district_id' => 'nullable',
                'street_name' => 'nullable',
                'building_no' => 'nullable',
                'user_mobile_no' => 'nullable',
                'company_name' => 'nullable',
                'position' => 'nullable',
                'work_nature' => 'nullable',
                'company_contact_no' => 'nullable',
                'inception_date' => 'nullable',
                'expiry_date' => 'nullable',
                'no_accident_3_year' => 'nullable',
                'no_ticket_12_month' => 'nullable',
                'no_point_12_month' => 'nullable',
                'vahicle_no' => 'nullable',
                'obtain_vahicle_info' => 'nullable',
                'vahicle_type_id' => 'nullable',
                'vahicle_brand_id' => 'nullable',
                'vahicle_category_id' => 'nullable',
                'vahicle_color_id' => 'nullable',
                'vahicle_register_no' => 'nullable',
                'engine_no' => 'nullable',
                'chassis_no' => 'nullable',
                'engine_type_id' => 'nullable',
                'engine_capacity' => 'nullable',
                'vehicle_manufacturing_date' => 'nullable',
                'vehicle_value' => 'nullable',
                'insurance_type' => 'nullable',
                'residence_id_front' => 'nullable',
                'residence_id_back' => 'nullable',
                'vehicle_license_front' => 'nullable',
                'vehicle_license_back' => 'nullable',
                'vehicle_photo_front' => 'nullable',
                'vehicle_photo_back' => 'nullable',
                'vehicle_photo_right' => 'nullable',
                'vehicle_photo_left' => 'nullable',
                'carseer_documents' => 'nullable',
                'autoscore_documents' => 'nullable',
                'customs_declaration' => 'nullable',
                'payment_status' => 'nullable',
                'plan_id' => 'required',
                "national_id_number" => 'nullable',
                "residency_number" => 'nullable',
            ]);

            // $plan = MotorInsurancePlan::with('fees')->with('commissionSchedules')->find($data['plan_id']);
            // dd($plan);
            $plan = MotorInsurancePlan::with([
                'fees',
                'commissionSchedules',
                'policy_covers',
                'additional_benefits'
            ])->find($data['plan_id']);
            if (!$plan) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Motor Insurance Plan not found',
                    'data' => []
                ]);
            }

            $data['insurance_company_id'] = $plan->insurance_company_id ?? 0;

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

            $restrictedCountries   = $this->normalizeRestricted($plan->restricted_country_ids);
            $restrictedCities      = $this->normalizeRestricted($plan->restricted_city_ids);
            $restrictedDistricts   = $this->normalizeRestricted($plan->restricted_district_ids);
            $restrictedAges        = $this->normalizeRestricted($plan->restricted_age_ids);
            $restrictedVehicleTypes      = $this->normalizeRestricted($plan->restricted_vehicle_type_ids);
            $restrictedVehicleBrands     = $this->normalizeRestricted($plan->restricted_vehicle_brand_ids);
            $restrictedVehicleCategories = $this->normalizeRestricted($plan->restricted_vehicle_category_ids);
            $restrictedEngineTypes       = $this->normalizeRestricted($plan->restricted_engine_type_ids);

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

            if (!empty($data['vahicle_type_id']) && in_array($data['vahicle_type_id'], $restrictedVehicleTypes)) {
                return $this->restrictionError('vehicle type');
            }
            if (!empty($data['vahicle_brand_id']) && in_array($data['vahicle_brand_id'], $restrictedVehicleBrands)) {
                return $this->restrictionError('vehicle brand');
            }
            if (!empty($data['vahicle_category_id']) && in_array($data['vahicle_category_id'], $restrictedVehicleCategories)) {
                return $this->restrictionError('vehicle category');
            }
            if (!empty($data['engine_type_id']) && in_array($data['engine_type_id'], $restrictedEngineTypes)) {
                return $this->restrictionError('engine type');
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
            $existingMotor = PurchasePolicy::find($request->purchase_id ?? 0);
            if ($existingMotor) {
                $motor = ClientMotorInsurance::find($existingMotor->policy_id);
                if ($motor) {
                    $motor->update($data);
                }
                $data['purchase_id'] = $existingMotor->id;
                $motor = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $motor->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);

                $motor = ClientMotorInsurance::create($data);

                $data['policy_type'] = 12;
                $data['policy_id'] = $motor->id;

                $motor = PurchasePolicy::savePurchasePolicy($data);
            }

            $data = ClientMotorInsurance::getMotorInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/motor_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = uniqid() . '_policy_' . $motor->id . '.pdf';
            $path = $directory . '/' . $filename;
            $net_premium = $plan->fees->net_premium ?? 0;
            // $feesPercentage = $plan->fees->fees;
            // $stampsPercentage = $plan->fees->stamps;
            // $salesTaxPercentage = $plan->fees->sales_tax;

            // $feesPercentage = $plan->fees->fees;
            // $stampsPercentage = $plan->fees->stamps;
            // $salesTaxPercentage = $plan->fees->sales_tax;
            $commission_percentage = $plan->fees->commission_percentage ?? 0;

            if ($plan->motor_plan_id == 1) {
                // Determine Net Premium from Comprehensive Schedule
                $vehicle_brand_id = $data['vahicle_brand_id'] ?? null;
                $vehicle_category_id = $data['vahicle_category_id'] ?? null;
                $vehicle_value = (float)($data['vehicle_value'] ?? 0);

                $premium_record = MotorInsurancePlanComprehensiveCoverPremium::where('motor_insurance_plan_id', $plan->id)
                    ->get()
                    ->filter(function ($record) use ($vehicle_brand_id, $vehicle_category_id, $vehicle_value) {
                        $brands = json_decode($record->vehicle_brand_id, true) ?: [];
                        $categories = json_decode($record->vehicle_category_id, true) ?: [];

                        $brand_match = empty($brands) || in_array($vehicle_brand_id, $brands);
                        $category_match = empty($categories) || in_array($vehicle_category_id, $categories);
                        $value_match = $vehicle_value >= $record->from && $vehicle_value <= $record->to;

                        return $brand_match && $category_match && $value_match;
                    })
                    ->first();
                // dd($data);
                Log::info('Matching Debug', [
                    'request_brand' => $vehicle_brand_id, // check if this is null
                    'request_val' => $vehicle_value,
                    'db_plan_id' => $plan->id
                ]);

                //  if ($premium_record) {
                //     $net_premium = (float)($premium_record->premium ?? $net_premium);
                // }
                // dd($premium_record);
                if (!$premium_record) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Vehicle value does not match any insured value range for this plan.'
                    ], 422);
                }

                if ($premium_record) {
                    // Check if the premium is a fixed amount (type 1) or a rate/percentage
                    if (isset($premium_record->premium_type) && $premium_record->premium_type == 1) {
                        // dd('3');
                        $net_premium = (float)$premium_record->premium;
                    } else {
                        // dd('4');
                        // Default to percentage calculation: (Rate / 100) * Vehicle Value
                        $net_premium = ($vehicle_value * (float)($premium_record->premium ?? 0)) / 100;
                    }
                }

                // Determine Commission from Commission Schedule
                $vehicle_type_id = $data['vahicle_type_id'] ?? null;
                $commission_record = MotorInsurancePlansCommissionScheduleCalculation::where('motor_insurance_plan_id', $plan->id)
                    ->when($vehicle_type_id, function ($query) use ($vehicle_type_id) {
                        return $query->where('vehicle_type_id', $vehicle_type_id);
                    })
                    ->where('from', '<=', (float)$net_premium)
                    ->where('to', '>=', (float)$net_premium)
                    ->first();

                if ($commission_record) {
                    $commission_percentage = $commission_record->commission;
                }

                // Adjust for accidents, tickets, and points
                $no_of_accidents = (int)($data['no_accident_3_year'] ?? 0);
                $no_of_points = (int)($data['no_point_12_month'] ?? 0);

                $total_adjustment_percentage = 0;

                // 1. Point-based increase
                if ($no_of_points > 0) {
                    $point_record = MotorInsurancePlanCondition::where('motor_insurance_plan_id', $plan->id)
                        ->where('no_of_points', $no_of_points)
                        ->first();
                    if ($point_record) {
                        $total_adjustment_percentage += (float)$point_record->increase_in_net_premium;
                    }
                }

                // 2. Accident-based adjustment
                if ($no_of_accidents > 0) {
                    // Increase based on accidents (using third year increase as per user request for 3 years)
                    $accident_record = MotorInsurancePlanNetPremiumIncreasePercentage::where('motor_insurance_plan_id', $plan->id)
                        ->where('no_of_accidents', $no_of_accidents)
                        ->first();
                    if ($accident_record) {
                        $total_adjustment_percentage += (float)$accident_record->third_year_increase;
                    }
                } else {
                    // Discount for no claims (using 3 years discount)
                    $discount_record = MotorInsurancePlanNoClaimDiscount::where('motor_insurance_plan_id', $plan->id)
                        ->where('year', 3)
                        ->first();
                    if ($discount_record) {
                        $total_adjustment_percentage -= (float)$discount_record->discount;
                    }
                }

                // Apply total adjustment to Net Premium
                if ($total_adjustment_percentage != 0) {
                    $net_premium += ($net_premium * $total_adjustment_percentage) / 100;
                }
            }

            $feesPercentage = $plan->fees->fees ?? 0;
            $stampsPercentage = $plan->fees->stamps ?? 0;
            $salesTaxPercentage = $plan->fees->sales_tax ?? 0;
            $cbjTaxPercentage = $plan->fees->cbj ?? 0;
            $cbjSalesTaxPercentage = $plan->fees->sales_tax_cbj ?? 0;

            $issuanceFees = ($net_premium * $feesPercentage) / 100;
            $stampAmount = ($net_premium * $stampsPercentage) / 100;
            $cbjContribution = ($net_premium * $cbjTaxPercentage) / 100;
            $salesTaxAmount = (($net_premium + $issuanceFees) * $salesTaxPercentage) / 100;
            $cbjSalesTaxAmount = ($cbjContribution * $cbjSalesTaxPercentage) / 100;
            $grossPremium = $net_premium + $issuanceFees + $stampAmount + $salesTaxAmount + $cbjContribution + $cbjSalesTaxAmount;


            // $purchase = [
            //     'net_premium'          => $plan->fees->net_premium,
            //     'fees'                 => $plan->fees->fees,
            //     'stamps'               => $plan->fees->stamps,
            //     'sales_tax'            => $plan->fees->sales_tax,
            //     'gross_premium'        => $plan->fees->gross_premium,
            //     'commission_amount'    => $plan->fees->commission_amount ?? 0,
            //     'purchase_id'          => $motor->id,
            //     'plan_id'              => $plan->id,
            //     'plan_name'            => $plan->plan_name,
            //     'policy_plan_limit'    => $plan->limit,
            //     'insurance_company_id' => $data['insurance_company_id'],
            //     'inception_date'       => $data['inception_date'],
            //     'expiry_date'          => $data['expiry_date'],
            //     'commission_percentage' => $data['commission_percentage'] ?? 0,
            //     'policy_pdf_url'       => $path,
            // ];
            $purchase = [
                'net_premium'          => $net_premium,
                'fees'                 => $issuanceFees,
                'stamps'               => $stampAmount,
                'sales_tax'            => $salesTaxAmount,
                'cbj'                  => $cbjContribution,
                'sales_tax_cbj'        => $cbjSalesTaxAmount,
                'gross_premium'        => $grossPremium,
                'commission_amount'    => ($net_premium * $commission_percentage) / 100,
                'purchase_id'          => $motor->id,
                'plan_id'              => $plan->id,
                'plan_name'            => $plan->plan_name,
                'policy_plan_limit'    => $plan->limit,
                'insurance_company_id' => $data['insurance_company_id'],
                'inception_date'       => $data['inception_date'],
                'expiry_date'          => $data['expiry_date'],
                'commission_percentage' => $commission_percentage,
                'policy_pdf_url'       => $path,
            ];

            PurchasePolicy::updatePurchasePolicy($purchase);

            $data->purchase_id = $motor->id;
            $plan->net_premium_amount = $net_premium;
            $plan->fees_amount = $issuanceFees;
            $plan->stamps_amount = $stampAmount;
            $plan->sales_tax_amount = $salesTaxAmount;
            $plan->cbj_amount = $cbjContribution;
            $plan->sales_tax_cbj_amount = $cbjSalesTaxAmount;
            $plan->gross_premium_amount = $grossPremium;
            $plan->commission_amount = ($net_premium * $commission_percentage) / 100;

            $client = Client::with('country.currency')->find($request->user_id);
            $abbr = optional(optional($client->country)->currency)->abbreviation ?? 'JOD';


            $pdf = PDF::loadView('pdf.motor_policy', [
                'data' => $data,
                'abbr' => $abbr,
                'plan' => $plan
            ]);
            $pdf->save($path);

            // $url = url('insurance_pdfs/motor_policy/' . $filename);
            // $data['url'] = $url;
            $data['url'] = url('insurance_pdfs/motor_policy/' . $filename);
            // $data->net_premium = $data->net_premium . '%';
            // $data->fees = $data->fees . '%';
            // $data->gross_premium = $data->gross_premium . '%';
            // $data->sales_tax = $data->sales_tax . '%';
            // $data->stamps = $data->stamps . '%';
            // $data->commission_percentage = $data->commission_percentage . '%';

            // $data->net_premium = $purchase['net_premium'];
            // $data->fees = $purchase['fees'];
            // $data->gross_premium = $purchase['gross_premium'];
            // $data->sales_tax = $purchase['sales_tax'];
            // $data->stamps = $purchase['stamps'];
            // $data->commission_amount = $purchase['commission_amount'];
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
                'message' => 'Motor Insurance Plan added successfully',
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

    public function getComprehensivePlan(Request $request)
    {
        try {
            $data = array();
            $data = MotorInsurancePlan::whereHas('insurance_company', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->where('motor_plan_id', 1)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Comprehensive Plan successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getCompulsory3MonthsPlan(Request $request)
    {
        try {
            $data = MotorInsurancePlan::whereHas('insurance_company', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->where('motor_plan_id', 2)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan 3 Months successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getCompulsory6MonthsPlan(Request $request)
    {
        try {

            $data = MotorInsurancePlan::whereHas('insurance_company', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->where('motor_plan_id', 3)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan 6 Months successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getCompulsory9MonthsPlan(Request $request)
    {
        try {

            $data = MotorInsurancePlan::whereHas('insurance_company', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->where('motor_plan_id', 4)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan 9 Months successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getCompulsory12MonthsPlan(Request $request)
    {
        try {

            $data = MotorInsurancePlan::whereHas('insurance_company', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->where('motor_plan_id', 5)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan 12 Months successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function MotorInsurancePlanTotalLossPremium(Request $request)
    {
        try {

            $data = MotorInsurancePlan::whereHas('insurance_company', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->where('motor_plan_id', 6)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan Total Loss Premium successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
}
