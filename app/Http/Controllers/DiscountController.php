<?php

namespace App\Http\Controllers;

use App\Models\DiscountModel;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    function index()
    {
        $discountdata = DiscountModel::all();

        return view('content.discount.discount', compact('discountdata'));
    }
    function DiscountCreate(Request $request)
    {
        info($request->all());
        DiscountModel::create([
            'coupan_code' => $request->code,
            'insurance_type' => $request->insurance_type,
            'discount_percentage' => $request->discount_percentage,
            'discount_description' => $request->discount_description,
        ]);
        return redirect()->route('pages.discount');
    }
    function destory(Request $request)
    {
        info($request->all()); 
        $code_id = $request->input('delete_code_id');
        $code = DiscountModel::find($code_id);
        $code->delete();

        return redirect()->back()->with('status', 'Customer deleted successfully');
    }
}
