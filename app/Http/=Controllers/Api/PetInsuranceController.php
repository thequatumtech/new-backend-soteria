<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\InsurancePlanModels\PetPlan;
use App\Models\{ClientPetsInsurance,PurchasePolicy,InsuranceCompany};
use Illuminate\Http\Request;
use PDF;

class PetInsuranceController extends Controller
{
    public function storePetsInsurance(Request $request)
    {
        try 
        {
            $data = $request->validate([
                'id'=>'nullable',
                'pets_type'=>'nullable',
                'first_name'=>'nullable',
                'last_name'=>'nullable',
                'third_name'=>'nullable',
                'family_name'=>'nullable',
                'nationality_no'=>'nullable',
                'id_residence_no'=>'nullable',
                'birth_date'=>'nullable',
                'pets_name'=>'nullable',
                'pets_dob'=>'nullable',
                'gender'=>'nullable',
                'type_of_pets'=>'nullable',
                'breed'=>'nullable',
                'pets_existing_condition_status'=>'nullable',
                'pets_existing_condition'=>'nullable',
                'insurance_limit'=>'nullable',
                'inception_date'=>'nullable',
                'expiry_date'=>'nullable',
                'plan_id'=>'required',
                'vaccine_document'=>'nullable',
                'pets_picture'=>'nullable',
                'pets_passport'=>'nullable',
                'personal_picture_documents'=>'nullable',
                'pets_permit'=>'nullable',
                'payment_status'=>'nullable',
            ]);
            $existingPet = PurchasePolicy::find($request->purchase_id ?? 0);
            $plan_data=PetPlan::find($data['plan_id']);
            $data['insurance_company_id']=$plan_data->insurance_company_id ?? 0;
            if ($existingPet) {
                $pets = ClientPetsInsurance::find($existingPet->policy_id);
                if ($pets) {
                    $pets->update($data);
                    $pets->toArray();
                }
                $data['purchase_id']=$existingPet->id;
                $pet = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $pet->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $pet = ClientPetsInsurance::create($data);
                $data['policy_type'] = 8;
                $data['policy_id'] = $pet->id;
                $pet = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientPetsInsurance::getPetsInsuranceDetails($data['policy_id']);
            $directory = public_path('insurance_pdfs/pet_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = uniqid() .'_policy_' . $pet->id . '.pdf';
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

            $purchase['purchase_id']=$pet->id;
            $purchase['plan_id']=$data->plan_id;
            $purchase['plan_name']=$plan_data->plan_name;
            $purchase['policy_plan_limit']=$plan_data->limit;
            $purchase['insurance_company_id']=$plan_data->insurance_company_id;
            $purchase['inception_date']=$data->inception_date;
            $purchase['expiry_date']=$data->expiry_date;
            $purchase['commission_percentage']=$plan_data->commission_percentage;
            $purchase['policy_pdf_url']=$path;
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$pet->id;
            $pdf = PDF::loadView('pdf/pet_policy', compact('data'));
            $pdf->save($path);
            $url = url('insurance_pdfs/pet_policy/' . $filename);
            $data['url']=$url;
            
            $data->net_premium = $data->net_premium.'%';
            $data->fees = $data->fees.'%';
            $data->gross_premium = $data->gross_premium.'%';
            $data->sales_tax = $data->sales_tax.'%';
            $data->stamps = $data->stamps.'%';
            $data->commission_percentage = $data->commission_percentage.'%';
         
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Pets Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => array()]);
        }
    }
    public function getPetsInsurancePlan(Request $request)
    {
        try {
            $data = PetPlan::with('policy_covers','insurance_company')->where('plan_name','LIKE','%'.$request->limit.'%')->get();
            $data->transform(function($item){
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' .$item->id.'/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' .$item->insurance_company->id.'/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Pets Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
}
