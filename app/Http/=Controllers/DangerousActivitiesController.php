<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\DangerousActivities;
use Illuminate\Validation\Rule;



class DangerousActivitiesController extends Controller
{
    //list data
    public function index()
    {
        $dangerous_activities = DangerousActivities::withTrashed()->get();
        return view('admin.dangerous_activities.dangerous_activities', ['dangerous_activities' => $dangerous_activities]);
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
                    Rule::unique('dangerous_activities')->whereNull('deleted_at'),
                ],
            ]);
            DangerousActivities::create($request->all());
            return redirect()->route('dangerous_activities.list')->with('success', __('messages.dangerous_activities.dangerous_activities_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('dangerous_activities.list')->with('error', __('messages.dangerous_activities.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,DangerousActivities $dangerous_activities)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('dangerous_activities')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $dangerous_activities = DangerousActivities::findOrFail($request->cd_id);
            $dangerous_activities->update($request->all());

            return redirect()->route('dangerous_activities.list')->with('success', __('messages.dangerous_activities.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('dangerous_activities.list')->with('error', __('messages.dangerous_activities.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $dangerous_activities = DangerousActivities::findOrFail($request->cd_id);
        $dangerous_activities->delete();
        return redirect()->route('dangerous_activities.list')->with('success', __('messages.dangerous_activities.deleted'));
    }
}
