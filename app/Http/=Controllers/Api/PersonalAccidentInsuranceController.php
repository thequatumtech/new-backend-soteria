<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ClientPersonalAccidentInsurance,PurchasePolicy};
use App\Models\InsurancePlanModels\PersonalAccidentPlan;
use Illuminate\Http\Request;
use PDF;
class PersonalAccidentInsuranceController extends Controller
{
    public function storePersonalAccidentInsurance(Request $request)
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
                'place_residence'=>'nullable',
                'company_name'=>'nullable',
                'position'=>'nullable',
                'work_nature'=>'nullable',
                'city_id'=>'nullable',
                'district_id'=>'nullable',
                'street_name'=>'nullable',
                'building_no'=>'nullable',
                'company_contact'=>'nullable',
                'company_city_id'=>'nullable',
                'inception_date'=>'nullable',
                'inception_period'=>'nullable',
                'occupany_type_work'=>'nullable',
                'photo_documents_1'=>'nullable',
                'photo_documents_2'=>'nullable',
                'photo_documents_3'=>'nullable',
                'photo_documents_4'=>'nullable',
                'dangerous_field'=>'nullable',
                'plan_id'=>'nullable',
                'payment_status'=>'nullable',
            ]);
            $existingPersonalAccident = PurchasePolicy::find($request->purchase_id ?? 0);
            $plan_data=PersonalAccidentPlan::find($data['plan_id']);
            $data['insurance_company_id']=$plan_data->insurance_company_id;
            $policy_period_day = $plan_data->inception_period ?? 0;
            $effective_date = $data['inception_date'];
            $expiry_date = date('Y-m-d', strtotime("+$policy_period_day days", strtotime($effective_date)));
            if ($request->hasFile('photo_documents_1')) {
                $file = $request->file('photo_documents_1');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('documents'), $fileName);
                $data['photo_documents_1'] = 'documents/' . $fileName;
            }
            if ($request->hasFile('photo_documents_2')) {
                $file = $request->file('photo_documents_2');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('documents'), $fileName);
                $data['photo_documents_2'] = 'documents/' . $fileName;
            }
            if ($request->hasFile('photo_documents_3')) {
                $file = $request->file('photo_documents_3');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('documents'), $fileName);
                $data['photo_documents_3'] = 'documents/' . $fileName;
            }
            
            if ($request->hasFile('photo_documents_4')) {
                $file = $request->file('photo_documents_4');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('documents'), $fileName);
                $data['photo_documents_4'] = 'documents/' . $fileName;
            }
                   
            if ($request->has('dangerours_field')) {
                $data['dangerous_field'] = is_array($request->dangerours_field) ? implode(',', $request->dangerours_field) : $request->dangerours_field;
            }
            if ($existingPersonalAccident) {
                $PersonalAccident = ClientPersonalAccidentInsurance::find($existingPersonalAccident->policy_id);
                if ($PersonalAccident) {
                    $PersonalAccident->update($data);
                    $PersonalAccident->toArray();
                }
                $data['purchase_id']=$existingPersonalAccident->id;
                $data['expiry_date']=$data['inception_date'];
                $PersonalAccident = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $PersonalAccident->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $PersonalAccident = ClientPersonalAccidentInsurance::create($data);
                $data['policy_type'] = 5;
                $data['policy_id'] = $PersonalAccident->id;
                $data['expiry_date']=$data['inception_date'];
                $PersonalAccident = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientPersonalAccidentInsurance::getClientPersonalAccidentInsuranceDetails($data['policy_id']);
            $directory = public_path('insurance_pdfs/personal_accident_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = uniqid() .'_policy_' . $PersonalAccident->id . '.pdf';
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

            $purchase['purchase_id']=$PersonalAccident->id;
            $purchase['plan_id']=$data->plan_id;
            $purchase['plan_name']=$plan_data->plan_name;
            $purchase['policy_plan_limit']=$plan_data->limit;
            $purchase['insurance_company_id']=$data->insurance_company_id;
            $purchase['inception_date']=$data->inception_date;
            $purchase['expiry_date']=$expiry_date;
            $purchase['commission_percentage']=$data->commission_percentage;
            $purchase['policy_pdf_url']=$path;
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$PersonalAccident->id;
            $pdf = PDF::loadView('pdf/personal_accident_policy', compact('data'));
            $pdf->save($path);
            $url = url('insurance_pdfs/personal_accident_policy/' . $filename);
            $data['url']=$url;
            
            $data->net_premium = $data->net_premium.'%';
            $data->fees = $data->fees.'%';
            $data->gross_premium = $data->gross_premium.'%';
            $data->sales_tax = $data->sales_tax.'%';
            $data->stamps = $data->stamps.'%';
            $data->commission_percentage = $data->commission_percentage.'%';
         
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Personal Accident Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(),'data' => array()]);
        }
    }
    
    public function getPersonalAccidentPlanInsurancePlan(Request $request)
    {
        try {
            $data = PersonalAccidentPlan::with('policy_covers','insurance_company')->where('limit','LIKE','%'.$request->limit.'%')->get();
            $data->transform(function($item){
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' .$item->id.'/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' .$item->insurance_company->id.'/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Personal Accident Plan Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
}
