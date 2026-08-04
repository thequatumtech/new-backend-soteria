<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Occupations;
use Illuminate\Validation\Rule;



class OccupationsController extends Controller
{
    //list data
    public function index()
    {
        $occupations = Occupations::withTrashed()->get();
        return view('admin.occupations.occupations', ['occupations' => $occupations]);
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
                    Rule::unique('occupations')->whereNull('deleted_at'),
                ],
            ]);
            Occupations::create($request->all());
            return redirect()->route('occupations.list')->with('success', __('messages.occupations.occupations_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('occupations.list')->with('error', __('messages.occupations.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,Occupations $occupations)
    {  
        
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('occupations')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $occupations = Occupations::findOrFail($request->cd_id);
            $occupations->update($request->all());

            return redirect()->route('occupations.list')->with('success', __('messages.occupations.edit_created'));        
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array
        
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('occupations.list')->with('error', __('messages.occupations.error'));
            // Handle errors here
        }
        
    }

    public function destroy(Request $request)
    {
        $occupations = Occupations::findOrFail($request->cd_id);
        $occupations->delete();
        return redirect()->route('occupations.list')->with('success', __('messages.occupations.deleted'));
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
                $existingRow = Occupations::where('name', $name)->whereNull('deleted_at')->first();
                if(!$existingRow){
                    if(!empty($name)){
                        $occupations = new Occupations();
                        $occupations->name = trim($name);
                        $occupations->save();
                    }                    
                }
            }           
        }

        // Redirect back with success message
        return back()->with('success',  __('messages.occupations.csvsuccess'));

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            // print_r($errors);
            // die;
            return redirect()->route('occupations.list')->with('error', __('messages.occupations.error'));
            // Handle errors here
        }
        
    }
}
