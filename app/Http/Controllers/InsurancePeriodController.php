<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\InsurancePeriod;
use Illuminate\Validation\Rule;



class InsurancePeriodController extends Controller
{
    //list data
    public function index()
    {
        $insurance_period = InsurancePeriod::withTrashed()->orderByRaw('CAST(SUBSTRING_INDEX(name, " ", 1) AS UNSIGNED) ASC')->get();
        // dd($insurance_period);
        return view('admin.insurance_period.insurance_period', ['insurance_period' => $insurance_period]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('insurance_periods')->whereNull('deleted_at'),
                ],
            ]);
            InsurancePeriod::create($request->all());
            return redirect()->route('insurance_period.list')->with('success', __('messages.insurance_period.insurance_period_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('insurance_period.list')->with('error', __('messages.insurance_period.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,InsurancePeriod $insurance_period)
    {

        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('insurance_periods')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $insurance_period = InsurancePeriod::findOrFail($request->cd_id);
            $insurance_period->update($request->all());

            return redirect()->route('insurance_period.list')->with('success', __('messages.insurance_period.edit_created'));
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('insurance_period.list')->with('error', __('messages.insurance_period.error'));
            // Handle errors here
        }

    }

    public function destroy(Request $request)
    {
        $insurance_period = InsurancePeriod::findOrFail($request->cd_id);
        $insurance_period->delete();
        return redirect()->route('insurance_period.list')->with('success', __('messages.insurance_period.deleted'));
    }
}
