<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api\HelperController;
use App\Http\Controllers\Controller;
use App\Models\IndividualPlanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndividualController extends Controller
{
    public function individualInsurance(Request $request)
    {
        $user = $request->user();
        $validate = Validator::make($request->all(), [
            'individual_type' => 'required',
            'row' => 'required_if:individual_type,family',
            'row.*.name' => 'required_if:individual_type,family|string',
            'row.*.dob' => 'required_if:individual_type,family|string',
            'previous_medical_detail' => 'required_if:previous_medical_condition,yes|string',
            'previous_medical_case_detail' => 'required_if:previous_medical_case,yes|string',
        ]);

        if ($validate->fails()) {
            return HelperController::sendJsonResponse(500, $validate->errors(), "Please enter valid data");
        }

        $individualPlanModel = IndividualPlanModel::where('user_id', $user->id)->first();
        if (!$individualPlanModel) {
            $individualPlanModel = new IndividualPlanModel();
            $individualPlanModel->user_id = $user->id;
        }
        $individualPlanModel->individual_type = $request->individual_type;
        if ($request->individual_type == "family") {
            $individualPlanModel->family_data = json_encode($request->row);
        }
        $individualPlanModel->age = isset($request->age) ? $request->age : null;
      
        $individualPlanModel->policy_holder = isset($request->policy_holder) ? $request->policy_holder : null;
        $individualPlanModel->national_id = isset($request->national_id) ? $request->national_id : null;
        $individualPlanModel->id_no = isset($request->id_no) ? $request->id_no : null;
        $individualPlanModel->previouse_medical_case = isset($request->previouse_medical_case) ? $request->previouse_medical_case : null;
        $individualPlanModel->medical_details = isset($request->medical_details) ? $request->medical_details : null;


        $individualPlanModel->save();

        return HelperController::sendJsonResponse(200, $individualPlanModel, "Please enter valid data");
    }
}
