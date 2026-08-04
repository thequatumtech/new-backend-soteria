<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{ClientOfficeInsurance,PurchasePolicy};
use App\Models\InsurancePlanModels\{OfficePlan,OfficePlanPolicyCover};
use PDF;
use Illuminate\Http\Request;

class OfficeInsuranceController extends Controller
{
    public function storeOfficeInsurance(Request $request)
    {
        try {

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
                'place_residence'=>'nullable',
                'company_name'=>'nullable',
                'company_register_national_id'=>'nullable',
                'company_register_id'=>'nullable',
                'office_type'=>'nullable',
                'no_of_floor'=>'nullable',
                'no_of_room'=>'nullable',
                'size_of_apartment'=>'nullable',
                'age_of_apartment'=>'nullable',
                'no_of_residence'=>'nullable',
                'office_category'=>'nullable',
                'block_no'=>'nullable',
                'plate_no'=>'nullable',
                'plot_no'=>'nullable',
                'effective_date'=>'nullable',
                'expiry_date'=>'nullable',
                'no_of_employee'=>'nullable',
                'country_id'=>'nullable',
                'city_id'=>'nullable',
                'district_id'=>'nullable',
                'street_name'=>'nullable',
                'building_no'=>'nullable',
                'office_no'=>'nullable',
                'company_telephone'=>'nullable',
                'company_owner_name'=>'nullable',
                'company_owner_telephone'=>'nullable',
                'partner_company_status'=>'nullable',
                'authorized_insurance_police_status'=>'nullable',
                'auth_company_register_status'=>'nullable',
                'provious_insurance_policy'=>'nullable',
                'insurance_declined_issue_status'=>'nullable',
                'claims_5_year_status'=>'nullable',
                'protection_system'=>'nullable',
                'insurance_limit'=>'nullable',
                'insurance_plan'=>'nullable',
                'inception_date'=>'nullable',
                'insurance_expiry_date'=>'nullable',
                'rent_contract_documents'=>'nullable',
                'property_photo_documents'=>'nullable',
                'contents_documents'=>'nullable',
                'policy_issuer_documents'=>'nullable',
                'company_owner_documents'=>'nullable',
                'career_municipality_license_documents'=>'nullable',
                'owner_id_documents'=>'nullable',
                'practice_documents'=>'nullable',
                'company_tax_certi_documents'=>'nullable',
                'plan_id'=>'required',
            ]);
              // new
              if ($data['country_id']) {
                $country = \App\Models\Country  ::find($data['country_id']);
                if (!$country) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected country does not exist.',
                        'data' => []
                    ], 422);
                }
            }
            
            if ($data['country_id'] && $data['city_id']) {
                $city = \App\Models\Cities::where('id', $data['city_id'])
                    ->where('country_id', $data['country_id'])
                    ->first();
            
                if (!$city) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected city does not belong to the selected country.',
                        'data' => []
                    ], 422);
                }
            }
            
            if ($data['city_id'] && $data['district_id']) {
                $district = \App\Models\District::where('id', $data['district_id'])
                    ->where('city_id', $data['city_id'])
                    ->first();
            
                if (!$district) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected district does not belong to the selected city.',
                        'data' => []
                    ], 422);
                }
            }
            // end
            $existingOffice = PurchasePolicy::find($request->purchase_id ?? 0);
            $plan_data=OfficePlan::find($data['plan_id']);
            $data['insurance_company_id']=$plan_data->insurance_company_id;
            if ($existingOffice) {
                $office = ClientOfficeInsurance::find($existingOffice->policy_id);
                if ($office) {
                    $office->update($data);
                    $office->toArray();
                }
                $data['purchase_id']=$existingOffice->id;
                $office = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $office->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $office = ClientOfficeInsurance::create($data);
                $data['policy_type'] = 2;
                $data['policy_id'] = $office->id;
                $office = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientOfficeInsurance::getOfficeInsuranceDetails($data['policy_id']);
            $directory = public_path('insurance_pdfs/office_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = uniqid() .'_policy_' . $office->id . '.pdf';
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

            $purchase['purchase_id']=$office->id;
            $purchase['plan_id']=$data->plan_id;
            $purchase['plan_name']=$plan_data->plan_name;
            $purchase['policy_plan_limit']=$plan_data->limit;
            $purchase['insurance_company_id']=$data->insurance_company_id;
            $purchase['inception_date']=$data->effective_date;
            $purchase['expiry_date']=$data->expiry_date;
            $purchase['commission_percentage']=$data->commission_percentage;
            $purchase['policy_pdf_url']=$path;
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$office->id;
            $pdf = PDF::loadView('pdf/office_policy', compact('data'));
            $pdf->save($path);
            $url = url('insurance_pdfs/office_policy/' . $filename);
            $data['url']=$url;

            $data->net_premium = $data->net_premium.'%';
            $data->fees = $data->fees.'%';
            $data->gross_premium = $data->gross_premium.'%';
            $data->sales_tax = $data->sales_tax.'%';
            $data->stamps = $data->stamps.'%';
            $data->commission_percentage = $data->commission_percentage.'%';
         

            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Office Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    
    public function getOfficeInsurancePlan(Request $request)
    {
        try {
            // $data = OfficePlan::with('policy_covers','insurance_company')->where('plan_name','LIKE','%'.$request->limit.'%')->get();
            $limits=json_decode($request->limit);
            $data=OfficePlan::with('policy_covers', 'insurance_company')->limit($limits)->get();
    
            $data->transform(function($item){
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' .$item->id.'/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' .$item->insurance_company->id.'/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Office Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
}
