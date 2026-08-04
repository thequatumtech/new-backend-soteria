<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\ProtectionSystem;
use Illuminate\Validation\Rule;



class ProtectionSystemController extends Controller
{
    //list data
    public function index()
    {
        $protection_system = ProtectionSystem::withTrashed()->get();
        return view('admin.protection_system.protection_system', ['protection_system' => $protection_system]);
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
                    Rule::unique('protection_systems')->whereNull('deleted_at'),
                ],
            ]);
            ProtectionSystem::create($request->all());
            return redirect()->route('protection_system.list')->with('success', __('messages.protection_system.protection_system_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('protection_system.list')->with('error', __('messages.protection_system.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,ProtectionSystem $protection_system)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('protection_systems')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $protection_system = ProtectionSystem::findOrFail($request->cd_id);
            $protection_system->update($request->all());

            return redirect()->route('protection_system.list')->with('success', __('messages.protection_system.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('protection_system.list')->with('error', __('messages.protection_system.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $protection_system = ProtectionSystem::findOrFail($request->cd_id);
        $protection_system->delete();
        return redirect()->route('protection_system.list')->with('success', __('messages.protection_system.deleted'));
    }
}
