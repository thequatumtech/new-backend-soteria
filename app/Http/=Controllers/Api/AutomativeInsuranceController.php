<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AutomativeInsuranceModel;
use Illuminate\Http\Request;

class AutomativeInsuranceController extends Controller
{
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'vahicle_type' => 'required|string|max:255',
            'vahicle_brand' => 'required|string|max:255',
            'vahicle_category' => 'required|string|max:255',
            'vahicle_color' => 'required|string|max:255',
            'vahicle_reg_no' => 'required|string|max:255',
            'vahicle_photo' => 'nullable|string|max:255',
            'insurance_type' => 'required|string|max:255',
            'policy_holder' => 'required|string|max:255',
            'national_id' => 'nullable|numeric',
            'chassis_no' => 'nullable|string|max:255',
            'no_of_prev_accident' => 'nullable|numeric',
            'no_of_passanger' => 'nullable|numeric',
            'engine_type' => 'nullable|string|max:255',
            'engine_capacity' => 'nullable|string|max:255',
            'moto_engine_no' => 'nullable|string|max:255',
            'manufacture_date' => 'nullable|date',
            'total_tickets' => 'nullable|numeric',
            'insurance_amount' => 'nullable|numeric',
        ]);

        // Create a new marine insurance record
        $automativeInsurance = AutomativeInsuranceModel::create($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Automative insurance record created successfully', 'data' => $automativeInsurance], 201);
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'vahicle_type' => 'required|string|max:255',
            'vahicle_brand' => 'required|string|max:255',
            'vahicle_category' => 'required|string|max:255',
            'vahicle_color' => 'required|string|max:255',
            'vahicle_reg_no' => 'required|string|max:255',
            'vahicle_photo' => 'nullable|string|max:255',
            'insurance_type' => 'required|string|max:255',
            'policy_holder' => 'required|string|max:255',
            'national_id' => 'nullable|numeric',
            'chassis_no' => 'nullable|string|max:255',
            'no_of_prev_accident' => 'nullable|numeric',
            'no_of_passanger' => 'nullable|numeric',
            'engine_type' => 'nullable|string|max:255',
            'engine_capacity' => 'nullable|string|max:255',
            'moto_engine_no' => 'nullable|string|max:255',
            'manufacture_date' => 'nullable|date',
            'total_tickets' => 'nullable|numeric',
            'insurance_amount' => 'nullable|numeric',
        ]);

        // Find the marine insurance record by ID
        $automativeInsurance = AutomativeInsuranceModel::findOrFail($id);

        // Update the record with the validated data
        $automativeInsurance->update($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'Automative insurance record updated successfully', 'data' => $automativeInsurance], 200);
    }
}
