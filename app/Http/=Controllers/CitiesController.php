<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Cities;
use Illuminate\Validation\Rule;



class CitiesController extends Controller
{
    //list data
    public function index()
    {
        $cities = Cities::withTrashed()->orderBy('name')->get();
        $countries = Country::all();
        return view('admin.cities.cities', ['cities' => $cities,'all_countries' => $countries]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'country_id' => 'required',
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('cities')->whereNull('deleted_at'),
                ],
            ]);
            Cities::create($request->all());
            return redirect()->route('cities.list')->with('success', __('messages.cities.cities_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('cities.list')->with('error', __('messages.cities.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,Cities $cities)
    {

        try {
            $request->validate([
                'country_id' => 'required',
                'name' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('cities')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $cities = Cities::findOrFail($request->cd_id);
            $cities->update($request->all());

            return redirect()->route('cities.list')->with('success', __('messages.cities.edit_created'));
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('cities.list')->with('error', __('messages.cities.error'));
            // Handle errors here
        }

    }

    public function destroy(Request $request)
    {
        $cities = Cities::findOrFail($request->cd_id);
        $cities->delete();
        return redirect()->route('cities.list')->with('success', __('messages.cities.deleted'));
    }


    //store data with csv
    public function csv(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'csv_file' => 'required|mimes:csv,txt'
            ]);

            // Read the CSV file
            $file = $request->file('csv_file');
            $csvData = file_get_contents($file);

            // Remove BOM if present
            $csvData = preg_replace('/^\xEF\xBB\xBF/', '', $csvData);

            // Parse CSV data
            $rows = array_filter(explode("\n", $csvData)); // Remove empty rows
            $parsedData = [];
            foreach ($rows as $row) {
                $data = str_getcsv($row);
                if (count($data) === 2) { // Ensure there are exactly 2 columns
                    $parsedData[] = $data;
                }
            }
            foreach ($parsedData as $rowData) {
                $country_name = $rowData[0];
                $city_name = $rowData[1];
                $country = Country::where('name', $country_name)->first();
                $existingRow = Cities::where('name', $city_name)->where('country_id', $country->id)->whereNull('deleted_at')->first();
                if (!$existingRow && !empty($country)) {
                    if (!empty($city_name)) {
                        $cities = new Cities();
                        $cities->country_id = $country->id;
                        $cities->name = trim($city_name);
                        $cities->save();
                    }
                }
            }

            // Redirect back with success message
            return back()->with('success', __('messages.cities.csvsuccess'));

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            // print_r($errors);
            // die;
            return redirect()->route('cities.list')->with('error', __('messages.cities.error'));
            // Handle errors here
        }
    }

    public function get_cities(Request $request)
    {
        $countries_ids = $request->restricted_country_ids;
        $all_cities = Cities::whereIn('country_id',$countries_ids)->orderBy('name')->get();
        return response()->json([
            'success' => 'Cities Fetched Successfully',
            'status' => 200,
            'data' => $all_cities
        ]);
    }

    public function downloadCitiesCsv()
    {
        $filePath = public_path('sample/cities.csv');
        return response()->download($filePath, 'cities.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

}
