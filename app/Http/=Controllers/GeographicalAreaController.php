<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\GeographicalArea;
use Illuminate\Validation\Rule;



class GeographicalAreaController extends Controller
{
    //list data
    public function index()
    {
        $geographical_area = GeographicalArea::withTrashed()->get();
        return view('admin.geographical_area.geographical_area', ['geographical_area' => $geographical_area]);
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
                    Rule::unique('geographical_areas')->whereNull('deleted_at'),
                ],
            ]);
            GeographicalArea::create($request->all());
            return redirect()->route('geographical_area.list')->with('success', __('messages.geographical_area.geographical_area_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('geographical_area.list')->with('error', __('messages.geographical_area.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,GeographicalArea $geographical_area)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('geographical_areas')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $geographical_area = GeographicalArea::findOrFail($request->cd_id);
            $geographical_area->update($request->all());

            return redirect()->route('geographical_area.list')->with('success', __('messages.geographical_area.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('geographical_area.list')->with('error', __('messages.geographical_area.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $geographical_area = GeographicalArea::findOrFail($request->cd_id);
        $geographical_area->delete();
        return redirect()->route('geographical_area.list')->with('success', __('messages.geographical_area.deleted'));
    }
}
