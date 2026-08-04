<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\ChronicDisease;
use Illuminate\Validation\Rule;



class ChronicDiseaseController extends Controller
{
    //list data
    public function index()
    {
        $chronic_disease = ChronicDisease::withTrashed()->get();
        return view('admin.chronic_disease.chronic_disease', ['chronic_disease' => $chronic_disease]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('chronic_diseases')->whereNull('deleted_at'),
                ],
            ]);
            ChronicDisease::create($request->all());
            return redirect()->route('chronic_disease.list')->with('success', __('messages.chronic_disease.chronic_disease_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('chronic_disease.list')->with('error', __('messages.chronic_disease.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,ChronicDisease $chronic_disease)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('chronic_diseases')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $chronic_disease = ChronicDisease::findOrFail($request->cd_id);
            $chronic_disease->update($request->all());

            return redirect()->route('chronic_disease.list')->with('success', __('messages.chronic_disease.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('chronic_disease.list')->with('error', __('messages.chronic_disease.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $chronic_disease = ChronicDisease::findOrFail($request->cd_id);
        $chronic_disease->delete();
        return redirect()->route('chronic_disease.list')->with('success', __('messages.chronic_disease.deleted'));
    }
}
