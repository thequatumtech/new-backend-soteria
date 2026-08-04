<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\OfficePlan;
use App\Models\InsurancePlanModels\OfficePlanPolicyCover;
use App\Models\LineOfBusiness;
use Illuminate\Http\Request;

class OfficePlanController extends Controller
{
    private const line_of_business_id = 4;
    public function office_plan(Request $request)
    {
        $title = __('messages.plans.office_plan');
        $add_route = route('office_plan.add_office_plan');
        $plans = OfficePlan::all();
        return view('admin.plan.office-plans.office_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_office_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"4"%')->get();
        $line_of_businesses = LineOfBusiness::find(self::line_of_business_id)->name;
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        return view('admin.plan.office-plans.add_office_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages'));
    }

    public function edit_office_plan(Request $request, $id)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"4"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $plan = OfficePlan::find($id);
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
        return view('admin.plan.office-plans.edit_office_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','plan'));
    }

    public function save_office_plan(Request $request)
    {
        if($request->form_type == 'add'){
            $office_insurance_plan = new OfficePlan();
            $office_insurance_plan->line_of_business_id = self::line_of_business_id;
            $office_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $office_insurance_plan->plan_name = $request->plan_name;
            $office_insurance_plan->policy_period = $request->policy_period;
            $office_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $office_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $office_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $office_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $office_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $office_insurance_plan->limit = $request->limit;
            $office_insurance_plan->net_premium = $request->net_premium;
            $office_insurance_plan->fees = $request->fees;
            $office_insurance_plan->stamps = $request->stamps;
            $office_insurance_plan->sales_tax = $request->sales_tax;
            $office_insurance_plan->gross_premium = $request->gross_premium;
            $office_insurance_plan->commission_percentage = $request->commission_percentage;
            $office_insurance_plan->commission_amount = $request->commission_amount;
            $office_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $office_insurance_plan->id), $newFilename);

                $office_insurance_plan->$file = $newFilename; // Store the filename in the database
                $office_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $office_insurance_plan_policy_covers = new OfficePlanPolicyCover();
                $office_insurance_plan_policy_covers->office_plan_id = $office_insurance_plan->id;
                $office_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $office_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $office_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $office_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $office_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                $office_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $office_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.add_success');
        } else if($request->form_type == 'edit'){
            $plan_id = $request->plan_id;
            $office_insurance_plan = OfficePlan::find($plan_id);
            $office_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $office_insurance_plan->plan_name = $request->plan_name;
            $office_insurance_plan->policy_period = $request->policy_period;
            $office_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $office_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $office_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $office_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $office_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $office_insurance_plan->limit = $request->limit;
            $office_insurance_plan->net_premium = $request->net_premium;
            $office_insurance_plan->fees = $request->fees;
            $office_insurance_plan->stamps = $request->stamps;
            $office_insurance_plan->sales_tax = $request->sales_tax;
            $office_insurance_plan->gross_premium = $request->gross_premium;
            $office_insurance_plan->commission_percentage = $request->commission_percentage;
            $office_insurance_plan->commission_amount = $request->commission_amount;
            $office_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $office_insurance_plan->id), $newFilename);

                $office_insurance_plan->$file = $newFilename; // Store the filename in the database
                $office_insurance_plan->save();
            }
            OfficePlanPolicyCover::where('office_plan_id',$plan_id)->delete();

            foreach ($request->policy_covers as $single) {
                $office_insurance_plan_policy_covers = new OfficePlanPolicyCover();
                $office_insurance_plan_policy_covers->office_plan_id = $plan_id;
                $office_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $office_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $office_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $office_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $office_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                $office_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $office_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('office_plan.office_plan')->with('success',$message);
    }

    public function delete_office_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        OfficePlanPolicyCover::where('office_plan_id',$plan_id)->delete();
        OfficePlan::find($plan_id)->delete();
        return back()->with('success',__('messages.plans.delete_success'));
    }
}
