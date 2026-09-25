<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\MarinePlan;
use App\Models\InsurancePlanModels\MarinePlanPolicyCover;
use App\Models\InsuredItemCategory;
use App\Models\InsuredItemSubCategory;
use App\Models\LineOfBusiness;
use App\Models\TypeOfCover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MarinePlanController extends Controller
{

    private const line_of_business_id = 8;

    public function marine_plan(Request $request)
    {
        $title = __('messages.plans.marine_plan');
        $add_route = route('marine_plan.add_marine_plan');
        $plans = MarinePlan::all();
        return view('admin.plan.marine-plans.marine_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_marine_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"8"%')->get();
        $line_of_businesses = LineOfBusiness::where('id', self::line_of_business_id)->first()->name;
        $countries = Country::orderBy('name', 'asc')->get();
        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $ages = Ages::all();
        $type_of_covers = TypeOfCover::all();
        $categories = InsuredItemCategory::all();
        $sub_categories = InsuredItemSubCategory::all();
        return view('admin.plan.marine-plans.add_marine_plan', compact('insurance_companies', 'line_of_businesses', 'countries', 'cities', 'districts', 'ages', 'type_of_covers', 'categories', 'sub_categories'));
    }

    public function edit_marine_plan(Request $request, $id)
    {

        $decryptedId = Crypt::decrypt($id);


        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"8"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $countries = Country::orderBy('name', 'asc')->get();
        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $ages = Ages::all();
        $plan = MarinePlan::find($decryptedId);
        $type_of_covers = TypeOfCover::all();
        $categories = InsuredItemCategory::all();
        $sub_categories = InsuredItemSubCategory::all();
        // Decode stored country and city IDs
        $selected_country_ids = $plan->restricted_country_ids ? json_decode($plan->restricted_country_ids, true) : [];
        $selected_city_ids = $plan->restricted_city_ids ? json_decode($plan->restricted_city_ids, true) : [];
        $selected_district_ids = $plan->restricted_district_ids ? json_decode($plan->restricted_district_ids, true) : [];

        return view('admin.plan.marine-plans.edit_marine_plan', compact('insurance_companies', 'line_of_businesses', 'countries', 'cities', 'districts', 'ages', 'plan', 'type_of_covers', 'categories', 'sub_categories', 'selected_country_ids', 'selected_city_ids', 'selected_district_ids'));
    }

    public function save_marine_plan(Request $request)
    {
       
        // dd($request->all());
        if ($request->form_type == 'add') {
            $marine_insurance_plan = new MarinePlan();
            $marine_insurance_plan->line_of_business_id = self::line_of_business_id;
            $marine_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $marine_insurance_plan->plan_name = $request->plan_name;
            $marine_insurance_plan->policy_period = $request->policy_period;
            $marine_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $marine_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $marine_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $marine_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $marine_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $marine_insurance_plan->category_allowed = $request->restricted_category_ids ? json_encode($request->restricted_category_ids) : null;
            $marine_insurance_plan->sub_category_allowed = $request->sub_category_allowed ? implode(',', $request->sub_category_allowed) : null;
            $marine_insurance_plan->type_of_cover_id = $request->type_of_cover_id;
            $marine_insurance_plan->limit = $request->limit;
            $marine_insurance_plan->net_premium = $request->net_premium;
            $marine_insurance_plan->fees = $request->fees;
            $marine_insurance_plan->stamps = $request->stamps;
            $marine_insurance_plan->sales_tax = $request->sales_tax;
            $marine_insurance_plan->cbj = $request->cbj;
            $marine_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $marine_insurance_plan->gross_premium = $request->gross_premium;
            $marine_insurance_plan->commission_percentage = $request->commission_percentage;
            $marine_insurance_plan->commission_amount = $request->commission_amount;
            // $marine_insurance_plan->shipment_origin_and_destination_country = $request->shipment_origin_and_destination_country ?? null;
            $marine_insurance_plan->shipment_origin_and_destination_country =
                $request->shipment_origin_and_destination_country
                ? json_encode($request->shipment_origin_and_destination_country)
                : null;
            $marine_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $marine_insurance_plan->id), $newFilename);

                $marine_insurance_plan->$file = $newFilename; // Store the filename in the database
                $marine_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $marine_insurance_plan_policy_covers = new MarinePlanPolicyCover();
                $marine_insurance_plan_policy_covers->marine_plan_id = $marine_insurance_plan->id;
                $marine_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $marine_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $marine_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $marine_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $marine_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                $marine_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $marine_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.add_success');
        } else if ($request->form_type == 'edit') {
            $plan_id = $request->plan_id;
            $marine_insurance_plan = MarinePlan::find($plan_id);
            $marine_insurance_plan->plan_name = $request->plan_name;
            $marine_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $marine_insurance_plan->policy_period = $request->policy_period;
            $marine_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $marine_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $marine_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $marine_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $marine_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            // $marine_insurance_plan->category_allowed = null;
            $marine_insurance_plan->category_allowed = $request->restricted_category_ids ? json_encode($request->restricted_category_ids) : null;
            $marine_insurance_plan->sub_category_allowed = $request->sub_category_allowed ? implode(',', $request->sub_category_allowed) : null;
            $marine_insurance_plan->type_of_cover_id = $request->type_of_cover_id;
            $marine_insurance_plan->limit = $request->limit;
            $marine_insurance_plan->net_premium = $request->net_premium;
            $marine_insurance_plan->fees = $request->fees;
            $marine_insurance_plan->stamps = $request->stamps;
            $marine_insurance_plan->sales_tax = $request->sales_tax;
            $marine_insurance_plan->cbj = $request->cbj;
            $marine_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $marine_insurance_plan->gross_premium = $request->gross_premium;
            $marine_insurance_plan->commission_percentage = $request->commission_percentage;
            $marine_insurance_plan->commission_amount = $request->commission_amount;
            // $marine_insurance_plan->shipment_origin_and_destination_country = $request->shipment_origin_and_destination_country ?? null;
            $marine_insurance_plan->shipment_origin_and_destination_country =
                $request->shipment_origin_and_destination_country
                ? json_encode($request->shipment_origin_and_destination_country)
                : null;
            $marine_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $marine_insurance_plan->id), $newFilename);

                $marine_insurance_plan->$file = $newFilename; // Store the filename in the database
                $marine_insurance_plan->save();
            }
            MarinePlanPolicyCover::where('marine_plan_id', $plan_id)->delete();

            foreach ($request->policy_covers as $single) {
                $marine_insurance_plan_policy_covers = new MarinePlanPolicyCover();
                $marine_insurance_plan_policy_covers->marine_plan_id = $plan_id;
                $marine_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $marine_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $marine_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $marine_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $marine_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                $marine_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $marine_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('marine_plan.marine_plan')->with('success', $message);
    }

    public function delete_marine_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        MarinePlanPolicyCover::where('marine_plan_id', $plan_id)->delete();
        MarinePlan::find($plan_id)->delete();
        return back()->with('success', __('messages.plans.delete_success'));
    }
}
