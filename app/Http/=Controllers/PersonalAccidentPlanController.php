<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsurancePeriod;
use App\Models\InsurancePlanModels\PersonalAccidentPlan;
use App\Models\InsurancePlanModels\PersonalAccidentPlanPolicyCover;
use App\Models\InsurancePlanModels\PersonalAccidentPlanPricingSchedule;
use App\Models\LineOfBusiness;
use App\Models\Occupations;
use Illuminate\Http\Request;

class PersonalAccidentPlanController extends Controller
{
    private const line_of_business_id = 9;
    public function personal_accident_plan(Request $request)
    {
        $title = __('messages.plans.personal_accident_plan');
        $add_route = route('personal_accident_plan.add_personal_accident_plan');
        $plans = PersonalAccidentPlan::all();
        return view('admin.plan.personal-accident-plans.personal_accident_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_personal_accident_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"9"%')->get();
        $line_of_businesses = LineOfBusiness::where('id',self::line_of_business_id)->first()->name;
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $occupations = Occupations::all();
        $insurance_periods = InsurancePeriod::all();
        return view('admin.plan.personal-accident-plans.add_personal_accident_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','occupations','insurance_periods'));
    }

    public function edit_personal_accident_plan(Request $request, $id)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"9"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $plan = PersonalAccidentPlan::find($id);
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
        $insurance_periods = InsurancePeriod::all();
        return view('admin.plan.personal-accident-plans.edit_personal_accident_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','plan','insurance_periods','occupations'));
    }

    public function save_personal_accident_plan(Request $request)
    {
        if($request->form_type == 'add'){
            $personal_accident_insurance_plan = new PersonalAccidentPlan();
            $personal_accident_insurance_plan->line_of_business_id = self::line_of_business_id;
            $personal_accident_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $personal_accident_insurance_plan->plan_name = $request->plan_name;
            $personal_accident_insurance_plan->insurance_period_id = $request->insurance_period_id;
            $personal_accident_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $personal_accident_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $personal_accident_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $personal_accident_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $personal_accident_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $personal_accident_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids?json_encode($request->restricted_occupation_ids):null;
            $personal_accident_insurance_plan->limit = $request->limit;
            $personal_accident_insurance_plan->net_premium = 0;
            $personal_accident_insurance_plan->fees = $request->fees;
            $personal_accident_insurance_plan->stamps = $request->stamps;
            $personal_accident_insurance_plan->sales_tax = $request->sales_tax;
            $personal_accident_insurance_plan->gross_premium = 0;
            $personal_accident_insurance_plan->commission_percentage = $request->commission_percentage;
            $personal_accident_insurance_plan->commission_amount = 0;
            $personal_accident_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $personal_accident_insurance_plan->id), $newFilename);

                $personal_accident_insurance_plan->$file = $newFilename; // Store the filename in the database
                $personal_accident_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $personal_accident_insurance_plan_policy_covers = new PersonalAccidentPlanPolicyCover();
                $personal_accident_insurance_plan_policy_covers->personal_accident_plan_id = $personal_accident_insurance_plan->id;
                $personal_accident_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $personal_accident_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                 $personal_accident_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']); 
                $personal_accident_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $personal_accident_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $personal_accident_insurance_plan_policy_covers->save();
            }
            foreach ($request->pricing_schedule as $single){
                $personal_accident_insurance_schedule = new PersonalAccidentPlanPricingSchedule();
                $personal_accident_insurance_schedule->personal_accident_plan_id = $personal_accident_insurance_plan->id;
                $personal_accident_insurance_schedule->age = $single['age'];
                $personal_accident_insurance_schedule->m_3 = $single['m_3'];
                $personal_accident_insurance_schedule->m_6 = $single['m_6'];
                $personal_accident_insurance_schedule->m_9 = $single['m_9'];
                $personal_accident_insurance_schedule->m_12 = $single['m_12'];
                $personal_accident_insurance_schedule->save();
            }

            $message = __('messages.plans.add_success');
        } else if($request->form_type == 'edit'){
            $plan_id = $request->plan_id;
            $personal_accident_insurance_plan = PersonalAccidentPlan::find($plan_id);
            $personal_accident_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $personal_accident_insurance_plan->plan_name = $request->plan_name;
            $personal_accident_insurance_plan->insurance_period_id = $request->insurance_period_id;
            $personal_accident_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $personal_accident_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $personal_accident_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $personal_accident_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $personal_accident_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $personal_accident_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids?json_encode($request->restricted_occupation_ids):null;
            $personal_accident_insurance_plan->limit = $request->limit;
//            $personal_accident_insurance_plan->net_premium = $request->net_premium;
            $personal_accident_insurance_plan->fees = $request->fees;
            $personal_accident_insurance_plan->stamps = $request->stamps;
            $personal_accident_insurance_plan->sales_tax = $request->sales_tax;
//            $personal_accident_insurance_plan->gross_premium = $request->gross_premium;
            $personal_accident_insurance_plan->commission_percentage = $request->commission_percentage;
//            $personal_accident_insurance_plan->commission_amount = $request->commission_amount;
            $personal_accident_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $personal_accident_insurance_plan->id), $newFilename);

                $personal_accident_insurance_plan->$file = $newFilename; // Store the filename in the database
                $personal_accident_insurance_plan->save();
            }

            PersonalAccidentPlanPolicyCover::where('personal_accident_plan_id',$plan_id)->delete();

            foreach ($request->policy_covers as $single) {
                $personal_accident_insurance_plan_policy_covers = new PersonalAccidentPlanPolicyCover();
                $personal_accident_insurance_plan_policy_covers->personal_accident_plan_id = $plan_id;
                $personal_accident_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $personal_accident_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                 $personal_accident_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']); 
                $personal_accident_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $personal_accident_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $personal_accident_insurance_plan_policy_covers->save();
            }

            PersonalAccidentPlanPricingSchedule::where('personal_accident_plan_id',$plan_id)->delete();

            foreach ($request->pricing_schedule as $single){
                $personal_accident_insurance_schedule = new PersonalAccidentPlanPricingSchedule();
                $personal_accident_insurance_schedule->personal_accident_plan_id = $personal_accident_insurance_plan->id;
                $personal_accident_insurance_schedule->age = $single['age'];
                $personal_accident_insurance_schedule->m_3 = $single['m_3'];
                $personal_accident_insurance_schedule->m_6 = $single['m_6'];
                $personal_accident_insurance_schedule->m_9 = $single['m_9'];
                $personal_accident_insurance_schedule->m_12 = $single['m_12'];
                $personal_accident_insurance_schedule->save();
            }
            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('personal_accident_plan.personal_accident_plan')->with('success',$message);
    }

    public function delete_personal_accident_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        PersonalAccidentPlanPolicyCover::where('personal_accident_plan_id',$plan_id)->delete();
        PersonalAccidentPlan::find($plan_id)->delete();
        return back()->with('success',__('messages.plans.delete_success'));
    }

}
