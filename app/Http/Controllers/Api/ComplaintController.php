<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{Complaint, InsuranceCompany, LineOfBusiness, PurchasePolicy};
use App\Models\InsurancePlanModels\{LifePlan, LifePlanPolicyCover, LifePlanPricingSchedule};
use PDF;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function getInsuranceType()
    {
        try {
            $insuranceTypes = [
                1  => __('messages.policy_types.1'),
                2  => __('messages.policy_types.2'),
                3  => __('messages.policy_types.3'),
                4  => __('messages.policy_types.4'),
                5  => __('messages.policy_types.5'),
                8  => __('messages.policy_types.8'),
                9  => __('messages.policy_types.9'),
                10 => __('messages.policy_types.10'),
                11 => __('messages.policy_types.11'),
                12 => __('messages.policy_types.12'),
            ];

            // Transform the array to the required structure
            $insuranceTypeList = [];
            foreach ($insuranceTypes as $id => $name) {
                $insuranceTypeList[] = [
                    'id'   => $id,
                    'name' => $name
                ];
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.insurance_types_retrieved_successfully'),
                'data' => $insuranceTypeList,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    public function getInsuranceCompany(Request $request)
    {
        try {
            // dd($request->insurance_type_id);
            // \DB::enableQueryLog(); 
            $data = PurchasePolicy::getPolicyCompanyByType($request);
            // dd(\DB::getQueryLog());
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.insurance_company_policy_retrieved_successfully'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
    public function storeComplaint(Request $request)
    {
        try {
            $data = $request->validate([
                'insurance_company_id' => 'required',
                'complaint_message' => 'required',
                'insurance_type' => 'required',
                'purchase_id' => 'required',
            ]);
            $lineOfBusiness = LineOfBusiness::where('name', 'LIKE', '%' . $data['insurance_type'])->first();
            $data['client_id'] = $request->user_id;
            $data['complaint_number'] = rand(0000000000, 9999999999);
            $data['complaint_date'] = date('Y-m-d');
            $data['complaint_status_id'] = 1;
            $data['line_of_business_id'] = $lineOfBusiness->id ?? 1;

            $Complaint = Complaint::create($data);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.complaint_added_successfully'),
                'data' => $Complaint,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
    public function getComplaintList(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $data = Complaint::with('status')->where('client_id', $user_id)->get();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.complaint_retrieved_successfully'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }
}
