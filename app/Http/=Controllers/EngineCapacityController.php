<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\EngineCapacity;
use Illuminate\Validation\Rule;



class EngineCapacityController extends Controller
{
    //list data
    public function index()
    {
        $engine_capacity = EngineCapacity::withTrashed()->get();
        return view('admin.engine_capacity.engine_capacity', ['engine_capacity' => $engine_capacity]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('engine_capacities')->whereNull('deleted_at'),
                ],
            ]);
            EngineCapacity::create($request->all());
            return redirect()->route('engine_capacity.list')->with('success', __('messages.engine_capacity.engine_capacity_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('engine_capacity.list')->with('error', __('messages.engine_capacity.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,EngineCapacity $engine_capacity)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('engine_capacities')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $engine_capacity = EngineCapacity::findOrFail($request->cd_id);
            $engine_capacity->update($request->all());

            return redirect()->route('engine_capacity.list')->with('success', __('messages.engine_capacity.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('engine_capacity.list')->with('error', __('messages.engine_capacity.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $engine_capacity = EngineCapacity::findOrFail($request->cd_id);
        $engine_capacity->delete();
        return redirect()->route('engine_capacity.list')->with('success', __('messages.engine_capacity.deleted'));
    }
}
