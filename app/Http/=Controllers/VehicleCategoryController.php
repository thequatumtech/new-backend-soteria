<?php

namespace App\Http\Controllers;

use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VehicleCategoryController extends Controller
{
    public function index()
    {
        $all_brands = VehicleBrand::orderBy('name')->get();
        $all_categories = VehicleCategory::all();
        return view('content.vehicleCategory.vehicle-category',compact('all_categories','all_brands'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                // 'age' => 'required|integer|max:100|unique:ages',
                'vehicle_brand_id' => [
                    'required',
                    'integer',
                ],
                'name' => [
                    'required',
                    'max:100',
                ]
            ]);
            $vehicle_category = new VehicleCategory();
            $vehicle_category->vehicle_brand_id = $request->vehicle_brand_id;
            $vehicle_category->name = $request->name;
            $vehicle_category->save();

            return back()->with('success', __('messages.vehicle_category.created'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return back()->with('error', __('messages.vehicle_category.error'));
            // Handle errors here
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'vehicle_category_id' => [
                    'required',
                    'integer',
                ],
                'vehicle_brand_id' => [
                    'required',
                    'integer',
                ],
                'name' => [
                    'required',
                    'max:100',
                ]
            ]);
            $vehicle_category = VehicleCategory::find($request->vehicle_category_id);
            $vehicle_category->vehicle_brand_id = $request->vehicle_brand_id;
            $vehicle_category->name = $request->name;
            $vehicle_category->save();

            return back()->with('success', __('messages.vehicle_category.updated'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return back()->with('error', __('messages.vehicle_category.error'));
            // Handle errors here
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'vehicle_category_id' => [
                    'required',
                    'integer',
                ]
            ]);
            VehicleCategory::find($request->vehicle_category_id)->delete();
            return back()->with('success', __('messages.vehicle_category.deleted'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return back()->with('error', __('messages.vehicle_category.delete_error'));
            // Handle errors here
        }
    }

    //store data with csv
    // public function csv(Request $request)
    // {
    //     try {

    //         // Validate the uploaded file
    //         $request->validate([
    //             'csv_file' => 'required|mimes:csv,txt'
    //         ]);

    //         // Read the CSV file
    //         $file = $request->file('csv_file');
    //         $csvData = file_get_contents($file);

    //         // Remove BOM if present
    //         $csvData = preg_replace('/^\xEF\xBB\xBF/', '', $csvData);

    //         // Parse CSV data
    //         $rows = array_filter(explode("\n", $csvData)); // Remove empty rows
    //         $parsedData = [];
    //         foreach ($rows as $row) {
    //             $data = str_getcsv($row);
    //             if (count($data) === 2) { // Ensure there are exactly 2 columns
    //                 $parsedData[] = $data;
    //             }
    //         }
    //         foreach ($parsedData as $rowData) {
    //             $brand_name = $rowData[0];
    //             $category_name = $rowData[1];
    //             $brand = VehicleBrand::where('name', $brand_name)->first();
    //             $existingRow = VehicleCategory::where('name', $category_name)->where('vehicle_brand_id', $brand->id)->whereNull('deleted_at')->first();
    //             if (!$existingRow && !empty($brand)) {
    //                 if (!empty($category_name)) {
    //                     $vehicle_category = new VehicleCategory();
    //                     $vehicle_category->vehicle_brand_id = $brand->id;
    //                     $vehicle_category->name = trim($category_name);
    //                     $vehicle_category->save();
    //                 }
    //             }
    //         }
    //         // Redirect back with success message
    //         return back()->with('success', __('messages.district.csvsuccess'));

    //     } catch (ValidationException $e) {
    //         // Validation failed, handle the error
    //         $errors = $e->validator->errors()->all();
    //         // print_r($errors);
    //         // die;
    //         return redirect()->route('pages.vehicle-brand')->with('error', __('messages.district.error'));
    //         // Handle errors here
    //     }

    // }
       public function csv(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|mimes:csv,txt'
            ]);

            $file = $request->file('csv_file');
            $csvData = file_get_contents($file);
            $csvData = preg_replace('/^\xEF\xBB\xBF/', '', $csvData);

            $rows = array_filter(preg_split("/\r\n|\n|\r/", $csvData));
            $parsedData = [];

            foreach ($rows as $row) {
                $data = str_getcsv(trim($row), "\t"); // 👈 use tab as delimiter
                if (count($data) >= 2) {
                    $parsedData[] = [trim($data[0]), trim($data[1])];
                }
            }

            // dd($parsedData); // Check here if needed

            foreach ($parsedData as $rowData) {
                $brand_name = ucwords(strtolower($rowData[0]));
                $category_name = ucwords(strtolower($rowData[1]));

                $brand = VehicleBrand::firstOrCreate(['name' => $brand_name]);

                $existingCategory = VehicleCategory::where('name', $category_name)
                    ->where('vehicle_brand_id', $brand->id)
                    ->whereNull('deleted_at')
                    ->first();

                if (!$existingCategory && !empty($category_name)) {
                    $vehicle_category = new VehicleCategory();
                    $vehicle_category->vehicle_brand_id = $brand->id;
                    $vehicle_category->name = $category_name;
                    $vehicle_category->save();
                }
            }

            return back()->with('success', __('messages.district.csvsuccess'));
        } catch (ValidationException $e) {
            return redirect()->route('pages.vehicle-brand')->with('error', __('messages.district.error'));
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function downloadCategoriesCsv()
    {
        $filePath = public_path('sample/categories.csv');
        return response()->download($filePath, 'categories.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }


}
