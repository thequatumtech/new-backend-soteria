<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use App\Models\HomeInsurancePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    function getCustomers(Request $request)
    {
        return HelperController::sendJsonResponse(200, CustomerModel::all(), __('messages.api.customer_get_successfully'));
    }
    function getCustomer(Request $request, $id)
    {
        $customer =  CustomerModel::find($id);
        return HelperController::sendJsonResponse(200, $customer, $customer ? __('messages.api.customer_get_successfully') : __('messages.api.customer_not_found'));
    }

    function createCustomer(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "full_name" => 'required',
            "email" => 'required|email',
            "mobile_no" => 'required',
            "password" => 'required|password',
            "occupation" => 'required',
            "gender" => 'required',
            "address" => 'required',
            "dob" => 'required',
            "housenoandbuildingname" => 'required',
            "street" => 'required',
            "country" => 'required',
            "city" => 'required',
            "state" => 'required',
            "district" => 'required',
            "national_id" => 'required',
            "road_name" => 'required',
            "house_no" => 'required',
        ]);
        if ($validation->fails()) {
            return HelperController::sendJsonResponse(400, $validation->errors(), $validation->errors()->first());
        }
        $customer = new CustomerModel();
        $customer->full_name = $request->full_name;
        $customer->email = $request->email;
        $customer->mobile_no = $request->mobile_no;
        $customer->password = $request->password;
        $customer->occupation = $request->occupation;
        $customer->gender = $request->gender;
        $customer->address = $request->address;
        $customer->dob = $request->dob;
        $customer->housenoandbuildingname = $request->housenoandbuildingname;
        $customer->street = $request->street;
        $customer->country = $request->country;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->district = $request->district;
        $customer->national_id = $request->national_id;
        $customer->road_name = $request->road_name;
        $customer->house_no = $request->house_no;
        $customer->save();
        $token = $customer->createToken('api_token');
        $customer['token'] = $token->plainTextToken;
        return HelperController::sendJsonResponse(200, $customer, __('messages.api.customer_create_successfully'));
    }

    function updateCustomer(Request $request, $id)
    {
        $customer = CustomerModel::find($id);
        if (!$customer) {
            return HelperController::sendJsonResponse(500, null, __('messages.api.customer_not_found'));
        }
        $validation = Validator::make($request->all(), [
            "email" => 'email',
        ]);
        if ($validation->fails()) {
            return HelperController::sendJsonResponse(400, $validation->errors(), $validation->errors()->first());
        }
        // $customer = new CustomerModel(); 
        if (isset($request->full_name) && $request->full_name) {
            $customer->full_name = $request->full_name;
        }
        // (isset($request->email) && $request->email) && $customer->email; 
        // (isset($request->mobile_no) && $request->mobile_no) && $customer->mobile_no; 
        if (isset($request->email) && $request->email) {
            $customer->email = $request->email;
        }
        if (isset($request->mobile_no) && $request->mobile_no) {
            $customer->mobile_no = $request->mobile_no;
        }
        if (isset($request->password) && $request->password) {
            $customer->password = $request->password;
        }
        if (isset($request->occupation) && $request->occupation) {
            $customer->occupation = $request->occupation;
        }
        if (isset($request->gender) && $request->gender) {
            $customer->gender = $request->gender;
        }
        if (isset($request->address) && $request->address) {
            $customer->address = $request->address;
        }
        if (isset($request->dob) && $request->dob) {
            $customer->dob = $request->dob;
        }
        if (isset($request->housenoandbuildingname) && $request->housenoandbuildingname) {
            $customer->housenoandbuildingname = $request->housenoandbuildingname;
        }
        if (isset($request->street) && $request->street) {
            $customer->street = $request->street;
        }
        if (isset($request->country) && $request->country) {
            $customer->country = $request->country;
        }
        if (isset($request->city) && $request->city) {
            $customer->city = $request->city;
        }
        if (isset($request->state) && $request->state) {
            $customer->state = $request->state;
        }
        if (isset($request->district) && $request->district) {
            $customer->district = $request->district;
        }
        if (isset($request->national_id) && $request->national_id) {
            $customer->national_id = $request->national_id;
        }
        if (isset($request->road_name) && $request->road_name) {
            $customer->road_name = $request->road_name;
        }
        if (isset($request->house_no) && $request->house_no) {
            $customer->house_no = $request->house_no;
        }

        // new filed  

        $customer->save();
        $token = $customer->createToken('api_token');
        $customer['token'] = $token->plainTextToken;
        return HelperController::sendJsonResponse(200, $customer, __('messages.api.customer_update_successfully'));
    }

    public function  homeInsurance(Request $request)
    {
        $arr = [];
        if (!isset($request->user()->id)) {
            return HelperController::sendJsonResponse(401, null, __('messages.api.customer_not_found'));
        }
        if ($request->has('fname') && $request->fname) {
            $arr['fname'] = $request->fname;
        }
        if ($request->has('nationality') && $request->nationality) {
            $arr['nationality'] = $request->nationality;
        }
        if ($request->has('national_id') && $request->national_id) {
            $arr['national_id'] = $request->national_id;
        }
        if ($request->has('coverage') && $request->coverage) {
            $arr['coverage'] = $request->coverage;
        }
        if ($request->has('category') && $request->category) {
            $arr['category'] = $request->category;
        }
        if ($request->has('sizeofvilla') && $request->sizeofvilla) {
            $arr['sizeofvilla'] = $request->sizeofvilla;
        }
        if ($request->has('location') && $request->location) {
            $arr['location'] = $request->location;
        }
        if ($request->has('nooffloors') && $request->nooffloors) {
            $arr['nooffloors'] = $request->nooffloors;
        }
        if ($request->has('noofrooms') && $request->noofrooms) {
            $arr['noofrooms'] = $request->noofrooms;
        }
        if ($request->has('homecategory') && $request->homecategory) {
            $arr['homecategory'] = $request->homecategory;
        }
        if ($request->has('effectivedate') && $request->effectivedate) {
            $arr['effectivedate'] = $request->effectivedate;
        }
        if ($request->has('expirydate') && $request->expirydate) {
            $arr['expirydate'] = $request->expirydate;
        }
        if ($request->has('limit') && $request->limit) {
            $arr['limit'] = $request->limit;
        }
        if ($request->has('BuildingNo') && $request->BuildingNo) {
            $arr['BuildingNo'] = $request->BuildingNo;
        }
        if ($request->has('BlockNo') && $request->BlockNo) {
            $arr['BlockNo'] = $request->BlockNo;
        }
        if ($request->has('PlaateNo') && $request->PlaateNo) {
            $arr['PlaateNo'] = $request->PlaateNo;
        }
        if ($request->has('PlotNo') && $request->PlotNo) {
            $arr['PlotNo'] = $request->PlotNo;
        }
        if ($request->has('NoResident') && $request->NoResident) {
            $arr['NoResident'] = $request->NoResident;
        }
        if (count($arr) > 0) {
            $homeInsturenPlan = HomeInsurancePlan::where('customer_id', $request->user()->id)->first();
            if ($homeInsturenPlan) {
                $homeInsturenPlan->update($arr);
                return HelperController::sendJsonResponse(200, $homeInsturenPlan, __('messages.api.data_save_successfully'));
            } else {
                $arr['customer_id'] = $request->user()->id;
                $homeInsturenPlan = HomeInsurancePlan::create($arr);
                return HelperController::sendJsonResponse(200, $homeInsturenPlan, __('messages.api.data_save_successfully'));
            }
        } else {
            return HelperController::sendJsonResponse(500, null, __('messages.api.input_not_valid'));
        }
    }

    public function  homeInsurancePlan(Request $request) {}
}
