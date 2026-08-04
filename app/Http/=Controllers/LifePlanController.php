<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\ChronicDisease;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\LifePlan;
use App\Models\InsurancePlanModels\LifePlanPolicyCover;
use App\Models\InsurancePlanModels\LifePlanPricingSchedule;
use App\Models\LineOfBusiness;
use App\Models\Occupations;
use Illuminate\Http\Request;

class LifePlanController extends Controller
{
    private const line_of_business_id = 2;
    public function life_plan(Request $request)
    {
        $title = __('messages.plans.life_plan');
        $add_route = route('life_plan.add_life_plan');
        $plans = LifePlan::all();
        return view('admin.plan.life-plans.life_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_life_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"2"%')->get();
        $line_of_businesses = LineOfBusiness::find(self::line_of_business_id)->name;
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $occupations = Occupations::all();
        $chronics = ChronicDisease::all();
        return view('admin.plan.life-plans.add_life_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','occupations','chronics'));
    }

    public function edit_life_plan(Request $request, $id)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"2"%')->get();
        $line_of_businesses = LineOfBusiness::find(self::line_of_business_id)->name;
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $plan = LifePlan::find($id);
/*        if($plan->restricted_country_ids) {
            $cities = Cities::whereIn('country_id', json_decode($plan->restricted_country_ids))->get();
            if($plan->restricted_city_ids) {
                $districts = District::whereIn('city_id', json_decode($plan->restricted_city_ids))->get();
            } else {
                $districts = [];
            }
        } else {
            $cities = $districts = [];
        }*/
        $occupations = Occupations::all();
        $chronics = ChronicDisease::all();
        return view('admin.plan.life-plans.edit_life_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','plan','occupations','chronics'));
    }

    public function save_life_plan(Request $request)
    {
        if($request->form_type == 'add'){
            $life_insurance_plan = new LifePlan();
            $life_insurance_plan->line_of_business_id = self::line_of_business_id;
            $life_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $life_insurance_plan->plan_name = $request->plan_name;
            $life_insurance_plan->policy_period = $request->policy_period;
            $life_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $life_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $life_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $life_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $life_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $life_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids?json_encode($request->restricted_occupation_ids):null;
            $life_insurance_plan->restricted_chronic_ids = $request->restricted_chronic_ids?json_encode($request->restricted_chronic_ids):null;
            $life_insurance_plan->limit = $request->limit;
            $life_insurance_plan->net_premium = $request->net_premium;
            $life_insurance_plan->fees = $request->fees;
            $life_insurance_plan->stamps = $request->stamps;
            $life_insurance_plan->sales_tax = $request->sales_tax;
            $life_insurance_plan->gross_premium = (float)($request->net_premium) + (float)($request->fees) + (float)($request->stamps) + (float)($request->sales_tax);
            $life_insurance_plan->commission_percentage = $request->commission_percentage;
            $life_insurance_plan->commission_amount = $request->commission_amount;
            $life_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $life_insurance_plan->id), $newFilename);

                $life_insurance_plan->$file = $newFilename; // Store the filename in the database
                $life_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $life_insurance_plan_policy_covers = new LifePlanPolicyCover();
                $life_insurance_plan_policy_covers->life_plan_id = $life_insurance_plan->id;
                $life_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $life_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $life_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']); 
                $life_insurance_plan_policy_covers->save();
            }

            foreach ($request->pricing_schedule as $single){
                $life_insurance_pricing_schedule = new LifePlanPricingSchedule();
                $life_insurance_pricing_schedule->life_plan_id = $life_insurance_plan->id;
                $life_insurance_pricing_schedule->age = $single['age'];
                $life_insurance_pricing_schedule->year_1 = $single['year_1'];
                $life_insurance_pricing_schedule->year_2 = $single['year_2'];
                $life_insurance_pricing_schedule->year_3 = $single['year_3'];
                $life_insurance_pricing_schedule->year_4 = $single['year_4'];
                $life_insurance_pricing_schedule->year_5 = $single['year_5'];
                $life_insurance_pricing_schedule->year_6 = $single['year_6'];
                $life_insurance_pricing_schedule->year_7 = $single['year_7'];
                $life_insurance_pricing_schedule->year_8 = $single['year_8'];
                $life_insurance_pricing_schedule->year_9 = $single['year_9'];
                $life_insurance_pricing_schedule->year_10 = $single['year_10'];
                $life_insurance_pricing_schedule->year_11 = $single['year_11'];
                $life_insurance_pricing_schedule->year_12 = $single['year_12'];
                $life_insurance_pricing_schedule->year_13 = $single['year_13'];
                $life_insurance_pricing_schedule->save();
            }
            $message = __('messages.plans.add_success');
        } else if($request->form_type == 'edit'){
            $plan_id = $request->plan_id;
            $life_insurance_plan = LifePlan::find($plan_id);
            $life_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $life_insurance_plan->plan_name = $request->plan_name;
            $life_insurance_plan->policy_period = $request->policy_period;
            $life_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $life_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $life_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $life_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $life_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $life_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids?json_encode($request->restricted_occupation_ids):null;
            $life_insurance_plan->restricted_chronic_ids = $request->restricted_chronic_ids?json_encode($request->restricted_chronic_ids):null;
            $life_insurance_plan->limit = $request->limit;
            $life_insurance_plan->net_premium = $request->net_premium;
            $life_insurance_plan->fees = $request->fees;
            $life_insurance_plan->stamps = $request->stamps;
            $life_insurance_plan->sales_tax = $request->sales_tax;
            $life_insurance_plan->gross_premium = (float)($request->net_premium) + (float)($request->fees) + (float)($request->stamps) + (float)($request->sales_tax);
            $life_insurance_plan->commission_percentage = $request->commission_percentage;
            $life_insurance_plan->commission_amount = $request->commission_amount;
            $life_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $life_insurance_plan->id), $newFilename);

                $life_insurance_plan->$file = $newFilename; // Store the filename in the database
                $life_insurance_plan->save();
            }
            LifePlanPolicyCover::where('life_plan_id',$plan_id)->delete();

            foreach ($request->policy_covers as $single) {
                $life_insurance_plan_policy_covers = new LifePlanPolicyCover();
                $life_insurance_plan_policy_covers->life_plan_id = $life_insurance_plan->id;
                $life_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $life_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $life_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']); 
                $life_insurance_plan_policy_covers->save();
            }

            LifePlanPricingSchedule::where('life_plan_id',$plan_id)->delete();
            foreach ($request->pricing_schedule as $single){
                $life_insurance_pricing_schedule = new LifePlanPricingSchedule();
                $life_insurance_pricing_schedule->life_plan_id = $life_insurance_plan->id;
                $life_insurance_pricing_schedule->age = $single['age'];
                $life_insurance_pricing_schedule->year_1 = $single['year_1'];
                $life_insurance_pricing_schedule->year_2 = $single['year_2'];
                $life_insurance_pricing_schedule->year_3 = $single['year_3'];
                $life_insurance_pricing_schedule->year_4 = $single['year_4'];
                $life_insurance_pricing_schedule->year_5 = $single['year_5'];
                $life_insurance_pricing_schedule->year_6 = $single['year_6'];
                $life_insurance_pricing_schedule->year_7 = $single['year_7'];
                $life_insurance_pricing_schedule->year_8 = $single['year_8'];
                $life_insurance_pricing_schedule->year_9 = $single['year_9'];
                $life_insurance_pricing_schedule->year_10 = $single['year_10'];
                $life_insurance_pricing_schedule->year_11 = $single['year_11'];
                $life_insurance_pricing_schedule->year_12 = $single['year_12'];
                $life_insurance_pricing_schedule->year_13 = $single['year_13'];
                $life_insurance_pricing_schedule->save();
            }
            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('life_plan.life_plan')->with('success',$message);
    }

    public function delete_life_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        LifePlanPolicyCover::where('life_plan_id',$plan_id)->delete();
        LifePlanPricingSchedule::where('life_plan_id',$plan_id)->delete();
        LifePlan::find($plan_id)->delete();
        return back()->with('success',__('messages.plans.delete_success'));
    }
}
