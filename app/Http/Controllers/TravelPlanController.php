<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\GeographicalArea;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\TravelPlan;
use App\Models\InsurancePlanModels\TravelPlanPolicyCover;
use App\Models\InsurancePlanModels\TravelPlanPricingSchedule;
use App\Models\InsurancePlanModels\TravelPlanSurchargeBand;
use App\Models\InsurancePlanModels\TravelPlanDiscountBand;
use App\Models\DangerousActivities;
use App\Models\LineOfBusiness;
use Illuminate\Http\Request;

class TravelPlanController extends Controller
{
    private const line_of_business_id = 10;

    public function travel_plan(Request $request)
    {
        $title = __('messages.plans.travel_plan');
        $add_route = route('travel_plan.add_travel_plan');
        $plans = TravelPlan::all();
        return view('admin.plan.travel-plans.travel_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_travel_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"10"%')->get();
        $line_of_businesses = LineOfBusiness::where('id', self::line_of_business_id)->first()->name;
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $geographical_areas = GeographicalArea::all();
        $dangerous_activities = DangerousActivities::all();
        return view('admin.plan.travel-plans.add_travel_plan', compact('insurance_companies', 'line_of_businesses', 'countries', 'cities', 'districts', 'ages', 'geographical_areas', 'dangerous_activities'));
    }

    public function edit_travel_plan(Request $request, $id)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"10"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $geographical_areas = GeographicalArea::all();
        $plan = TravelPlan::find($id);
        $selected_country_ids = $plan->restricted_country_ids ? json_decode($plan->restricted_country_ids, true) : [];
        $selected_city_ids = $plan->restricted_city_ids ? json_decode($plan->restricted_city_ids, true) : [];
        $selected_district_ids = $plan->restricted_district_ids ? json_decode($plan->restricted_district_ids, true) : [];

        // Load only related cities and districts
        // $cities = count($selected_country_ids) > 0
        //     ? Cities::whereIn('country_id', $selected_country_ids)->get()
        //     : collect(); // empty collection

