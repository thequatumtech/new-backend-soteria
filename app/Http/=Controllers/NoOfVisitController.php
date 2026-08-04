<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\NoOfVisit;
use Illuminate\Validation\Rule;

class NoOfVisitController extends Controller
{
    //
    //list data
    public function index()
    {
        $no_of_visits = NoOfVisit::withTrashed()->get();
        return view('admin.no_of_visits.no_of_visits', ['no_of_visits' => $no_of_visits]);
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
                    Rule::unique('no_of_visits')->whereNull('deleted_at'),
                ],
            ]);
            NoOfVisit::create($request->all());
            return redirect()->route('no_of_visits.list')->with('success', __('messages.no_of_visits.no_of_visits_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('no_of_visits.list')->with('error', __('messages.no_of_visits.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,NoOfVisit $no_of_visits)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('no_of_visits')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $no_of_visits = NoOfVisit::findOrFail($request->cd_id);
            $no_of_visits->update($request->all());

            return redirect()->route('no_of_visits.list')->with('success', __('messages.no_of_visits.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('no_of_visits.list')->with('error', __('messages.no_of_visits.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $no_of_visits = NoOfVisit::findOrFail($request->cd_id);
        $no_of_visits->delete();
        return redirect()->route('no_of_visits.list')->with('success', __('messages.no_of_visits.deleted'));
    }
}
