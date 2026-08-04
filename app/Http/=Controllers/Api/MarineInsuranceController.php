<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InsurancePlanModels\MarinePlan;
use App\Models\{ClientMarineInsurance,FamilyMarineInsuranceMember,PurchasePolicy};
use Illuminate\Http\Request;
use PDF;
class MarineInsuranceController extends Controller
{
    public function storeMarineInsurance(Request $request)
    {
        try {
            $data = $request->validate([
                'company_status'=>'nullable',
                'first_name'=>'nullable',
                'last_name'=>'nullable',
                'third_name'=>'nullable',
                'family_name'=>'nullable',
                'nationality'=>'nullable',
                'nationality_no'=>'nullable',
                'id_residence_no'=>'nullable',
                'birth_date'=>'nullable',
                'gender'=>'nullable',
                'company_name'=>'nullable',
                'company_reg_notional_id'=>'nullable',
                'company_reg_no'=>'nullable',
                'company_country_id'=>'nullable',
                'company_city_id'=>'nullable',
                'company_district_id'=>'nullable',
                'company_street_name'=>'nullable',
                'company_building_no'=>'nullable',
                'company_office_no'=>'nullable',
                'company_contact'=>'nullable',
                'owner_first_name'=>'nullable',
                'owner_last_name'=>'nullable',
                'owner_third_name'=>'nullable',
                'owner_family_name'=>'nullable',
                'company_owner_contact'=>'nullable',
                'company_partner_status'=>'nullable',
                'company_authorized_status'=>'nullable',
                'authorized_positions'=>'nullable',
                'company_register_status'=>'nullable',
                'register_document'=>'nullable',
                'vayage_from_id'=>'nullable',
                'through_country_id'=>'nullable',
                'destination_country_id'=>'nullable',
                'type_of_transportation'=>'nullable',
                'type_of_cover'=>'nullable',
                'item_category_id'=>'nullable',
                'item_subcategory_id'=>'nullable',
                'insurance_limit'=>'nullable',
                'bill_no'=>'nullable',
                'effective_date'=>'nullable',
                'expiry_date'=>'nullable',
                'insured_items'=>'nullable',
                'existing_policy_status'=>'nullable',
                'existing_policy_desc'=>'nullable',
                'declined_insurance_status'=>'nullable',
                'declined_insurance_desc'=>'nullable',
                'claims_accident_status'=>'nullable',
                'claims_accident_desc'=>'nullable',
                'billing_of_landing_doc'=>'nullable',
                'copy_of_invoice_doc'=>'nullable',
                'insured_id_doc'=>'nullable',
                'policy_issuer_doc'=>'nullable',
                'company_reg_owner_doc'=>'nullable',
                'career_municipality_license_doc'=>'nullable',
                'company_tax_certificate_doc'=>'nullable',
                'practice_certificate_doc'=>'nullable',
                'plan_id'=>'nullable',
                'payment_status'=>'nullable'
            ]);

            $existingMarine = PurchasePolicy::find($request->purchase_id ?? 0);
            $plan_data=MarinePlan::find($data['plan_id']);
            $data['insurance_company_id']=$plan_data->insurance_company_id;
            if ($existingMarine) {
                $Marine = ClientMarineInsurance::find($existingMarine->policy_id);
                if ($Marine) {
                    $Marine->update($data);
                    $Marine->toArray();
                }
                $data['purchase_id']=$existingMarine->id;
                $data['inception_date']=$data['effective_date'];
                $Marine = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $Marine->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $Marine = ClientMarineInsurance::create($data);
                $data['policy_type'] = 11;
                $data['policy_id'] = $Marine->id;
                $data['inception_date']=$data['effective_date'];
                $Marine = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientMarineInsurance::getMarineInsuranceDetails($data['policy_id']);
            $directory = public_path('insurance_pdfs/marine_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = uniqid() .'_policy_' . $Marine->id . '.pdf';
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

            $purchase['purchase_id']=$Marine->id;
            $purchase['plan_id']=$data->plan_id;
            $purchase['plan_name']=$plan_data->plan_name;
            $purchase['policy_plan_limit']=$plan_data->limit;
            $purchase['insurance_company_id']=$data->insurance_company_id;
            $purchase['inception_date']=$data->inception_date;
            $purchase['expiry_date']=$data->expiry_date;
            $purchase['commission_percentage']=$data->commission_percentage;
            $purchase['policy_pdf_url']=$path;
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$Marine->id;
            $pdf = PDF::loadView('pdf/marine_policy', compact('data'));
            $pdf->save($path);
            $url = url('insurance_pdfs/marine_policy/' . $filename);
            $data['url']=$url;

            $data->net_premium = $data->net_premium.'%';
            $data->fees = $data->fees.'%';
            $data->gross_premium = $data->gross_premium.'%';
            $data->sales_tax = $data->sales_tax.'%';
            $data->stamps = $data->stamps.'%';
            $data->commission_percentage = $data->commission_percentage.'%';
         
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Marine Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => array()]);
        }
    }
    public function getMarineInsurancePlan(Request $request)
    {
        try {
            $data = MarinePlan::with('policy_covers','insurance_company')->where('limit','LIKE','%'.$request->limit.'%')->get();
            $data->transform(function($item){
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' .$item->id.'/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' .$item->insurance_company->id.'/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Marine Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
}
