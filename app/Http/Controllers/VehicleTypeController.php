<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\VehicleBrand;
class VehicleTypeController extends Controller
{
    public function index()
    {
        $vehicleTypes = VehicleType::all();

        $brands = VehicleBrand::all();
        return view('content.vehicleType.vehicleType', ['vehicleTypes' => $vehicleTypes,'brands' => $brands]);
    }

    // public function create(Request $request)
    // {
    //     $request->validate([
    //         'name' => [
    //             'required',
    //             'string',
    //             'max:50',
    //             Rule::unique('vehicle_types')->whereNull('deleted_at'),
    //         ],
    //     ]);

    //     VehicleType::create($request->all());

    //     return redirect()->route('pages.vehicle-type')->with('success', __('messages.table_headers.type_created'));
    // }
    public function create(Request $request)
    {
        $validated = $request->validate([
            'vehicle_brand_id' => ['required', 'exists:vehicle_brands,id'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_types')->whereNull('deleted_at'),
            ],
        ], [
            // Custom messages
            'vehicle_brand_id.required' => __('messages.validation.required_brand'),
            'vehicle_brand_id.exists' => __('messages.validation.invalid_brand'),
            'name.required' => __('messages.validation.required_name'),
            'name.string' => __('messages.validation.string_name'),
            'name.max' => __('messages.validation.max_name'),
            'name.unique' => __('messages.validation.unique_name'),
        ]);

        VehicleType::create($validated);

        return redirect()->route('pages.vehicle-type')->with('success', __('messages.table_headers.type_created'));
    }

    public function edit($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return response()->json([
            'vehicleType' => $vehicleType,
            'status' => 200
        ]);
    }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'vehicle_type_id' => 'required|numeric|exists:vehicle_types,id',
    //         'name' => [
    //             'required',
    //             'string',
    //             'max:50',
    //             Rule::unique('vehicle_types', 'name')
    //                 ->ignore($request->vehicle_type_id) // Exclude the current record by ID
    //                 ->whereNull('deleted_at'), // Only check rows where deleted_at is NULL
    //         ],
    //     ]);

    //     $vehicleType = VehicleType::findOrFail($request->vehicle_type_id);
    //     $vehicleType->update($request->all());

    //     return redirect()->route('pages.vehicle-type')->with('success', __('messages.table_headers.type_updated'));
    // }
    public function update(Request $request)
    {
        $request->validate([
            'vehicle_type_id' => 'required|numeric|exists:vehicle_types,id',
            'vehicle_brand_id' => 'required|exists:vehicle_brands,id',
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('vehicle_types', 'name')
                    ->ignore($request->vehicle_type_id)
                    ->whereNull('deleted_at'),
            ],
        ], [
            'vehicle_type_id.required' => __('messages.validation.required_type_id'),
            'vehicle_brand_id.required' => __('messages.validation.required_brand'),
            'vehicle_brand_id.exists' => __('messages.validation.invalid_brand'),
            'name.required' => __('messages.validation.required_name'),
            'name.string' => __('messages.validation.string_name'),
            'name.max' => __('messages.validation.max_name'),
            'name.unique' => __('messages.validation.unique_name'),
        ]);

        $vehicleType = VehicleType::findOrFail($request->vehicle_type_id);
        $vehicleType->update($request->only(['name', 'vehicle_brand_id']));

        return redirect()->route('pages.vehicle-type')->with('success', __('messages.table_headers.type_updated'));
    }

    public function destroy(Request $request)
    {
        $vehicleType = VehicleType::findOrFail($request->delete_vehicle_type_id);
        $vehicleType->delete();

        return redirect()->route('pages.vehicle-type')->with('success', __('messages.table_headers.type_deleted'));
    }
}
