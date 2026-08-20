<?php

namespace App\Http\Controllers;

use App\Models\Ages;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;



class AgeController extends Controller
{
    //list data
    public function index()
    {
        $ages = Ages::withTrashed()->get();
        return view('admin.age.age', ['ages' => $ages]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'age' => [
                    'required',
                    'integer',
                    'max:100',
                    Rule::unique('ages')
                        ->where('type', $request->type)
                        ->whereNull('deleted_at'),
                ],
                'type' => ['required', 'in:year,month'],
            ]);
            Ages::create($request->all());
            return redirect()->route('ages.list')->with('success', __('messages.age.age_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('ages.list')->with('error', __('messages.age.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request, Ages $age)
    {

        try {
            $request->validate([
                'age' => [
                    'required',
                    'integer',
                    'max:100',
                    Rule::unique('ages')
                        ->ignore($request->age_id)
                        ->where('type', $request->type)
                        ->whereNull('deleted_at'),
                ],
                'type' => ['required', 'in:year,month'],
            ]);

            $age = Ages::findOrFail($request->age_id);
            $age->update($request->all());

            return redirect()->route('ages.list')->with('success', __('messages.age.edit_created'));
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('ages.list')->with('error', __('messages.age.error'));
            // Handle errors here
        }
    }

    public function destroy(Request $request)
    {
        $age = Ages::findOrFail($request->age_id);
        $age->delete();
        return redirect()->route('ages.list')->with('success', __('messages.age.deleted'));
    }
}
