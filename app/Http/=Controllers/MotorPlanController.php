<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\MotorPlan;
use Illuminate\Validation\Rule;

class MotorPlanController extends Controller
{
    //
    //list data
    public function index()
    {
        $motor_plan = MotorPlan::withTrashed()->get();
        return view('admin.motor_plan.motor_plan', ['motor_plan' => $motor_plan]);
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
                    Rule::unique('motor_plans')->whereNull('deleted_at'),
                ],
            ]);
            MotorPlan::create($request->all());
            return redirect()->route('motor_plan.list')->with('success', __('messages.motor_plan.motor_plan_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('motor_plan.list')->with('error', __('messages.motor_plan.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,MotorPlan $motor_plan)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('motor_plans')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $motor_plan = MotorPlan::findOrFail($request->cd_id);
            $motor_plan->update($request->all());

            return redirect()->route('motor_plan.list')->with('success', __('messages.motor_plan.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('motor_plan.list')->with('error', __('messages.motor_plan.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $motor_plan = MotorPlan::findOrFail($request->cd_id);
        $motor_plan->delete();
        return redirect()->route('motor_plan.list')->with('success', __('messages.motor_plan.deleted'));
    }
}
