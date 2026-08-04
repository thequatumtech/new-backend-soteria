<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\PetPlan;
use App\Models\InsurancePlanModels\PetPlanPolicyCover;
use App\Models\LineOfBusiness;
use Illuminate\Http\Request;

class PetPlanController extends Controller
{

    private const line_of_business_id = 11;

    public function pet_plan(Request $request)
    {
        $title = __('messages.plans.pet_plan');
        $add_route = route('pet_plan.add_pet_plan');
        $plans = PetPlan::all();
        return view('admin.plan.pet-plans.pet_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_pet_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"11"%')->get();
        $line_of_businesses = LineOfBusiness::where('id', self::line_of_business_id)->first()->name;
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        return view('admin.plan.pet-plans.add_pet_plan', compact('insurance_companies', 'line_of_businesses', 'countries', 'cities', 'districts', 'ages'));
    }

    public function edit_pet_plan(Request $request, $id)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"11"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $countries = Country::all();
        $cities = Cities::all();
        $districts = District::all();
        $ages = Ages::all();
        $plan = PetPlan::find($id);

        // Decode stored country, city, district, age, and pet age IDs
        $selected_country_ids = $plan->restricted_country_ids ? json_decode($plan->restricted_country_ids, true) : [];
        $selected_city_ids = $plan->restricted_city_ids ? json_decode($plan->restricted_city_ids, true) : [];
        $selected_district_ids = $plan->restricted_district_ids ? json_decode($plan->restricted_district_ids, true) : [];
        $selected_age_ids = $plan->restricted_age_ids ? json_decode($plan->restricted_age_ids, true) : [];
        $selected_pet_age_ids = $plan->restricted_pet_age_ids ? json_decode($plan->restricted_pet_age_ids, true) : [];

        return view('admin.plan.pet-plans.edit_pet_plan', compact(
            'insurance_companies',
            'line_of_businesses',
            'countries',
            'cities',
            'districts',
            'ages',
            'plan',
            'selected_country_ids',
            'selected_city_ids',
            'selected_district_ids',
            'selected_age_ids',
            'selected_pet_age_ids'
        ));
    }

    public function save_pet_plan(Request $request)
    {
        if ($request->form_type == 'add') {
            $pet_insurance_plan = new PetPlan();
            $pet_insurance_plan->line_of_business_id = self::line_of_business_id;
            $pet_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $pet_insurance_plan->plan_name = $request->plan_name;
            $pet_insurance_plan->policy_period = $request->policy_period;
            $pet_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $pet_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $pet_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $pet_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $pet_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $pet_insurance_plan->restricted_pet_age_ids = $request->restricted_pet_age_ids ? json_encode($request->restricted_pet_age_ids) : null;
            $pet_insurance_plan->limit = $request->limit;
            $pet_insurance_plan->net_premium = $request->net_premium ?? 0;
            $pet_insurance_plan->fees = $request->fees;
            $pet_insurance_plan->stamps = $request->stamps;
            $pet_insurance_plan->sales_tax = $request->sales_tax;
            $pet_insurance_plan->cbj = $request->cbj;
            $pet_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $pet_insurance_plan->gross_premium = $request->gross_premium ?? 0;
            $pet_insurance_plan->commission_percentage = $request->commission_percentage;
            $pet_insurance_plan->commission_amount = $request->commission_amount ?? 0;
            $pet_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $pet_insurance_plan->id), $newFilename);

                $pet_insurance_plan->$file = $newFilename; // Store the filename in the database
                $pet_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $pet_insurance_plan_policy_covers = new PetPlanPolicyCover();
                $pet_insurance_plan_policy_covers->pet_plan_id = $pet_insurance_plan->id;
                $pet_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                $pet_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $pet_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $pet_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                $pet_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $pet_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.add_success');
        } else if ($request->form_type == 'edit') {
            $plan_id = $request->plan_id;
            $pet_insurance_plan = PetPlan::find($plan_id);
            $pet_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $pet_insurance_plan->plan_name = $request->plan_name;
            $pet_insurance_plan->policy_period = $request->policy_period;
            $pet_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $pet_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $pet_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $pet_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $pet_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $pet_insurance_plan->restricted_pet_age_ids = $request->restricted_pet_age_ids ? json_encode($request->restricted_pet_age_ids) : null;
            $pet_insurance_plan->limit = $request->limit;
            $pet_insurance_plan->net_premium = $request->net_premium ?? 0;
            $pet_insurance_plan->fees = $request->fees;
            $pet_insurance_plan->stamps = $request->stamps;
            $pet_insurance_plan->sales_tax = $request->sales_tax;
            $pet_insurance_plan->cbj = $request->cbj;
            $pet_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $pet_insurance_plan->gross_premium = $request->gross_premium ?? 0;
            $pet_insurance_plan->commission_percentage = $request->commission_percentage;
            $pet_insurance_plan->commission_amount = $request->commission_amount ?? 0;
            $pet_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $pet_insurance_plan->id), $newFilename);

                $pet_insurance_plan->$file = $newFilename; // Store the filename in the database
                $pet_insurance_plan->save();
            }
            PetPlanPolicyCover::where('pet_plan_id', $plan_id)->delete();

            foreach ($request->policy_covers as $single) {
                $pet_insurance_plan_policy_covers = new PetPlanPolicyCover();
                $pet_insurance_plan_policy_covers->pet_plan_id = $plan_id;
                $pet_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                $pet_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $pet_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $pet_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                $pet_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $pet_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('pet_plan.pet_plan')->with('success', $message);
    }

    public function delete_pet_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        PetPlanPolicyCover::where('pet_plan_id', $plan_id)->delete();
        PetPlan::find($plan_id)->delete();
        return back()->with('success', __('messages.plans.delete_success'));
    }
}
