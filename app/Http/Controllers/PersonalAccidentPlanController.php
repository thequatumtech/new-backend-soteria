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
use App\Models\DangerousActivities;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;

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
        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"9"%')->get();
        $line_of_businesses = LineOfBusiness::where('id', self::line_of_business_id)->first()->name;
        $countries = Country::orderBy('name', 'asc')->get();
        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $ages = Ages::all();
        $occupations = Occupations::all();
        $dangerous_activities = DangerousActivities::all();
        $insurance_periods = InsurancePeriod::whereNull('deleted_at')->get();
        return view('admin.plan.personal-accident-plans.add_personal_accident_plan', compact('insurance_companies', 'line_of_businesses', 'countries', 'cities', 'districts', 'ages', 'occupations', 'insurance_periods', 'dangerous_activities'));
    }

    public function edit_personal_accident_plan(Request $request, $id)
    {

        $decryptedId = Crypt::decrypt($id);

        $insurance_companies = InsuranceCompany::where('line_of_business_id', 'like', '%"9"%')->get();
        $line_of_businesses = LineOfBusiness::all();
        $countries = Country::orderBy('name', 'asc')->get();
        $cities = Cities::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $ages = Ages::all();
        $plan = PersonalAccidentPlan::find($decryptedId);
        $selected_country_ids = $plan->restricted_country_ids ? json_decode($plan->restricted_country_ids, true) : [];
        $selected_city_ids = $plan->restricted_city_ids ? json_decode($plan->restricted_city_ids, true) : [];
        $selected_district_ids = $plan->restricted_district_ids ? json_decode($plan->restricted_district_ids, true) : [];
        $occupations = Occupations::all();
        $dangerous_activities = DangerousActivities::all();
        $insurance_periods = InsurancePeriod::whereNull('deleted_at')->get();
        return view('admin.plan.personal-accident-plans.edit_personal_accident_plan', compact('insurance_companies', 'line_of_businesses', 'countries', 'cities', 'districts', 'ages', 'plan', 'insurance_periods', 'occupations', 'selected_country_ids', 'selected_city_ids', 'selected_district_ids', 'dangerous_activities'));
    }

    public function save_personal_accident_plan(Request $request)
    {
        $insurancePeriod = InsurancePeriod::find($request->insurance_period_id);
        $monthNumber = preg_replace('/[^0-9]/', '', $insurancePeriod->name ?? '');
        $monthColumn = 'm_' . $monthNumber;

        if ($request->form_type == 'add') {
            $personal_accident_insurance_plan = new PersonalAccidentPlan();
            $personal_accident_insurance_plan->line_of_business_id = self::line_of_business_id;
            $personal_accident_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $personal_accident_insurance_plan->plan_name = $request->plan_name;
            $personal_accident_insurance_plan->insurance_period_id = $request->insurance_period_id;
            // $personal_accident_insurance_plan->policy_period = $monthNumber;
            $personal_accident_insurance_plan->policy_period = null;

            $personal_accident_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $personal_accident_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $personal_accident_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $personal_accident_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $personal_accident_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $personal_accident_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids ? json_encode($request->restricted_occupation_ids) : null;
            $personal_accident_insurance_plan->restricted_dangerous_activities_ids = $request->restricted_dangerous_activities_ids ? json_encode($request->restricted_dangerous_activities_ids) : null;
            $personal_accident_insurance_plan->limit = $request->limit;
            $personal_accident_insurance_plan->net_premium = 0;
            $personal_accident_insurance_plan->fees = $request->fees;
            $personal_accident_insurance_plan->stamps = $request->stamps;
            $personal_accident_insurance_plan->sales_tax = $request->sales_tax;
            $personal_accident_insurance_plan->cbj = $request->cbj;
            $personal_accident_insurance_plan->sales_tax_cbj = $request->salextaxcbj;
            $personal_accident_insurance_plan->gross_premium = 0;
            $personal_accident_insurance_plan->commission_percentage = $request->commission_percentage;
            $personal_accident_insurance_plan->commission_amount = 0;
            $personal_accident_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName();
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $personal_accident_insurance_plan->id), $newFilename);
                $personal_accident_insurance_plan->$file = $newFilename;
                $personal_accident_insurance_plan->save();
            }

            foreach ($request->policy_covers as $single) {
                $personal_accident_insurance_plan_policy_covers = new PersonalAccidentPlanPolicyCover();
                $personal_accident_insurance_plan_policy_covers->personal_accident_plan_id = $personal_accident_insurance_plan->id;
                $personal_accident_insurance_plan_policy_covers->cover_name = $single['cover_name'];
                $personal_accident_insurance_plan_policy_covers->cover_limit = str_replace(',', '', $single['cover_limit']);
                $personal_accident_insurance_plan_policy_covers->cover_deductible = $single['cover_deductible'];
                $personal_accident_insurance_plan_policy_covers->save();
            }
            foreach ($request->pricing_schedule as $index => $schedule) {
                if (isset($schedule['age'])) {

                    PersonalAccidentPlanPricingSchedule::create([
                        'personal_accident_plan_id' => $personal_accident_insurance_plan->id,
                        'json_data' => json_encode($schedule),
                        'age'  => $schedule['age'],
                        'm_1'  => $schedule['m_1']  ?? null,
                        'm_2'  => $schedule['m_2']  ?? null,
                        'm_3'  => $schedule['m_3']  ?? null,
                        'm_4'  => $schedule['m_4']  ?? null,
                        'm_5'  => $schedule['m_5']  ?? null,
                        'm_6'  => $schedule['m_6']  ?? null,
                        'm_7'  => $schedule['m_7']  ?? null,
                        'm_8'  => $schedule['m_8']  ?? null,
                        'm_9'  => $schedule['m_9']  ?? null,
                        'm_10' => $schedule['m_10'] ?? null,
                        'm_11' => $schedule['m_11'] ?? null,
                        'm_12' => $schedule['m_12'] ?? null,
                    ]);
                }
            }

            $message = __('messages.plans.add_success');
        } else if ($request->form_type == 'edit') {
            $plan_id = $request->plan_id;
            $personal_accident_insurance_plan = PersonalAccidentPlan::find($plan_id);
            if (!$personal_accident_insurance_plan) {
                return redirect()->back()->withErrors(['error' => 'Plan not found']);
            }

            $personal_accident_insurance_plan->insurance_company_id = $request->insurance_company_id;
            $personal_accident_insurance_plan->plan_name = $request->plan_name;
            $personal_accident_insurance_plan->insurance_period_id = $request->insurance_period_id;
            // $personal_accident_insurance_plan->policy_period = $monthNumber;
            $personal_accident_insurance_plan->policy_period = null;

            $personal_accident_insurance_plan->insurance_policy_text = $request->insurance_policy_text ?? null;
            $personal_accident_insurance_plan->restricted_country_ids = $request->restricted_country_ids ? json_encode($request->restricted_country_ids) : null;
            $personal_accident_insurance_plan->restricted_city_ids = $request->restricted_city_ids ? json_encode($request->restricted_city_ids) : null;
            $personal_accident_insurance_plan->restricted_district_ids = $request->restricted_district_ids ? json_encode($request->restricted_district_ids) : null;
            $personal_accident_insurance_plan->restricted_age_ids = $request->restricted_age_ids ? json_encode($request->restricted_age_ids) : null;
            $personal_accident_insurance_plan->restricted_occupation_ids = $request->restricted_occupation_ids ? json_encode($request->restricted_occupation_ids) : null;
            $personal_accident_insurance_plan->restricted_dangerous_activities_ids = $request->restricted_dangerous_activities_ids ? json_encode($request->restricted_dangerous_activities_ids) : null;
            $personal_accident_insurance_plan->limit = $request->limit;
            $personal_accident_insurance_plan->fees = $request->fees;
            $personal_accident_insurance_plan->stamps = $request->stamps;
            $personal_accident_insurance_plan->sales_tax = $request->sales_tax;
            $personal_accident_insurance_plan->cbj = $request->cbj;
            $personal_accident_insurance_plan->sales_tax_cbj = $request->salextaxcbj;
            $personal_accident_insurance_plan->commission_percentage = $request->commission_percentage;
            $personal_accident_insurance_plan->save();

            $file = 'insurance_policy_pdf';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName();
                $newFilename = $file . "_file_" . time() . "." . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->move(public_path('uploads/insurance_plans/' . $personal_accident_insurance_plan->id), $newFilename);
                $personal_accident_insurance_plan->$file = $newFilename;
                $personal_accident_insurance_plan->save();
            }

            $requestCoverIds = [];
            foreach ($request->policy_covers as $single) {
                if (!empty($single['id'])) {
                    $requestCoverIds[] = $single['id'];
                }
            }
            PersonalAccidentPlanPolicyCover::where('personal_accident_plan_id', $plan_id)
                ->whereNotIn('id', $requestCoverIds)
                ->delete();

            foreach ($request->policy_covers as $single) {
                if (!empty($single['id'])) {
                    $cover = PersonalAccidentPlanPolicyCover::find($single['id']);
                    if ($cover) {
                        $cover->update([
                            'cover_name'       => $single['cover_name'],
                            'cover_limit'      => str_replace(',', '', $single['cover_limit']),
                            'cover_deductible' => $single['cover_deductible'],
                            'cover_premium'    => $single['cover_premium'],
                        ]);
                    }
                } else {
                    PersonalAccidentPlanPolicyCover::create([
                        'personal_accident_plan_id' => $plan_id,
                        'cover_name'       => $single['cover_name'],
                        'cover_limit'      => str_replace(',', '', $single['cover_limit']),
                        'cover_deductible' => $single['cover_deductible'],
                        'cover_premium'    => $single['cover_premium'] ?? 0,
                    ]);
                }
            }

            PersonalAccidentPlanPricingSchedule::where('personal_accident_plan_id', $plan_id)->delete();

            foreach ($request->pricing_schedule as $index => $schedule) {
                if (isset($schedule['age'])) {
                    PersonalAccidentPlanPricingSchedule::create([
                        'personal_accident_plan_id' => $plan_id,
                        'json_data' => json_encode($schedule),
                        'age'  => $schedule['age'],
                        'm_1'  => $schedule['m_1']  ?? null,
                        'm_2'  => $schedule['m_2']  ?? null,
                        'm_3'  => $schedule['m_3']  ?? null,
                        'm_4'  => $schedule['m_4']  ?? null,
                        'm_5'  => $schedule['m_5']  ?? null,
                        'm_6'  => $schedule['m_6']  ?? null,
                        'm_7'  => $schedule['m_7']  ?? null,
                        'm_8'  => $schedule['m_8']  ?? null,
                        'm_9'  => $schedule['m_9']  ?? null,
                        'm_10' => $schedule['m_10'] ?? null,
                        'm_11' => $schedule['m_11'] ?? null,
                        'm_12' => $schedule['m_12'] ?? null,
                    ]);
                }
            }

            $message = __('messages.plans.edit_success');
        } else {
            return redirect()->back()->withErrors(['error' => 'Invalid form type']);
        }

        return redirect()->route('personal_accident_plan.personal_accident_plan')->with('success', $message);
    }
    public function delete_personal_accident_plan(Request $request)
    {
        $plan_id = $request->delete_plan_id;
        PersonalAccidentPlanPolicyCover::where('personal_accident_plan_id', $plan_id)->delete();
        PersonalAccidentPlan::find($plan_id)->delete();
        return back()->with('success', __('messages.plans.delete_success'));
    }
}
