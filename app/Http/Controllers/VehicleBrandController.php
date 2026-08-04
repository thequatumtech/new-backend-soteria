<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VehicleBrandController extends Controller
{
    public function index()
    {
        $vehicleBrands = VehicleBrand::all();

        return view('content.vehicleBrand.vehicle', ['vehicleBrands' => $vehicleBrands]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_brands')->whereNull('deleted_at'),
            ],
        ]);

        $request->validate([
            'name' => 'required|string|max:50|unique:vehicle_brands',
        ]);

        VehicleBrand::create($request->all());

        return redirect()->route('pages.vehicle-brand')->with('success', __('messages.table_headers.brand_created'));
    }

    public function edit($id)
    {
        $vehicleBrand = VehicleBrand::findOrFail($id);
        return response()->json([
            'vehicleBrand' => $vehicleBrand,
            'status' => 200
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'vehicle_brand_id' => 'required|numeric|exists:vehicle_brands,id',
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_brands', 'name')
                    ->ignore($request->vehicle_brand_id) // Exclude the current record by ID
                    ->whereNull('deleted_at'), // Only check rows where deleted_at is NULL
            ],
        ]);

        $vehicleBrand = VehicleBrand::findOrFail($request->vehicle_brand_id);
        $vehicleBrand->name = $request->name;
        $vehicleBrand->save();

        return redirect()->route('pages.vehicle-brand')->with('success', __('messages.table_headers.brand_updated'));
    }

    public function destroy(Request $request)
    {
        $vehicleBrand = VehicleBrand::findOrFail($request->delete_vehicle_brand_id);
        VehicleCategory::where('vehicle_brand_id', $vehicleBrand->id)->delete();
        $vehicleBrand->delete();

        return redirect()->route('pages.vehicle-brand')->with('success', __('messages.table_headers.brand_deleted'));
    }

    public function getCategory()
    {
        return "OK";
    }

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
                    $existingRow = VehicleBrand::where('name', $name)->whereNull('deleted_at')->first();;

                    if(!$existingRow){
                        if(!empty($name)){
                            $vehicleBrand = new VehicleBrand();
                            $vehicleBrand->name = trim($name);
                            $vehicleBrand->save();
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

    public function downloadBrandsCsv()
    {
        $filePath = public_path('sample/brands.csv');
        return response()->download($filePath, 'brands.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

}
