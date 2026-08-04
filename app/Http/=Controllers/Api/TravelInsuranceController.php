<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsurancePlanModels\TravelPlan;
use App\Models\{ClientTravelInsurance,FamilyTravelInsuranceMember,PurchasePolicy};
use PDF;
class TravelInsuranceController extends Controller
{
    public function storeTravelInsurance(Request $request)
    {
        try {
            $data = $request->validate([
                'self_family_status'=>'nullable',
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
                'passport_document'=>'nullable',
                'departure_from_country_id'=>'nullable',
                'destination_country_id'=>'nullable',
                'additional_destination_country_id'=>'nullable',
                'geographical_area_id'=>'nullable',
                'effective_date'=>'nullable',
                'travel_days'=>'nullable',
                'expiry_date'=>'nullable',
                'insurance_limit'=>'nullable',
                'plan_id'=>'required',
                'payment_status'=>'nullable',
                'multiple_destination'=>'nullable',
                'dangerous_activities'=>'nullable',
            ]);
            if ($request->has('dangerous_activities')) {
                $data['dangerous_activities'] = is_array($request->dangerous_activities) ? implode(',', $request->dangerous_activities) : $request->dangerous_activities;
            }
            if ($request->has('multiple_destination')) {
                $data['multiple_destination'] = is_array($request->multiple_destination) ? implode(',', $request->multiple_destination) : $request->multiple_destination;
            }
            $existingtravel = PurchasePolicy::find($request->purchase_id ?? 0);
            $plan_data=travelPlan::find($data['plan_id']);
            $data['insurance_company_id']=$plan_data->insurance_company_id ?? 0;
            if ($existingtravel) {
                $travels = ClientTravelInsurance::find($existingtravel->policy_id);
                if ($travels) {
                    $travels->update($data);
                    $travels->toArray();
                }
                $data['purchase_id']=$existingtravel->id;
                $data['inception_date']=$data['effective_date'];
                $travel = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $travel->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $travel = ClientTravelInsurance::create($data);
                $data['policy_type'] = 10;
                $data['policy_id'] = $travel->id;
                $data['inception_date']=$data['effective_date'];
                $travel = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientTravelInsurance::getTravelsInsuranceDetails($data['policy_id']);
            $directory = public_path('insurance_pdfs/travel_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = uniqid() .'_policy_' . $travel->id . '.pdf';
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

            $purchase['purchase_id']=$travel->id;
            $purchase['plan_id']=$data->plan_id;
            $purchase['plan_name']=$plan_data->plan_name;
            $purchase['policy_plan_limit']=$plan_data->limit;
            $purchase['insurance_company_id']=$data->insurance_company_id;
            $purchase['inception_date']=$data->inception_date;
            $purchase['expiry_date']=$data->expiry_date;
            $purchase['commission_percentage']=$data->commission_percentage;
            $purchase['policy_pdf_url']=$path;
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$travel->id;
            $pdf = PDF::loadView('pdf/travel_policy', compact('data'));
            $pdf->save($path);
            $url = url('insurance_pdfs/travel_policy/' . $filename);
            $data['url']=$url;
            if(!empty($request->members))
            {
                foreach ($request->members as $single) {
                    $family_member = new FamilyTravelInsuranceMember();
                    $family_member->client_insurance_id = $travel->id;
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
                    $family_member->place_residence = $single['place_residence'];
                    $family_member->passport_document = $single['passport_document'];
                    $family_member->save();
                }
            }
            
            $data->net_premium = $data->net_premium.'%';
            $data->fees = $data->fees.'%';
            $data->gross_premium = $data->gross_premium.'%';
            $data->sales_tax = $data->sales_tax.'%';
            $data->stamps = $data->stamps.'%';
            $data->commission_percentage = $data->commission_percentage.'%';
         
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Travel Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => array()]);
        }
    }
    public function getTravelInsurancePlan(Request $request)
    {
        try {
            $data = TravelPlan::with('policy_covers', 'insurance_company');
            if (!empty($request->limit)) {
                $data->where('plan_name', 'LIKE', '%' . $request->limit . '%');
            }
            $data = $data->get();
            $data->transform(function($item){
                if (!empty($item->insurance_policy_pdf)) {
                    $item->insurance_policy_pdf = url('uploads/insurance_plans/' .$item->id.'/' . $item->insurance_policy_pdf);
                }
                if (!empty($item->insurance_company->privacy_policy)) {
                    $item->insurance_company->privacy_policy = url('insurance/' .$item->insurance_company->id.'/' . $item->insurance_company->privacy_policy);
                }
                return $item;
            });
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Travel Insurance Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
}
