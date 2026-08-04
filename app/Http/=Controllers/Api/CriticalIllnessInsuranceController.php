<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{CriticalIllnessInsurance,PurchasePolicy};
use App\Models\InsurancePlanModels\{CriticalIllnessPlan,CriticalIllnessPlanPolicyCover};
use PDF;
use Illuminate\Http\Request;

class CriticalIllnessInsuranceController extends Controller
{
    public function storeCriticalIllnessInsurance(Request $request)
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
                'beneficiary_first_name'=>'nullable',
                'beneficiary_last_name'=>'nullable',
                'beneficiary_third_name'=>'nullable',
                'marital_status'=>'nullable',
                'place_residence'=>'nullable',
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
                'company_contact'=>'nullable',
                'height'=>'nullable',
                'wight'=>'nullable',
                'chronic_diseases_id'=>'nullable',
                'previous_operation'=>'nullable',
                'operation_details'=>'nullable',
                'previous_insurance_policy'=>'nullable',
                'previous_insurance_policy_details'=>'nullable',
                'insurance_amount'=>'nullable',
                'insurance_plan'=>'nullable',
                'inception_date'=>'required',
                'expiry_date'=>'required',
                'passport_id_documents'=>'nullable',
                'insured_documents'=>'nullable',
                'plan_id'=>'nullable',
                'payment_status'=>'nullable',
            ]);
            $existingCriticalIllness = PurchasePolicy::find($request->purchase_id ?? 0);
            $plan_data=CriticalIllnessPlan::find($data['plan_id']);
            $data['insurance_company_id']=$plan_data->insurance_company_id;
            if ($existingCriticalIllness) {
                $CriticalIllness = CriticalIllnessInsurance::find($existingCriticalIllness->policy_id);
                if ($CriticalIllness) {
                    $CriticalIllness->update($data);
                    $CriticalIllness->toArray();
                }
                $data['purchase_id']=$existingCriticalIllness->id;
                $CriticalIllness = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $CriticalIllness->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $CriticalIllness = CriticalIllnessInsurance::create($data);
                $data['policy_type'] = 4;
                $data['policy_id'] = $CriticalIllness->id;
                $CriticalIllness = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = CriticalIllnessInsurance::getCriticalIllnessInsuranceDetails($data['policy_id']);

            $directory = public_path('insurance_pdfs/criticalIllness_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }            
            $filename = uniqid() .'_policy_' . $CriticalIllness->id . '.pdf';
            $path = $directory . '/' . $filename;

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

            $purchase['purchase_id']=$CriticalIllness->id;
            $purchase['plan_id']=$data->plan_id;
            $purchase['plan_name']=$plan_data->plan_name;
            $purchase['policy_plan_limit']=$plan_data->limit;
            $purchase['insurance_company_id']=$data->insurance_company_id;
            $purchase['inception_date']=$data->inception_date;
            $purchase['expiry_date']=$data->expiry_date;
            $purchase['commission_percentage']=$data->commission_percentage;
            $purchase['policy_pdf_url']=$path;
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$CriticalIllness->id;
            $pdf = PDF::loadView('pdf/criticalIllness_policy', compact('data'));
            $pdf->save($path);
            $url = url('insurance_pdfs/criticalIllness_policy/' . $filename);
            $data['url']=$url;
            
            $data->net_premium = $data->net_premium.'%';
            $data->fees = $data->fees.'%';
            $data->gross_premium = $data->gross_premium.'%';
            $data->sales_tax = $data->sales_tax.'%';
            $data->stamps = $data->stamps.'%';
            $data->commission_percentage = $data->commission_percentage.'%';
         
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Critical Illness Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    
    public function getCriticalIllnessInsurancePlan(Request $request)
    {
        try {
            $data = CriticalIllnessPlan::with('policy_covers','insurance_company')->where('plan_name','LIKE','%'.$request->limit.'%')->get();
            $data->transform(function($item){
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' .$item->id.'/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' .$item->insurance_company->id.'/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get CriticalIllness Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
}
