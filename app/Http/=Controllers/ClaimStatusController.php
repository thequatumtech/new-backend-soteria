<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\ClaimStatus;
use Illuminate\Validation\Rule;



class ClaimStatusController extends Controller
{
    //list data
    public function index()
    {
        $claim_status = ClaimStatus::withTrashed()->get();
        return view('admin.claim_status.claim_status', ['claim_status' => $claim_status]);
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
                    Rule::unique('claim_statuses')->whereNull('deleted_at'),
                ],
            ]);
            ClaimStatus::create($request->all());
            return redirect()->route('claim_status.list')->with('success', __('messages.claim_status.claim_status_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('claim_status.list')->with('error', __('messages.claim_status.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,ClaimStatus $claim_status)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('claim_statuses')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $claim_status = ClaimStatus::findOrFail($request->cd_id);
            $claim_status->update($request->all());

            return redirect()->route('claim_status.list')->with('success', __('messages.claim_status.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('claim_status.list')->with('error', __('messages.claim_status.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $claim_status = ClaimStatus::findOrFail($request->cd_id);
        $claim_status->delete();
        return redirect()->route('claim_status.list')->with('success', __('messages.claim_status.deleted'));
    }
}
