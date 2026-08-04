<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{ClientHomeInsurance,PurchasePolicy};
use App\Models\InsurancePlanModels\{HomePlan,HomePlanPolicyCover};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HomeInsuranceController extends Controller
{
    public function storeHomeInsurance(Request $request)
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
                'place_of_residence'=>'nullable',
                'home_type'=>'nullable',
                'no_of_floor'=>'nullable',
                'no_of_room'=>'nullable',
                'size_of_apartment'=>'nullable',
                'no_of_residence'=>'nullable',
                'home_category'=>'nullable',
                'block_no'=>'nullable',
                'plate_no'=>'nullable',
                'plot_no'=>'nullable',
                'country_id'=>'nullable',
                'city_id'=>'nullable',
                'district_id'=>'nullable',
                'street_name'=>'nullable',
                'building_no'=>'nullable',
                'company_name'=>'nullable',
                'city_id_2'=>'nullable',
                'position'=>'nullable',
                'work_nature'=>'nullable',
                'previous_policy'=>'nullable',
                'company_declined_to_issue'=>'nullable',
                'claims_accidents_past'=>'nullable',
                'protection_system'=>'nullable',
                'insurance_limit'=>'nullable',
                'plan_id'=>'nullable',
                'effective_date'=>'nullable',
                'expiry_date'=>'nullable',
                'rent_contract'=>'nullable',
                'property_document'=>'nullable',
                'content_document'=>'nullable',
                'payment_status'=>'nullable',
            ]);
             // /new
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
            $existingHome = PurchasePolicy::find($request->purchase_id ?? 0);
            $plan_data=HomePlan::find($data['plan_id']);
            $data['insurance_company_id']=$plan_data->insurance_company_id;
            if ($existingHome) {
                $home = ClientHomeInsurance::find($existingHome->policy_id);
                if ($home) {
                    $home->update($data);
                    $home->toArray();
                }
                $data['purchase_id']=$existingHome->id;
                $data['inception_date']=$data['effective_date'];
                $home = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $home->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $home = ClientHomeInsurance::create($data);
                $data['policy_type'] = 1;
                $data['policy_id'] = $home->id;
                $data['inception_date']=$data['effective_date'];
                $home = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientHomeInsurance::getHomeInsuranceDetails($data['policy_id']);
            $directory = public_path('insurance_pdfs/home_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = uniqid() .'_policy_' . $home->id . '.pdf';
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
            $purchase['purchase_id'] = $home->id;
            $purchase['plan_id'] = $data->plan_id;
            $purchase['plan_name'] = $plan_data->plan_name;
            $purchase['policy_plan_limit'] = $plan_data->limit;
            $purchase['insurance_company_id'] = $data->insurance_company_id;
            $purchase['inception_date'] = $data->effective_date;
            $purchase['expiry_date'] = $data->expiry_date;
            $purchase['commission_percentage'] = $data->commission_percentage;
            $purchase['policy_pdf_url'] = $path;
            
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$home->id;
            $pdf = Pdf::loadView('pdf/home_policy', compact('data'));
            $pdf->save($path);
            $url = url('insurance_pdfs/home_policy/' . $filename);
            $data['url']=$url;

            $data->net_premium = $data->net_premium.'%';
            $data->fees = $data->fees.'%';
            $data->gross_premium = $data->gross_premium.'%';
            $data->sales_tax = $data->sales_tax.'%';
            $data->stamps = $data->stamps.'%';
            $data->commission_percentage = $data->commission_percentage.'%';
         
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Home Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    
    public function getHomeInsurancePlan(Request $request)
    {
        try {
            $data = HomePlan::with('policy_covers','insurance_company')->where('plan_name','LIKE','%'.$request->limit.'%')->get();
            $data->transform(function($item){
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' .$item->id.'/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' .$item->insurance_company->id.'/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Home Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
}
