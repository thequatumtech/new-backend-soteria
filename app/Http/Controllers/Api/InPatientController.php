<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientInPatientInsurance;
use App\Models\InsurancePlanModels\{InPatientPlan,InOutPatientPlan};
use Illuminate\Http\Request;

class InPatientController extends Controller
{
    public function storInPatientInsurance(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'inception_date'=>'required',
                'plan_id'=>'required',
            ]);
            // Helper function to generate unique file name
            $generateUniqueFileName = function($file) {
                return uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            };
    
            // Process file uploads
            if ($request->hasFile('photo_documents_1')) {
                $validatedData['photo_documents_1'] = $request->file('photo_documents_1')->move('public/InPatientPlan_insurance/photo_documents_1', $generateUniqueFileName($request->file('photo_documents_1')));
            }
            if ($request->hasFile('photo_documents_2')) {
                $validatedData['photo_documents_2'] = $request->file('photo_documents_2')->move('public/InPatientPlan_insurance/photo_documents_2', $generateUniqueFileName($request->file('photo_documents_2')));
            }
            if ($request->hasFile('photo_documents_3')) {
                $validatedData['photo_documents_3'] = $request->file('photo_documents_3')->move('public/InPatientPlan_insurance/photo_documents_3', $generateUniqueFileName($request->file('photo_documents_3')));
            }

    
            // Set additional fields
            $validatedData['client_id'] = $request->user_id;
            $validatedData['police_no'] = rand(10000000, 99999999);
    
            // Create the InPatientPlan Insurance record
            $InPatientPlan = ClientInPatientInsurance::create($validatedData);
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add InPatientPlan Insurance Plan successfully','data' => $InPatientPlan]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
 private function cleanNumber($value)
    {
        if ($value === null) return 0;

        $clean = preg_replace('/[^\d.]/', '', $value);

        return is_numeric($clean) ? (float)$clean : 0;
    }    
    public function getInPatientInsurancePlan(Request $request)
    {
        try {
            $data = InPatientPlan::with('policy_covers', 'insurance_company')
                ->whereHas('insurance_company', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->where('limit', 'LIKE', '%' . $request->limit . '%')
                ->get();
            $data->transform(function ($item) {
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get In-Patient Insurance Plan successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }

    public function getOutPatientInsurancePlan(Request $request)
    {
        try {
            $data = InOutPatientPlan::with('policy_covers', 'insurance_company')
                ->whereHas('insurance_company', function ($q) {
                    $q->whereNull('deleted_at');
                })
                ->where('limit', 'LIKE', '%' . $request->limit . '%')
                ->get();
            $data->transform(function ($item) {
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' . $item->id . '/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' . $item->insurance_company->id . '/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get In & Out Patient Insurance Plan successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }
    
}