        // $districts = count($selected_city_ids) > 0
        //     ? District::whereIn('city_id', $selected_city_ids)->get()
        //     : collect(); // empty collection
        $dangerous_activities = DangerousActivities::all();
        return view('admin.plan.travel-plans.edit_travel_plan', compact('insurance_companies', 'line_of_businesses', 'countries', 'cities', 'districts', 'ages', 'geographical_areas', 'plan', 'selected_country_ids', 'selected_city_ids', 'selected_district_ids', 'dangerous_activities'));
    }

    public function save_travel_plan(Request $request)
    {
        if ($request->form_type == 'add') {
            // dd($request->all());    
            $travel_insurance_plan = new TravelPlan();
            $travel_insurance_plan->line_of_business_id = self::line_of_business_id;
            $travel_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $travel_insurance_plan->plan_name = $request->plan_name;
            $travel_insurance_plan->policy_period = $request->policy_period;
            $travel_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $travel_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $travel_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $travel_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $travel_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $travel_insurance_plan->restricted_dangerous_activities_ids = $request->restricted_dangerous_activities_ids ? json_encode($request->restricted_dangerous_activities_ids) : null;
            // $travel_insurance_plan->geographical_areas_ids = $request->geographical_areas_ids;
            $travel_insurance_plan->geographical_areas_ids = $request->geographical_areas_ids;
            $travel_insurance_plan->countries = $request->countries;
            $travel_insurance_plan->limit = $request->limit;
            $travel_insurance_plan->net_premium = $request->net_premium ?? 0;
            $travel_insurance_plan->fees = $request->fees;
            $travel_insurance_plan->stamps = $request->stamps;
            $travel_insurance_plan->sales_tax = $request->sales_tax;
            $travel_insurance_plan->cbj = $request->cbj;
            $travel_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $travel_insurance_plan->gross_premium = $request->gross_premium ?? 0;
            $travel_insurance_plan->commission_percentage = $request->commission_percentage;
            $travel_insurance_plan->commission_amount = $request->commission_amount ?? 0;

            $travel_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $travel_insurance_plan->id), $newFilename);

                $travel_insurance_plan->$file = $newFilename; // Store the filename in the database
                $travel_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $travel_insurance_plan_policy_covers = new TravelPlanPolicyCover();
                $travel_insurance_plan_policy_covers->travel_plan_id = $travel_insurance_plan->id;
                $travel_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $travel_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $travel_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $travel_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                // $travel_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                // $travel_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $travel_insurance_plan_policy_covers->save();
            }

            foreach ($request->plan_pricing as $single) {
                $travel_insurance_plan_pricing_schedule = new TravelPlanPricingSchedule();
                $travel_insurance_plan_pricing_schedule->travel_plan_id = $travel_insurance_plan->id;
                $travel_insurance_plan_pricing_schedule->min_days = $single['min_days'];
                $travel_insurance_plan_pricing_schedule->max_days = $single['max_days'];
                $travel_insurance_plan_pricing_schedule->price = $single['price'];
                $travel_insurance_plan_pricing_schedule->save();
            }
            foreach ($request->surcharge_band as $single) {
                $travel_insurance_plan_surcharge_band = new TravelPlanSurchargeBand();
                $travel_insurance_plan_surcharge_band->travel_plan_id = $travel_insurance_plan->id;

                $age_band = explode('-', $single['age_band']);
                $min_age = (int) trim($age_band[0]);
                $max_age = isset($age_band[1]) ? (int) trim($age_band[1]) : 100;

                $travel_insurance_plan_surcharge_band->min_age = $min_age;
                $travel_insurance_plan_surcharge_band->max_age = $max_age;
                $travel_insurance_plan_surcharge_band->surcharge = $single['surcharge'];
                $travel_insurance_plan_surcharge_band->save();
            }
            foreach ($request->discount_band as $single) {
                $travel_insurance_plan_discount_band = new TravelPlanDiscountBand();
                $travel_insurance_plan_discount_band->travel_plan_id = $travel_insurance_plan->id;

                $age_band = explode('-', $single['age_band']);
                $min_age = (int) trim($age_band[0]);
                $max_age = isset($age_band[1]) ? (int) trim($age_band[1]) : 100;

                $travel_insurance_plan_discount_band->min_age = $min_age;
                $travel_insurance_plan_discount_band->max_age = $max_age;
                $travel_insurance_plan_discount_band->discount = $single['discount'];
                $travel_insurance_plan_discount_band->save();
            }

            $message = __('messages.plans.add_success');
        } else if ($request->form_type == 'edit') {

            $plan_id = $request->plan_id;
            $travel_insurance_plan = TravelPlan::find($plan_id);
            $travel_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $travel_insurance_plan->plan_name = $request->plan_name;
            $travel_insurance_plan->policy_period = $request->policy_period;
            $travel_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $travel_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $travel_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $travel_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $travel_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $travel_insurance_plan->restricted_dangerous_activities_ids = $request->restricted_dangerous_activities_ids ? json_encode($request->restricted_dangerous_activities_ids) : null;
            $travel_insurance_plan->geographical_areas_ids = $request->geographical_areas_ids;
            $travel_insurance_plan->countries = $request->countries;
            $travel_insurance_plan->limit = $request->limit;
            $travel_insurance_plan->net_premium = $request->net_premium ?? 0;
            $travel_insurance_plan->fees = $request->fees;
            $travel_insurance_plan->stamps = $request->stamps;
            $travel_insurance_plan->sales_tax = $request->sales_tax;
            $travel_insurance_plan->cbj = $request->cbj;
            $travel_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $travel_insurance_plan->gross_premium = $request->gross_premium ?? 0;
            $travel_insurance_plan->commission_percentage = $request->commission_percentage;
            $travel_insurance_plan->commission_amount = $request->commission_amount ?? 0;
            $travel_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $travel_insurance_plan->id), $newFilename);

                $travel_insurance_plan->$file = $newFilename; // Store the filename in the database
                $travel_insurance_plan->save();
            }
            TravelPlanPolicyCover::where('travel_plan_id', $plan_id)->delete();

            foreach ($request->policy_covers as $single) {
                $travel_insurance_plan_policy_covers = new TravelPlanPolicyCover();
                $travel_insurance_plan_policy_covers->travel_plan_id = $plan_id;
                $travel_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $travel_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $travel_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $travel_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                // $travel_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                // $travel_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $travel_insurance_plan_policy_covers->save();
            }

            TravelPlanPricingSchedule::where('travel_plan_id', $plan_id)->delete();
            foreach ($request->plan_pricing as $single) {
                $travel_insurance_plan_pricing_schedule = new TravelPlanPricingSchedule();
                $travel_insurance_plan_pricing_schedule->travel_plan_id = $travel_insurance_plan->id;
                $travel_insurance_plan_pricing_schedule->min_days = $single['min_days'];
                $travel_insurance_plan_pricing_schedule->max_days = $single['max_days'];
                $travel_insurance_plan_pricing_schedule->price = $single['price'];
                $travel_insurance_plan_pricing_schedule->save();
            }

            TravelPlanSurchargeBand::where('travel_plan_id', $plan_id)->delete();

            foreach ($request->surcharge_band as $single) {
                $travel_insurance_plan_surcharge_band = new TravelPlanSurchargeBand();
                $travel_insurance_plan_surcharge_band->travel_plan_id = $travel_insurance_plan->id;

                $age_band = explode('-', $single['age_band']);
                $min_age = isset($age_band[0]) ? (int)trim($age_band[0]) : 0;
                $max_age = isset($age_band[1]) ? (int)trim($age_band[1]) : 100;

                $travel_insurance_plan_surcharge_band->min_age = $min_age;
                $travel_insurance_plan_surcharge_band->max_age = $max_age;
                $travel_insurance_plan_surcharge_band->surcharge = $single['surcharge'];
                $travel_insurance_plan_surcharge_band->save();
            }

            TravelPlanDiscountBand::where('travel_plan_id', $plan_id)->delete();

            foreach ($request->discount_band as $single) {
                $travel_insurance_plan_discount_band = new TravelPlanDiscountBand();
                $travel_insurance_plan_discount_band->travel_plan_id = $travel_insurance_plan->id;

                $age_band = explode('-', $single['age_band']);
                $min_age = isset($age_band[0]) ? (int)trim($age_band[0]) : 0;
                $max_age = isset($age_band[1]) ? (int)trim($age_band[1]) : 100;

                $travel_insurance_plan_discount_band->min_age = $min_age;
                $travel_insurance_plan_discount_band->max_age = $max_age;
                $travel_insurance_plan_discount_band->discount = $single['discount'];
                $travel_insurance_plan_discount_band->save();
            }

            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('travel_plan.travel_plan')->with('success', $message);
    }

    public function delete_travel_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        TravelPlanPolicyCover::where('travel_plan_id', $plan_id)->delete();
        TravelPlan::find($plan_id)->delete();
        return back()->with('success', __('messages.plans.delete_success'));
    }
    // public function getCountriesByGeoArea(Request $request)
    // {
    //     $area = GeographicalArea::find($request->geo_area_id);
    //     if (!$area || empty($area->countries)) {
    //         return response()->json(['countries' => []]);
    //     }
    //     $countries = Country::whereIn('id', $area->countries)->get(['id', 'name']);
    //     return response()->json(['countries' => $countries]);
    // }
}
