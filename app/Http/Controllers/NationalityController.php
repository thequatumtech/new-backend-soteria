<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Nationality;
use Illuminate\Validation\Rule;



class NationalityController extends Controller
{
    //list data
    public function index()
    {
        $nationality = Nationality::withTrashed()->get();
        return view('admin.nationality.nationality', ['nationality' => $nationality]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('nationalities')->whereNull('deleted_at'),
                ],
            ]);
            Nationality::create($request->all());
            return redirect()->route('nationality.list')->with('success', __('messages.nationality.nationality_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('nationality.list')->with('error', __('messages.nationality.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,Nationality $nationality)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('nationalities')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $nationality = Nationality::findOrFail($request->cd_id);
            $nationality->update($request->all());

            return redirect()->route('nationality.list')->with('success', __('messages.nationality.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('nationality.list')->with('error', __('messages.nationality.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $nationality = Nationality::findOrFail($request->cd_id);
        $nationality->delete();
        return redirect()->route('nationality.list')->with('success', __('messages.nationality.deleted'));
    }


    //store data with csv
    public function csv(Request $request)
    {
        try{

        // Validate the uploaded file
        $request->validate([
        'csv_file' => 'required|mimes:csv,txt'
        ]);

        // Read the CSV file
        $file = $request->file('csv_file');
        $csvData = file_get_contents($file);

        // Parse CSV data
        $rows = explode("\n", $csvData);
        foreach ($rows as $k => $row) {
            if($k>0)
            {
                $rowData = str_getcsv($row);
                $name = $rowData[0];
                $existingRow = Nationality::where('name', $name)->whereNull('deleted_at')->first();
                if(!$existingRow){
                    if(!empty($name)){
                        $nationality = new Nationality();
                        $nationality->name = trim($name);
                        $nationality->save();
                    }                    
                }
            }           
        }

        // Redirect back with success message
        return back()->with('success',  __('messages.nationality.csvsuccess'));

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            // print_r($errors);
            // die;
            return redirect()->route('nationality.list')->with('error', __('messages.nationality.error'));
            // Handle errors here
        }
        
    }
}
