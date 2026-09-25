<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\HomePlan;
use App\Models\InsurancePlanModels\HomePlanPolicyCover;
use App\Models\LineOfBusiness;
use Illuminate\Http\Request;
use App\Models\ProtectionSystem;
use Illuminate\Support\Facades\Crypt;


class HomePlanController extends Controller
{
    private const line_of_business_id = 3;
    public function home_plan(Request $request)
    {
        $title = __('messages.plans.home_plan');
        $add_route = route('home_plan.add_home_plan');
        $plans = HomePlan::all();
        return view('admin.plan.home-plans.home_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_home_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"3"%')->get();
        $line_of_businesses = LineOfBusiness::where('id', self::line_of_business_id)->first()->name;
        $countries = Country::orderBy('name', 'asc')->get();
        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $ages = Ages::all();
        $protection_systems = ProtectionSystem::all();
        return view('admin.plan.home-plans.add_home_plan', compact('insurance_companies', 'line_of_businesses', 'countries', 'cities', 'districts', 'ages', 'protection_systems'));
    }


    public function edit_home_plan(Request $request, $id)
    {
        // $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"3"%')->get();
        // $line_of_businesses = LineOfBusiness::all();
        // $plan = HomePlan::find($id);

        $decryptedId = Crypt::decrypt($id);

        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"3"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $plan = HomePlan::find($decryptedId);

        $countries = Country::orderBy('name', 'asc')->get();

        // Decode stored country and city IDs
        $selected_country_ids = $plan->restricted_country_ids ? json_decode($plan->restricted_country_ids, true) : [];
        $selected_city_ids = $plan->restricted_city_ids ? json_decode($plan->restricted_city_ids, true) : [];
        $selected_district_ids = $plan->restricted_district_ids ? json_decode($plan->restricted_district_ids, true) : [];


        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $ages = Ages::all();
        $protection_systems = ProtectionSystem::all();
        $selected_home_age_ids = $plan->restricted_home_age_ids ? json_decode($plan->restricted_home_age_ids, true) : [];
        $selected_protection_system_ids = $plan->restricted_protection_system_ids ? json_decode($plan->restricted_protection_system_ids, true) : [];

        return view('admin.plan.home-plans.edit_home_plan', compact(
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
            'protection_systems',
            'selected_protection_system_ids',
            'selected_home_age_ids'
        ));
    }

    public function save_home_plan(Request $request)
    {
        if ($request->form_type == 'add') {
            $home_insurance_plan = new HomePlan();
            $home_insurance_plan->line_of_business_id = self::line_of_business_id;
            $home_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $home_insurance_plan->plan_name = $request->plan_name;
            $home_insurance_plan->policy_period = $request->policy_period;
            $home_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $home_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $home_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $home_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $home_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $home_insurance_plan->restricted_protection_system_ids = $request->restricted_protection_system_ids ? json_encode($request->restricted_protection_system_ids) : null;
            $home_insurance_plan->restricted_home_age_ids = $request->restricted_home_age_ids ? json_encode($request->restricted_home_age_ids) : null;
            $home_insurance_plan->limit = $request->limit;
            // $home_insurance_plan->limit = str_replace(',', '', $request->limit);
            $home_insurance_plan->net_premium = $request->net_premium;
            $home_insurance_plan->fees = $request->fees;
            $home_insurance_plan->stamps = $request->stamps;
            $home_insurance_plan->sales_tax = $request->sales_tax;
            $home_insurance_plan->cbj = $request->cbj;
            $home_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $home_insurance_plan->gross_premium = $request->gross_premium;
            $home_insurance_plan->commission_percentage = $request->commission_percentage;
            $home_insurance_plan->commission_amount = $request->commission_amount;
            $home_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $home_insurance_plan->id), $newFilename);

                $home_insurance_plan->$file = $newFilename; // Store the filename in the database
                $home_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $home_insurance_plan_policy_covers = new HomePlanPolicyCover();
                $home_insurance_plan_policy_covers->home_plan_id = $home_insurance_plan->id;
                $home_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $home_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $home_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $home_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $home_insurance_plan_policy_covers->cover_rate = $single['cover_rate'];
                $home_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $home_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.add_success');
        } else if ($request->form_type == 'edit') {
            $plan_id = $request->plan_id;
            $home_insurance_plan = HomePlan::find($plan_id);
            $home_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $home_insurance_plan->plan_name = $request->plan_name;
            $home_insurance_plan->policy_period = $request->policy_period;
            $home_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $home_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $home_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $home_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $home_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $home_insurance_plan->restricted_protection_system_ids = $request->restricted_protection_system_ids ? json_encode($request->restricted_protection_system_ids) : null;
            $home_insurance_plan->restricted_home_age_ids = $request->restricted_home_age_ids ? json_encode($request->restricted_home_age_ids) : null;
            $home_insurance_plan->limit = $request->limit;
            // $home_insurance_plan->limit = str_replace(',', '', $request->limit);
            $home_insurance_plan->net_premium = $request->net_premium;
            $home_insurance_plan->fees = $request->fees;
            $home_insurance_plan->stamps = $request->stamps;
            $home_insurance_plan->sales_tax = $request->sales_tax;
            $home_insurance_plan->cbj = $request->cbj;
            $home_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $home_insurance_plan->gross_premium = $request->gross_premium;
            $home_insurance_plan->commission_percentage = $request->commission_percentage;
            $home_insurance_plan->commission_amount = $request->commission_amount;
            $home_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $home_insurance_plan->id), $newFilename);

                $home_insurance_plan->$file = $newFilename; // Store the filename in the database
                $home_insurance_plan->save();
            }
            $requestCoverIds = [];
            foreach ($request->policy_covers as $single) {
                if (!empty($single['id'])) {
                    $requestCoverIds[] = $single['id'];
                }
            }

            HomePlanPolicyCover::where('home_plan_id', $plan_id)
                ->whereNotIn('id', $requestCoverIds)
                ->delete();
            foreach ($request->policy_covers as $single) {

                if (!empty($single['id'])) {
                    $cover = HomePlanPolicyCover::find($single['id']);
                    if ($cover) {
                        $cover->update([
                            'cover_name' => $single['cover_name'],
                            'cover_limit' => str_replace(',', '', $single['cover_limit']),
                            'cover_deductible' => $single['cover_deductible'],
                            'cover_rate' => $single['cover_rate'],
                            'cover_premium' => $single['cover_premium'],
                        ]);
                    }
                } else {
                    // Only create new if not empty
                    HomePlanPolicyCover::create([
                        'home_plan_id' => $plan_id,
                        'cover_name' => $single['cover_name'],
                        'cover_limit' => str_replace(',', '', $single['cover_limit']),
                        'cover_deductible' => $single['cover_deductible'],
                        'cover_rate' => $single['cover_rate'],
                        'cover_premium' => $single['cover_premium'],
                    ]);
                }
            }
            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('home_plan.home_plan')->with('success', $message);
    }

    public function delete_home_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        HomePlanPolicyCover::where('home_plan_id', $plan_id)->delete();
        HomePlan::find($plan_id)->delete();
        return back()->with('success', __('messages.plans.delete_success'));
    }
}
