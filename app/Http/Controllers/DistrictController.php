<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\District;
use Illuminate\Validation\Rule;



class DistrictController extends Controller
{
    //list data
    public function index()
    {
        $district = District::withTrashed()->orderBy('name')->get();
        $cities = Cities::all();
        return view('admin.district.district', ['district' => $district,'all_cities' => $cities]);
    }

    //store data
    public function create(Request $request)
    {
        try {
            $request->validate([
                'city_id' => 'required',
                'name' => [
                    'required',
                    'string',
                    Rule::unique('districts')->whereNull('deleted_at'),
                ],
            ]);
            District::create($request->all());
            return redirect()->route('district.list')->with('success', __('messages.district.district_created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('district.list')->with('error', __('messages.district.error'));
            // Handle errors here
        }
    }

    //update data
    public function update(Request $request,District $district)
    {

        try {
            $request->validate([
                'city_id' => 'required',
                'name' => [
                    'required',
                    'string',
                    Rule::unique('districts')->ignore($request->cd_id)->whereNull('deleted_at'),
                ],
            ]);

            $district = District::findOrFail($request->cd_id);
            $district->update($request->all());

            return redirect()->route('district.list')->with('success', __('messages.district.edit_created'));
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('district.list')->with('error', __('messages.district.error'));
            // Handle errors here
        }

    }

    public function destroy(Request $request)
    {
        $district = District::findOrFail($request->cd_id);
        $district->delete();
        return redirect()->route('district.list')->with('success', __('messages.district.deleted'));
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
                $city_name = $rowData[0];
                $district_name = $rowData[1];
                $city = Cities::where('name', $city_name)->first();
                $existingRow = District::where('name', $district_name)->where('city_id', $city->id)->whereNull('deleted_at')->first();
                if (!$existingRow && !empty($city)) {
                    if (!empty($district_name)) {
                        $district = new District();
                        $district->city_id = $city->id;
                        $district->name = trim($district_name);
                        $district->save();
                    }
                }
            }
            // Redirect back with success message
            return back()->with('success', __('messages.district.csvsuccess'));

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            // print_r($errors);
            // die;
            return redirect()->route('pages.vehicle-brand')->with('error', __('messages.district.error'));
            // Handle errors here
        }

    }


    public function get_districts(Request $request)
    {
        $cities_ids = $request->restricted_cities_ids;
        $all_districts = District::whereIn('city_id',$cities_ids)->orderBy('name')->get();
        return response()->json([
            'success' => 'Districts Fetched Successfully',
            'status' => 200,
            'data' => $all_districts
        ]);
    }

    public function downloadDistrictsCsv()
    {
        $filePath = public_path('sample/districts.csv');
        return response()->download($filePath, 'districts.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

}
