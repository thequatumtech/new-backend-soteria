<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\Cities;
use App\Models\ClaimDeductible;
use App\Models\Country;
use App\Models\District;
use App\Models\EngineType;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\MotorInsurancePlan;
use App\Models\InsurancePlanModels\MotorInsurancePlan12MonthsCompulsoryPremium;
use App\Models\InsurancePlanModels\MotorInsurancePlan3MonthsCompulsoryPremium;
use App\Models\InsurancePlanModels\MotorInsurancePlan6MonthsCompulsoryPremium;
use App\Models\InsurancePlanModels\MotorInsurancePlan9MonthsCompulsoryPremium;
use App\Models\InsurancePlanModels\MotorInsurancePlanAdditionalBenefit;
use App\Models\InsurancePlanModels\MotorInsurancePlanComprehensiveCoverFee;
use App\Models\InsurancePlanModels\MotorInsurancePlanComprehensiveCoverPremium;
use App\Models\InsurancePlanModels\MotorInsurancePlanCondition;
use App\Models\InsurancePlanModels\MotorInsurancePlanNetPremiumIncreasePercentage;
use App\Models\InsurancePlanModels\MotorInsurancePlanNoClaimDiscount;
use App\Models\InsurancePlanModels\MotorInsurancePlanPolicyCover;
use App\Models\InsurancePlanModels\MotorInsurancePlanTotalLossPremium;
use App\Models\LineOfBusiness;
use App\Models\MotorPlan;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    public function motor_plan_comprehensive(Request $request)
    {
        $title = __('messages.plans.comprehensive_plan');
        $add_route = route('motor_plan.add_motor_plan_comprehensive');
        $plans = MotorInsurancePlan::where('motor_plan_id', 1)->get();
        return view('admin.plan.motor_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_motor_plan_comprehensive(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"6"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $motor_plans = MotorPlan::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $vehicle_types = VehicleType::all();
        $vehicle_brands = VehicleBrand::all();
        $vehicle_categories = VehicleCategory::all();
        $engine_types = EngineType::all();
        $claim_deductibles = ClaimDeductible::all();
        return view('admin.plan.add_motor_plan_comprehensive', compact('insurance_companies', 'line_of_businesses', 'motor_plans', 'countries', 'cities', 'districts', 'ages', 'vehicle_types', 'vehicle_brands', 'vehicle_categories', 'engine_types', 'claim_deductibles'));
    }

    public function motor_plan_compulsory_3_months(Request $request)
    {
        $title = __('messages.plans.compulsory_3_plan');
        $add_route = route('motor_plan.add_motor_plan_compulsory_3_months');
        $plans = MotorInsurancePlan::where('motor_plan_id', 2)->get();
        return view('admin.plan.motor_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_motor_plan_compulsory_3_months(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"6"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $motor_plans = MotorPlan::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $vehicle_types = VehicleType::all();
        $vehicle_brands = VehicleBrand::all();
        $vehicle_categories = VehicleCategory::all();
        $engine_types = EngineType::all();
        $claim_deductibles = ClaimDeductible::all();
        return view('admin.plan.add_motor_plan_compulsory_3', compact('insurance_companies', 'line_of_businesses', 'motor_plans', 'countries', 'cities', 'districts', 'ages', 'vehicle_types', 'vehicle_brands', 'vehicle_categories', 'engine_types', 'claim_deductibles'));
    }

    public function motor_plan_compulsory_6_months(Request $request)
    {
        $title = __('messages.plans.compulsory_6_plan');
        $add_route = route('motor_plan.add_motor_plan_compulsory_6_months');
        $plans = MotorInsurancePlan::where('motor_plan_id', 3)->get();
        return view('admin.plan.motor_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_motor_plan_compulsory_6_months(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"6"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $motor_plans = MotorPlan::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $vehicle_types = VehicleType::all();
        $vehicle_brands = VehicleBrand::all();
        $vehicle_categories = VehicleCategory::all();
        $engine_types = EngineType::all();
        $claim_deductibles = ClaimDeductible::all();
        return view('admin.plan.add_motor_plan_compulsory_6', compact('insurance_companies', 'line_of_businesses', 'motor_plans', 'countries', 'cities', 'districts', 'ages', 'vehicle_types', 'vehicle_brands', 'vehicle_categories', 'engine_types', 'claim_deductibles'));
    }

    public function motor_plan_compulsory_9_months(Request $request)
    {
        $title = __('messages.plans.compulsory_9_plan');
        $add_route = route('motor_plan.add_motor_plan_compulsory_9_months');
        $plans = MotorInsurancePlan::where('motor_plan_id', 4)->get();
        return view('admin.plan.motor_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_motor_plan_compulsory_9_months(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"6"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $motor_plans = MotorPlan::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $vehicle_types = VehicleType::all();
        $vehicle_brands = VehicleBrand::all();
        $vehicle_categories = VehicleCategory::all();
        $engine_types = EngineType::all();
        $claim_deductibles = ClaimDeductible::all();
        return view('admin.plan.add_motor_plan_compulsory_9', compact('insurance_companies', 'line_of_businesses', 'motor_plans', 'countries', 'cities', 'districts', 'ages', 'vehicle_types', 'vehicle_brands', 'vehicle_categories', 'engine_types', 'claim_deductibles'));
    }

    public function motor_plan_compulsory_12_months(Request $request)
    {
        $title = __('messages.plans.compulsory_12_plan');
        $add_route = route('motor_plan.add_motor_plan_compulsory_12_months');
        $plans = MotorInsurancePlan::where('motor_plan_id', 5)->get();
        return view('admin.plan.motor_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_motor_plan_compulsory_12_months(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"6"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $motor_plans = MotorPlan::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $vehicle_types = VehicleType::all();
        $vehicle_brands = VehicleBrand::all();
        $vehicle_categories = VehicleCategory::all();
        $engine_types = EngineType::all();
        $claim_deductibles = ClaimDeductible::all();
        return view('admin.plan.add_motor_plan_compulsory_12', compact('insurance_companies', 'line_of_businesses', 'motor_plans', 'countries', 'cities', 'districts', 'ages', 'vehicle_types', 'vehicle_brands', 'vehicle_categories', 'engine_types', 'claim_deductibles'));
    }

    public function motor_plan_total_loss(Request $request)
    {
        $title = __('messages.plans.total_loss_plan');
        $add_route = route('motor_plan.add_motor_plan_total_loss');
        $plans = MotorInsurancePlan::where('motor_plan_id', 6)->get();
        return view('admin.plan.motor_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_motor_plan_total_loss(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"6"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $motor_plans = MotorPlan::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $vehicle_types = VehicleType::all();
        $vehicle_brands = VehicleBrand::all();
        $vehicle_categories = VehicleCategory::all();
        $engine_types = EngineType::all();
        $claim_deductibles = ClaimDeductible::all();
        return view('admin.plan.add_motor_plan_total_loss', compact('insurance_companies', 'line_of_businesses', 'motor_plans', 'countries', 'cities', 'districts', 'ages', 'vehicle_types', 'vehicle_brands', 'vehicle_categories', 'engine_types', 'claim_deductibles'));
    }

    public function add_motor_plan(Request $request)
    {
        if ($request->form_type == 'add') {
            $motor_insurance_plan = new MotorInsurancePlan();
            $motor_insurance_plan->line_of_business_id = $request->line_of_business_id;
            $motor_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $motor_insurance_plan->motor_plan_id = $request->motor_plan_id;
            $motor_insurance_plan->plan_name = $request->plan_name;
            $motor_insurance_plan->start_date = $request->start_date;
            $motor_insurance_plan->end_date = $request->end_date;
            $motor_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $motor_insurance_plan->restricted_country_ids = json_encode($request->restricted_country_ids);
            $motor_insurance_plan->restricted_city_ids = json_encode($request->restricted_city_ids);
            $motor_insurance_plan->restricted_district_ids = json_encode($request->restricted_district_ids);
            $motor_insurance_plan->restricted_age_ids = json_encode($request->restricted_age_ids);
            $motor_insurance_plan->restricted_vehicle_type_ids = json_encode($request->restricted_vehicle_type_ids);
            $motor_insurance_plan->restricted_vehicle_brand_ids = json_encode($request->restricted_vehicle_brand_ids);
            $motor_insurance_plan->restricted_vehicle_category_ids = json_encode($request->restricted_vehicle_category_ids);
            $motor_insurance_plan->restricted_engine_type_ids = json_encode($request->restricted_engine_type_ids);
            $motor_insurance_plan->claim_deductible_ids = json_encode($request->claim_deductible_ids);
            $motor_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $motor_insurance_plan->id), $newFilename);

                $motor_insurance_plan->$file = $newFilename; // Store the filename in the database
                $motor_insurance_plan->save();
            }

            foreach ($request->increase_in_net_premium as $key => $value) {
                $motor_insurance_plan_conditions = new MotorInsurancePlanCondition();
                $motor_insurance_plan_conditions->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_conditions->no_of_points = $key;
                $motor_insurance_plan_conditions->increase_in_net_premium = $value;
                $motor_insurance_plan_conditions->save();
            }

            for ($i = 0; $i <= 10; $i++) {
                $motor_insurance_plan_net_premium_increase_percentages = new MotorInsurancePlanNetPremiumIncreasePercentage();
                $motor_insurance_plan_net_premium_increase_percentages->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_net_premium_increase_percentages->no_of_accidents = $i;
                $motor_insurance_plan_net_premium_increase_percentages->current_year_increase = $request->current_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->first_year_increase = $request->first_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->second_year_increase = $request->second_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->third_year_increase = $request->third_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->fourth_year_increase = $request->fourth_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->fifth_year_increase = $request->fifth_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->save();
            }

            foreach ($request->discount as $key => $value) {
                $motor_insurance_plan_no_claim_discounts = new MotorInsurancePlanNoClaimDiscount();
                $motor_insurance_plan_no_claim_discounts->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_no_claim_discounts->year = $key;
                $motor_insurance_plan_no_claim_discounts->discount = $value;
                $motor_insurance_plan_no_claim_discounts->save();
            }

            foreach ($request->policy_covers as $single) {
                $motor_insurance_plan_policy_covers = new MotorInsurancePlanPolicyCover();
                $motor_insurance_plan_policy_covers->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $motor_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                 $motor_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']); 
                $motor_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $motor_insurance_plan_policy_covers->save();
            }

            foreach ($request->additional_benefits as $single) {
                $motor_insurance_plan_additional_benefits = new MotorInsurancePlanAdditionalBenefit();
                $motor_insurance_plan_additional_benefits->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_additional_benefits->benefit_name = $single['benefit_name'];
                $motor_insurance_plan_additional_benefits->benefit_limit = $single['benefit_limit'];
                $motor_insurance_plan_additional_benefits->benefit_deductible = $single['benefit_deductible'];
                $motor_insurance_plan_additional_benefits->save();
            }

            $motor_insurance_plan_comprehensive_cover_fees = new MotorInsurancePlanComprehensiveCoverFee();
            $motor_insurance_plan_comprehensive_cover_fees->motor_insurance_plan_id = $motor_insurance_plan->id;
            $motor_insurance_plan_comprehensive_cover_fees->fees = $request->fees;
            $motor_insurance_plan_comprehensive_cover_fees->stamps = $request->stamps;
            $motor_insurance_plan_comprehensive_cover_fees->sales_tax = $request->sales_tax;
            $motor_insurance_plan_comprehensive_cover_fees->net_premium = $request->net_premium;
            $motor_insurance_plan_comprehensive_cover_fees->commission_percentage = $request->commission_percentage;
            $motor_insurance_plan_comprehensive_cover_fees->commission_amount = $request->commission_amount;
            $motor_insurance_plan_comprehensive_cover_fees->save();

            if ($request->motor_plan_id == 1) {
                foreach ($request->comprehensive_schedule as $single) {
                    $motor_insurance_plan_comprehensive_cover_premiums = new MotorInsurancePlanComprehensiveCoverPremium();
                    $motor_insurance_plan_comprehensive_cover_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_comprehensive_cover_premiums->vehicle_brand_id = $single['vehicle_brand_id'];
                    $motor_insurance_plan_comprehensive_cover_premiums->vehicle_category_id = $single['vehicle_category_id'];
                    $motor_insurance_plan_comprehensive_cover_premiums->insured_value = $single['insured_value'];
                    $motor_insurance_plan_comprehensive_cover_premiums->premium = $single['premium'];
                    $motor_insurance_plan_comprehensive_cover_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_comprehensive')->with('success', __('messages.plans.add_success'));
            } elseif ($request->motor_plan_id == 2) {
                foreach ($request->compulsory_schedule_3_months as $single) {
                    $motor_insurance_plan_3_months_compulsory_premiums = new MotorInsurancePlan3MonthsCompulsoryPremium();
                    $motor_insurance_plan_3_months_compulsory_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_3_months_compulsory_premiums->vehicle_type = $single['vehicle_type'];
                    $motor_insurance_plan_3_months_compulsory_premiums->premium = $single['premium'];
                    $motor_insurance_plan_3_months_compulsory_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_compulsory_3_months')->with('success', __('messages.plans.add_success'));
            } elseif ($request->motor_plan_id == 3) {
                foreach ($request->compulsory_schedule_6_months as $single) {
                    $motor_insurance_plan_6_months_compulsory_premiums = new MotorInsurancePlan6MonthsCompulsoryPremium();
                    $motor_insurance_plan_6_months_compulsory_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_6_months_compulsory_premiums->vehicle_type = $single['vehicle_type'];
                    $motor_insurance_plan_6_months_compulsory_premiums->premium = $single['premium'];
                    $motor_insurance_plan_6_months_compulsory_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_compulsory_6_months')->with('success', __('messages.plans.add_success'));
            } elseif ($request->motor_plan_id == 4) {
                foreach ($request->compulsory_schedule_9_months as $single) {
                    $motor_insurance_plan_9_months_compulsory_premiums = new MotorInsurancePlan9MonthsCompulsoryPremium();
                    $motor_insurance_plan_9_months_compulsory_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_9_months_compulsory_premiums->vehicle_type = $single['vehicle_type'];
                    $motor_insurance_plan_9_months_compulsory_premiums->premium = $single['premium'];
                    $motor_insurance_plan_9_months_compulsory_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_compulsory_9_months')->with('success', __('messages.plans.add_success'));
            } elseif ($request->motor_plan_id == 5) {
                foreach ($request->compulsory_schedule_12_months as $single) {
                    $motor_insurance_plan_12_months_compulsory_premiums = new MotorInsurancePlan12MonthsCompulsoryPremium();
                    $motor_insurance_plan_12_months_compulsory_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_12_months_compulsory_premiums->vehicle_type = $single['vehicle_type'];
                    $motor_insurance_plan_12_months_compulsory_premiums->premium = $single['premium'];
                    $motor_insurance_plan_12_months_compulsory_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_compulsory_12_months')->with('success', __('messages.plans.add_success'));
            } elseif ($request->motor_plan_id == 6) {
                foreach ($request->total_loss_schedule as $single) {
                    $motor_insurance_plan_total_loss_premiums = new MotorInsurancePlanTotalLossPremium();
                    $motor_insurance_plan_total_loss_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_total_loss_premiums->vehicle_brand_id = $single['vehicle_brand_id'];
                    $motor_insurance_plan_total_loss_premiums->vehicle_category_id = $single['vehicle_category_id'];
                    $motor_insurance_plan_total_loss_premiums->insured_value = $single['insured_value'];
                    $motor_insurance_plan_total_loss_premiums->premium = $single['premium'];
                    $motor_insurance_plan_total_loss_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_total_loss')->with('success', __('messages.plans.add_success'));
            }
        }

        if ($request->form_type == 'edit') {
            $motor_insurance_plan = MotorInsurancePlan::find($request->plan_id);
            $motor_insurance_plan->plan_name = $request->plan_name;
            $motor_insurance_plan->start_date = $request->start_date;
            $motor_insurance_plan->end_date = $request->end_date;
            $motor_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $motor_insurance_plan->restricted_country_ids = json_encode($request->restricted_country_ids);
            $motor_insurance_plan->restricted_city_ids = json_encode($request->restricted_city_ids);
            $motor_insurance_plan->restricted_district_ids = json_encode($request->restricted_district_ids);
            $motor_insurance_plan->restricted_age_ids = json_encode($request->restricted_age_ids);
            $motor_insurance_plan->restricted_vehicle_type_ids = json_encode($request->restricted_vehicle_type_ids);
            $motor_insurance_plan->restricted_vehicle_brand_ids = json_encode($request->restricted_vehicle_brand_ids);
            $motor_insurance_plan->restricted_vehicle_category_ids = json_encode($request->restricted_vehicle_category_ids);
            $motor_insurance_plan->restricted_engine_type_ids = json_encode($request->restricted_engine_type_ids);
            $motor_insurance_plan->claim_deductible_ids = json_encode($request->claim_deductible_ids);
            $motor_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $motor_insurance_plan->id), $newFilename);

                $motor_insurance_plan->$file = $newFilename; // Store the filename in the database
                $motor_insurance_plan->save();
            }

            MotorInsurancePlanCondition::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
            foreach ($request->increase_in_net_premium as $key => $value) {
                $motor_insurance_plan_conditions = new MotorInsurancePlanCondition();
                $motor_insurance_plan_conditions->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_conditions->no_of_points = $key;
                $motor_insurance_plan_conditions->increase_in_net_premium = $value;
                $motor_insurance_plan_conditions->save();
            }

            MotorInsurancePlanNetPremiumIncreasePercentage::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
            for ($i = 0; $i <= 10; $i++) {
                $motor_insurance_plan_net_premium_increase_percentages = new MotorInsurancePlanNetPremiumIncreasePercentage();
                $motor_insurance_plan_net_premium_increase_percentages->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_net_premium_increase_percentages->no_of_accidents = $i;
                $motor_insurance_plan_net_premium_increase_percentages->current_year_increase = $request->current_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->first_year_increase = $request->first_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->second_year_increase = $request->second_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->third_year_increase = $request->third_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->fourth_year_increase = $request->fourth_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->fifth_year_increase = $request->fifth_year_increase[$i];
                $motor_insurance_plan_net_premium_increase_percentages->save();
            }

            MotorInsurancePlanNoClaimDiscount::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
            foreach ($request->discount as $key => $value) {
                $motor_insurance_plan_no_claim_discounts = new MotorInsurancePlanNoClaimDiscount();
                $motor_insurance_plan_no_claim_discounts->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_no_claim_discounts->year = $key;
                $motor_insurance_plan_no_claim_discounts->discount = $value;
                $motor_insurance_plan_no_claim_discounts->save();
            }

            MotorInsurancePlanPolicyCover::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
            foreach ($request->policy_covers as $single) {
                $motor_insurance_plan_policy_covers = new MotorInsurancePlanPolicyCover();
                $motor_insurance_plan_policy_covers->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $motor_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $motor_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']); 
                $motor_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $motor_insurance_plan_policy_covers->save();
            }

            MotorInsurancePlanAdditionalBenefit::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
            foreach ($request->additional_benefits as $single) {
                $motor_insurance_plan_additional_benefits = new MotorInsurancePlanAdditionalBenefit();
                $motor_insurance_plan_additional_benefits->motor_insurance_plan_id = $motor_insurance_plan->id;
                $motor_insurance_plan_additional_benefits->benefit_name = $single['benefit_name'];
                $motor_insurance_plan_additional_benefits->benefit_limit = $single['benefit_limit'];
                $motor_insurance_plan_additional_benefits->benefit_deductible = $single['benefit_deductible'];
                $motor_insurance_plan_additional_benefits->save();
            }

            MotorInsurancePlanComprehensiveCoverFee::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
            $motor_insurance_plan_comprehensive_cover_fees = new MotorInsurancePlanComprehensiveCoverFee();
            $motor_insurance_plan_comprehensive_cover_fees->motor_insurance_plan_id = $motor_insurance_plan->id;
            $motor_insurance_plan_comprehensive_cover_fees->fees = $request->fees;
            $motor_insurance_plan_comprehensive_cover_fees->stamps = $request->stamps;
            $motor_insurance_plan_comprehensive_cover_fees->sales_tax = $request->sales_tax;
            $motor_insurance_plan_comprehensive_cover_fees->net_premium = $request->net_premium;
            $motor_insurance_plan_comprehensive_cover_fees->commission_percentage = $request->commission_percentage;
            $motor_insurance_plan_comprehensive_cover_fees->commission_amount = $request->commission_amount;
            $motor_insurance_plan_comprehensive_cover_fees->save();

            if ($request->motor_plan_id == 1) {
                MotorInsurancePlanComprehensiveCoverPremium::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
                foreach ($request->comprehensive_schedule as $single) {
                    $motor_insurance_plan_comprehensive_cover_premiums = new MotorInsurancePlanComprehensiveCoverPremium();
                    $motor_insurance_plan_comprehensive_cover_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_comprehensive_cover_premiums->vehicle_brand_id = $single['vehicle_brand_id'];
                    $motor_insurance_plan_comprehensive_cover_premiums->vehicle_category_id = $single['vehicle_category_id'];
                    $motor_insurance_plan_comprehensive_cover_premiums->insured_value = $single['insured_value'];
                    $motor_insurance_plan_comprehensive_cover_premiums->premium = $single['premium'];
                    $motor_insurance_plan_comprehensive_cover_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_comprehensive')->with('success', __('messages.plans.edit_success'));
            } elseif ($request->motor_plan_id == 2) {
                MotorInsurancePlan3MonthsCompulsoryPremium::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
                foreach ($request->compulsory_schedule_3_months as $single) {
                    $motor_insurance_plan_3_months_compulsory_premiums = new MotorInsurancePlan3MonthsCompulsoryPremium();
                    $motor_insurance_plan_3_months_compulsory_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_3_months_compulsory_premiums->vehicle_type = $single['vehicle_type'];
                    $motor_insurance_plan_3_months_compulsory_premiums->premium = $single['premium'];
                    $motor_insurance_plan_3_months_compulsory_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_compulsory_3_months')->with('success', __('messages.plans.edit_success'));
            } elseif ($request->motor_plan_id == 3) {
                MotorInsurancePlan6MonthsCompulsoryPremium::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
                foreach ($request->compulsory_schedule_6_months as $single) {
                    $motor_insurance_plan_6_months_compulsory_premiums = new MotorInsurancePlan6MonthsCompulsoryPremium();
                    $motor_insurance_plan_6_months_compulsory_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_6_months_compulsory_premiums->vehicle_type = $single['vehicle_type'];
                    $motor_insurance_plan_6_months_compulsory_premiums->premium = $single['premium'];
                    $motor_insurance_plan_6_months_compulsory_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_compulsory_6_months')->with('success', __('messages.plans.edit_success'));
            } elseif ($request->motor_plan_id == 4) {
                MotorInsurancePlan9MonthsCompulsoryPremium::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
                foreach ($request->compulsory_schedule_9_months as $single) {
                    $motor_insurance_plan_9_months_compulsory_premiums = new MotorInsurancePlan9MonthsCompulsoryPremium();
                    $motor_insurance_plan_9_months_compulsory_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_9_months_compulsory_premiums->vehicle_type = $single['vehicle_type'];
                    $motor_insurance_plan_9_months_compulsory_premiums->premium = $single['premium'];
                    $motor_insurance_plan_9_months_compulsory_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_compulsory_9_months')->with('success', __('messages.plans.edit_success'));
            } elseif ($request->motor_plan_id == 5) {
                MotorInsurancePlan12MonthsCompulsoryPremium::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
                foreach ($request->compulsory_schedule_12_months as $single) {
                    $motor_insurance_plan_12_months_compulsory_premiums = new MotorInsurancePlan12MonthsCompulsoryPremium();
                    $motor_insurance_plan_12_months_compulsory_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_12_months_compulsory_premiums->vehicle_type = $single['vehicle_type'];
                    $motor_insurance_plan_12_months_compulsory_premiums->premium = $single['premium'];
                    $motor_insurance_plan_12_months_compulsory_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_compulsory_12_months')->with('success', __('messages.plans.edit_success'));
            } elseif ($request->motor_plan_id == 6) {
                MotorInsurancePlanTotalLossPremium::where('motor_insurance_plan_id', $motor_insurance_plan->id)->delete();
                foreach ($request->total_loss_schedule as $single) {
                    $motor_insurance_plan_total_loss_premiums = new MotorInsurancePlanTotalLossPremium();
                    $motor_insurance_plan_total_loss_premiums->motor_insurance_plan_id = $motor_insurance_plan->id;
                    $motor_insurance_plan_total_loss_premiums->vehicle_brand_id = $single['vehicle_brand_id'];
                    $motor_insurance_plan_total_loss_premiums->vehicle_category_id = $single['vehicle_category_id'];
                    $motor_insurance_plan_total_loss_premiums->insured_value = $single['insured_value'];
                    $motor_insurance_plan_total_loss_premiums->premium = $single['premium'];
                    $motor_insurance_plan_total_loss_premiums->save();
                }
                return redirect()->route('motor_plan.motor_plan_total_loss')->with('success', __('messages.plans.edit_success'));
            }
        }
    }

    public function delete_motor_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        $found = MotorInsurancePlan::find($plan_id);
        if ($found) {
            $motor_plan_id = $found->motor_plan_id;
            MotorInsurancePlanCondition::where('motor_insurance_plan_id', $found->id)->delete();
            MotorInsurancePlanNetPremiumIncreasePercentage::where('motor_insurance_plan_id', $found->id)->delete();
            MotorInsurancePlanPolicyCover::where('motor_insurance_plan_id', $found->id)->delete();
            MotorInsurancePlanNoClaimDiscount::where('motor_insurance_plan_id', $found->id)->delete();
            MotorInsurancePlanAdditionalBenefit::where('motor_insurance_plan_id', $found->id)->delete();
            MotorInsurancePlanComprehensiveCoverFee::where('motor_insurance_plan_id', $found->id)->delete();

            if ($motor_plan_id == 1) {
                MotorInsurancePlanComprehensiveCoverPremium::where('motor_insurance_plan_id', $found->id)->delete();
            } elseif ($motor_plan_id == 2) {
                MotorInsurancePlan3MonthsCompulsoryPremium::where('motor_insurance_plan_id', $found->id)->delete();
            } elseif ($motor_plan_id == 3) {
                MotorInsurancePlan6MonthsCompulsoryPremium::where('motor_insurance_plan_id', $found->id)->delete();
            } elseif ($motor_plan_id == 4) {
                MotorInsurancePlan9MonthsCompulsoryPremium::where('motor_insurance_plan_id', $found->id)->delete();
            } elseif ($motor_plan_id == 5) {
                MotorInsurancePlan12MonthsCompulsoryPremium::where('motor_insurance_plan_id', $found->id)->delete();
            } elseif ($motor_plan_id == 6) {
                MotorInsurancePlanTotalLossPremium::where('motor_insurance_plan_id', $found->id)->delete();
            }
        }
        $found->delete();
        return back()->with('success', __('messages.plans.delete_success'));
    }

    public function edit_motor_plan(Request $request, $id)
    {
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $vehicle_types = VehicleType::all();
        $vehicle_brands = VehicleBrand::all();
        $vehicle_categories = VehicleCategory::all();
        $engine_types = EngineType::all();
        $claim_deductibles = ClaimDeductible::all();
        $plan = MotorInsurancePlan::find($id);
        if ($plan->motor_plan_id == 1) {
            $view_name = 'admin.plan.edit_motor_plan_comprehensive';
        } elseif ($plan->motor_plan_id == 2) {
            $view_name = 'admin.plan.edit_motor_plan_compulsory_3';
        } elseif ($plan->motor_plan_id == 3) {
            $view_name = 'admin.plan.edit_motor_plan_compulsory_6';
        } elseif ($plan->motor_plan_id == 4) {
            $view_name = 'admin.plan.edit_motor_plan_compulsory_9';
        } elseif ($plan->motor_plan_id == 5) {
            $view_name = 'admin.plan.edit_motor_plan_compulsory_12';
        } elseif ($plan->motor_plan_id == 6) {
            $view_name = 'admin.plan.edit_motor_plan_total_loss';
        }
        return view($view_name, compact('plan', 'countries', 'cities', 'districts', 'ages', 'vehicle_types', 'vehicle_brands', 'vehicle_categories', 'engine_types', 'claim_deductibles'));
    }

}
