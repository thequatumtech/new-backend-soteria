<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\ClaimDeductible;
use Illuminate\Validation\Rule;



class ClaimDeductibleController extends Controller
{
    //list data
    public function index()
    {
        $claim_deductible = ClaimDeductible::withTrashed()->get();
        return view('admin.claim_deductible.claim_deductible', ['claim_deductible' => $claim_deductible]);
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
                    Rule::unique('claim_deductibles')->whereNull('deleted_at'),
                ],
            ]);
            ClaimDeductible::create($request->all());
            return redirect()->route('claim_deductible.list')->with('success', __('messages.claim_deductible.claim_deductible_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('claim_deductible.list')->with('error', __('messages.claim_deductible.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,ClaimDeductible $claim_deductible)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('claim_deductibles')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $claim_deductible = ClaimDeductible::findOrFail($request->cd_id);
            $claim_deductible->update($request->all());

            return redirect()->route('claim_deductible.list')->with('success', __('messages.claim_deductible.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('claim_deductible.list')->with('error', __('messages.claim_deductible.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $claim_deductible = ClaimDeductible::findOrFail($request->cd_id);
        $claim_deductible->delete();
        return redirect()->route('claim_deductible.list')->with('success', __('messages.claim_deductible.deleted'));
    }
}
