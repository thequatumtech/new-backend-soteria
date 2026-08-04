<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\InPatientDeductible;
use Illuminate\Validation\Rule;


class InPatientDeductibleController extends Controller
{
    //list data
    public function index()
    {
        $in_patient_deductible = InPatientDeductible::withTrashed()->get();
        return view('admin.in_patient_deductible.in_patient_deductible', ['in_patient_deductible' => $in_patient_deductible]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('in_patient_deductibles')->whereNull('deleted_at'),
                ],
            ]);
            InPatientDeductible::create($request->all());
            return redirect()->route('in_patient_deductible.list')->with('success', __('messages.in_patient_deductible.in_patient_deductible_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('in_patient_deductible.list')->with('error', __('messages.in_patient_deductible.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,InPatientDeductible $in_patient_deductible)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('in_patient_deductibles')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $in_patient_deductible = InPatientDeductible::findOrFail($request->cd_id);
            $in_patient_deductible->update($request->all());

            return redirect()->route('in_patient_deductible.list')->with('success', __('messages.in_patient_deductible.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('in_patient_deductible.list')->with('error', __('messages.in_patient_deductible.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $in_patient_deductible = InPatientDeductible::findOrFail($request->cd_id);
        $in_patient_deductible->delete();
        return redirect()->route('in_patient_deductible.list')->with('success', __('messages.in_patient_deductible.deleted'));
    }
}
