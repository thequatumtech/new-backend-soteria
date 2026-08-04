<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\DentalPlan;
use App\Models\InsurancePlanModels\DentalPlanPolicyCover;
use App\Models\LineOfBusiness;
use Illuminate\Http\Request;

class DentalPlanController extends Controller
{
    private const line_of_business_id = 7;

    public function dental_plan(Request $request)
    {
        $title = __('messages.plans.dental_plan');
        $add_route = route('dental_plan.add_dental_plan');
        $plans = DentalPlan::all();
        return view('admin.plan.dental-plans.dental_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_dental_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"7"%')->get();
        $line_of_businesses = LineOfBusiness::where('id',self::line_of_business_id)->first()->name;
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        return view('admin.plan.dental-plans.add_dental_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages'));
    }

    public function edit_dental_plan(Request $request, $id)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"7"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $plan = DentalPlan::find($id);
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

        return view('admin.plan.dental-plans.edit_dental_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','plan','selected_country_ids','selected_city_ids','selected_district_ids'));
    }

    public function save_dental_plan(Request $request)
    {
        if($request->form_type == 'add'){
            $dental_insurance_plan = new DentalPlan();
            $dental_insurance_plan->line_of_business_id = self::line_of_business_id;
            $dental_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $dental_insurance_plan->plan_name = $request->plan_name;
            $dental_insurance_plan->policy_period = $request->policy_period;
            $dental_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $dental_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $dental_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $dental_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $dental_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $dental_insurance_plan->limit = $request->limit;
            $dental_insurance_plan->net_premium = $request->net_premium??0;
            $dental_insurance_plan->fees = $request->fees;
            $dental_insurance_plan->stamps = $request->stamps;
            $dental_insurance_plan->sales_tax = $request->sales_tax;
            $dental_insurance_plan->cbj = $request->cbj;
            $dental_insurance_plan->sales_tax_cbj = $request->salextaxcbj;
          
            $dental_insurance_plan->gross_premium = $request->gross_premium??0;
            $dental_insurance_plan->commission_percentage = $request->commission_percentage;
            $dental_insurance_plan->commission_amount = $request->commission_amount??0;
            $dental_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $dental_insurance_plan->id), $newFilename);

                $dental_insurance_plan->$file = $newFilename; // Store the filename in the database
                $dental_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $dental_insurance_plan_policy_covers = new DentalPlanPolicyCover();
                $dental_insurance_plan_policy_covers->dental_plan_id = $dental_insurance_plan->id;
                $dental_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $dental_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $dental_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']); 
                $dental_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $dental_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.add_success');
        } else if($request->form_type == 'edit'){
            $plan_id = $request->plan_id;
            $dental_insurance_plan = DentalPlan::find($plan_id);
            $dental_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $dental_insurance_plan->plan_name = $request->plan_name;
            $dental_insurance_plan->policy_period = $request->policy_period;
            $dental_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $dental_insurance_plan->restricted_country_ids = $request->restricted_country_ids?json_encode($request->restricted_country_ids):null;
            $dental_insurance_plan->restricted_city_ids = $request->restricted_city_ids?json_encode($request->restricted_city_ids):null;
            $dental_insurance_plan->restricted_district_ids = $request->restricted_district_ids?json_encode($request->restricted_district_ids):null;
            $dental_insurance_plan->restricted_age_ids = $request->restricted_age_ids?json_encode($request->restricted_age_ids):null;
            $dental_insurance_plan->limit = $request->limit;
            $dental_insurance_plan->net_premium = $request->net_premium??0;
            $dental_insurance_plan->fees = $request->fees;
            $dental_insurance_plan->stamps = $request->stamps;
            $dental_insurance_plan->sales_tax = $request->sales_tax;
            $dental_insurance_plan->cbj = $request->cbj;
            $dental_insurance_plan->sales_tax_cbj = $request->salextaxcbj;
          
            $dental_insurance_plan->gross_premium = $request->gross_premium??0;
            $dental_insurance_plan->commission_percentage = $request->commission_percentage;
            $dental_insurance_plan->commission_amount = $request->commission_amount??0;
            $dental_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $dental_insurance_plan->id), $newFilename);

                $dental_insurance_plan->$file = $newFilename; // Store the filename in the database
                $dental_insurance_plan->save();
            }
            DentalPlanPolicyCover::where('dental_plan_id',$plan_id)->delete();

            foreach ($request->policy_covers as $single) {
                $dental_insurance_plan_policy_covers = new DentalPlanPolicyCover();
                $dental_insurance_plan_policy_covers->dental_plan_id = $plan_id;
                $dental_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $dental_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $dental_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']); 
                $dental_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $dental_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('dental_plan.dental_plan')->with('success',$message);
    }

    public function delete_dental_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        DentalPlanPolicyCover::where('dental_plan_id',$plan_id)->delete();
        DentalPlan::find($plan_id)->delete();
        return back()->with('success',__('messages.plans.delete_success'));
    }
}
