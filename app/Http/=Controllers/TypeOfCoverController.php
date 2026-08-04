<?php

namespace App\Http\Controllers;

use App\Models\TypeOfCover;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;



class TypeOfCoverController extends Controller
{
    //list data
    public function index()
    {
        $type_of_covers = TypeOfCover::withTrashed()->get();
        return view('admin.type_of_cover.type_of_cover', ['type_of_covers' => $type_of_covers]);
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
                    Rule::unique('type_of_covers')->whereNull('deleted_at'),
                ],
            ]);
            TypeOfCover::create($request->all());
            return redirect()->route('type_of_covers.list')->with('success', __('messages.type_of_covers.type_of_covers_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('type_of_covers.list')->with('error', __('messages.type_of_covers.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,TypeOfCover $type_of_covers)
    {

        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('type_of_covers')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $type_of_covers = TypeOfCover::findOrFail($request->cd_id);
            $type_of_covers->update($request->all());

            return redirect()->route('type_of_covers.list')->with('success', __('messages.type_of_covers.edit_created'));
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('type_of_covers.list')->with('error', __('messages.type_of_covers.error'));
            // Handle errors here
        }

    }

    public function destroy(Request $request)
    {
        $type_of_covers = TypeOfCover::findOrFail($request->cd_id);
        $type_of_covers->delete();
        return redirect()->route('type_of_covers.list')->with('success', __('messages.type_of_covers.deleted'));
    }
}
