<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\ChronicDisease;
use App\Models\Cities;
use App\Models\Country;
use App\Models\DangerousActivities;
use App\Models\District;
use App\Models\InPatientDeductible;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\InPatientPlan;
use App\Models\InsurancePlanModels\InPatientPlanAdditionalBenefit;
use App\Models\InsurancePlanModels\InPatientPlanPolicyCover;
use App\Models\InsurancePlanModels\InPatientPlanPricingSchedule;
use App\Models\LineOfBusiness;
use App\Models\MedicalNetwork;
use App\Models\Occupations;
use Illuminate\Http\Request;

class InPatientPlanController extends Controller
{
    private const line_of_business_id = 5;

    public function in_patient_plan(Request $request)
    {
        $title = __('messages.plans.in_patient_plan');
        $add_route = route('in_patient_plan.add_in_patient_plan');
        $plans = InPatientPlan::all();
        return view('admin.plan.in-patient-plans.in_patient_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_in_patient_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"5"%')->get();
        $line_of_businesses = LineOfBusiness::where('id',self::line_of_business_id)->first()->name;
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $occupations = Occupations::all();
        $chronics = ChronicDisease::all();
        $dangerous_activities = DangerousActivities::all();
        $in_patient_deductibles = InPatientDeductible::all();
        $medical_networks = MedicalNetwork::all();
        return view('admin.plan.in-patient-plans.add_in_patient_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','dangerous_activities','in_patient_deductibles','medical_networks','occupations','chronics','dangerous_activities'));
    }

    public function edit_in_patient_plan(Request $request, $id)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"5"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $occupations = Occupations::all();
        $chronics = ChronicDisease::all();
        $dangerous_activities = DangerousActivities::all();
        $in_patient_deductibles = InPatientDeductible::all();
        $medical_networks = MedicalNetwork::all();
        $plan = InPatientPlan::find($id);
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
         // Decode stored country and city IDs
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
        return view('admin.plan.in-patient-plans.edit_in_patient_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','plan','dangerous_activities','in_patient_deductibles','medical_networks','occupations','chronics','selected_country_ids','selected_city_ids','selected_district_ids'));
    }

    public function save_in_patient_plan(Request $request)
    {
        if($request->form_type == 'add'){
            $in_patient_insurance_plan = new InPatientPlan();
            $in_patient_insurance_plan->line_of_business_id = self::line_of_business_id;
            $in_patient_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $in_patient_insurance_plan->plan_name = $request->plan_name;
            $in_patient_insurance_plan->policy_period = $request->policy_period;
            $in_patient_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $in_patient_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $in_patient_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $in_patient_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $in_patient_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $in_patient_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids?json_encode($request->restricted_occupation_ids):null;
            $in_patient_insurance_plan->restricted_chronic_ids = $request->restricted_chronic_ids?json_encode($request->restricted_chronic_ids):null;
            $in_patient_insurance_plan->restricted_dangerous_activities_ids = $request->restricted_dangerous_activities_ids?json_encode($request->restricted_dangerous_activities_ids):null;
            $in_patient_insurance_plan->in_patient_deductibles_ids = $request->in_patient_deductibles_ids?json_encode($request->in_patient_deductibles_ids):null;
            $in_patient_insurance_plan->medical_networks_ids = $request->medical_networks_ids?json_encode($request->medical_networks_ids):null;
            $in_patient_insurance_plan->limit = $request->limit;
            $in_patient_insurance_plan->net_premium = 0;
            $in_patient_insurance_plan->fees = $request->fees;
            $in_patient_insurance_plan->stamps = $request->stamps;
            $in_patient_insurance_plan->sales_tax = $request->sales_tax;
            $in_patient_insurance_plan->cbj = $request->cbj;
            $in_patient_insurance_plan->sales_tax_cbj = $request->salextaxcbj;
       
            $in_patient_insurance_plan->gross_premium = 0;
            $in_patient_insurance_plan->commission_percentage = $request->commission_percentage;
            $in_patient_insurance_plan->commission_amount = 0;
            $in_patient_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $in_patient_insurance_plan->id), $newFilename);

                $in_patient_insurance_plan->$file = $newFilename; // Store the filename in the database
                $in_patient_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $in_patient_insurance_plan_policy_covers = new InPatientPlanPolicyCover();
                $in_patient_insurance_plan_policy_covers->in_patient_plan_id = $in_patient_insurance_plan->id;
                $in_patient_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                $in_patient_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $in_patient_insurance_plan_policy_covers->save();
            }

            foreach ($request->additional_benefits as $single) {
                $in_patient_insurance_plan_additional_benefits = new InPatientPlanAdditionalBenefit();
                $in_patient_insurance_plan_additional_benefits->in_patient_plan_id = $in_patient_insurance_plan->id;
                $in_patient_insurance_plan_additional_benefits->benefit_name = $single['benefit_name'];
                $in_patient_insurance_plan_additional_benefits->benefit_limit = $single['benefit_limit'];
                $in_patient_insurance_plan_additional_benefits->save();
            }

            foreach ($request->male_pricing_schedule as $single) {
                $in_patient_plan_pricing_schedules = new InPatientPlanPricingSchedule();
                $in_patient_plan_pricing_schedules->in_patient_plan_id = $in_patient_insurance_plan->id;
                $arr = explode("-", $single['age_band']);
                $lower_age = (integer)$arr[0];
                $upper_age = isset($arr[1])?(integer)$arr[1]:100;
                $in_patient_plan_pricing_schedules->lower_age = $lower_age;
                $in_patient_plan_pricing_schedules->upper_age = $upper_age;
                $in_patient_plan_pricing_schedules->gender = 1;
                $in_patient_plan_pricing_schedules->vip_class = $single['vip_class'];
                $in_patient_plan_pricing_schedules->first_class = $single['first_class'];
                $in_patient_plan_pricing_schedules->second_class = $single['second_class'];
                $in_patient_plan_pricing_schedules->third_class = $single['third_class'];
                $in_patient_plan_pricing_schedules->save();
            }

            foreach ($request->female_pricing_schedule as $single) {
                $in_patient_plan_pricing_schedules = new InPatientPlanPricingSchedule();
                $in_patient_plan_pricing_schedules->in_patient_plan_id = $in_patient_insurance_plan->id;
                $arr = explode("-", $single['age_band']);
                $lower_age = (integer)$arr[0];
                $upper_age = isset($arr[1])?(integer)$arr[1]:100;
                $in_patient_plan_pricing_schedules->lower_age = $lower_age;
                $in_patient_plan_pricing_schedules->upper_age = $upper_age;
                $in_patient_plan_pricing_schedules->gender = 2;
                $in_patient_plan_pricing_schedules->vip_class = $single['vip_class'];
                $in_patient_plan_pricing_schedules->first_class = $single['first_class'];
                $in_patient_plan_pricing_schedules->second_class = $single['second_class'];
                $in_patient_plan_pricing_schedules->third_class = $single['third_class'];
                $in_patient_plan_pricing_schedules->save();
            }
            $message = __('messages.plans.add_success');
        } else if($request->form_type == 'edit'){
            $plan_id = $request->plan_id;
            
            $in_patient_insurance_plan = InPatientPlan::find($plan_id);
            $in_patient_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $in_patient_insurance_plan->plan_name = $request->plan_name;
            $in_patient_insurance_plan->policy_period = $request->policy_period;
            $in_patient_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $in_patient_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $in_patient_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $in_patient_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $in_patient_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $in_patient_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids?json_encode($request->restricted_occupation_ids):null;
            $in_patient_insurance_plan->restricted_chronic_ids = $request->restricted_chronic_ids?json_encode($request->restricted_chronic_ids):null;
            $in_patient_insurance_plan->restricted_dangerous_activities_ids = $request->restricted_dangerous_activities_ids?json_encode($request->restricted_dangerous_activities_ids):null;
            $in_patient_insurance_plan->in_patient_deductibles_ids = $request->in_patient_deductibles_ids?json_encode($request->in_patient_deductibles_ids):null;
            $in_patient_insurance_plan->medical_networks_ids = $request->medical_networks_ids?json_encode($request->medical_networks_ids):null;
            $in_patient_insurance_plan->limit = $request->limit;
            $in_patient_insurance_plan->net_premium = 0;
            $in_patient_insurance_plan->fees = $request->fees;
            $in_patient_insurance_plan->stamps = $request->stamps;
            $in_patient_insurance_plan->sales_tax = $request->sales_tax;
            $in_patient_insurance_plan->cbj = $request->cbj;
            $in_patient_insurance_plan->sales_tax_cbj = $request->salextaxcbj;
         
            $in_patient_insurance_plan->gross_premium = 0;
            $in_patient_insurance_plan->commission_percentage = $request->commission_percentage;
            $in_patient_insurance_plan->commission_amount = 0;
            $in_patient_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $in_patient_insurance_plan->id), $newFilename);

                $in_patient_insurance_plan->$file = $newFilename; // Store the filename in the database
                $in_patient_insurance_plan->save();
            }

            InPatientPlanPolicyCover::where('in_patient_plan_id',$plan_id)->delete();

            foreach ($request->policy_covers as $single) {
                $in_patient_insurance_plan_policy_covers = new InPatientPlanPolicyCover();
                $in_patient_insurance_plan_policy_covers->in_patient_plan_id = $plan_id;
                $in_patient_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                $in_patient_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $in_patient_insurance_plan_policy_covers->save();
            }

            InPatientPlanAdditionalBenefit::where('in_patient_plan_id',$plan_id)->delete();
            foreach ($request->additional_benefits as $single) {
                $in_patient_insurance_plan_additional_benefits = new InPatientPlanAdditionalBenefit();
                $in_patient_insurance_plan_additional_benefits->in_patient_plan_id = $plan_id;
                $in_patient_insurance_plan_additional_benefits->benefit_name = $single['benefit_name'];
                $in_patient_insurance_plan_additional_benefits->benefit_limit = $single['benefit_limit'];
                $in_patient_insurance_plan_additional_benefits->save();
            }

            InPatientPlanPricingSchedule::where('in_patient_plan_id',$plan_id)->delete();
            foreach ($request->male_pricing_schedule as $single) {
                $in_patient_plan_pricing_schedules = new InPatientPlanPricingSchedule();
                $in_patient_plan_pricing_schedules->in_patient_plan_id = $plan_id;
                $arr = explode("-", $single['age_band']);
                $lower_age = (integer)$arr[0];
                $upper_age = isset($arr[1])?(integer)$arr[1]:100;
                $in_patient_plan_pricing_schedules->lower_age = $lower_age;
                $in_patient_plan_pricing_schedules->upper_age = $upper_age;
                $in_patient_plan_pricing_schedules->gender = 1;
                $in_patient_plan_pricing_schedules->vip_class = $single['vip_class'];
                $in_patient_plan_pricing_schedules->first_class = $single['first_class'];
                $in_patient_plan_pricing_schedules->second_class = $single['second_class'];
                $in_patient_plan_pricing_schedules->third_class = $single['third_class'];
                $in_patient_plan_pricing_schedules->save();
            }

            foreach ($request->female_pricing_schedule as $single) {
                $in_patient_plan_pricing_schedules = new InPatientPlanPricingSchedule();
                $in_patient_plan_pricing_schedules->in_patient_plan_id = $plan_id;
                $arr = explode("-", $single['age_band']);
                $lower_age = (integer)$arr[0];
                $upper_age = isset($arr[1])?(integer)$arr[1]:100;
                $in_patient_plan_pricing_schedules->lower_age = $lower_age;
                $in_patient_plan_pricing_schedules->upper_age = $upper_age;
                $in_patient_plan_pricing_schedules->gender = 2;
                $in_patient_plan_pricing_schedules->vip_class = $single['vip_class'];
                $in_patient_plan_pricing_schedules->first_class = $single['first_class'];
                $in_patient_plan_pricing_schedules->second_class = $single['second_class'];
                $in_patient_plan_pricing_schedules->third_class = $single['third_class'];
                $in_patient_plan_pricing_schedules->save();
            }

            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('in_patient_plan.in_patient_plan')->with('success',$message);
    }

    public function delete_in_patient_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        InPatientPlanPolicyCover::where('in_patient_plan_id',$plan_id)->delete();
        InPatientPlanPricingSchedule::where('in_patient_plan_id',$plan_id)->delete();
        InPatientPlanAdditionalBenefit::where('in_patient_plan_id',$plan_id)->delete();
        InPatientPlan::find($plan_id)->delete();
        return back()->with('success',__('messages.plans.delete_success'));
    }

}
