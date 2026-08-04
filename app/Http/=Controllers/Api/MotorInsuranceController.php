<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\{ClientMotorInsurance,PurchasePolicy,MotorPlan};
use App\Models\InsurancePlanModels\{MotorInsurancePlan,MotorInsurancePlanComprehensiveCoverPremium,MotorInsurancePlan3MonthsCompulsoryPremium,MotorInsurancePlan6MonthsCompulsoryPremium,MotorInsurancePlan9MonthsCompulsoryPremium,MotorInsurancePlan12MonthsCompulsoryPremium,MotorInsurancePlanTotalLossPremium};
use PDF;
use Illuminate\Http\Request;

class MotorInsuranceController extends Controller
{
    public function storeMotorInsurance(Request $request)
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
                'occupancy'=>'nullable',
                'city_id'=>'nullable',
                'district_id'=>'nullable',
                'street_name'=>'nullable',
                'building_no'=>'nullable',
                'user_mobile_no'=>'nullable',
                'company_name'=>'nullable',
                'position'=>'nullable',
                'work_nature'=>'nullable',
                'company_contact_no'=>'nullable',
                'inception_date'=>'nullable',
                'expiry_date'=>'nullable',
                'no_accident_3_year'=>'nullable',
                'no_ticket_12_month'=>'nullable',
                'no_point_12_month'=>'nullable',
                'vahicle_no'=>'nullable',
                'obtain_vahicle_info'=>'nullable',
                'vahicle_type_id'=>'nullable',
                'vahicle_brand_id'=>'nullable',
                'vahicle_category_id'=>'nullable',
                'vahicle_color_id'=>'nullable',
                'vahicle_register_no'=>'nullable',
                'engine_no'=>'nullable',
                'chassis_no'=>'nullable',
                'engine_type_id'=>'nullable',
                'engine_capacity'=>'nullable',
                'vehicle_manufacturing_date'=>'nullable',
                'vehicle_value'=>'nullable',
                'insurance_type'=>'nullable',
                'residence_id_front'=>'nullable',
                'residence_id_back'=>'nullable',
                'vehicle_license_front'=>'nullable',
                'vehicle_license_back'=>'nullable',
                'vehicle_photo_front'=>'nullable',
                'vehicle_photo_back'=>'nullable',
                'vehicle_photo_right'=>'nullable',
                'vehicle_photo_left'=>'nullable',
                'carseer_documents'=>'nullable',
                'autoscore_documents'=>'nullable',
                'customs_declaration'=>'nullable',
                'payment_status'=>'nullable',
                'plan_id'=> 'required',
                "national_id_number"=>'nullable',
                "residency_number"=>'nullable',
            ]);
            $existingMotor = PurchasePolicy::find($request->purchase_id ?? 0);
            $plan_data=MotorInsurancePlan::with(['fees'])->find($data['plan_id']);
            $data['national_id_number']='12';
            $data['residency_number']='12';
            if (!$plan_data) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Motor Insurance Plan not found',
                    'data' => []
                ]);
            }
            $data['insurance_company_id']=$plan_data->insurance_company_id ?? 0;
            if ($existingMotor) {
                $Motor = ClientMotorInsurance::find($existingMotor->policy_id);
                if ($Motor) {
                    $Motor->update($data);
                    $Motor->toArray();
                }
                $data['purchase_id']=$existingMotor->id;
                $Motor = PurchasePolicy::updatePurchasePolicy($data);
                $data['policy_id'] = $Motor->policy_id;
            } else {
                $data['client_id'] = $request->user_id;
                $data['police_no'] = rand(10000000, 99999999);
                $Motor = ClientMotorInsurance::create($data);
                $data['policy_type'] = 12;
                $data['policy_id'] = $Motor->id;
                $Motor = PurchasePolicy::savePurchasePolicy($data);
            }
            $data = ClientMotorInsurance::getMotorInsuranceDetails($data['policy_id']);
            $directory = public_path('insurance_pdfs/motor_policy');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $filename = uniqid() .'_policy_' . $Motor->id . '.pdf';
            $path = $directory . '/' . $filename;

            $net_premium = $plan_data->fees->net_premium;
            $fees_percentage = $plan_data->fees->fees;
            $stamps_percentage = $plan_data->fees->stamps;
            $sales_tax_percentage = $plan_data->fees->sales_tax;
            $fee_amount = ($net_premium * $fees_percentage) / 100;
            $stamp_amount = ($net_premium * $stamps_percentage) / 100;
            $sales_tax_amount = (($net_premium + $fee_amount + $stamp_amount) * $sales_tax_percentage) / 100;
            $gross_premium = $net_premium + $fee_amount + $stamp_amount + $sales_tax_amount;
            $commission_amount = ($gross_premium * $plan_data->fees->commission_percentage) / 100;

            $purchase['net_premium'] = $net_premium;
            $purchase['fees'] = $fee_amount;
            $purchase['stamps'] = $stamp_amount;
            $purchase['sales_tax'] = $sales_tax_amount;
            $purchase['gross_premium'] = $gross_premium;
            $purchase['commission_amount'] = $commission_amount;
            
            $purchase['purchase_id']=$Motor->id;
            $purchase['plan_id']=$data->plan_id;
            $purchase['plan_name']=$plan_data->plan_name;
            $purchase['policy_plan_limit']=$plan_data->limit;
            $purchase['insurance_company_id']=$data->insurance_company_id;
            $purchase['inception_date']=$data->inception_date;
            $purchase['expiry_date']=$data->expiry_date;
            $purchase['commission_percentage']=$data->commission_percentage;
            $purchase['policy_pdf_url']=$path;
            PurchasePolicy::updatePurchasePolicy($purchase);
            $data->purchase_id=$Motor->id;
            $pdf = PDF::loadView('pdf/motor_policy', compact('data'));
            $pdf->save($path);
            $url = url('insurance_pdfs/motor_policy/' . $filename);
            $data['url']=$url;
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Add Motor Insurance Plan successfully', 'data' => $Motor]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the creation process
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function getComprehensivePlan(Request $request)
    {
        try {
            $data=array();
            $data=MotorInsurancePlan::where('motor_plan_id', 1)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Comprehensive Plan successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    public function getCompulsory3MonthsPlan(Request $request)
    {
        try {
            $data=MotorInsurancePlan::where('motor_plan_id', 2)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan 3 Months successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    public function getCompulsory6MonthsPlan(Request $request)
    {
        try {
            
            $data=MotorInsurancePlan::where('motor_plan_id', 3)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan 6 Months successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    public function getCompulsory9MonthsPlan(Request $request)
    {
        try {
            
            $data=MotorInsurancePlan::where('motor_plan_id', 4)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan 9 Months successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    public function getCompulsory12MonthsPlan(Request $request)
    {
        try {
            
            $data=MotorInsurancePlan::where('motor_plan_id', 5)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan 12 Months successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    public function MotorInsurancePlanTotalLossPremium(Request $request)
    {
        try {
            
            $data=MotorInsurancePlan::where('motor_plan_id', 6)->get();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Motor Insurance Plan Total Loss Premium successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
}
