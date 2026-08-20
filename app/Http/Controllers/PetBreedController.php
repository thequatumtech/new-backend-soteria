<?php

namespace App\Http\Controllers;

use App\Models\PetBreed;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class PetBreedController extends Controller
{
    public function index()
    {
        $breeds = PetBreed::withTrashed()->get();
        return view('admin.pets.breed', ['breeds' => $breeds]);
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'type' => ['required', 'in:dog,cat'],
                'breed' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('pet_breeds')
                        ->where('type', $request->type)
                        ->whereNull('deleted_at'),
                ],
            ]);

            PetBreed::create($request->all());
            return redirect()->route('pet_breed.list')->with('success', __('messages.pets_breed.pet_breed_create_success'));
        } catch (ValidationException $e) {
            return redirect()->route('pet_breed.list')->with('error', __('messages.pets_breed.pet_breed_error'));
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'type' => ['required', 'in:dog,cat'],
                'breed' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('pet_breeds')
                        ->ignore($request->breed_id)
                        ->where('type', $request->type)
                        ->whereNull('deleted_at'),
                ],
            ]);

            $breed = PetBreed::findOrFail($request->breed_id);
            $breed->update($request->all());
            return redirect()->route('pet_breed.list')->with('success', __('messages.pets_breed.pet_breed_update_success'));
        } catch (ValidationException $e) {
            return redirect()->route('pet_breed.list')->with('error', __('messages.pets_breed.pet_breed_error'));
        }
    }

    public function destroy(Request $request)
    {
        $breed = PetBreed::findOrFail($request->breed_id);
        $breed->delete();
        $message = __('messages.pets_breed.pet_breed_delete_success');
        return redirect()->route('pet_breed.list')->with('success',  $message);
    }
}
