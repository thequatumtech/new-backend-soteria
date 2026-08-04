<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Country;
use Illuminate\Validation\Rule;



class CountryController extends Controller
{
    //list data
    public function index()
    {
        $country = Country::withTrashed()->orderBy('name')->get();
        return view('admin.country.country', ['country' => $country]);
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
                    Rule::unique('countries')->whereNull('deleted_at'),
                ],
            ]);
            Country::create($request->all());
            return redirect()->route('country.list')->with('success', __('messages.country.country_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('country.list')->with('error', __('messages.country.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,Country $country)
    {

        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('countries')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $country = Country::findOrFail($request->cd_id);
            $country->update($request->all());

            return redirect()->route('country.list')->with('success', __('messages.country.edit_created'));
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('country.list')->with('error', __('messages.country.error'));
            // Handle errors here
        }

    }

    public function destroy(Request $request)
    {
        $country = Country::findOrFail($request->cd_id);
        $country->delete();
        return redirect()->route('country.list')->with('success', __('messages.country.deleted'));
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
                $existingRow = Country::where('name', $name)->whereNull('deleted_at')->first();;

                if(!$existingRow){
                    if(!empty($name)){
                        $country = new Country();
                        $country->name = trim($name);
                        $country->save();
                    }
                }
            }
        }

        // Redirect back with success message
        return back()->with('success',  __('messages.country.csvsuccess'));

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            // print_r($errors);
            // die;
            return redirect()->route('country.list')->with('error', __('messages.country.error'));
            // Handle errors here
        }

    }

    public function downloadCountriesCsv()
    {
        $filePath = public_path('sample/countries.csv');
        return response()->download($filePath, 'countries.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
