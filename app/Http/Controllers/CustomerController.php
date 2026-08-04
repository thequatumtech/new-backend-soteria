<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;

class CustomerController extends Controller
{
    function index(Request $request)
    {
        // info($request->all());
        $customerdatas = CustomerModel::all();

        return view('content.customer.customer', ['customerdata' => $customerdatas]);
    }
    function CustomerCreate(Request $request)
    {
        info($request->all());

        $customercreatedata = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'occupation' => $request->occupation,
            'mobile_no' => $request->mobile_no,
            'gender' => $request->gender,
            'address' => $request->address,
            'dob' => $request->dob,
            'housenoandbuildingname' => $request->housenoandbuildingname,
            'street' => $request->street,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'district' => $request->district,

        ];

        $customerdatas =  CustomerModel::create($customercreatedata);
        info($request->stamp_of_company);
        // if (count($request->stamp_of_company) > 0) {
        //     $customerdatas->addMultipleMediaFromRequest(['stamp_of_company'])->each(function ($fileAdder) {
        //         $fileAdder->toMediaCollection('customer-files');
        //     });

        // }
        $customerdatas->clearMediaCollection('customer-files');
        $customerdatas->addMediaFromRequest('stamp_of_company')->toMediaCollection('customer-files');
        return redirect()->route('pages.customer');
    }

    function CustomerEdit($id)
    {
        $customerdata = CustomerModel::find($id);
        info($customerdata);
        return response()->json([
            'customer' => $customerdata,
            'status' => 200
        ]);
    }
    public function CustomerUpdate(Request $request)
    {
        $customer = CustomerModel::find($request->customer_id);

        if ($customer) {
            $customer->update([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'occupation' => $request->occupation,
                'mobile_no' => $request->mobile_no,
                'gender' => $request->gender,
                'address' => $request->address,
                'dob' => $request->dob,
                'housenoandbuildingname' => $request->housenoandbuildingname,
                'street' => $request->street,
                'country' => $request->country,
                'city' => $request->city,
                'state' => $request->state,
                'district' => $request->district,
                'stamp_of_company' => $request->stamp_of_company,
            ]);

            $customer->clearMediaCollection('customer-files');
            $customer->addMediaFromRequest('stamp_of_company')->toMediaCollection('customer-files');

            return redirect()->back()->with('success', 'Customer Update successfully');
        }

        return response()->back()->with('error', 'Customer not found');
    }

    function destoryCustomer(Request $request)
    {
        $customer_id = $request->input('delete_customer_id');
        $customer = CustomerModel::find($customer_id);
        $customer->delete();

        return redirect()->back()->with('status', 'Customer deleted successfully');
    }
}
