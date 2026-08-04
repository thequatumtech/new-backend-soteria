<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{Complaint, InsuranceCompany, LineOfBusiness, PurchasePolicy};
use App\Models\InsurancePlanModels\{LifePlan,LifePlanPolicyCover,LifePlanPricingSchedule};
use PDF;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function getInsuranceType()
    {
        try {
            $insuranceTypes = [
                1  => 'Home Insurance',
                2  => 'Office Insurance',
                3  => 'Life Insurance',
                4  => 'Critical Illness Insurance',
                5  => 'Personal Accident Insurance',
                8  => 'Pets Insurance',
                9  => 'Dental Insurance',
                10 => 'Travel Insurance',
                11 => 'Marine Insurance',
                12 => 'Motor Insurance'
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
                'message' => 'Insurance Types retrieved successfully',
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
            $data=PurchasePolicy::getPolicyCompanyByType($request);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Insurance Company Policy retrieved successfully',
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
            $data['complaint_date']=date('Y-m-d');
            $data['complaint_status_id']=1;
            $data['line_of_business_id']=$lineOfBusiness->id ?? 1;
            
            $Complaint = Complaint::create($data);
    
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Complaint added successfully',
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
            $user_id=$request->user_id;
            $data = Complaint::with('status')->where('client_id',$user_id)->get();
            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Complaint retrieved successfully',
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
