<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\MedicalNetwork;
use Illuminate\Validation\Rule;



class MedicalNetworkController extends Controller
{
    //list data
    public function index()
    {
        $medical_network = MedicalNetwork::withTrashed()->get();
        return view('admin.medical_network.medical_network', ['medical_network' => $medical_network]);
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
                    Rule::unique('medical_networks')->whereNull('deleted_at'),
                ],
            ]);
            MedicalNetwork::create($request->all());
            return redirect()->route('medical_network.list')->with('success', __('messages.medical_network.medical_network_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('medical_network.list')->with('error', __('messages.medical_network.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,MedicalNetwork $medical_network)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('medical_networks')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $medical_network = MedicalNetwork::findOrFail($request->cd_id);
            $medical_network->update($request->all());

            return redirect()->route('medical_network.list')->with('success', __('messages.medical_network.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('medical_network.list')->with('error', __('messages.medical_network.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $medical_network = MedicalNetwork::findOrFail($request->cd_id);
        $medical_network->delete();
        return redirect()->route('medical_network.list')->with('success', __('messages.medical_network.deleted'));
    }
}
