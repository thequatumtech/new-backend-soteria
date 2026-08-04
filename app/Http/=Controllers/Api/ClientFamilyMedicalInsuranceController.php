<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ClientFamilyMedicalInsurance,FamilyMedicalInsuranceMember,PurchasePolicy};
use App\Models\InsurancePlanModels\{InOutPatientPlan,InPatientPlan};
use Illuminate\Http\Request;
use PDF;

class ClientFamilyMedicalInsuranceController extends Controller
{
    public function storeFamilyMedicalInsurance(Request $request)
    {
        try {
            // Validate the incoming request data
            $data = $request->validate([
                'first_name'=>'nullable',
                'last_name'=>'nullable',
                'third_name'=>'nullable',
                'family_name'=>'nullable',
                'nationality'=>'nullable',
                'nationality_no'=>'nullable',
                'id_residence_no'=>'nullable',
                'birth_date'=>'nullable',
                'gender'=>'nullable',
                'marital_status'=>'nullable',
                'occupancy_work'=>'nullable',
                'city_id'=>'nullable',
                'district_id'=>'nullable',
                'street_name'=>'nullable',
                'building_no'=>'nullable',
                'company_name'=>'nullable',
                'position'=>'nullable',
                'work_nature'=>'nullable',
                'company_city_id'=>'nullable',
                'company_district_id'=>'nullable',
                'company_street_name'=>'nullable',
                'company_building_no'=>'nullable',
                'company_company_contact'=>'nullable',
                'existing_policy_status'=>'nullable',
                'existing_policy_company_name'=>'nullable',
                'existing_policy_expiry_date'=>'nullable',
                'existing_policy_card'=>'nullable',
                'height'=>'nullable',
                'wight'=>'nullable',
                'chronic_diseases_id'=>'nullable',
                'previous_operation'=>'nullable',
                'operation_details'=>'nullable',
                'pregnant_status'=>'nullable',
                'pregnant_month'=>'nullable',
                'dangerous_status'=>'nullable',
                'dangerous_id'=>'nullable',
                'passport_front_id'=>'nullable',
                'passport_back_id'=>'nullable',
                'family_book_documents'=>'nullable',
                'personal_picture_documents'=>'nullable',
                'other_documents'=>'nullable',
                'inception_date'=>'nullable',
                'expiry_date'=>'nullable',
                'insurance_type'=>'nullable',
                'insurance_class'=>'nullable',
                'inpatient_deductible_id'=>'nullable|exists:in_patient_deductibles,id',
                'outpatient_deductible_id'=>'nullable|exists:out_patient_deductibles,id',
                'no_of_visits_id'=>'nullable|exists:no_of_visits,id',
                'insurance_limit'=>'nullable',
                'insurance_type_status'=>'nullable',
                'plan_id'=>'nullable',
            ]);

            $existingMedical = PurchasePolicy::find($request->purchase_id ?? 0);
            if($data['insurance_type']==1)
            {
                $plan_data=InPatientPlan::find($data['plan_id']);
                $data['insurance_company_id']=$plan_data->insurance_company_id;    
            }
            else
            {
                $plan_data=InOutPatientPlan::find($data['plan_id']);
                $data['insurance_company_id']=$plan_data->insurance_company_id;
            }
            if ($existingMedical) {
                $Medical = ClientFamilyMedicalInsurance::find($existingMedical->policy_id);
                if ($Medical) {
                    $Medical->update($data);
                    $Medical->toArray();
                }
                $data['purchase_id']=$existingMedical->id;
                $Medical = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $Medical->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $Medical = ClientFamilyMedicalInsurance::create($data);
                if ($data['insurance_type_status']==1) {
                    $data['policy_type'] = 6;
                }else{
                    $data['policy_type'] = 7;
                }
                $data['policy_id'] = $Medical->id;
                $Medical = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientFamilyMedicalInsurance::getClientFamilyMedicalInsuranceDetails($data);
            if ($data['insurance_type_status']==1) {
                $directory = public_path('insurance_pdfs/individual_medical_insurance');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                $filename = uniqid() .'_policy_' . $Medical->id . '.pdf';
                $path = $directory . '/' . $filename;
            }else{
                $directory = public_path('insurance_pdfs/family_medical_insurance');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                $filename = uniqid() .'_policy_' . $Medical->id . '.pdf';
                $path = $directory . '/' . $filename;
            }
            
            $net_premium = $plan_data->net_premium;
            $fees_percentage = $data->fees;
            $stamps_percentage = $data->stamps;
            $sales_tax_percentage = $data->sales_tax;
            
            $fee_amount = ($net_premium * $fees_percentage) / 100;
            $stamp_amount = ($net_premium * $stamps_percentage) / 100;
            $sales_tax_amount = (($net_premium + $fee_amount + $stamp_amount) * $sales_tax_percentage) / 100;
            $gross_premium = $net_premium + $fee_amount + $stamp_amount + $sales_tax_amount;
            $commission_amount = ($gross_premium * $data->commission_percentage) / 100;
            
            $purchase['net_premium'] = $net_premium;
            $purchase['fees'] = $fee_amount;
            $purchase['stamps'] = $stamp_amount;
            $purchase['sales_tax'] = $sales_tax_amount;
            $purchase['gross_premium'] = $gross_premium;
            $purchase['commission_amount'] = $commission_amount;

            $purchase['purchase_id']=$Medical->id;
            $purchase['plan_id']=$data->plan_id;
            $purchase['plan_name']=$plan_data->plan_name;
            $purchase['policy_plan_limit']=$plan_data->limit;
            $purchase['insurance_company_id']=$data->insurance_company_id;
            $purchase['inception_date']=$data->inception_date;
            $purchase['expiry_date']=$data->expiry_date;
            $purchase['commission_percentage']=$data->commission_percentage;
            $purchase['policy_pdf_url']=$path;
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$Medical->id;
            if ($data['insurance_type_status']==1) {
                $directory = public_path('insurance_pdfs/individual_medical_insurance');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                $pdf = PDF::loadView('pdf/individual_medical_insurance', compact('data'));
                $filename = uniqid() .'_policy_' . $Medical->id . '.pdf';
                $path = $directory . '/' . $filename;
                $pdf->save($path);
                $url = url('insurance_pdfs/individual_medical_insurance/' . $filename);
            }else{
                $directory = public_path('insurance_pdfs/family_medical_insurance');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                $pdf = PDF::loadView('pdf/family_medical_insurance', compact('data'));
                $filename = uniqid() .'_policy_' . $Medical->id . '.pdf';
                $path = $directory . '/' . $filename;
                $pdf->save($path);
                $url = url('insurance_pdfs/family_medical_insurance/' . $filename);
            }
            $data['url']=$url;
            
            if(!empty($request->members))
            {
                foreach ($request->members as $single) {
                    $family_member = new FamilyMedicalInsuranceMember();
                    $family_member->client_insurance_id = $data['id'];
                    $family_member->first_name = $single['first_name'];
                    $family_member->last_name = $single['last_name'];
                    $family_member->third_name = $single['third_name'];
                    $family_member->family_name = $single['family_name'];
                    $family_member->relation = $single['relation'];
                    $family_member->nationality = $single['nationality'];
                    $family_member->nationality_no = $single['nationality_no'];
                    $family_member->id_residence_no = $single['id_residence_no'];
                    $family_member->birth_date = $single['birth_date'];
                    $family_member->gender = $single['gender'];
                    $family_member->marital_status = $single['marital_status'];
                    $family_member->occupancy_work = $single['occupancy_work'];
                    $family_member->height = $single['height'];
                    $family_member->wight = $single['wight'];
                    $family_member->chronic_diseases_id = $single['chronic_diseases_id'];
                    $family_member->previous_operation = $single['previous_operation'];
                    $family_member->operation_details = $single['operation_details'];
                    $family_member->pregnant_status = $single['pregnant_status'];
                    $family_member->pregnant_month = $single['pregnant_month'];
                    $family_member->dangerous_status = $single['dangerous_status'];
                    $family_member->dangerous_id = $single['dangerous_id'];
                    $family_member->save();
                }
            }

            $data->net_premium = $data->net_premium.'%';
            $data->fees = $data->fees.'%';
            $data->gross_premium = $data->gross_premium.'%';
            $data->sales_tax = $data->sales_tax.'%';
            $data->stamps = $data->stamps.'%';
            $data->commission_percentage = $data->commission_percentage.'%';
         
            if ($data['insurance_type_status']==1) {
                return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Individual Medical Insurance Plan successfully','data' => $data]);
            }
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Family Medical Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => array()]);
        }
    }
}
