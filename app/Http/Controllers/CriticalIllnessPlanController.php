<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use App\Models\ChronicDisease;
use App\Models\Cities;
use App\Models\Country;
use App\Models\District;
use App\Models\InsuranceCompany;
use App\Models\InsurancePlanModels\CriticalIllnessPlan;
use App\Models\InsurancePlanModels\CriticalIllnessPlanPolicyCover;
use App\Models\LineOfBusiness;
use App\Models\Occupations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CriticalIllnessPlanController extends Controller
{
    private const line_of_business_id = 1;
    public function critical_illness_plan(Request $request)
    {
        $title = __('messages.plans.critical_illness_plan');
        $add_route = route('critical_illness_plan.add_critical_illness_plan');
        $plans = CriticalIllnessPlan::all();
        return view('admin.plan.critical-illness-plans.critical_illness_plan', compact('title', 'add_route', 'plans'));
    }

    public function add_critical_illness_plan(Request $request)
    {
        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"1"%')->get();
        $line_of_businesses = LineOfBusiness::find(self::line_of_business_id)->name;
        $countries = Country::orderBy('name', 'asc')->get();
        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $ages = Ages::all();
        $occupations = Occupations::all();
        $chronics = ChronicDisease::all();
        return view('admin.plan.critical-illness-plans.add_critical_illness_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','occupations','chronics'));
    }

    public function edit_critical_illness_plan(Request $request, $id)
    {
        $decryptedId = Crypt::decrypt($id);

        $insurance_companies = InsuranceCompany::where('line_of_business_id','like','%"1"%')->get();
        $line_of_businesses = LineOfBusiness::find(self::line_of_business_id)->name;
        $countries = Country::orderBy('name', 'asc')->get();
        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $ages = Ages::all();
        $plan = CriticalIllnessPlan::find($decryptedId);

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
        $occupations = Occupations::all();
        $chronics = ChronicDisease::all();
        return view('admin.plan.critical-illness-plans.edit_critical_illness_plan',compact('insurance_companies','line_of_businesses','countries','cities','districts','ages','plan','chronics','occupations',
            'selected_country_ids',
            'selected_city_ids',
            'selected_district_ids'));
    }

    public function save_critical_illness_plan(Request $request)
    {
        if($request->form_type == 'add'){
            $critical_illness_insurance_plan = new CriticalIllnessPlan();
            $critical_illness_insurance_plan->line_of_business_id = self::line_of_business_id;
            $critical_illness_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $critical_illness_insurance_plan->plan_name = $request->plan_name;
            $critical_illness_insurance_plan->policy_period = $request->policy_period;
            $critical_illness_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $critical_illness_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $critical_illness_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $critical_illness_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $critical_illness_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $critical_illness_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids ? json_encode($request->restricted_occupation_ids) : null;
            $critical_illness_insurance_plan->restricted_chronic_ids = $request->restricted_chronic_ids ? json_encode($request->restricted_chronic_ids) : null;
            $critical_illness_insurance_plan->limit = $request->limit;
            $critical_illness_insurance_plan->net_premium = $request->net_premium;
            $critical_illness_insurance_plan->fees = $request->fees;
            $critical_illness_insurance_plan->stamps = $request->stamps;
            $critical_illness_insurance_plan->sales_tax = $request->sales_tax;
            $critical_illness_insurance_plan->cbj = $request->cbj;
            $critical_illness_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $critical_illness_insurance_plan->gross_premium = $request->gross_premium;
            $critical_illness_insurance_plan->commission_percentage = $request->commission_percentage;
            $critical_illness_insurance_plan->commission_amount = $request->commission_amount;
            $critical_illness_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $critical_illness_insurance_plan->id), $newFilename);

                $critical_illness_insurance_plan->$file = $newFilename; // Store the filename in the database
                $critical_illness_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $critical_illness_insurance_plan_policy_covers = new CriticalIllnessPlanPolicyCover();
                $critical_illness_insurance_plan_policy_covers->critical_illness_plan_id = $critical_illness_insurance_plan->id;
                $critical_illness_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $critical_illness_insurance_plan_policy_covers->cover_limit = $single['cover_limit'];
                $critical_illness_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $critical_illness_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $critical_illness_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                $critical_illness_insurance_plan_policy_covers->save();
            }
            $message = __('messages.plans.add_success');
        } else if($request->form_type == 'edit'){
            $plan_id = $request->plan_id;
            $critical_illness_insurance_plan = CriticalIllnessPlan::find($plan_id);
            $critical_illness_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $critical_illness_insurance_plan->plan_name = $request->plan_name;
            $critical_illness_insurance_plan->policy_period = $request->policy_period;
            $critical_illness_insurance_plan->insurance_policy_text = $request->insurance_policy_text??null;
            $critical_illness_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $critical_illness_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $critical_illness_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $critical_illness_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $critical_illness_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids ? json_encode($request->restricted_occupation_ids) : null;
            $critical_illness_insurance_plan->restricted_chronic_ids = $request->restricted_chronic_ids ? json_encode($request->restricted_chronic_ids) : null;
            $critical_illness_insurance_plan->limit = $request->limit;
            $critical_illness_insurance_plan->net_premium = $request->net_premium;
            $critical_illness_insurance_plan->fees = $request->fees;
            $critical_illness_insurance_plan->stamps = $request->stamps;
            $critical_illness_insurance_plan->sales_tax = $request->sales_tax;
            $critical_illness_insurance_plan->cbj = $request->cbj;
            $critical_illness_insurance_plan->sales_tax_cbj = $request->salextaxcbj;

            $critical_illness_insurance_plan->gross_premium = $request->gross_premium;
            $critical_illness_insurance_plan->commission_percentage = $request->commission_percentage;
            $critical_illness_insurance_plan->commission_amount = $request->commission_amount;
            $critical_illness_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $critical_illness_insurance_plan->id), $newFilename);

                $critical_illness_insurance_plan->$file = $newFilename;
                $critical_illness_insurance_plan->save();
            }
            // CriticalIllnessPlanPolicyCover::where('critical_illness_plan_id',$plan_id)->delete();
              $requestCoverIds = [];
                foreach ($request->policy_covers as $single) {
                    if (!empty($single['id'])) {
                        $requestCoverIds[] = $single['id'];
                    }
                }

             CriticalIllnessPlanPolicyCover::where('critical_illness_plan_id', $plan_id)
                    ->whereNotIn('id', $requestCoverIds)
                    ->delete();
            foreach ($request->policy_covers as $single) {
                // $critical_illness_insurance_plan_policy_covers = new CriticalIllnessPlanPolicyCover();
                // $critical_illness_insurance_plan_policy_covers->critical_illness_plan_id = $plan_id;
                // $critical_illness_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                // $critical_illness_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                // $critical_illness_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                // $critical_illness_insurance_plan_policy_covers->cover_premium = $single['cover_premium'];
                // $critical_illness_insurance_plan_policy_covers->save();
                if (!empty($single['id'])) {
                    $cover = CriticalIllnessPlanPolicyCover::find($single['id']);
                    if ($cover) {
                        $cover->update([
                            'cover_name' => $single['cover_name'],
                            'cover_limit' => str_replace(',', '', $single['cover_limit']),
                            'cover_deductible' => $single['cover_deductible'],
                            'cover_premium' => $single['cover_premium'],
                        ]);
                    }
                } else {
                    CriticalIllnessPlanPolicyCover::create([
                        'critical_illness_plan_id' => $plan_id,
                        'cover_name' => $single['cover_name'],
                        'cover_limit' => str_replace(',', '', $single['cover_limit']),
                        'cover_deductible' => $single['cover_deductible'],
                        'cover_premium' => $single['cover_premium'],
                    ]);
                }
            }
            $message = __('messages.plans.edit_success');
        }
        return redirect()->route('critical_illness_plan.critical_illness_plan')->with('success',$message);
    }

    public function delete_critical_illness_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        CriticalIllnessPlanPolicyCover::where('critical_illness_plan_id',$plan_id)->delete();
        CriticalIllnessPlan::find($plan_id)->delete();
        return back()->with('success',__('messages.plans.delete_success'));
    }
}
