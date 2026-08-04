<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\OutPatientDeductible;
use Illuminate\Validation\Rule;


class OutPatientDeductibleController extends Controller
{
    //list data
    public function index()
    {
        $out_patient_deductible = OutPatientDeductible::withTrashed()->get();
        return view('admin.out_patient_deductible.out_patient_deductible', ['out_patient_deductible' => $out_patient_deductible]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('out_patient_deductibles')->whereNull('deleted_at'),
                ],
            ]);
            OutPatientDeductible::create($request->all());
            return redirect()->route('out_patient_deductible.list')->with('success', __('messages.out_patient_deductible.out_patient_deductible_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('out_patient_deductible.list')->with('error', __('messages.out_patient_deductible.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,OutPatientDeductible $out_patient_deductible)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('out_patient_deductibles')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $out_patient_deductible = OutPatientDeductible::findOrFail($request->cd_id);
            $out_patient_deductible->update($request->all());

            return redirect()->route('out_patient_deductible.list')->with('success', __('messages.out_patient_deductible.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('out_patient_deductible.list')->with('error', __('messages.out_patient_deductible.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $out_patient_deductible = OutPatientDeductible::findOrFail($request->cd_id);
        $out_patient_deductible->delete();
        return redirect()->route('out_patient_deductible.list')->with('success', __('messages.out_patient_deductible.deleted'));
    }
}
