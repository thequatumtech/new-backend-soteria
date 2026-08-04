<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\ComplaintStatus;
use Illuminate\Validation\Rule;



class ComplaintStatusController extends Controller
{
    //list data
    public function index()
    {
        $complaint_status = ComplaintStatus::withTrashed()->get();
        return view('admin.complaint_status.complaint_status', ['complaint_status' => $complaint_status]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('complaint_statuses')->whereNull('deleted_at'),
                ],
            ]);
            ComplaintStatus::create($request->all());
            return redirect()->route('complaint_status.list')->with('success', __('messages.complaint_status.complaint_status_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('complaint_status.list')->with('error', __('messages.complaint_status.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,ComplaintStatus $complaint_status)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('complaint_statuses')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $complaint_status = ComplaintStatus::findOrFail($request->cd_id);
            $complaint_status->update($request->all());

            return redirect()->route('complaint_status.list')->with('success', __('messages.complaint_status.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('complaint_status.list')->with('error', __('messages.complaint_status.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $complaint_status = ComplaintStatus::findOrFail($request->cd_id);
        $complaint_status->delete();
        return redirect()->route('complaint_status.list')->with('success', __('messages.complaint_status.deleted'));
    }
}
