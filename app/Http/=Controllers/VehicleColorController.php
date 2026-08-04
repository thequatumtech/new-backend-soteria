<?php

namespace App\Http\Controllers;

use App\Models\VehicleColor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VehicleColorController extends Controller
{
    public function index()
    {
        $vehicleColors = VehicleColor::all();

        return view('content.vehicleColor.vehicleColor', ['vehicleColors' => $vehicleColors]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_colors')->whereNull('deleted_at'),
            ],
        ]);
        VehicleColor::create($request->all());

        return redirect()->route('pages.vehicle-color')->with('success', __('messages.table_headers.color_created'));
    }

    public function edit($id)
    {
        $vehicleColor = VehicleColor::findOrFail($id);
        return response()->json([
            'vehicleColor' => $vehicleColor,
            'status' => 200
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'vehicle_color_id' => 'required|numeric|exists:vehicle_colors,id',
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_colors', 'name')
                    ->ignore($request->vehicle_color_id) // Exclude the current record by ID
                    ->whereNull('deleted_at'), // Only check rows where deleted_at is NULL
            ],
        ]);

        $vehicleColor = VehicleColor::findOrFail($request->vehicle_color_id);
        $vehicleColor->name = $request->name;
        $vehicleColor->save();

        return redirect()->route('pages.vehicle-color')->with('success', __('messages.table_headers.color_updated'));
    }

    public function destroy(Request $request)
    {
        $vehicleColor = VehicleColor::findOrFail($request->delete_vehicle_color_id);
        $vehicleColor->delete();

        return redirect()->route('pages.vehicle-color')->with('success', __('messages.table_headers.color_deleted'));
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
                    $existingRow = VehicleColor::where('name', $name)->whereNull('deleted_at')->first();;

                    if(!$existingRow){
                        if(!empty($name)){
                            $color = new VehicleColor();
                            $color->name = trim($name);
                            $color->save();
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
            return redirect()->route('pages.vehicle-color')->with('error', __('messages.country.error'));
            // Handle errors here
        }
    }

    public function downloadColoursCsv()
    {
        $filePath = public_path('sample/colors.csv');
        return response()->download($filePath, 'colors.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }


}
