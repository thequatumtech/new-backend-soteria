<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Language;
use Illuminate\Validation\Rule;



class LanguageController extends Controller
{
    //list data
    public function index()
    {
        $language = Language::withTrashed()->get();
        return view('admin.language.language', ['language' => $language]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('languages')->whereNull('deleted_at'),
                ],
            ]);
            Language::create($request->all());
            return redirect()->route('language.list')->with('success', __('messages.language.language_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('language.list')->with('error', __('messages.language.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,Language $language)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    Rule::unique('languages')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $language = Language::findOrFail($request->cd_id);
            $language->update($request->all());

            return redirect()->route('language.list')->with('success', __('messages.language.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('language.list')->with('error', __('messages.language.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $language = Language::findOrFail($request->cd_id);
        $language->delete();
        return redirect()->route('language.list')->with('success', __('messages.language.deleted'));
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
                $existingRow = Language::where('name', $name)->whereNull('deleted_at')->first();
                if(!$existingRow){
                    if(!empty($name)){
                        $language = new Language();
                        $language->name = trim($name);
                        $language->save();
                    }                    
                }
            }           
        }

        // Redirect back with success message
        return back()->with('success',  __('messages.language.csvsuccess'));

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            // print_r($errors);
            // die;
            return redirect()->route('language.list')->with('error', __('messages.language.error'));
            // Handle errors here
        }
        
    }
}
