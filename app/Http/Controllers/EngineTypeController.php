<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\EngineType;
use Illuminate\Validation\Rule;



class EngineTypeController extends Controller
{
    //list data
    public function index()
    {
        $engine_type = EngineType::withTrashed()->get();
        return view('admin.engine_type.engine_type', ['engine_type' => $engine_type]);
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
                    Rule::unique('engine_types')->whereNull('deleted_at'),
                ],
            ]);
            EngineType::create($request->all());
            return redirect()->route('engine_type.list')->with('success', __('messages.engine_type.engine_type_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('engine_type.list')->with('error', __('messages.engine_type.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,EngineType $engine_type)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('engine_types')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $engine_type = EngineType::findOrFail($request->cd_id);
            $engine_type->update($request->all());

            return redirect()->route('engine_type.list')->with('success', __('messages.engine_type.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('engine_type.list')->with('error', __('messages.engine_type.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $engine_type = EngineType::findOrFail($request->cd_id);
        $engine_type->delete();
        return redirect()->route('engine_type.list')->with('success', __('messages.engine_type.deleted'));
    }
}
